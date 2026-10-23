<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Deporte extends Model
{
    protected $fillable = ['nombre'];

    public function canchas(): HasMany
    {
        return $this->hasMany(Cancha::class);
    }

    public function reservas(): HasManyThrough
    {
        return $this->hasManyThrough(Reserva::class, Cancha::class);
    }
}
