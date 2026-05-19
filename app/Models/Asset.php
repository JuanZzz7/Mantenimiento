<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'location', 'status', 'acquisition_date',
        'brand', 'model', 'serial_number', 'category', 'description', 'image',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────────────────────
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function maintenancePlans()
    {
        return $this->hasMany(MaintenancePlan::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%");
        });
    }

    // ─── Accessors ────────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'activo'          => 'Activo',
            'inactivo'        => 'Inactivo',
            'en_mantenimiento'=> 'En Mantenimiento',
            default           => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'activo'           => 'success',
            'inactivo'         => 'secondary',
            'en_mantenimiento' => 'warning',
            default            => 'info',
        };
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('img/asset-default.png');
    }

    public function getLastMaintenanceAttribute()
    {
        return $this->workOrders()
                    ->where('status', 'completada')
                    ->latest('completed_at')
                    ->first();
    }
}
