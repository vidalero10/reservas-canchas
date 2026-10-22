<?php

namespace Database\Seeders;

use App\Models\Deporte;
use Illuminate\Database\Seeder;

class DeporteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Fútbol 5', 'Básquet', 'Vóley'] as $nombre) {
            Deporte::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
