<table class="table table-striped">
    <thead class="thead-dark">
    <tr>
        <th>Por Defecto</th>
        <th>Día</th>
        <th>Horario</th>
        <th>Porcentaje</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($rules as $rule)
        <tr>
            <td>{!! Form::radio('tipo_lista', '', $rule->tipo, ['disabled']) !!}</td>
            <td>{{ $rule->days }}</td>
            <td>{{ $rule->hora_inicial . ' - ' . $rule->hora_final }}</td>
            <td>{{ $rule->porcentaje }} </td>
            <td>
                {!! Form::open(['method' => 'DELETE','route' => ['rules.destroy', $rule->id_regla],'style'=>'display:inline']) !!}
                    <button type="submit" class="btn btn-danger btn-delete" disabled><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    <input type="checkbox" class="confirm-delete">
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>