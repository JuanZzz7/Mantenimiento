<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'active', 'locale',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'active'            => 'boolean',
        ];
    }

    // ─── Roles ───────────────────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTecnico(): bool
    {
        return $this->role === 'tecnico';
    }

    // ─── Relaciones ───────────────────────────────────────────────────────────
    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function createdWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeTecnicos($query)
    {
        return $query->where('role', 'tecnico');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initials = urlencode(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=1e40af&color=fff&size=128";
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'   => 'Administrador',
            'tecnico' => 'Técnico',
            default   => ucfirst($this->role),
        };
    }
}
