<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenancePlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'asset_id'    => 'required|exists:assets,id',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'frequency'   => 'required|in:semanal,mensual,trimestral,semestral,anual',
            'next_run_at' => 'nullable|date|after_or_equal:today',
            'active'      => 'boolean',
        ];
    }
}
