<?php

namespace Database\Seeders;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Seeder;

class ReservaSeeder extends Seeder
{
    private const CANTIDAD = 150;
    private const MAX_INTENTOS = 5000;

    public function run(): void
    {
        $clienteIds = Cliente::query()->pluck('id')->all();
        $canchaIds = Cancha::query()->pluck('id')->all();
        $creadas = 0;
        $intentos = 0;

        while ($creadas < self::CANTIDAD && $intentos < self::MAX_INTENTOS) {
            $intentos++;
            $cancelada = $creadas % 10 === 0;

            $datos = [
                'cliente_id' => fake()->randomElement($clienteIds),
                'cancha_id' => fake()->randomElement($canchaIds),
            ];

            if ($cancelada) {
                // Estas reservas ya pasaron; se registran canceladas sin aplicar la regla de cancelaci¢n futura.
                $fecha = fake()->dateTimeBetween('-30 days', '-1 day')->format('Y-m-d');
                $datos += [
                    'fecha' => $fecha,
                    'estado' => Reserva::ESTADO_CANCELADA,
                    'cancelada_en' => Carbon::parse($fecha)->subDay(),
                ];
            }

            try {
                Reserva::factory()->create($datos);
                $creadas++;
            } catch (DomainException) {
                // Una coincidencia de horario se intenta de nuevo con otro cliente, cancha u hora.
            }
        }

        if ($creadas < self::CANTIDAD) {
            throw new DomainException("No se pudieron crear las {$creadas} reservas tras ".self::MAX_INTENTOS.' intentos.');
        }
    }
}
