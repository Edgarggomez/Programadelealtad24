@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Agregar tarjeta</div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row justify-content-md-center">
                                <div class="col alert alert-dark">
                                    <h4 class="text-center">Tarjeta Principal</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col alert alert-dark text-center">
                                    {{ $client->mainCardName }}
                                </div>
                                <div class="col alert alert-dark text-center">
                                    {{ $client->mainCardNumber }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-center alert alert-light">Tarjetas Adicionales</h4>
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Tarjeta</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->cards as $card)
                                    <tr>
                                        <td>{{ $card->tarjeta }}</td>
                                        <td>{{ $card->nombre }}</td>
                                        <td>
                                            {!! Form::open(['method' => 'DELETE','route' => ['cards.destroy', $card->id_tarjeta],'style'=>'display:inline']) !!}
                                                <button type="submit" class="btn btn-danger btn-delete" disabled><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                <input type="checkbox" class="confirm-delete">
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <h4 class="text-center alert alert-light">Nueva Tarjeta Adicional</h4>
                        <form method="post" action="{{ route('cards.store') }}">
                            @csrf
                            <input type="hidden" name="id_cliente" value="{{ $client->id_cliente }}">
                            <div class="row">
                                <div class="col">    
                                    <label for="tarjeta" class="sr-only">Tarjeta:</label>
                                    <input type="text" class="form-control" name="tarjeta" placeholder="Tarjeta"/>
                                </div>
                                <div class="col">
                                    <label for="nombre" class="sr-only">Nombre:</label>
                                    <input type="text" class="form-control" name="nombre" placeholder="Nombre"/>
                                </div>
                                <div class="col">
                                    <div></div>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection