<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;

class RuleFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'porcentaje' => 'required|numeric|min:0|max:100',
            'hora_inicial' => 'required|numeric|min:0|max:23',
            'hora_final' => 'required|numeric|min:0|max:23',
            'lunes' => 'required_without_all:martes,miercoles,jueves,viernes,sabado,domingo',
            'tipo' => Rule::unique('reglas')->where(function ($query) {
                $query->where([['tipo', true], ['id_ubicacion', $this->id_ubicacion]]);
            })
        ];
    }

    public function messages()
    {
        return [
            'tipo.unique' => 'Solo puede existir una regla por defecto',
            'lunes.required_without_all' => 'Debe seleccionarse al menos un día'
        ];
    }

    public function attributes()
    {
        return [
             'porcentaje' => 'Porcentaje',
             'hora_inicial' => 'Hora de inicio',
             'hora_final' => 'Hora de cierre',
        ];
    }
}
