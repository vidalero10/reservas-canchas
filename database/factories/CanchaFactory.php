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
        $deporte = fake()->randomElement(['F£tbol 5', 'B squet', 'V¢ley']);
        [$diurna, $nocturna] = match ($deporte) {
            'B squet' => [60, 90],
            'V¢ley' => [50, 80],
            default => [fake()->numberBetween(80, 100), fake()->numberBetween(120, 150)],
        };

        return [
            'deporte_id' => Deporte::factory()->state(['nombre' => $deporte]),
            'nombre' => 'Cancha '.fake()->unique()->numberBetween(1, 10000),
            'tarifa_diurna' => $diurna,
            'tarifa_nocturna' => $nocturna,
            'activa' => true,
        ];
    }
}
