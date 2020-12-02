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
        return [
            'tarjeta' => 'required|unique:tarjetas,tarjeta|exists:tarjetas_cc',
            'nombre' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'tarjeta.exists' => 'El número de tarjeta ingresado no existe',
            'tarjeta.unique' => 'El número de tarjeta ya está asignada a otro cliente'
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
