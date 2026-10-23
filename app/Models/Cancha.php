<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cancha extends Model
{
    use HasFactory;

    protected $fillable = ['deporte_id', 'nombre', 'tarifa_diurna', 'tarifa_nocturna', 'activa', 'descripcion', 'imagen'];

    protected function casts(): array
    {
        return ['tarifa_diurna' => 'decimal:2', 'tarifa_nocturna' => 'decimal:2', 'activa' => 'boolean'];
    }

    public function deporte(): BelongsTo { return $this->belongsTo(Deporte::class); }
    public function reservas(): HasMany { return $this->hasMany(Reserva::class); }

    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->imagen)) {
            return \Illuminate\Support\Facades\Storage::url($this->imagen);
        }
        $deporte = mb_strtolower($this->deporte?->nombre ?? '', 'UTF-8');
        if (str_contains($deporte, 'squet') || str_contains($deporte, 'bas')) {
            return asset('images/basquet.jpg');
        }
        if (str_contains($deporte, 'ley') || str_contains($deporte, 'vol')) {
            return asset('images/voley.jpg');
        }
        return asset('images/futbol.jpg');
    }

    public function getIconoAttribute(): string
    {
        $deporte = mb_strtolower($this->deporte?->nombre ?? '', 'UTF-8');
        if (str_contains($deporte, 'squet') || str_contains($deporte, 'bas')) {
            return '🏀';
        }
        if (str_contains($deporte, 'ley') || str_contains($deporte, 'vol')) {
            return '🏐';
        }
        return '⚽';
    }

    public function getSuperficieAttribute(): string
    {
        $deporte = mb_strtolower($this->deporte?->nombre ?? '', 'UTF-8');
        if (str_contains($deporte, 'squet') || str_contains($deporte, 'bas')) {
            return 'Parquet Flotante Pro';
        }
        if (str_contains($deporte, 'ley') || str_contains($deporte, 'vol')) {
            return 'Piso Deportivo Sintético';
        }
        return 'Césped Sintético 50mm';
    }
}