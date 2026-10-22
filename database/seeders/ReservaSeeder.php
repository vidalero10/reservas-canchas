<?php

namespace Database\Seeders;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use Carbon\CarbonImmutable;
use DomainException;
use Illuminate\Database\Seeder;
use RuntimeException;

class ReservaSeeder extends Seeder
{
    private const CANTIDAD = 150;

    private const INTENTOS_MAXIMOS = 5000;

    public function run(): void
    {
        $clientes = Cliente::query()->pluck('id')->all();
        $canchas = Cancha::query()->pluck('id')->all();
        $canceladas = (int) round(self::CANTIDAD * 0.10);

        for ($i = 0; $i < $canceladas; $i++) {
            Reserva::factory()->cancelada()->create([
                'cliente_id' => fake()->randomElement($clientes),
                'cancha_id' => fake()->randomElement($canchas),
                'fecha' => CarbonImmutable::today()->subDays(fake()->numberBetween(1, 30))->toDateString(),
            ]);
        }

        $confirmadasObjetivo = self::CANTIDAD - $canceladas;
        $confirmadasCreadas = 0;
        $intentos = 0;

        while ($confirmadasCreadas < $confirmadasObjetivo && $intentos < self::INTENTOS_MAXIMOS) {
            $intentos++;

            try {
                Reserva::factory()->create([
                    'cliente_id' => fake()->randomElement($clientes),
                    'cancha_id' => fake()->randomElement($canchas),
                ]);
                $confirmadasCreadas++;
            } catch (DomainException) {
                // Los solapes son esperables al generar horarios aleatorios y se vuelven a intentar.
            }
        }

        if ($confirmadasCreadas < $confirmadasObjetivo) {
            throw new RuntimeException("Se crearon {$confirmadasCreadas} de {$confirmadasObjetivo} reservas confirmadas tras {$intentos} intentos.");
        }
    }
}
