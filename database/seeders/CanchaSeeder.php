<?php

namespace Database\Seeders;

use App\Models\Cancha;
use App\Models\Deporte;
use Illuminate\Database\Seeder;

class CanchaSeeder extends Seeder
{
    public function run(): void
    {
        $deportes = Deporte::query()->pluck('id', 'nombre');

        $configuracion = [
            ['nombre' => 'Cancha 1', 'deporte' => 'Fútbol 5', 'diurna' => 90, 'nocturna' => 130],
            ['nombre' => 'Cancha 2', 'deporte' => 'Fútbol 5', 'diurna' => 100, 'nocturna' => 140],
            ['nombre' => 'Cancha 3', 'deporte' => 'Fútbol 5', 'diurna' => 80, 'nocturna' => 150],
            ['nombre' => 'Cancha 4', 'deporte' => 'Básquet', 'diurna' => 60, 'nocturna' => 90],
            ['nombre' => 'Cancha 5', 'deporte' => 'Básquet', 'diurna' => 60, 'nocturna' => 90],
            ['nombre' => 'Cancha 6', 'deporte' => 'Básquet', 'diurna' => 60, 'nocturna' => 90],
            ['nombre' => 'Cancha 7', 'deporte' => 'Vóley', 'diurna' => 50, 'nocturna' => 80],
            ['nombre' => 'Cancha 8', 'deporte' => 'Vóley', 'diurna' => 50, 'nocturna' => 80],
        ];

        foreach ($configuracion as $cancha) {
            Cancha::updateOrCreate(
                ['nombre' => $cancha['nombre']],
                [
                    'deporte_id' => $deportes[$cancha['deporte']],
                    'tarifa_diurna' => $cancha['diurna'],
                    'tarifa_nocturna' => $cancha['nocturna'],
                    'activa' => true,
                ],
            );
        }
    }
}
