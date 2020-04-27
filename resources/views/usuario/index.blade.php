@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Administración de Usuario</div>
        
        <div class="card-body">
          <div class="row">
            
            <div class="col-lg-12 margin-tb">
              
              <div class="pull-right">
                
                <a class="btn btn-primary" href="{{ route('usuarios.create') }}"> Crear Nuevo Usuario</a>
                
              </div>
              
            </div>
            
          </div>
          
          
          @if ($message = Session::get('success'))
          
          <div class="alert alert-success">
            
            <p>{{ $message }}</p>
            
          </div>
          
          @endif
          
          
          <table class="table table-bordered">
            
            <tr>
              
              <th>No</th>
              
              <th>Nombre</th>
              
              <th>Correo</th>
              
              <th>Roles</th>
              
              <th>Acción</th>
              
            </tr>
            
            @foreach ($users as $key => $user)
            
            <tr>
              
              <td>{{ $user->id }}</td>
              
              <td>{{ $user->name }}</td>
              
              <td>{{ $user->email }}</td>
              
              <td>
                
                @if(!empty($user->getRoleNames()))
                
                @foreach($user->getRoleNames() as $v)
                
                <label class="badge badge-success">{{ $v }}</label>
                
                @endforeach
                
                @endif
                
              </td>
              
              <td>
                
                <a class="btn btn-primary" href="{{ route('usuarios.edit',$user->id) }}">Editar</a>
                
                {!! Form::open(['method' => 'DELETE','route' => ['usuarios.destroy', $user->id],'style'=>'display:inline']) !!}
                
                {!! Form::submit('Elimiar', ['class' => 'btn btn-danger']) !!}
                
                {!! Form::close() !!}
                
              </td>
              
            </tr>
            
            @endforeach
            
          </table>
          
          
          {!! $users->render() !!}
          
        </div>
      </div>
    </div>
  </div>
</div>


@endsection