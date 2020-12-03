@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">Historial de Movimientos</div>
            <div class="card-body">

                <hr>
                {!! Form::open(['method' => 'GET','route' => ['movimientosSaldo.index']]) !!}
                    <div class="row">
                        <div class="col-4 form-group">
                            {!! Form::label('search', 'Buscar:', ['class' => 'sr-only']) !!}
                            {!! Form::text('search', old('search'), ['class' => 'form-control', 'placeholder' => 'Buscar por nombre, tarjeta', 'autofocus']) !!}
                        </div>
                        <div class="col-4 form-group">
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
                        <div class="col-2 form-group">
                            {!! Form::label('inicio', 'Fecha inicio: ') !!}
                            {!! Form::date('inicio', old('inicio'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-2 form-group">
                            {!! Form::label('inicio', 'Fecha fin: ') !!}
                            {!! Form::date('fin', old('fin'), ['class' => 'form-control']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
                <span data-href="{{ route('movimientosSaldo.export') }}" id="export" class="btn btn-success btn-sm" onclick="exportTasks(event.target);">Exportar</span>
                <hr>
                    @if (isset($unCliente) && !empty($unCliente))
                        <div class="row">
                            <div class="col alert alert-dark text-center">
                                {{ $unCliente->nombre }}
                            </div>
                            <div class="col alert alert-dark text-center">
                                {{ $unCliente->saldo }}
                            </div>
                        </div>
                        <hr>
                    @endif

                <div class="row">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Tarjeta</th>
                                <th>Estatus Tarjeta</th>
                                <th>Fecha Mov.</th>
                                <th>Ubicación</th>
                                <th>Cargo/Abono</th>
                                <th>Origen</th>
                                <th>Folio</th>
                                <th>Monto</th>
                                <th>Saldo</th>
                                <th>Autor</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movimientos as $mov)
                                <tr>
                                    <td>{{ $mov->tarjeta->tarjeta }}</td>
                                    <td>{{ $mov->tarjeta->estatus ? 'Activo' : 'Inactivo' }}</td>
                                    <td>{{ $mov->fecha_mov }}</td>
                                    <td>{{ $mov->ubicacion->ubicacion ?? 'N/A' }}</td>
                                    <td>{{ $mov->tipo }}</td>
                                    <td>{{ $mov->origen }}</td>
                                    <td>{{ $mov->folio }}</td>
                                    <td>{{ $mov->monto }}</td>
                                    <td>{{ $mov->saldo_nuevo }}</td>
                                    <td>
                                        @switch($mov->tipo_usuario)
                                            @case('S')
                                                Sistema
                                                @break
                                            @case('C')
                                                Cliente
                                                @break
                                            @case('G')
                                                Gerente
                                                @break
                                            @default
                                                ''
                                        @endswitch
                                    </td>
                                    <td>{{ $mov->email_usuario ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    {!! $movimientos->render() !!}
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
