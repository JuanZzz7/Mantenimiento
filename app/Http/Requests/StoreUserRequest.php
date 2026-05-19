<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('user') ?? 'NULL';
        return [
            'name'     => 'required|string|max:100',
            'email'    => "required|email|unique:users,email,{$userId}",
            'password' => $userId === 'NULL' ? 'required|min:8|confirmed' : 'nullable|min:8|confirmed',
            'role'     => 'required|in:admin,tecnico',
            'phone'    => 'nullable|string|max:20',
            'active'   => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Ya existe un usuario con ese correo.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
