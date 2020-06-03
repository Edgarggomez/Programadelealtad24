@extends('base')

@section('main')
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1 class="display-3">Agregar tarjeta</h1>
        <div>
            <form method="post" action="{{ route('cards.store') }}">
                @csrf
                <input type="hidden" name="client_id" value="{{$client->id}}">
                <div class="form-group row">    
                    <label class="col-sm-2 col-form-label">Nombre:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control-plaintext" readonly value="{{ $client->name }}"/>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tarjeta Principal:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control-plaintext" readonly value="{{ $client->card }}"/>
                    </div>
                </div>
                
                @foreach ($cards as $card)
                <div class="form-group">    
                    <label >Tarjeta:</label>
                    <input type="text" class="form-control" readonly value="{{ $card->card }}"/>
                </div>
                
                <div class="form-group">
                    <label >Nombre:</label>
                    <input type="text" class="form-control" readonly value="{{ $card->name }}"/>
                </div>
                @endforeach
                
                <div class="form-group">    
                    <label for="card">Tarjeta:</label>
                    <input type="text" class="form-control" name="card"/>
                </div>
                
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" class="form-control" name="name"/>
                </div>
                
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection