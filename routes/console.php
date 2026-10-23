<?php

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('usuarios:promover-admin {email}', function (string $email) {
    $usuario = User::query()->where('email', $email)->first();

    if (!$usuario) {
        $this->error('No existe una cuenta con ese correo.');
        return Command::FAILURE;
    }

    if ($usuario->rol === User::ROL_ADMIN) {
        $this->info('La cuenta ya es administradora.');
        return Command::SUCCESS;
    }

    if (!$this->confirm("Promover {$usuario->email} a administradora?")) {
        return Command::FAILURE;
    }

    $usuario->rol = User::ROL_ADMIN;
    $usuario->save();

    $this->info('Cuenta promovida a administradora.');
    return Command::SUCCESS;
})->purpose('Promueve una cuenta existente al rol de administrador');
