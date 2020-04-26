@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Alta Usuario</div>

                <div class="card-body">
                    @if(isset($user))
                        {{ Form::model($user, ['route' => ['usuarios.update', $user->id], 'method' => 'patch']) }}
                    @else
                        {{ Form::open(['route' => 'usuarios.store']) }}
                    @endif
                        {{ Form::token() }}
                        <div class="form-group">
                            {{ Form::label('name', 'Nombre:') }}
                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Escriba su nombre']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Correo Electronico:') }}
                            {{ Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'cliente_consentido@ejemplo.com']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('role', 'Rol:') }}
                            {{ Form::select('role', ['admin' => 'Administrador', 'gerente' => 'Gerente', 'operador' => 'Operador'], old('role'), ['class' => 'form-control', 'placeholder' => 'Rol']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('status', 'Estado:') }}
                            {{ Form::select('status', ['a' => 'Activo', 'i' => 'Inactivo', 'r' => 'Archivado'], old('status'), ['class' => 'form-control', 'placeholder' => 'Estado']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('id_ubicacion', 'Ubicación:') }}
                            {{ Form::select('id_ubicacion', $ubicacion, old('id_ubication'), ['class' => 'form-control', 'placeholder' => 'Ubicación']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('password', 'Contraseña:') }}
                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Contraseña']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('password-confirm', 'Confirmar Contraseña:') }}
                            {{ Form::password('password-confirm', ['class' => 'form-control', 'placeholder' => 'Contraseña']) }}
                        </div>
                        {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection