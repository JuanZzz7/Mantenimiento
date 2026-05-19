<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id', 'name', 'description', 'frequency',
        'last_run_at', 'next_run_at', 'active',
    ];

    protected function casts(): array
    {
        return [
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
            'active'      => 'boolean',
        ];
    }

    // ─── Relaciones ───────────────────────────────────────────────────────────
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeDue($query)
    {
        return $query->active()->where('next_run_at', '<=', now());
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'semanal'     => 'Semanal (7 días)',
            'mensual'     => 'Mensual (30 días)',
            'trimestral'  => 'Trimestral (90 días)',
            'semestral'   => 'Semestral (180 días)',
            'anual'       => 'Anual (365 días)',
            default       => ucfirst($this->frequency),
        };
    }

    public function getFrequencyDaysAttribute(): int
    {
        return match ($this->frequency) {
            'semanal'    => 7,
            'mensual'    => 30,
            'trimestral' => 90,
            'semestral'  => 180,
            'anual'      => 365,
            default      => 30,
        };
    }

    public function calculateNextRun(): \Carbon\Carbon
    {
        $base = $this->last_run_at ?? now();
        return $base->copy()->addDays($this->frequency_days);
    }
}
