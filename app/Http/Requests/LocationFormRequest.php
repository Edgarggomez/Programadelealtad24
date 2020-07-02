<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'ubicacion' => 'required|string',
            'id_tda' => 'required|exists:tiendas_cc',
            'id_bd' => 'required|exists:bds_cc',
            'estatus' => 'required|boolean'
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
