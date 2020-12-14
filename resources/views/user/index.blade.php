@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Administración de Usuario</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('users.create') }}"> Crear Nuevo Usuario</a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! Form::open(['method' => 'GET','route' => ['users.index']]) !!}
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
                                <th>Correo</th>
                                <th>Estatus</th>
                                <th>Roles</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->status == '1' ? 'Activo' : 'Inactivo' }}</td>
                                    <td>
                                        @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $v)
                                                <label class="badge badge-info">{{ $v }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-success" href="{{ route('users.edit', $user->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}

                                            <button type="submit" class="btn btn-danger btn-delete" disabled="false"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                            <input type="checkbox" class="confirm-delete">

                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $users->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
