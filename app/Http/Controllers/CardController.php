<?php

namespace App\Http\Controllers;

use App\Tarjeta;
use App\Cliente;
use App\Http\Requests\CardFormRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Cliente $client)
    {
        return view('card.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CardFormRequest $request)
    {
        $input = $request->all();
        $input['adicional'] = true;
        $card = Tarjeta::createOrUpdate($input);
        return redirect(route('cards.create', $input['id_cliente']))->with('success', '¡Tarjeta añadida exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function show(Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarjeta $tarjeta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tarjeta  $tarjeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarjeta $card)
    {
        $card->status = 0;
        $card->save();
        return redirect(route('cards.create', $card->id_cliente))->with('success', 'Tarjeta borrada exitosamente!');
    }
}
