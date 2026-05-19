<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'asset_id', 'type', 'priority', 'status', 'description',
        'assigned_to', 'created_by', 'maintenance_plan_id',
        'scheduled_date', 'started_at', 'completed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'datetime',
            'started_at'     => 'datetime',
            'completed_at'   => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (! $model->code) {
                $count = static::withTrashed()->count() + 1;
                $model->code = 'OT-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function maintenancePlan()
    {
        return $this->belongsTo(MaintenancePlan::class);
    }

    public function spares()
    {
        return $this->belongsToMany(Spare::class, 'work_order_spares')
                    ->withPivot(['quantity', 'unit_price'])
                    ->withTimestamps();
    }

    public function workOrderSpares()
    {
        return $this->hasMany(WorkOrderSpare::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'en_proceso');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completada');
    }

    public function scopeForTecnico($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('code', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhereHas('asset', fn($a) => $a->where('name', 'like', "%{$term}%"));
        });
    }

    // ─── Accessors ───────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendiente'   => 'Pendiente',
            'en_proceso'  => 'En Proceso',
            'completada'  => 'Completada',
            'cancelada'   => 'Cancelada',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendiente'  => 'warning',
            'en_proceso' => 'info',
            'completada' => 'success',
            'cancelada'  => 'secondary',
            default      => 'light',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'baja'    => 'success',
            'media'   => 'info',
            'alta'    => 'warning',
            'critica' => 'danger',
            default   => 'secondary',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'baja'    => 'Baja',
            'media'   => 'Media',
            'alta'    => 'Alta',
            'critica' => 'Crítica',
            default   => ucfirst($this->priority),
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'correctiva'  => 'Correctiva',
            'preventiva'  => 'Preventiva',
            default       => ucfirst($this->type),
        };
    }

    public function getTotalCostAttribute(): float
    {
        return $this->workOrderSpares->sum(fn($s) => $s->quantity * $s->unit_price);
    }
}
