<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderSpare extends Model
{
    use HasFactory;

    protected $table = 'work_order_spares';

    protected $fillable = [
        'work_order_id', 'spare_id', 'quantity', 'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'integer',
            'unit_price' => 'float',
        ];
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function spare()
    {
        return $this->belongsTo(Spare::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}
