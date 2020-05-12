@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                @if(isset($ubication))
                    <div class="card-header">Modificar Regla</div>
                @else
                    <div class="card-header">Alta Regla</div>
                @endif

                <div class="card-body">
                    @if(isset($location))
                        {!! Form::model($location, ['route' => ['locations.edit', $location->id_ubicacion], 'method' => 'get']) !!}
                    @endif
                        <div class="form-group">
                            {!! Form::label('ubicacion', 'Ubicación:') !!}
                            {!! Form::text('ubicacion', old('ubicacion'), ['class' => 'form-control' . ( $errors->has('ubicacion') ? ' is-invalid' : null ), 'placeholder' => 'Escriba la ubicación', 'disabled' => true]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('estatus', 'Estado:') !!}
                            {!! Form::select('estatus', [1 => 'Activo', 0 => 'Inactivo'], old('estatus'), ['class' => 'form-control' . ( $errors->has('estatus') ? ' is-invalid' : null ), 'placeholder' => 'Estado', 'disabled' => true]) !!}
                        </div>
                        {!! Form::submit('Editar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                    <hr/>
                    @includeWhen($location->rules->isNotEmpty(), 'location.list', ['rules' => $location->rules])
                    @if(isset($rule))
                        {!! Form::model($rule, ['route' => ['rules.update', $rule->id_regla], 'method' => 'patch']) !!}
                    @else
                        {!! Form::open(['route' => 'rules.store']) !!}
                    @endif
                        <div class="form-group">
                            {!! Form::hidden('id_ubicacion', $location->id_ubicacion) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('porcentaje', 'Porcentaje:') !!}
                            {!! Form::number('porcentaje', old('porcentaje'), ['min' => '0', 'max' => '100', 'class' => 'form-control' . ( $errors->has('porcentaje') ? ' is-invalid' : null ), 'placeholder' => 'Escriba el porcentaje']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('horario', 'Horario:') !!}
                            <div class="row">
                                <div class="col">
                                    {!! Form::select('hora_inicial', $hours, old('hora_inicial'), ['class' => 'form-control' . ( $errors->has('hora_inicial') ? ' is-invalid' : null ), 'placeholder' => 'Inicio']) !!}
                                </div>
                                <div class="col">
                                    {!! Form::select('hora_final', $hours, old('hora_final'), ['class' => 'form-control' . ( $errors->has('hora_final') ? ' is-invalid' : null ), 'placeholder' => 'Fin']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    {!! Form::checkbox('lunes', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="lunes">Lunes</label>
                                </div>
                                <div class="col">
                                    {!! Form::checkbox('martes', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="martes">Martes</label>
                                </div>
                                <div class="col">
                                    {!! Form::checkbox('miercoles', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="miercoles">Miercoles</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    {!! Form::checkbox('jueves', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="jueves">Jueves</label>
                                </div>
                                <div class="col">
                                    {!! Form::checkbox('viernes', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="viernes">Viernes</label>
                                </div>
                                <div class="col">
                                    {!! Form::checkbox('sabado', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="sabado">Sabado</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    {!! Form::checkbox('domingo', 1, false, ['class' => 'form-check-input']) !!}
                                    <label class="form-check-label" for="domingo">Domingo</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::checkbox('tipo', 1, false, ['class' => 'form-check-input']) !!}
                            <label class="form-check-label" for="domingo">Por defecto</label>
                        </div>
                        {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection