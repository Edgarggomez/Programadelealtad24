<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LocationFormRequest extends FormRequest
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
            'ubicacion' => 'required|string|max:30|unique:ubicaciones,ubicacion,'. $this->id_ubicacion .',id_ubicacion',
            'id_tda' => ['exists:tiendas_cc', Rule::unique('ubicaciones')->ignore($this->id_ubicacion, 'id_ubicacion')],
            'id_bd' => 'required|exists:bds_cc',
            'estatus' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'id_tda.unique' => 'Esta tienda ya está asignada a otra ubicación',
            'ubicacion.unique' => 'Esta ubicación ya existe en la base de datos'
        ];
    }

    public function attributes()
    {
        return [
            'ubicacion' => 'Ubicación',
            'id_tda' => 'Tienda',
            'id_bd' => 'Base de datos',
            'estatus' => 'Estado'
        ];
    }
}
