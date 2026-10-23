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
        return ['nombre' => fake()->unique()->word()];
    }

    public function futbol5(): static
    {
        return $this->state(fn () => ['nombre' => 'F£tbol 5']);
    }

    public function basquet(): static
    {
        return $this->state(fn () => ['nombre' => 'B squet']);
    }

    public function voley(): static
    {
        return $this->state(fn () => ['nombre' => 'V¢ley']);
    }
}
