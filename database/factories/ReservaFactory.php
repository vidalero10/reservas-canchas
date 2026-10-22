<?php

namespace Database\Factories;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Reserva> */
class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'cancha_id' => Cancha::factory(),
            'fecha' => fake()->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d'),
            'hora_inicio' => sprintf('%02d:00:00', fake()->numberBetween(8, 20)),
            'duracion_horas' => fake()->randomElement([1, 2]),
            'estado' => Reserva::ESTADO_CONFIRMADA,
            'cancelada_en' => null,
        ];
    }

    public function cancelada(): static
    {
        return $this->state(fn () => [
            'estado' => Reserva::ESTADO_CANCELADA,
            'cancelada_en' => now(),
        ]);
    }
}
