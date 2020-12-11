@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pagar Bolsa</div>



                <div class="card-body">

                    <hr>
                    {!! Form::open(['method' => 'GET','route' => ['reportes.bolsaPagar']]) !!}
                    <div class="row">
                        <div class="col-8 form-group">
                            <input list="ubicaciones" name="ubicacion" id="ubicacion" class="form-control" placeholder="Buscar por ubicación">
                            <datalist id="ubicaciones">
                                @foreach ($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion->ubicacion }}">{{ $ubicacion->ubicacion }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-2">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 form-group">
                            {!! Form::label('inicio', 'Fecha inicio: ') !!}
                            {!! Form::date('inicio', old('inicio'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-4 form-group">
                            {!! Form::label('inicio', 'Fecha fin: ') !!}
                            {!! Form::date('fin', old('fin'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <span data-href="{{ route('reportes.bolsaPagarExportar') }}" id="export" class="btn btn-success btn-sm" onclick="exportTasks(event.target);">Exportar</span>
                    <hr>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Ubicación</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pagares as $pagar)
                                <tr>
                                    <td>{{ $pagar->ubicacion }}</td>
                                    <td>{{ $pagar->monto }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $pagares->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function exportTasks(_this) {
       let _url = $(_this).data('href') + '?' + window.location.href.split('?')[1];
       window.location.href = _url;
    }
 </script>
@endsection
