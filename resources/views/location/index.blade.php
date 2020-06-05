@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Búsqueda de Ubicación</div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('locations.create') }}">Nueva Ubicación</a>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    {!! Form::open(['method' => 'GET','route' => ['locations.index']]) !!}
                        <div class="row">
                            <div class="col">
                                {!! Form::label('search', 'Buscar:', ['class' => 'sr-only']) !!}
                                {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Buscar por nombre', 'autofocus']) !!}
                            </div>
                            <div class="col">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <hr>
                    
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Estatus</th>
                                <th>Regla</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        @foreach ($locations as $location)
                        <tr>
                            <td>{{ $location->ubicacion }}</td>
                            <td>{{ $location->estatus ? 'Activo' : 'Inactivo' }}</td>
                            <td>
                                <a href="{{ route('rules.create', $location->id_ubicacion) }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </td>
                            <td>
                                <a class="btn btn-success" href="{{ route('locations.edit',$location->id_ubicacion) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                
                                {!! Form::open(['method' => 'DELETE','route' => ['locations.destroy', $location->id_ubicacion],'style'=>'display:inline']) !!}
                                    <button type="submit" class="btn btn-danger btn-delete" disabled><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    <input type="checkbox" class="confirm-delete">
                                {!! Form::close() !!}
                                
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    {!! $locations->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection