<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Cliente> */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        $prefijo = fake()->randomElement(['6', '7']);

        return [
            'ci' => (string) fake()->unique()->numberBetween(1000000, 9999999),
            'nombre' => fake('es_ES')->name(),
            'telefono' => $prefijo.fake()->numerify('#######'),
        ];
    }
}
