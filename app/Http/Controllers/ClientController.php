<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Tarjeta;
use App\MovimientoSaldo;
use Illuminate\Http\Request;
use App\Location;
use App\Http\Requests\ClientFormRequest;
use App\TarjetaCC;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:alta cliente', ['only' => ['create','store']]);
        $this->middleware('permission:edicion cliente', ['only' => ['edit','update', 'index']]);
        $this->middleware('permission:baja cliente', ['only' => ['destroy']]);
        $this->middleware('permission:abono manual cg', ['only' => ['editBalance', 'updateBalance']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Cliente::search($request->search)->paginate(10);
        return view('client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $location = Location::pluck('ubicacion', 'id_ubicacion');
        return view('client.form', compact('location', 'location'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientFormRequest $request)
    {
        $input = $request->all();
        $client = Cliente::create($input);
        $input['adicional'] = false;
        $input['id_cliente'] = $client->id_cliente;
        $card = Tarjeta::createOrUpdate($input);
        $client->id_tarjeta_principal = $card->id_tarjeta;
        $client->save();
        return redirect(route('clients.index'))->with('success', '¡Cliente creado exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $client)
    {
        $location = Location::pluck('ubicacion', 'id_ubicacion');
        return view('client.form', compact('location', 'client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(ClientFormRequest $request, Cliente $client)
    {
        $input = $request->all();
        $input['adicional'] = false;
        $input['id_cliente'] = $client->id_cliente;
        $card = Tarjeta::createOrUpdate($input);
        $input['id_tarjeta_principal'] = $card->id_tarjeta;
        $client->update($input);
        return redirect(route('clients.index'))->with('success', '¡Cliente actualizado exitosamente!');
    }


    /**
     * Show the form for editing the balance of the client.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function editBalance(Cliente $client)
    {
        return view('client.add_balance', compact('client'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function updateBalance(Request $request, Cliente $client)
    {
        //validation
        $validatedData = $request->validate([
            'saldo_adicional' => 'required|numeric|min:0'
        ]);
        $input = $request->all();
        $movSaldo = new MovimientoSaldo;
        $movSaldo->id_cliente = $client->id_cliente;
        $movSaldo->id_tarjeta = $client->id_tarjeta_principal;
        $movSaldo->tipo = 'abono';
        $movSaldo->origen = 'saldo_adicional';
        $movSaldo->monto = $input['saldo_adicional'];
        $movSaldo->saldo_anterior = $client->saldo ?? 0;
        $movSaldo->saldo_nuevo = $client->saldo + $input['saldo_adicional'];
        $movSaldo->tipo_usuario = 'G';
        $movSaldo->email_usuario = Auth::user()->email;
        $movSaldo->save();
        $client->fecha_actualizacion_saldo = Carbon::now();
        $client->saldo = $movSaldo->saldo_nuevo;
        $client->save();
        return redirect(route('clients.index'))->with('success', '¡Saldo añadido exitosamente!');
    }

    public function balanceHistory(Cliente $client)
    {
        $movimientos = MovimientoSaldo::where('id_cliente', $client->id_cliente)
            ->orderBy('fecha_mov', 'desc')
            ->paginate(10);
        return view('client.balance_history', compact('movimientos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $client)
    {
        $client->delete();
        return redirect(route('clients.index'))->with('success', '¡Cliente eliminado exitosamente!');
    }
}
