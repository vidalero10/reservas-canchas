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

        $canchas = [
            ["F\u{00FA}tbol 5", 80, 120],
            ["F\u{00FA}tbol 5", 90, 130],
            ["F\u{00FA}tbol 5", 100, 150],
            ["B\u{00E1}squet", 60, 90],
            ["B\u{00E1}squet", 60, 90],
            ["B\u{00E1}squet", 60, 90],
            ["V\u{00F3}ley", 50, 80],
            ["V\u{00F3}ley", 50, 80],
        ];

        foreach ($canchas as $indice => [$deporte, $diurna, $nocturna]) {
            Cancha::firstOrCreate(
                ['nombre' => 'Cancha '.($indice + 1)],
                [
                    'deporte_id' => $deportes[$deporte],
                    'tarifa_diurna' => $diurna,
                    'tarifa_nocturna' => $nocturna,
                    'activa' => true,
                ],
            );
        }
    }
}
