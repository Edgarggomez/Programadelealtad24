@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">Administración de Cliente</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-right">
                            <a class="btn btn-primary" href="{{ route('clients.create') }}"> Crear Nuevo Cliente</a>
                        </div>
                    </div>
                </div>
                <hr>
                {!! Form::open(['method' => 'GET','route' => ['clients.index']]) !!}
                    <div class="row">
                        <div class="col">
                            {!! Form::label('search', 'Buscar:', ['class' => 'sr-only']) !!}
                            {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Buscar por nombre, correo', 'autofocus']) !!}
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
                            <th>Tarjeta</th>
                            <th>Saldo</th>
                            <th>Estatus</th>
                            <th>Tarjeta Adicional</th>
                            <th colspan="2">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $key => $client)
                        
                            <tr>
                                <td>{{ $client->nombre }}</td>
                                <td>{{ $client->mainCardNumber }}</td>
                                <td>{{ $client->saldo }}</td>
                                <td>{{ $client->estatus == '1' ? 'Activo' : 'Inactivo' }}</td>
                                <td>
                                    {{-- Radio button for Adtional Cards --}}
                                </td>
                                <td>
                                    @if ($client->flotilla)
                                        <a href="{{ route('cards.create', $client->id_cliente)}}" class="btn btn-primary" ><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-success" href="{{ route('clients.edit', $client->id_cliente) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                    @can('baja cliente')
                                        {!! Form::open(['method' => 'DELETE','route' => ['clients.destroy', $client->id_cliente],'style'=>'display:inline']) !!}
                                            <button type="submit" class="btn btn-danger btn-delete" disabled><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            <input type="checkbox" class="confirm-delete">
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $clients->render() !!}
            </div>
        </div>
    </div>
</div>
@endsection