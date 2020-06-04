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
                                
                                            {!! Form::button('<svg class="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 00-1 1v1a1 1 0 001 1H3v9a2 2 0 002 2h6a2 2 0 002-2V4h.5a1 1 0 001-1V2a1 1 0 00-1-1H10a1 1 0 00-1-1H7a1 1 0 00-1 1H2.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM8 5a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 018 5zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clip-rule="evenodd"/>
                                            </svg>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                            
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