@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if(isset($location))
                    <div class="card-header">Modificar Ubicación</div>
                @else
                    <div class="card-header">Alta Ubicación</div>
                @endif

                <div class="card-body">
                    @if(isset($location))
                        {!! Form::model($location, ['route' => ['locations.update', $location->id_ubicacion], 'method' => 'patch']) !!}
                    @else
                        {!! Form::open(['route' => 'locations.store']) !!}
                    @endif
                        <div class="form-group">
                            {!! Form::label('ubicacion', 'Ubicación:') !!}
                            {!! Form::text('ubicacion', old('ubicacion'), ['class' => 'form-control' . ( $errors->has('ubicacion') ? ' is-invalid' : null ), 'placeholder' => 'Escriba la ubicación']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('estatus', 'Estado:') !!}
                            {!! Form::select('estatus', [1 => 'Activo', 0 => 'Inactivo'], old('estatus'), ['class' => 'form-control' . ( $errors->has('estatus') ? ' is-invalid' : null ), 'placeholder' => 'Estado']) !!}
                        </div>
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection