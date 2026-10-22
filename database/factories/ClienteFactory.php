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
        $faker = fake('es_ES');

        return [
            'ci' => $faker->unique()->numerify('#######'),
            'nombre' => $faker->name(),
            'telefono' => fake()->randomElement(['6', '7']).fake()->numerify('#######'),
        ];
    }
}
