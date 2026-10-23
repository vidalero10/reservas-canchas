@extends('layouts.app')
@section('title', 'Mis Reservas · Panel del Cliente')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                <span>Panel Personal del Jugador</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                Mis Reservas
            </h1>
            <p class="mt-2 text-sm text-slate-300 max-w-xl">
                Historial de solicitudes, estados de pago y agenda de tus próximos partidos en JuegaYa.
            </p>
        </div>

        <a href="{{ route('reservas.create') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3.5 text-sm font-extrabold text-pitch-950 shadow-md shadow-emerald-900/30 hover:from-emerald-400 hover:to-emerald-500 transition">
            <span>＋ Nueva reserva</span>
        </a>
    </div>
</div>

@if (!$cliente)
    <div role="alert" class="mt-8 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-900 shadow-sm">
        <strong class="font-bold text-base block">Cuenta sin ficha de cliente vinculada</strong>
        <p class="text-xs sm:text-sm mt-1">Contacta a administración para activar tu registro y asociar tus reservas personales.</p>
    </div>
@elseif ($reservas->isEmpty())
    <!-- Empty State -->
    <div class="mt-8 rounded-[2.5rem] border border-dashed border-slate-300 bg-white p-12 sm:p-16 text-center shadow-sm">
        <span class="grid h-16 w-16 place-items-center rounded-2xl bg-emerald-50 text-3xl mx-auto text-emerald-700">⚽</span>
        <h2 class="mt-4 font-display text-2xl font-black text-slate-900">Aún no tienes reservas registradas</h2>
        <p class="mt-2 text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
            Explora las canchas de Fútbol 5, Básquetbol o Vóley y asegura tu primer turno en el complejo.
        </p>
        <a href="{{ route('reservas.create') }}" 
           class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-xs font-black text-white hover:bg-emerald-700 transition shadow-md shadow-emerald-700/20">
            <span>Reservar mi primera cancha</span>
            <span aria-hidden="true">→</span>
        </a>
    </div>
@else
    @php
        $totalReservas = $reservas->count();
        $confirmadas = $reservas->where('estado', \App\Models\Reserva::ESTADO_CONFIRMADA)->count();
        $pendientes = $reservas->where('estado', \App\Models\Reserva::ESTADO_PENDIENTE)->count();
        $montoTotalInvertido = $reservas->where('estado', \App\Models\Reserva::ESTADO_CONFIRMADA)->sum('monto');
    @endphp

    <!-- Metrics Summary Cards -->
    <div class="mt-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Solicitudes</span>
            <p class="mt-2 font-display text-3xl font-black text-slate-900">{{ $totalReservas }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Historial registrado</span>
        </div>

        <div class="rounded-3xl border border-emerald-200/80 bg-emerald-50/70 p-5 shadow-sm">
            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Confirmadas</span>
            <p class="mt-2 font-display text-3xl font-black text-emerald-950">{{ $confirmadas }}</p>
            <span class="text-[11px] text-emerald-700/80 mt-1 block">{{ $pendientes }} pendientes de pago</span>
        </div>

        <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Inversión Confirmada</span>
            <p class="mt-2 font-display text-3xl font-black text-slate-900">Bs {{ number_format((float) $montoTotalInvertido, 2) }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Partidos completados y programados</span>
        </div>
    </div>

    <!-- Reservations Table Card -->
    <section class="mt-8 overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/80 px-6 py-4 flex items-center justify-between">
            <h2 class="font-display text-base font-extrabold text-slate-900">
                Detalle de Reservas Realizadas
            </h2>
            <span class="text-xs font-bold text-slate-500">{{ $totalReservas }} registros</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-xs sm:text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Fecha & Día</th>
                        <th class="px-6 py-4">Horario</th>
                        <th class="px-6 py-4">Cancha Deportiva</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Monto</th>
                        <th class="px-6 py-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($reservas as $reserva)
                        @php
                            $esPendiente = ($reserva->estado === \App\Models\Reserva::ESTADO_PENDIENTE);
                            $esConfirmada = ($reserva->estado === \App\Models\Reserva::ESTADO_CONFIRMADA);
                            $esCancelada = ($reserva->estado === \App\Models\Reserva::ESTADO_CANCELADA);
                        @endphp

                        <tr class="transition hover:bg-slate-50/60">
                            <!-- Fecha -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <strong class="font-black text-slate-900 block text-sm">
                                    {{ $reserva->fecha->format('d/m/Y') }}
                                </strong>
                                <span class="text-[11px] text-slate-400 capitalize">
                                    {{ \Carbon\Carbon::parse($reserva->fecha)->locale('es')->translatedFormat('l') }}
                                </span>
                            </td>

                            <!-- Horario -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-bold text-slate-800 font-display text-sm">
                                    {{ substr($reserva->hora_inicio, 0, 5) }} – {{ substr($reserva->hora_fin, 0, 5) }}
                                </span>
                                <span class="block text-[11px] text-slate-500">
                                    {{ $reserva->duracion_horas }} {{ $reserva->duracion_horas === 1 ? 'hora' : 'horas' }}
                                </span>
                            </td>

                            <!-- Cancha -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $reserva->cancha->imagen_url }}" 
                                         alt="{{ $reserva->cancha->nombre }}" 
                                         class="h-10 w-12 rounded-lg object-cover shadow-sm">
                                    <div>
                                        <strong class="font-extrabold text-slate-900 block">
                                            {{ $reserva->cancha->nombre }}
                                        </strong>
                                        <span class="text-[11px] font-semibold text-emerald-700">
                                            {{ $reserva->cancha->icono }} {{ $reserva->cancha->deporte->nombre }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                @if ($esPendiente)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-900">
                                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>Pendiente de pago</span>
                                    </span>
                                @elseif ($esConfirmada)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-900">
                                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                                        <span>✓ Confirmada</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        <span>Cancelada</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Monto -->
                            <td class="whitespace-nowrap px-6 py-4">
                                <strong class="font-black text-slate-900 text-sm">
                                    Bs {{ number_format((float) $reserva->monto, 2) }}
                                </strong>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                @if ($esPendiente || $esConfirmada)
                                    <form method="POST" 
                                          action="{{ route('cliente.reservas.cancelar', $reserva) }}" 
                                          onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva? Recuerda que debe ser con al menos 24 horas de anticipación.')" 
                                          class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="rounded-xl border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-50 hover:border-rose-300 transition">
                                            Cancelar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <!-- Cancellation Policy Explanatory Box -->
    <div class="mt-6 rounded-2xl border border-amber-200/80 bg-amber-50/80 p-5 text-xs text-amber-950 leading-relaxed shadow-sm flex items-start gap-3">
        <span class="text-lg">ℹ️</span>
        <div>
            <strong>Políticas de Reserva & Cancelación:</strong> Las solicitudes quedan en estado <em>Pendiente</em> mientras administración valida tu comprobante bancario. Puedes cancelar tu turno sin recargo con un mínimo de <strong>24 horas de anticipación</strong> al inicio del horario fijado.
        </div>
    </div>
@endif
@endsection
