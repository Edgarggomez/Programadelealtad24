<?php

namespace App\Http\Controllers;

use App\User;
use App\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserFormRequest;

class UserController extends Controller
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
        $users = User::paginate(10);
        return view('usuario.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicacion = Ubicacion::pluck('ubicacion', 'id_ubicacion');
        return view('usuario.formulario', compact('ubicacion', 'ubicacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($input['role']);
        return redirect(route('usuarios.index'))->with('success', '¡Usuario creado exitosamente!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        $ubicacion = Ubicacion::pluck('ubicacion', 'id_ubicacion');
        return view('usuario.formulario')->with(['user' => $usuario, 'ubicacion' => $ubicacion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $usuario->update($input);
        $usuario->syncRoles($input['role']);
        return redirect(route('usuarios.index'))->with('success', '¡Usuario actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $usuario->status = "r";
        $usuario->save();
        return redirect(route('usuarios.index'))->with('success', '¡Usuario archivado exitosamente!');;
    }
}
