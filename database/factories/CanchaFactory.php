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
        $deporte = fake()->randomElement(["F\u{00FA}tbol 5", "B\u{00E1}squet", "V\u{00F3}ley"]);
        [$diurna, $nocturna] = match ($deporte) {
            "B\u{00E1}squet" => [60, 90],
            "V\u{00F3}ley" => [50, 80],
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
