<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Deporte;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use DomainException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservaController extends Controller
{
    public function index(): View
    {
        $deportes = Deporte::query()
            ->with(['canchas' => fn ($consulta) => $consulta->where('activa', true)->orderBy('nombre')])
            ->orderBy('nombre')
            ->get();

        return view('welcome', compact('deportes'));
    }

    public function misReservas(Request $request): View
    {
        $cliente = $request->user()->cliente;
        $reservas = $cliente
            ? $cliente->reservas()->with('cancha.deporte')->orderByDesc('fecha')->orderByDesc('hora_inicio')->get()
            : collect();

        return view('reservas.mis_reservas', compact('reservas', 'cliente'));
    }

    public function reservasDelDia(Request $request): View
    {
        $filtros = $request->validate([
            'fecha' => ['nullable', 'date'],
            'cancha_id' => ['nullable', 'integer', 'exists:canchas,id'],
            'mover_dia' => ['nullable', 'in:-1,1'],
        ]);

        $fecha = $filtros['fecha'] ?? now()->toDateString();
        if (isset($filtros['mover_dia'])) {
            $fecha = Carbon::parse($fecha)->addDays((int) $filtros['mover_dia'])->toDateString();
        }
        $canchaId = $filtros['cancha_id'] ?? null;
        $reservas = Reserva::query()
            ->with(['cliente', 'cancha.deporte'])
            ->bloqueanHorario()
            ->delDia($fecha)
            ->when($canchaId, fn ($consulta) => $consulta->deCancha($canchaId))
            ->orderBy('hora_inicio')
            ->get();
        $canchas = Cancha::query()->with('deporte')->orderBy('nombre')->get();
        $esAdmin = true;

        return view('reservas.del_dia', compact('reservas', 'canchas', 'fecha', 'canchaId', 'esAdmin'));
    }

    public function create(Request $request): View
    {
        $deportes = Deporte::query()->orderBy('nombre')->get();
        $canchaInicial = Cancha::query()->where('activa', true)->find($request->query('cancha_id'));
        $deporteInicial = old('deporte_id', $canchaInicial?->deporte_id);
        $cliente = $request->user()->cliente;

        return view('reservas.create', compact('deportes', 'deporteInicial', 'cliente'));
    }

    public function canchasDisponibles(Request $request)
    {
        $datos = $request->validate([
            'deporte_id' => ['required', 'integer', 'exists:deportes,id'],
            'fecha' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'duracion_horas' => ['required', 'integer', 'between:1,4'],
        ]);

        $inicio = Carbon::createFromFormat('H:i', $datos['hora_inicio']);
        $minutosInicio = ((int) $inicio->format('H') * 60) + (int) $inicio->format('i');
        $minutosFin = $minutosInicio + ((int) $datos['duracion_horas'] * 60);
        if ($minutosInicio < 480 || $minutosInicio >= 1380 || $minutosFin > 1380) {
            return response()->json(['message' => 'El horario debe estar entre las 08:00 y las 23:00, considerando la duración elegida.'], 422);
        }

        $fin = $inicio->copy()->addHours((int) $datos['duracion_horas']);
        $horaInicio = $inicio->format('H:i:s');
        $horaFin = $fin->format('H:i:s');
        $canchas = Cancha::query()
            ->with('deporte')
            ->where('deporte_id', $datos['deporte_id'])
            ->where('activa', true)
            ->whereDoesntHave('reservas', fn ($consulta) => $consulta
                ->bloqueanHorario()
                ->delDia($datos['fecha'])
                ->where('hora_inicio', '<', $horaFin)
                ->where('hora_fin', '>', $horaInicio))
            ->orderBy('nombre')
            ->get()
            ->map(function (Cancha $cancha) use ($inicio, $datos): array {
                $centavos = 0;
                for ($hora = 0; $hora < (int) $datos['duracion_horas']; $hora++) {
                    $horaFacturada = $inicio->copy()->addHours($hora);
                    $tarifa = (int) $horaFacturada->format('H') >= 18 ? $cancha->tarifa_nocturna : $cancha->tarifa_diurna;
                    $centavos += (int) round((float) $tarifa * 100);
                }

                return [
                    'id' => $cancha->id,
                    'nombre' => $cancha->nombre,
                    'descripcion' => $cancha->descripcion,
                    'superficie' => $cancha->superficie,
                    'imagen' => $cancha->imagen_url,
                    'tarifa_diurna' => $cancha->tarifa_diurna,
                    'tarifa_nocturna' => $cancha->tarifa_nocturna,
                    'monto_estimado' => number_format($centavos / 100, 2, '.', ''),
                ];
            });

        return response()->json(['canchas' => $canchas]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cancha_id' => ['required', 'integer', Rule::exists('canchas', 'id')->where('activa', true)],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'duracion_horas' => ['required', 'integer', 'between:1,4'],
        ]);

        $cliente = $request->user()->cliente;
        if (!$cliente) {
            return back()->withInput()->withErrors(['reserva' => 'Tu cuenta todavia no tiene una ficha de cliente vinculada.']);
        }

        try {
            $reserva = Reserva::create($datos + [
                'cliente_id' => $cliente->id,
                'estado' => Reserva::ESTADO_PENDIENTE,
            ]);
        } catch (DomainException $excepcion) {
            return back()->withInput()->withErrors(['reserva' => $excepcion->getMessage()]);
        }

        $reserva->load(['cliente', 'cancha.deporte']);
        $telefono = preg_replace('/\\D+/', '', (string) config('services.whatsapp.reservas'));
        $mensaje = implode("\n", [
            'Hola, quiero continuar con esta reserva de cancha:',
            'Reserva: #'.$reserva->id,
            'Cliente: '.$reserva->cliente->nombre,
            'Deporte: '.$reserva->cancha->deporte->nombre,
            'Cancha: '.$reserva->cancha->nombre,
            'Fecha: '.$reserva->fecha->format('d/m/Y'),
            'Horario: '.substr((string) $reserva->hora_inicio, 0, 5).' a '.substr((string) $reserva->hora_fin, 0, 5),
            'Duracion: '.$reserva->duracion_horas.' horas',
            'Monto total a pagar: Bs '.number_format((float) $reserva->monto, 2),
            'Estado: pendiente de confirmacion de pago.',
        ]);
        $enlaceWhatsApp = 'https://wa.me/'.$telefono.'?text='.rawurlencode($mensaje);

        return redirect()->away($enlaceWhatsApp);
    }

    public function cancelar(Reserva $reserva, Request $request): RedirectResponse
    {
        abort_unless($request->user()->cliente?->id === $reserva->cliente_id, 404);

        try {
            $reserva->cancelar();
        } catch (DomainException $excepcion) {
            return back()->withErrors(['reserva' => $excepcion->getMessage()]);
        }

        return back()->with('success', 'La reserva fue cancelada.');
    }

    public function confirmarComoAdmin(Reserva $reserva): RedirectResponse
    {
        try {
            $reserva->confirmarPago();
        } catch (DomainException $excepcion) {
            return back()->withErrors(['reserva' => $excepcion->getMessage()]);
        }

        return back()->with('success', 'Pago verificado y reserva confirmada.');
    }

    public function cancelarComoAdmin(Reserva $reserva): RedirectResponse
    {
        try {
            if ($reserva->estado === Reserva::ESTADO_PENDIENTE) {
                $reserva->rechazarPendiente();
                return back()->with('success', 'Solicitud rechazada y horario liberado.');
            }

            $reserva->cancelar();
        } catch (DomainException $excepcion) {
            return back()->withErrors(['reserva' => $excepcion->getMessage()]);
        }

        return back()->with('success', 'La reserva fue cancelada.');
    }

    public function reporteIngresos(): View
    {
        $reporte = DB::table('deportes')
            ->leftJoin('canchas', 'canchas.deporte_id', '=', 'deportes.id')
            ->leftJoin('reservas', function (JoinClause $union) {
                $union->on('reservas.cancha_id', '=', 'canchas.id')
                    ->where('reservas.estado', '=', Reserva::ESTADO_CONFIRMADA);
            })
            ->select(
                'deportes.id',
                'deportes.nombre as deporte',
                DB::raw('COALESCE(SUM(reservas.monto), 0) AS ingresos_totales'),
                DB::raw('COUNT(reservas.id) AS cantidad_reservas'),
            )
            ->groupBy('deportes.id', 'deportes.nombre')
            ->orderBy('deportes.nombre')
            ->get();

        $ingresosTotales = $reporte->sum(fn ($fila) => (float) $fila->ingresos_totales);
        $reservasConfirmadas = $reporte->sum(fn ($fila) => (int) $fila->cantidad_reservas);

        return view('reportes.ingresos', compact('reporte', 'ingresosTotales', 'reservasConfirmadas'));
    }
}
