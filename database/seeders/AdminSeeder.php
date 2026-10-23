<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // La cuenta de ejemplo solo se crea en desarrollo y pruebas, nunca en produccion.
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $usuario = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@reservas.test')],
            [
                'name' => 'Administrador',
                'password' => env('ADMIN_PASSWORD', 'Admin1234!'),
            ],
        );

        $usuario->rol = User::ROL_ADMIN;
        $usuario->save();

        $this->command?->info("Cuenta admin lista: {$usuario->email}");
    }
}