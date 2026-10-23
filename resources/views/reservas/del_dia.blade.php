@extends('layouts.app')
@section('title', 'Agenda y Verificación de Pagos · Panel Administrativo')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                <span>Centro de Operaciones</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                Agenda del Día & Verificación de Pagos
            </h1>
            <p class="mt-2 text-sm text-slate-300 max-w-xl">
                Confirma transferencias bancarias, aprueba turnos solicitados y gestiona los horarios del complejo.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.canchas.index') }}" 
               class="rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-xs font-bold text-white backdrop-blur hover:bg-white/20 transition">
                ⚙ Gestionar Canchas
            </a>
            <a href="{{ route('admin.reportes.ingresos') }}" 
               class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-extrabold text-white hover:bg-emerald-700 transition">
                📊 Ver Ingresos
            </a>
        </div>
    </div>
</div>

<!-- Date & Court Filter Bar -->
<div class="mt-8 rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-7 shadow-sm">
    <form method="GET" action="{{ route('admin.reservas.del_dia') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
        <!-- Fecha -->
        <div class="lg:col-span-3">
            <label for="fecha" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                Fecha de la Agenda:
            </label>
            <input id="fecha" 
                   type="date" 
                   name="fecha" 
                   value="{{ $fecha }}" 
                   class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
        </div>

        <!-- Cancha -->
        <div class="lg:col-span-4">
            <label for="cancha_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                Filtrar por Cancha:
            </label>
            <select id="cancha_id" 
                    name="cancha_id" 
                    class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-xs font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Todas las canchas ({{ $canchas->count() }})</option>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}" @selected((string) $canchaId === (string) $cancha->id)>
                        {{ $cancha->nombre }} · {{ $cancha->deporte->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buttons -->
        <div class="sm:col-span-2 lg:col-span-5 flex flex-wrap items-center gap-2">
            <button type="submit" 
                    class="rounded-xl bg-pitch-900 px-4 py-2.5 text-xs font-extrabold text-white hover:bg-emerald-700 transition">
                Consultar
            </button>
            <button type="submit" 
                    name="mover_dia" 
                    value="-1" 
                    class="rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                ← Anterior
            </button>
            <button type="submit" 
                    name="mover_dia" 
                    value="1" 
                    class="rounded-xl border border-emerald-300 bg-emerald-50 px-3.5 py-2.5 text-xs font-extrabold text-emerald-900 hover:bg-emerald-100 transition">
                Siguiente →
            </button>
        </div>
    </form>
</div>

<!-- Agenda Status Bar -->
<div class="mt-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">Jornada Activa</span>
        <h2 class="font-display text-xl font-black text-white capitalize">
            {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
        </h2>
    </div>

    <div class="flex items-center gap-3">
        <span class="rounded-full bg-white px-3.5 py-1.5 text-xs font-bold text-slate-700 border border-slate-200 shadow-sm">
            Total: <strong>{{ $reservas->count() }}</strong>
        </span>
        <span class="rounded-full bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-900 border border-amber-200 shadow-sm">
            Pendientes: <strong>{{ $reservas->where('estado', \App\Models\Reserva::ESTADO_PENDIENTE)->count() }}</strong>
        </span>
        <span class="rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-900 border border-emerald-200 shadow-sm">
            Confirmadas: <strong>{{ $reservas->where('estado', \App\Models\Reserva::ESTADO_CONFIRMADA)->count() }}</strong>
        </span>
    </div>
</div>

<!-- Reservations Table -->
<div class="mt-6 overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white shadow-sm">
    @if ($reservas->isEmpty())
        <div class="p-12 sm:p-16 text-center">
            <span class="grid h-16 w-16 place-items-center rounded-2xl bg-emerald-50 text-3xl mx-auto text-emerald-700">🗓️</span>
            <h3 class="mt-4 font-display text-xl font-black text-slate-900">Agenda libre para esta fecha</h3>
            <p class="mt-1 text-xs text-slate-500">No existen solicitudes ni reservas programadas para los filtros seleccionados.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-xs sm:text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Horario</th>
                        <th class="px-6 py-4">Cancha</th>
                        <th class="px-6 py-4">Cliente</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4">Monto</th>
                        <th class="px-6 py-4 text-right">Acciones Administrativas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($reservas as $reserva)
                        @php
                            $esPendiente = ($reserva->estado === \App\Models\Reserva::ESTADO_PENDIENTE);
                        @endphp

                        <tr class="transition hover:bg-slate-50/70">
                            <!-- Horario -->
                            <td class="whitespace-nowrap px-6 py-4 font-extrabold text-slate-900">
                                <span class="text-sm font-black font-display block">
                                    {{ substr($reserva->hora_inicio, 0, 5) }} – {{ substr($reserva->hora_fin, 0, 5) }}
                                </span>
                                <span class="text-[11px] font-normal text-slate-400">
                                    {{ $reserva->duracion_horas }} {{ $reserva->duracion_horas === 1 ? 'hora' : 'horas' }}
                                </span>
                            </td>

                            <!-- Cancha -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $reserva->cancha->imagen_url }}" 
                                         alt="{{ $reserva->cancha->nombre }}" 
                                         class="h-9 w-12 rounded-lg object-cover shadow-sm">
                                    <div>
                                        <strong class="font-extrabold text-slate-900 block text-xs">
                                            {{ $reserva->cancha->nombre }}
                                        </strong>
                                        <span class="text-[11px] text-emerald-700 font-semibold">
                                            {{ $reserva->cancha->icono }} {{ $reserva->cancha->deporte->nombre }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Cliente -->
                            <td class="px-6 py-4">
                                <strong class="font-bold text-slate-800 block text-xs">
                                    {{ $reserva->cliente->nombre }}
                                </strong>
                                <span class="text-[11px] text-slate-400">
                                    CI: {{ $reserva->cliente->ci }} · {{ $reserva->cliente->telefono ?: 'S/N' }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                @if ($esPendiente)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-900">
                                        <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>Pendiente de pago</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-900">
                                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                                        <span>✓ Confirmada</span>
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
                                <div class="flex items-center justify-end gap-2">
                                    @if ($esPendiente)
                                        <form method="POST" 
                                              action="{{ route('admin.reservas.confirmar', $reserva) }}" 
                                              onsubmit="return confirm('¿Ya verificaste el comprobante y deseas confirmar esta reserva?')" 
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="rounded-xl bg-emerald-600 px-3 py-1.5 text-xs font-black text-white hover:bg-emerald-700 shadow-sm transition">
                                                ✓ Confirmar Pago
                                            </button>
                                        </form>

                                        <form method="POST" 
                                              action="{{ route('admin.reservas.cancelar', $reserva) }}" 
                                              onsubmit="return confirm('¿Rechazar esta solicitud y liberar el horario para otro cliente?')" 
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="rounded-xl border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-50 transition">
                                                Rechazar
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" 
                                              action="{{ route('admin.reservas.cancelar', $reserva) }}" 
                                              onsubmit="return confirm('¿Cancelar esta reserva ya confirmada?')" 
                                              class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="rounded-xl border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-50 transition">
                                                Cancelar Reserva
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
