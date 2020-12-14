@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                @if(isset($client))
                    <div class="card-header">Modificar Cliente @role('gerente') <a class="btn btn-primary float-right" href="{{ route('client.editBalance', $client->id_cliente)}}">Añadir saldo</a> @endrole</div>
                @else
                    <div class="card-header">Alta Cliente</div>
                @endif

                <div class="card-body">
                    @if(isset($client))
                        {!! Form::model($client, ['route' => ['clients.update', $client->id_cliente], 'method' => 'patch']) !!}
                        {!! Form::hidden('id_cliente', $client->id_cliente) !!}
                    @else
                        {!! Form::open(['route' => 'clients.store']) !!}
                    @endif
                        <div class="form-group">
                            {!! Form::label('nombre', 'Nombre:') !!}
                            {!! Form::text('nombre', old('nombre'), ['class' => 'form-control' . ( $errors->has('nombre') ? ' is-invalid' : null ), 'placeholder' => 'Escriba su nombre']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('sexo', 'Sexo:') !!}
                            {!! Form::select('sexo', ['M'=>'Hombre', 'F'=>'Mujer'], old('sexo'), ['class' => 'form-control' . ( $errors->has('sexo') ? ' is-invalid' : null ), 'placeholder' => 'Seleccione']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('correo', 'Correo Electronico:') !!}
                            {!! Form::email('correo', old('correo'), ['class' => 'form-control' . ( $errors->has('correo') ? ' is-invalid' : null ), 'placeholder' => 'cliente_consentido@ejemplo.com']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('celular', 'Celular:') !!}
                            {!! Form::text('celular', old('celular'), ['class' => 'form-control' . ( $errors->has('celular') ? ' is-invalid' : null ), 'placeholder' => 'Escriba número de celular']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('rfc', 'RFC:') !!}
                            {!! Form::text('rfc', old('rfc'), ['class' => 'form-control' . ( $errors->has('rfc') ? ' is-invalid' : null ), 'placeholder' => 'Escriba su RFC', 'maxlength' => '16']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('tarjeta', 'Tarjeta:') !!}
                            {!! Form::text('tarjeta', old('tarjeta'), ['class' => 'form-control' . ( $errors->has('tarjeta') ? ' is-invalid' : null ), 'placeholder' => 'Escriba número de tarjeta']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('flotilla', '¿Flotilla?:') !!}
                            {!! Form::select('flotilla', [1 => 'Sí', 0 => 'No'], old('flotilla'), ['class' => 'form-control' . ( $errors->has('flotilla') ? ' is-invalid' : null ), 'placeholder' => 'Seleccione']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('id_ubicacion', 'Ubicación:') !!}
                            {!! Form::select('id_ubicacion', $location, old('id_ubication'), ['class' => 'form-control' . ( $errors->has('id_ubicacion') ? ' is-invalid' : null ), 'placeholder' => 'Seleccione ubicación']) !!}
                        </div>
                        @if(isset($client))
                            <hr>
                            <div class="form-group">
                                {!! Form::label('estatus', 'Estado:') !!}
                                {!! Form::select('estatus', ['1' => 'Activo', '0' => 'Inactivo' , '2' => 'Suspendido'], old('estatus'), ['class' => 'form-control' . ( $errors->has('estatus') ? ' is-invalid' : null ), 'placeholder' => 'Estado']) !!}
                            </div>
                        @endif

                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
