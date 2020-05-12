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
            <td>{!! Form::radio('tipo', 1, $rule->tipo, ['disabled']) !!}</td>
            <td>{{ $rule->days }}</td>
            <td>{{ $rule->hora_inicial . ' - ' . $rule->hora_final }}</td>
            <td>{{ $rule->porcentaje }} </td>
            <td>--</td>
        </tr>
    @endforeach
</table>