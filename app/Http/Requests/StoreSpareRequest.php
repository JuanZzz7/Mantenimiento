<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpareRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $spare   = $this->route('spare');
        $spareId = $spare instanceof \App\Models\Spare ? $spare->id : 'NULL';
        return [
            'code'      => "required|string|max:50|unique:spares,code,{$spareId},id,deleted_at,NULL",
            'name'      => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'unit'      => 'required|string|max:30',
            'stock'     => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'price'     => 'required|numeric|min:0',
            'supplier'  => 'nullable|string|max:150',
            'location'  => 'nullable|string|max:100',
            'category'  => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Ya existe un repuesto con ese código.',
            'stock.min'   => 'El stock no puede ser negativo.',
            'price.min'   => 'El precio no puede ser negativo.',
        ];
    }
}
