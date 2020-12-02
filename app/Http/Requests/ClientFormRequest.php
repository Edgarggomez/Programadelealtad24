<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientFormRequest extends FormRequest
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
            'id_ubicacion' => 'required|numeric',
            'nombre' => 'required|string|max:255',
            'rfc' => ['required'],
            'correo' => ['required','email','max:255', Rule::unique('clientes')->ignore($this->id_cliente, 'id_cliente')],
            'celular' => ['required', 'regex:/^[0-9]{11}$/'],
            'sexo' => ['required', Rule::in(['F', 'M'])],
            'flotilla' => 'boolean',
            'estatus' => [Rule::in([0, 1, 2])],
            'fecha_nacimiento' => 'date',
            'tarjeta' => 'required|exists:tarjetas_cc,tarjeta|unique:tarjetas,tarjeta,' . $this->id_cliente . ',id_cliente'
        ];
    }

    public function messages()
    {
        return [
            'celular.regex' => 'El número de celular debe contener 11 digitos númericos',
            'tarjeta.exists' => 'El número de tarjeta ingresado no ha sido creado',
            'tarjeta.unique' => 'El número de tarjeta ya está asignado a otro cliente'
        ];
    }

    public function attributes()
    {
        return [
            'nombre' => 'Nombre',
            'rfc' => 'RFC',
            'correo' => 'Correo Electronico',
            'celular' => 'Celular',
            'estatus' => 'Estado',
            'flotilla' => 'Flotilla',
            'tarjeta' => 'Tarjeta',
            'membresia' => 'Membresia',
            'id_ubicacion' => 'Ubicacion',
            'sexo' => 'Sexo',
        ];
    }
}
