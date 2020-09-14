<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CardFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['tarjeta'] = 'required|unique:tarjetas,id_tarjeta';
        $rules['nombre'] = 'required|string|max:255';

        /* if (!$this->adicional) {
            $rules['id_cliente'] = Rule::unique('tarjetas')->where(function ($query) {
                $query->where('adicional', false);
            });
        } */

        return $rules;
    }

    public function messages()
    {
        return [
            'id_cliente.unique' => 'Solo puede existir una tarjeta principal por cliente',
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'tarjeta' => 'Tarjeta'
        ];
    }
}
