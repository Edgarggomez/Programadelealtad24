@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configuración primer consumo</div>
                <div class="card-body">
                    @if(isset($setting))
                        {!! Form::model($setting, ['route' => ['settings.update', $setting->id], 'method' => 'patch']) !!}
                    @else
                        {!! Form::open(['route' => 'settings.store']) !!}
                    @endif
                        {!! Form::hidden('name', 'PRIMER_CONSUMO') !!}
                        <div class="form-group">
                            {!! Form::label('value', 'Monto:') !!}
                            {!! Form::number('value', old('value'), ['step' => '.01', 'class' => 'form-control' . ( $errors->has('value') ? ' is-invalid' : null ), 'placeholder' => 'Escriba el monto']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('status', 'Estado:') !!}
                            {!! Form::select('status', [1 => 'Activo', 0 => 'Inactivo'], old('status'), ['class' => 'form-control' . ( $errors->has('status') ? ' is-invalid' : null ), 'placeholder' => 'Estado']) !!}
                        </div>
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection