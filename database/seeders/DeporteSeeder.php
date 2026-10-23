<?php

namespace Database\Seeders;

use App\Models\Deporte;
use Illuminate\Database\Seeder;

class DeporteSeeder extends Seeder
{
    public function run(): void
    {
        foreach (["F\u{00FA}tbol 5", "B\u{00E1}squet", "V\u{00F3}ley"] as $nombre) {
            Deporte::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
