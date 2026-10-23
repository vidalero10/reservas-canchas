<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cancha extends Model
{
    protected $fillable = ['deporte_id', 'nombre', 'tarifa_diurna', 'tarifa_nocturna', 'activa'];

    protected function casts(): array
    {
        return [
            'tarifa_diurna' => 'decimal:2',
            'tarifa_nocturna' => 'decimal:2',
            'activa' => 'boolean',
        ];
    }

    public function deporte(): BelongsTo
    {
        return $this->belongsTo(Deporte::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
