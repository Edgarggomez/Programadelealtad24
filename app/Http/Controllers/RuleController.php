<?php

namespace App\Http\Controllers;

use App\Regla;
use App\Location;
use App\TiendaCC;
use App\BdCC;
use Illuminate\Http\Request;
use App\Http\Requests\RuleFormRequest;

class RuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }


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
    public function create(Location $location)
    {
        for ($i=0; $i < 24; $i++) {
            $hours[] = $i;
        }
        $tdas = TiendaCC::pluck('nombre', 'id_tda');
        $bds = BdCC::pluck('nombre', 'id_bd');

        return view('location.rules', compact(['hours', 'location', 'tdas', 'bds']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuleFormRequest $request)
    {
        $input = $request->all();
        $input['regla'] = 'NA';
        $input['estatus'] = true;
        $input['monto'] = 10.2;
        Regla::create($input);
        return redirect(route('locations.index'))->with('success', '¡Regla creada exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Regla  $regla
     * @return \Illuminate\Http\Response
     */
    public function show(Regla $regla)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Regla  $regla
     * @return \Illuminate\Http\Response
     */
    public function edit(Regla $regla)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Regla  $regla
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Regla $regla)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Regla  $regla
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regla $rule)
    {
        if(Location::find($rule->id_ubicacion)->rules()->count() < 2) {
            return redirect()->back()->withErrors(['No se puede eliminar esta regla, debe existir al menos una regla']);
        }
        $rule->delete();
        return redirect(route('locations.index'))->with('success', '¡Regla eliminada!');
    }
}
