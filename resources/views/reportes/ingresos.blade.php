@extends('layouts.app')
@section('title', 'Reporte de Ingresos y Rendimiento')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                <span>Analítica Financiera</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                Rendimiento del Complejo
            </h1>
            <p class="mt-2 text-sm text-slate-300 max-w-xl">
                Recaudación histórica y partidos confirmados distribuidos por disciplina deportiva en JuegaYa.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reservas.del_dia') }}" 
               class="rounded-xl bg-emerald-600 px-5 py-3 text-xs font-black text-white hover:bg-emerald-700 shadow-md shadow-emerald-900/30 transition">
                📅 Agenda del Día
            </a>
            <a href="{{ route('admin.canchas.index') }}" 
               class="rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-xs font-bold text-white backdrop-blur hover:bg-white/20 transition">
                ⚙ Canchas
            </a>
        </div>
    </div>
</div>

@php
    $promedioPorReserva = $reservasConfirmadas > 0 ? ($ingresosTotales / $reservasConfirmadas) : 0;
@endphp

<!-- Analytics Summary Metric Cards -->
<section class="mt-8 grid gap-5 sm:grid-cols-3">
    <!-- Card 1: Ingresos Totales -->
    <article class="sport-card rounded-3xl bg-gradient-to-br from-emerald-600 to-pitch-900 p-6 text-white shadow-lg shadow-emerald-950/20">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-200">Ingresos Totales</span>
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/15 text-sm font-black">Bs</span>
        </div>
        <p class="mt-4 font-display text-4xl font-black tracking-tight">
            Bs {{ number_format($ingresosTotales, 2) }}
        </p>
        <span class="mt-2 block text-xs text-emerald-100/70">Solo pagos verificados y confirmados</span>
    </article>

    <!-- Card 2: Reservas Confirmadas -->
    <article class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Reservas Concretadas</span>
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-800 text-lg">✓</span>
        </div>
        <p class="mt-4 font-display text-4xl font-black tracking-tight text-slate-900">
            {{ number_format($reservasConfirmadas) }}
        </p>
        <span class="mt-2 block text-xs text-slate-400">Partidos jugados o en agenda confirmada</span>
    </article>

    <!-- Card 3: Ticket Promedio -->
    <article class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Promedio por Turno</span>
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-slate-700 text-sm font-black">📈</span>
        </div>
        <p class="mt-4 font-display text-4xl font-black tracking-tight text-slate-900">
            Bs {{ number_format($promedioPorReserva, 2) }}
        </p>
        <span class="mt-2 block text-xs text-slate-400">Ingreso medio por reserva completada</span>
    </article>
</section>

<!-- Sports Revenue Breakdown Table & Visual Bars -->
<section class="mt-8 overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/70 px-6 py-5">
        <div>
            <span class="text-xs font-black uppercase tracking-[0.16em] text-emerald-700 block">Distribución</span>
            <h2 class="font-display text-lg font-black text-slate-900 mt-0.5">Recaudación por Disciplina</h2>
        </div>
        <span class="rounded-full bg-slate-200/70 px-3 py-1 text-xs font-bold text-slate-700">
            {{ $reporte->count() }} Deportes
        </span>
    </div>

    @if ($reporte->isEmpty())
        <div class="p-12 text-center text-slate-500">
            No existen registros históricos de reservas.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[550px] text-left text-xs sm:text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Deporte</th>
                        <th class="px-6 py-4 text-center">Partidos Confirmados</th>
                        <th class="px-6 py-4 text-right">Participación</th>
                        <th class="px-6 py-4 text-right">Recaudación (Bs)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($reporte as $fila)
                        @php
                            $nombreNorm = mb_strtolower($fila->deporte, 'UTF-8');
                            $icono = str_contains($nombreNorm, 'squet') || str_contains($nombreNorm, 'bas') 
                                ? '🏀' 
                                : (str_contains($nombreNorm, 'ley') || str_contains($nombreNorm, 'vol') ? '🏐' : '⚽');
                            $porcentaje = $ingresosTotales > 0 ? (($fila->ingresos_totales / $ingresosTotales) * 100) : 0;
                        @endphp

                        <tr class="transition hover:bg-slate-50/60">
                            <!-- Deporte -->
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-slate-100 text-lg">
                                        {{ $icono }}
                                    </span>
                                    <div>
                                        <strong class="font-black text-slate-900 text-sm block">
                                            {{ $fila->deporte }}
                                        </strong>
                                        <span class="text-[11px] text-slate-400">Disciplina oficial</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Partidos -->
                            <td class="px-6 py-5 text-center font-bold text-slate-700">
                                {{ number_format((int) $fila->cantidad_reservas) }}
                            </td>

                            <!-- Porcentaje Visual Bar -->
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <div class="w-24 h-2 rounded-full bg-slate-100 overflow-hidden hidden sm:block">
                                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">{{ number_format($porcentaje, 1) }}%</span>
                                </div>
                            </td>

                            <!-- Recaudación -->
                            <td class="px-6 py-5 text-right font-black text-emerald-800 text-sm sm:text-base font-display">
                                Bs {{ number_format((float) $fila->ingresos_totales, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200 bg-slate-50/90 font-black text-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-sm font-black">Totales del Complejo</th>
                        <th class="px-6 py-4 text-center text-sm font-black">{{ number_format($reservasConfirmadas) }}</th>
                        <th class="px-6 py-4 text-right text-xs text-slate-500">100%</th>
                        <th class="px-6 py-4 text-right text-base font-black text-emerald-800">
                            Bs {{ number_format($ingresosTotales, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</section>
@endsection
