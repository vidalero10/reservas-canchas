<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CanchaPublicaController extends Controller
{
    public function index(): View
    {
        $canchas = Cancha::query()->where('activa', true)->with('deporte')->orderBy('nombre')->get();
        return view('canchas.index', compact('canchas'));
    }

    public function disponibilidad(Request $request): View
    {
        $datos = $request->validate([
            'fecha' => ['nullable', 'date', 'after_or_equal:today'],
            'siguiente_dia' => ['nullable', 'in:1'],
        ]);
        $fecha = $datos['fecha'] ?? now()->toDateString();
        if (isset($datos['siguiente_dia'])) {
            $fecha = Carbon::parse($fecha)->addDay()->toDateString();
        }
        $canchas = $this->canchasConReservas($fecha);
        $bloques = $this->crearBloquesHorario($canchas);
        return view('canchas.disponibilidad', compact('canchas', 'fecha', 'bloques'));
    }

    public function show(Request $request, Cancha $cancha): View
    {
        abort_unless($cancha->activa, 404);
        $datos = $request->validate([
            'fecha' => ['nullable', 'date', 'after_or_equal:today'],
            'siguiente_dia' => ['nullable', 'in:1'],
        ]);
        $fecha = $datos['fecha'] ?? now()->toDateString();
        if (isset($datos['siguiente_dia'])) {
            $fecha = Carbon::parse($fecha)->addDay()->toDateString();
        }
        $cancha->load('deporte');
        $cancha->load(['reservas' => fn ($consulta) => $consulta->bloqueanHorario()->delDia($fecha)->orderBy('hora_inicio')]);
        $bloques = $this->crearBloquesHorario(collect([$cancha]))[$cancha->id];
        return view('canchas.show', compact('cancha', 'fecha', 'bloques'));
    }

    private function canchasConReservas(string $fecha)
    {
        return Cancha::query()->where('activa', true)->with('deporte')
            ->with(['reservas' => fn ($consulta) => $consulta->bloqueanHorario()->delDia($fecha)->orderBy('hora_inicio')])
            ->orderBy('nombre')->get();
    }

    /** Construye los tramos en PHP para distinguir intervalos libres y ocupados. */
    private function crearBloquesHorario($canchas): array
    {
        return $canchas->mapWithKeys(function (Cancha $cancha): array {
            $bloques = [];
            $cursor = Carbon::createFromTime(8, 0);
            $cierre = Carbon::createFromTime(23, 0);

            foreach ($cancha->reservas as $reserva) {
                $inicio = Carbon::createFromFormat('H:i', substr((string) $reserva->hora_inicio, 0, 5));
                $fin = Carbon::createFromFormat('H:i', substr((string) $reserva->hora_fin, 0, 5));
                if ($cursor->lt($inicio)) {
                    $bloques[] = ['tipo' => 'Libre', 'inicio' => $cursor->format('H:i'), 'fin' => $inicio->format('H:i')];
                }
                $estadoTurno = $reserva->estado === Reserva::ESTADO_PENDIENTE ? 'Pendiente de pago' : 'Reservada';
                $bloques[] = ['tipo' => $estadoTurno, 'inicio' => $inicio->format('H:i'), 'fin' => $fin->format('H:i')];
                if ($fin->gt($cursor)) {
                    $cursor = $fin->copy();
                }
            }
            if ($cursor->lt($cierre)) {
                $bloques[] = ['tipo' => 'Libre', 'inicio' => $cursor->format('H:i'), 'fin' => $cierre->format('H:i')];
            }
            return [$cancha->id => $bloques];
        })->all();
    }
}
