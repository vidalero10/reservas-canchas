<?php

namespace App\Models;

use Carbon\CarbonImmutable;
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
        'cliente_id',
        'cancha_id',
        'fecha',
        'hora_inicio',
        'duracion_horas',
        'hora_fin',
        'monto',
        'estado',
        'cancelada_en',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cancelada_en' => 'datetime',
            'monto' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Reserva $reserva): void {
            $reserva->prepararReserva();
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cancha(): BelongsTo
    {
        return $this->belongsTo(Cancha::class);
    }

    public function scopeConfirmadas(Builder $consulta): Builder
    {
        return $consulta->where('estado', self::ESTADO_CONFIRMADA);
    }

    public function scopeDelDia(Builder $consulta, mixed $fecha): Builder
    {
        return $consulta->whereDate('fecha', CarbonImmutable::parse($fecha)->toDateString());
    }

    public function scopeDeCancha(Builder $consulta, int $canchaId): Builder
    {
        return $consulta->where('cancha_id', $canchaId);
    }

    public function cancelar(): void
    {
        if ($this->estado !== self::ESTADO_CONFIRMADA) {
            throw new DomainException('Solo se pueden cancelar reservas confirmadas.');
        }

        $inicio = CarbonImmutable::parse($this->fecha->toDateString().' '.$this->hora_inicio);

        if (now()->greaterThan($inicio->subHours(24))) {
            throw new DomainException('La reserva debe cancelarse con al menos 24 horas de anticipación.');
        }

        $this->estado = self::ESTADO_CANCELADA;
        $this->cancelada_en = now();
        $this->save();
    }

    private function prepararReserva(): void
    {
        // La creación desde Eloquent debe respetar el mismo estado confirmado que define la base.
        $this->estado ??= self::ESTADO_CONFIRMADA;

        if (! is_numeric($this->duracion_horas) || $this->duracion_horas < 1 || $this->duracion_horas > 4) {
            throw new DomainException('La duración debe estar entre 1 y 4 horas.');
        }

        $inicio = CarbonImmutable::parse(CarbonImmutable::parse($this->fecha)->toDateString().' '.$this->hora_inicio);
        $fin = $inicio->addHours((int) $this->duracion_horas);

        // El horario operativo limita el inicio y el fin para no aceptar reservas fuera de atención.
        if ($inicio->format('H:i:s') < '08:00:00' || $fin->format('H:i:s') > '23:00:00') {
            throw new DomainException('La reserva debe quedar dentro del horario de 08:00 a 23:00.');
        }

        $this->hora_fin = $fin->format('H:i:s');
        $cancha = Cancha::find($this->cancha_id);

        if (! $cancha) {
            throw new DomainException('La cancha indicada no existe.');
        }

        $monto = 0.0;
        for ($hora = 0; $hora < (int) $this->duracion_horas; $hora++) {
            $horaFacturada = $inicio->addHours($hora);
            $monto += (float) ($horaFacturada->format('H:i:s') >= '18:00:00'
                ? $cancha->tarifa_nocturna
                : $cancha->tarifa_diurna);
        }

        $this->monto = number_format($monto, 2, '.', '');

        // Un índice unique solo evita duplicados exactos; los intervalos solapados requieren esta validación.
        if ($this->estado === self::ESTADO_CONFIRMADA) {
            $haySolape = self::query()
                ->confirmadas()
                ->where('cancha_id', $this->cancha_id)
                ->whereDate('fecha', $inicio->toDateString())
                ->where('hora_inicio', '<', $fin->format('H:i:s'))
                ->where('hora_fin', '>', $inicio->format('H:i:s'))
                ->exists();

            if ($haySolape) {
                throw new DomainException('La cancha ya tiene una reserva confirmada en ese horario.');
            }
        }
    }
}
