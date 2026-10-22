<?php

namespace Database\Factories;

use App\Models\Cancha;
use App\Models\Deporte;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Cancha> */
class CanchaFactory extends Factory
{
    protected $model = Cancha::class;

    public function definition(): array
    {
        return [
            'deporte_id' => Deporte::factory()->futbol5(),
            'nombre' => 'Cancha '.fake()->unique()->numberBetween(1, 9999),
            'tarifa_diurna' => fake()->numberBetween(80, 100),
            'tarifa_nocturna' => fake()->numberBetween(120, 150),
            'activa' => true,
        ];
    }

    public function basquet(): static
    {
        return $this->state(fn () => [
            'deporte_id' => Deporte::factory()->basquet(),
            'tarifa_diurna' => 60,
            'tarifa_nocturna' => 90,
        ]);
    }

    public function voley(): static
    {
        return $this->state(fn () => [
            'deporte_id' => Deporte::factory()->voley(),
            'tarifa_diurna' => 50,
            'tarifa_nocturna' => 80,
        ]);
    }
}
