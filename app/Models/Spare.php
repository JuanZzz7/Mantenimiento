<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Spare extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'description', 'unit',
        'stock', 'stock_min', 'price', 'supplier', 'location', 'category'
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'float',
            'stock'     => 'integer',
            'stock_min' => 'integer',
        ];
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────
    public function workOrders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_spares')
                    ->withPivot(['quantity', 'unit_price'])
                    ->withTimestamps();
    }

    public function workOrderSpares()
    {
        return $this->hasMany(WorkOrderSpare::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_min');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('supplier', 'like', "%{$term}%");
        });
    }

    // ─── Accessors ───────────────────────────────────────────────────────────
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->stock_min;
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->stock === 0) return 'danger';
        if ($this->stock <= $this->stock_min) return 'warning';
        return 'success';
    }

    // ─── Métodos ─────────────────────────────────────────────────────────────
    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);

        if ($this->stock <= $this->stock_min) {
            $admins = User::where('role', 'admin')->where('active', true)->get();
            foreach ($admins as $admin) {
                // Notificar solo si no hay una notificación idéntica reciente (opcional, pero buena práctica)
                $admin->notify(new \App\Notifications\LowStockAlert($this));
            }
        }
    }

    public function increaseStock(int $qty): void
    {
        $this->increment('stock', $qty);
    }
}
