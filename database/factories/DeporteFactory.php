<?php

namespace Database\Factories;

use App\Models\Deporte;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Deporte> */
class DeporteFactory extends Factory
{
    protected $model = Deporte::class;

    public function definition(): array
    {
        return ['nombre' => fake('es_ES')->unique()->randomElement(['Fútbol 5', 'Básquet', 'Vóley'])];
    }

    public function futbol5(): static
    {
        return $this->state(fn () => ['nombre' => 'Fútbol 5']);
    }

    public function basquet(): static
    {
        return $this->state(fn () => ['nombre' => 'Básquet']);
    }

    public function voley(): static
    {
        return $this->state(fn () => ['nombre' => 'Vóley']);
    }
}
