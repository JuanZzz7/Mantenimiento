<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'code'             => 'required|string|max:50|unique:assets,code,' . $this->route('asset'),
            'name'             => 'required|string|max:150',
            'location'         => 'required|string|max:100',
            'status'           => 'required|in:activo,inactivo,en_mantenimiento',
            'acquisition_date' => 'nullable|date',
            'brand'            => 'nullable|string|max:100',
            'model'            => 'nullable|string|max:100',
            'serial_number'    => 'nullable|string|max:100',
            'category'         => 'nullable|string|max:100',
            'description'      => 'nullable|string|max:1000',
            'image'            => 'nullable|image|max:2048',
        ];
    }
}
