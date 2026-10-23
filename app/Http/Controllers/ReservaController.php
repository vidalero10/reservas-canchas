<?php

namespace App\Http\Controllers;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Deporte;
use App\Models\Reserva;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function reservasDelDia(Request $request): View
    {
        $filtros = $request->validate([
            'fecha' => ['nullable', 'date'],
            'cancha_id' => ['nullable', 'integer', 'exists:canchas,id'],
        ]);

        $fecha = $filtros['fecha'] ?? now()->toDateString();
        $canchaId = $filtros['cancha_id'] ?? null;

        $reservas = Reserva::query()
            ->with(['cliente', 'cancha.deporte'])
            ->confirmadas()
            ->delDia($fecha)
            ->when($canchaId, fn ($consulta) => $consulta->deCancha($canchaId))
            ->orderBy('hora_inicio')
            ->get();

        $canchas = Cancha::query()->where('activa', true)->with('deporte')->orderBy('nombre')->get();

        return view('reservas.del_dia', compact('reservas', 'canchas', 'fecha', 'canchaId'));
    }

    public function create(): View
    {
        $canchas = Cancha::query()
            ->where('activa', true)
            ->with('deporte')
            ->orderBy('nombre')
            ->get();
        $clientes = Cliente::query()->orderBy('nombre')->get();

        return view('reservas.create', compact('canchas', 'clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'cancha_id' => ['required', 'integer', 'exists:canchas,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'duracion_horas' => ['required', 'integer', 'between:1,4'],
        ]);

        try {
            Reserva::create($datos);
        } catch (DomainException $excepcion) {
            return back()->withInput()->withErrors(['reserva' => $excepcion->getMessage()]);
        }

        return redirect()->route('reservas.del_dia', ['fecha' => $datos['fecha'], 'cancha_id' => $datos['cancha_id']])
            ->with('success', 'La reserva se creo correctamente.');
    }

    public function cancelar(Reserva $reserva): RedirectResponse
    {
        try {
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
