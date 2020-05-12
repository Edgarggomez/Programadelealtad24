@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                @if(isset($user))
                    <div class="card-header">Modificar Usuario</div>
                @else
                    <div class="card-header">Alta Usuario</div>
                @endif

                <div class="card-body">
                    @if(isset($user))
                        {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
                    @else
                        {!! Form::open(['route' => 'users.store']) !!}
                    @endif
                        {!! Form::token() !!}
                        <div class="form-group">
                            {!! Form::label('name', 'Nombre:') !!}
                            {!! Form::text('name', old('name'), ['class' => 'form-control' . ( $errors->has('name') ? ' is-invalid' : null ), 'placeholder' => 'Escriba su nombre']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'Correo Electronico:') !!}
                            {!! Form::email('email', old('email'), ['class' => 'form-control' . ( $errors->has('email') ? ' is-invalid' : null ), 'placeholder' => 'cliente_consentido@ejemplo.com']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('role', 'Rol:') !!}
                            {!! Form::select('role', ['admin' => 'Administrador', 'gerente' => 'Gerente', 'operador' => 'Operador'], old('role'), ['class' => 'form-control' . ( $errors->has('role') ? ' is-invalid' : null ), 'placeholder' => 'Rol']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('status', 'Estado:') !!}
                            {!! Form::select('status', ['a' => 'Activo', 'i' => 'Inactivo'], old('status'), ['class' => 'form-control' . ( $errors->has('status') ? ' is-invalid' : null ), 'placeholder' => 'Estado']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('id_ubicacion', 'Ubicación:') !!}
                            {!! Form::select('id_ubicacion', $location, old('id_ubication'), ['class' => 'form-control' . ( $errors->has('id_ubicacion') ? ' is-invalid' : null ), 'placeholder' => 'Ubicación']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('password', 'Contraseña:') !!}
                            {!! Form::password('password', ['class' => 'form-control' . ( $errors->has('password') ? ' is-invalid' : null ), 'placeholder' => 'Contraseña']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('password_confirmation', 'Confirmar Contraseña:') !!}
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Contraseña']) !!}
                        </div>
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection