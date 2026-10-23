<?php

namespace App\Models;

use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    public const ESTADO_CONFIRMADA = 'confirmada';
    public const ESTADO_CANCELADA = 'cancelada';

    protected $fillable = [
        'cliente_id', 'cancha_id', 'fecha', 'hora_inicio', 'duracion_horas',
        'hora_fin', 'monto', 'estado', 'cancelada_en',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cancelada_en' => 'datetime',
            'monto' => 'decimal:2',
            'duracion_horas' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Reserva $reserva): void {
            $reserva->prepararReserva();
        });
    }

    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function cancha(): BelongsTo { return $this->belongsTo(Cancha::class); }

    public function scopeConfirmadas(Builder $query): Builder
    {
        return $query->where('estado', self::ESTADO_CONFIRMADA);
    }

    public function scopeDelDia(Builder $query, mixed $fecha): Builder
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopeDeCancha(Builder $query, int|string $canchaId): Builder
    {
        return $query->where('cancha_id', $canchaId);
    }

    public function cancelar(): void
    {
        $inicio = Carbon::parse($this->fecha)
            ->setTimeFromTimeString((string) $this->hora_inicio);

        // Un dia completo permite liberar y volver a ofrecer el horario.
        if ($inicio->lt(now()->addHours(24))) {
            throw new DomainException('La reserva solo se puede cancelar con 24 horas o mas de anticipacion.');
        }

        $this->estado = self::ESTADO_CANCELADA;
        $this->cancelada_en = now();
        $this->save();
    }

    private function prepararReserva(): void
    {
        $duracion = (int) $this->duracion_horas;
        if ($duracion < 1 || $duracion > 4) {
            throw new DomainException('La duracion debe ser de 1 a 4 horas.');
        }

        $inicio = Carbon::parse((string) $this->hora_inicio);
        $inicioMinutos = ((int) $inicio->format('H')) * 60 + (int) $inicio->format('i');
        if ($inicioMinutos < 8 * 60 || $inicioMinutos >= 23 * 60) {
            throw new DomainException('El inicio debe ser desde las 08:00 y antes de las 23:00.');
        }

        $finMinutos = $inicioMinutos + ($duracion * 60);
        if ($finMinutos > 23 * 60) {
            throw new DomainException('La reserva debe terminar a mas tardar a las 23:00.');
        }

        $fin = $inicio->copy()->addHours($duracion);
        $this->hora_inicio = $inicio->format('H:i:s');
        $this->hora_fin = $fin->format('H:i:s');

        $cancha = Cancha::find($this->cancha_id);
        if (!$cancha) {
            throw new DomainException('La cancha indicada no existe.');
        }

        $montoCentavos = 0;
        for ($hora = 0; $hora < $duracion; $hora++) {
            $horaCobro = $inicio->copy()->addHours($hora);
            $tarifa = (int) $horaCobro->format('H') >= 18
                ? $cancha->tarifa_nocturna
                : $cancha->tarifa_diurna;
            $montoCentavos += (int) round(((float) $tarifa) * 100);
        }
        $this->monto = number_format($montoCentavos / 100, 2, '.', '');

        // Un indice unique solo detecta duplicados exactos; aqui se revisan los solapes.
        $solapada = static::query()
            ->confirmadas()
            ->where('cancha_id', $this->cancha_id)
            ->whereDate('fecha', $this->fecha)
            ->where('hora_inicio', '<', $this->hora_fin)
            ->where('hora_fin', '>', $this->hora_inicio)
            ->exists();

        if ($solapada && ($this->estado ?? self::ESTADO_CONFIRMADA) === self::ESTADO_CONFIRMADA) {
            throw new DomainException('La cancha ya tiene una reserva confirmada que se solapa con ese horario.');
        }
    }
}
