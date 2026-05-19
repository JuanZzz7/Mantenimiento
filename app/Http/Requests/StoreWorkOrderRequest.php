<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id'       => 'required|exists:assets,id',
            'type'           => 'required|in:correctiva,preventiva',
            'priority'       => 'required|in:baja,media,alta,critica',
            'description'    => 'required|string|min:10|max:2000',
            'assigned_to'    => 'nullable|exists:users,id',
            'scheduled_date' => 'nullable|date',
            'notes'          => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'asset_id.required'    => 'Debe seleccionar un activo.',
            'asset_id.exists'      => 'El activo seleccionado no existe.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min'      => 'La descripción debe tener al menos 10 caracteres.',
        ];
    }
}
