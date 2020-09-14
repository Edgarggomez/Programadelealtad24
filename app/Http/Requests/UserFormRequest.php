<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255',  Rule::unique('users')->ignore($this->id)],
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(Role::all()->pluck('name')->toArray())],
            'status' => ['required', Rule::in([0, 1, -1])],
            'id_ubicacion' => 'nullable|numeric'
        ];
    }

    public function attributes()
    {
        return [
             'name' => 'Nombre',
             'email' => 'Correo Electronico',
             'password' => 'Clave',
             'role' => 'Rol',
             'status' => 'Estado',
             'id_ubicacion' => 'Ubicación',
        ];
    }
}
