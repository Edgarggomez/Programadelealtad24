<?php

namespace App\Http\Controllers;

use App\Location;
use App\TiendaCC;
use App\BdCC;
use App\Http\Requests\LocationFormRequest;
use Illuminate\Http\Request;

class LocationController extends Controller
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
    public function index(Request $request)
    {
        $locations = Location::search($request->search)->paginate(10);
        return view('location.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tdas = TiendaCC::pluck('nombre', 'id_tda');
        $bds = BdCC::pluck('nombre', 'id_bd');
        return view('location.form', compact(['tdas', 'bds']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationFormRequest $request)
    {
        $input = $request->all();
        $location = Location::create($input);
        return redirect(route('rules.create', $location->id_ubicacion))->with('success', '¡Ubicación creada exitosamente!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        for ($i=0; $i < 24; $i++) {
            $hours[] = $i;
        }
        $tdas = TiendaCC::pluck('nombre', 'id_tda');
        $bds = BdCC::pluck('nombre', 'id_bd');
        return view('location.form', compact(['location', 'hours', 'tdas', 'bds']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationFormRequest $request, Location $location)
    {
        $location->update($request->all());
        return redirect(route('locations.index'))->with('success', '¡Ubicación actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect(route('locations.index'))->with('success', '¡Ubicación eliminada!');
    }
}
