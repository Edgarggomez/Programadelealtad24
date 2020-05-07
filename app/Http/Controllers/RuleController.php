<?php

namespace App\Http\Controllers;

use App\Regla;
use Illuminate\Http\Request;

class RuleController extends Controller
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
    public function create()
    {

        for ($i=0; $i < 24; $i++) { 
            $hours[] = $i;
        }
        return view('location.rules', compact('hours'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
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
    public function destroy(Regla $regla)
    {
        //
    }
}
