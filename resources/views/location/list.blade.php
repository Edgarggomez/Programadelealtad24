<table class="table table-bordered">
    <tr>
        <th>Por Defecto</th>
        <th>Día</th>
        <th>Horario</th>
        <th>Porcentaje</th>
        <th>Acción</th>
    </tr>
    @foreach ($rules as $rule)
        <tr>
            <td>{!! Form::radio('tipo_lista', '', $rule->tipo, ['disabled']) !!}</td>
            <td>{{ $rule->days }}</td>
            <td>{{ $rule->hora_inicial . ' - ' . $rule->hora_final }}</td>
            <td>{{ $rule->porcentaje }} </td>
            <td>
                {!! Form::open(['method' => 'DELETE','route' => ['rules.destroy', $rule->id_regla],'style'=>'display:inline']) !!}
                                
                {!! Form::button('<svg class="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 00-1 1v1a1 1 0 001 1H3v9a2 2 0 002 2h6a2 2 0 002-2V4h.5a1 1 0 001-1V2a1 1 0 00-1-1H10a1 1 0 00-1-1H7a1 1 0 00-1 1H2.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM8 5a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 018 5zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clip-rule="evenodd"/>
                </svg>', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
</table>