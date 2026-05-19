<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id'       => 'required|exists:assets,id',
            'type'           => 'required|in:correctiva,preventiva',
            'priority'       => 'required|in:baja,media,alta,critica',
            'status'         => 'required|in:pendiente,en_proceso,completada,cancelada',
            'description'    => 'required|string|min:10|max:2000',
            'assigned_to'    => 'nullable|exists:users,id',
            'scheduled_date' => 'nullable|date',
            'started_at'     => 'nullable|date',
            'completed_at'   => 'nullable|date|after_or_equal:started_at',
            'notes'          => 'nullable|string|max:2000',
        ];
    }
}
