<?php

namespace Tests\Feature;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Deporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanchasRedesignTest extends TestCase
{
    use RefreshDatabase;

    private Deporte $futbol;
    private Cancha $cancha;
    private User $usuarioCliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->futbol = Deporte::create(['nombre' => 'Fútbol 5']);
        $this->cancha = Cancha::create([
            'deporte_id' => $this->futbol->id,
            'nombre' => 'Cancha 1 Pro',
            'tarifa_diurna' => 80,
            'tarifa_nocturna' => 120,
            'activa' => true,
            'descripcion' => 'Cancha sintética de nivel profesional.',
        ]);

        $this->usuarioCliente = User::create([
            'name' => 'Jugador Demo',
            'email' => 'cliente@test.com',
            'password' => bcrypt('password123'),
        ]);
        $this->usuarioCliente->rol = User::ROL_CLIENTE;
        $this->usuarioCliente->save();

        Cliente::create([
            'user_id' => $this->usuarioCliente->id,
            'nombre' => 'Jugador Demo',
            'ci' => '1234567 LP',
            'telefono' => '71234567',
        ]);
    }

    public function test_pagina_inicio_carga_exitosamente(): void
    {
        $response = $this->get(route('inicio'));
        $response->assertStatus(200);
        $response->assertSee('JuegaYa');
        $response->assertSee('Fútbol 5');
    }

    public function test_catalogo_canchas_muestra_listado(): void
    {
        $response = $this->get(route('canchas.index'));
        $response->assertStatus(200);
        $response->assertSee('Cancha 1 Pro');
        $response->assertSee('Bs 80.00');
    }

    public function test_disponibilidad_general_carga(): void
    {
        $response = $this->get(route('canchas.disponibilidad'));
        $response->assertStatus(200);
        $response->assertSee('Disponibilidad de Canchas');
        $response->assertSee('Cancha 1 Pro');
    }

    public function test_detalle_de_cancha_muestra_ficha_y_horarios(): void
    {
        $response = $this->get(route('canchas.show', $this->cancha));
        $response->assertStatus(200);
        $response->assertSee('Cancha 1 Pro');
        $response->assertSee('Tarifas por hora');
    }

    public function test_login_y_registro_cargan_correctamente(): void
    {
        $this->get(route('login'))->assertStatus(200)->assertSee('Bienvenido de vuelta');
        $this->get(route('register'))->assertStatus(200)->assertSee('Crea tu cuenta');
    }

    public function test_cliente_puede_ver_formulario_de_reserva_y_sus_reservas(): void
    {
        $this->actingAs($this->usuarioCliente);

        $reservaCreate = $this->get(route('reservas.create'));
        $reservaCreate->assertStatus(200);
        $reservaCreate->assertSee('Reserva a nombre de');

        $misReservas = $this->get(route('cliente.reservas.index'));
        $misReservas->assertStatus(200);
        $misReservas->assertSee('Mis Reservas');
    }

    public function test_endpoint_ajax_canchas_disponibles_retorna_json(): void
    {
        $this->actingAs($this->usuarioCliente);

        $response = $this->getJson(route('reservas.canchas_disponibles', [
            'deporte_id' => $this->futbol->id,
            'fecha' => now()->toDateString(),
            'hora_inicio' => '19:00',
            'duracion_horas' => 2,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'canchas' => [
                '*' => [
                    'id', 'nombre', 'descripcion', 'superficie', 'imagen', 'tarifa_diurna', 'tarifa_nocturna', 'monto_estimado',
                ],
            ],
        ]);
    }
}
