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
            'rfc' => ['required', 'regex:/[A-Z]{4}[0-9]{6}[A-Z0-9]{3}/'],
            'correo' => ['required','email','max:255', Rule::unique('clientes')->ignore($this->id_cliente, 'id_cliente')],
            'celular' => 'required|max:255',
            'sexo' => ['required', Rule::in(['F', 'M'])],
            'flotilla' => 'boolean',
            'estatus' => [Rule::in([0, 1, 1])],
            'fecha_nacimiento' => 'date',
            'tarjeta' => ['required', Rule::unique('tarjetas')->where(function ($query) {
                return $query->whereNotIn('id_cliente', ["''", $this->id_cliente ? $this->id_cliente : "''"]);
            })]
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
