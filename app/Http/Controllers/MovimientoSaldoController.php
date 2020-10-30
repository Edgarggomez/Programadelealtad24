<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\MovimientoSaldo;
use App\Tarjeta;
use Illuminate\Http\Request;

class MovimientoSaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cards = Tarjeta::search($request->search)->get()->pluck('id_tarjeta')->toArray();
        $clients = Cliente::search($request->search)->get()->pluck('id_cliente')->toArray();
        $mov_query = MovimientoSaldo::where(function($query) use($clients, $cards) {
            $query->whereIn('id_cliente', $clients)
                ->orWhereIn('id_tarjeta', $cards);
        })
        ->when($request->inicio, function($query, $fecha_inicio) {
            return $query->where('fecha_mov', '>=', $fecha_inicio);
        })
        ->when($request->fin, function($query, $fecha_fin) {
            return $query->where('fecha_mov', '<=', $fecha_fin);
        });

        $movimientos = $mov_query->paginate(10);


        return view('client.balance_history', compact('movimientos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function show(MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function edit(MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovimientoSaldo $movimientoSaldo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MovimientoSaldo  $movimientoSaldo
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovimientoSaldo $movimientoSaldo)
    {
        //
    }
}
