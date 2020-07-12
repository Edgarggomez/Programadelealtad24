@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Saldo Adicional</div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row justify-content-md-center">
                                <div class="col alert alert-dark">
                                    <h4 class="text-center">{{ $client->nombre }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col alert alert-dark text-center">
                                    {{ $client->nombreTarjeta }}
                                </div>
                                <div class="col alert alert-dark text-center">
                                    {{ $client->tarjeta }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col alert alert-dark text-right">
                                    {{ $client->saldo ?? '0' }}
                                </div>
                            </div>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('clients.updateBalance', $client->id_cliente) }}">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label for="saldo_adicional" class="sr-only">Monto a añadir:</label>
                                    <input type="text" class="form-control" name="saldo_adicional" placeholder="Monto a añadir"/>
                                </div>
                                <div class="col">
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