@extends('layouts.app')
@section('title', 'Disponibilidad y Horarios de Canchas')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400 animate-ping"></span>
                <span>Agenda Oficial en Vivo</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl lg:text-5xl">
                Disponibilidad de Canchas
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed">
                Revisa los intervalos libres de toda la jornada (08:00 a 23:00) y programa tu próximo encuentro deportivo sin solapamientos.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @auth
                @if (!auth()->user()->esAdministrador())
                    <a href="{{ route('reservas.create', ['fecha' => $fecha]) }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-3 text-sm font-extrabold text-pitch-950 shadow-md shadow-emerald-900/30 hover:from-emerald-400 hover:to-emerald-500 transition">
                        <span>＋ Iniciar reserva directa</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- Date Selector & Sport Filters Toolbar -->
<div class="mt-8 rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm">
    <form method="GET" action="{{ route('canchas.disponibilidad') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <!-- Date Selector -->
        <div class="flex flex-col sm:flex-row sm:items-end gap-3 flex-1">
            <div class="w-full sm:w-64">
                <label for="fecha" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                    Fecha de consulta:
                </label>
                <input id="fecha" 
                       type="date" 
                       name="fecha" 
                       min="{{ now()->toDateString() }}" 
                       value="{{ $fecha }}" 
                       required 
                       class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-xs font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" 
                        class="rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-extrabold text-white hover:bg-emerald-700 transition">
                    Consultar fecha
                </button>
                <button type="submit" 
                        name="siguiente_dia" 
                        value="1" 
                        class="rounded-xl border border-emerald-600 bg-emerald-50 px-4 py-2.5 text-xs font-extrabold text-emerald-900 hover:bg-emerald-100 transition">
                    Siguiente día →
                </button>
            </div>
        </div>

        <!-- Current Date Presentation -->
        <div class="text-right border-t border-slate-100 pt-3 lg:border-0 lg:pt-0">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">Jornada seleccionada</span>
            <span class="font-display text-base font-black text-slate-900">
                {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
            </span>
        </div>
    </form>

    <!-- Legend Quick Indicators -->
    <div class="mt-5 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-600">
        <span class="flex items-center gap-1.5">
            <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
            <span>Libre (disponible)</span>
        </span>
        <span class="flex items-center gap-1.5">
            <span class="h-3 w-3 rounded-full bg-amber-500"></span>
            <span>Pendiente de pago</span>
        </span>
        <span class="flex items-center gap-1.5">
            <span class="h-3 w-3 rounded-full bg-rose-500"></span>
            <span>Reservada (ocupada)</span>
        </span>
        <span class="ml-auto text-[11px] text-slate-400 hidden sm:inline">
            Turnos de 08:00 a 23:00
        </span>
    </div>
</div>

<!-- Canchas Schedule List -->
<div class="mt-8 space-y-6">
    @forelse ($canchas as $cancha)
        <section class="overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm transition hover:shadow-md">
            <!-- Court Header -->
            <div class="flex flex-col gap-4 border-b border-slate-100 bg-slate-50/70 p-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                <div class="flex items-center gap-4">
                    <img src="{{ $cancha->imagen_url }}" 
                         alt="{{ $cancha->nombre }}" 
                         class="h-14 w-16 rounded-xl object-cover shadow-sm">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-black text-emerald-800">
                                {{ $cancha->icono }} {{ $cancha->deporte->nombre }}
                            </span>
                            <span class="text-xs font-medium text-slate-400">·</span>
                            <span class="text-xs font-bold text-slate-500">{{ $cancha->superficie }}</span>
                        </div>
                        <h2 class="mt-1 font-display text-xl font-extrabold text-slate-900">
                            {{ $cancha->nombre }}
                        </h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <span class="text-[11px] text-slate-500 block">Tarifas (Bs/h)</span>
                        <span class="text-xs font-extrabold text-slate-900">
                            Diurna: Bs {{ number_format((float) $cancha->tarifa_diurna, 0) }} · Nocturna: Bs {{ number_format((float) $cancha->tarifa_nocturna, 0) }}
                        </span>
                    </div>

                    <a href="{{ route('canchas.show', ['cancha' => $cancha, 'fecha' => $fecha]) }}" 
                       class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800 transition">
                        Ver ficha
                    </a>

                    <a href="{{ route('reservas.create', ['cancha_id' => $cancha->id, 'fecha' => $fecha]) }}" 
                       class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-extrabold text-white shadow-sm hover:bg-emerald-700 transition">
                        Reservar
                    </a>
                </div>
            </div>

            <!-- Schedule Blocks -->
            <div class="p-5 sm:p-7">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @forelse ($bloques[$cancha->id] as $bloque)
                        @php
                            $esLibre = ($bloque['tipo'] === 'Libre');
                            $esPendiente = ($bloque['tipo'] === 'Pendiente de pago');
                        @endphp

                        <div class="rounded-2xl p-4 border transition {{ $esLibre ? 'border-emerald-200 bg-emerald-50/70 hover:border-emerald-400' : ($esPendiente ? 'border-amber-200 bg-amber-50/70' : 'border-rose-200 bg-rose-50/70') }}">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider {{ $esLibre ? 'text-emerald-800' : ($esPendiente ? 'text-amber-900' : 'text-rose-800') }}">
                                    <span class="h-2 w-2 rounded-full {{ $esLibre ? 'bg-emerald-500' : ($esPendiente ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    <span>{{ $bloque['tipo'] }}</span>
                                </span>
                                @if ($esLibre)
                                    <span class="text-[10px] font-bold text-emerald-700">Libre</span>
                                @endif
                            </div>

                            <p class="mt-2 text-lg font-black text-slate-900 font-display">
                                {{ $bloque['inicio'] }} – {{ $bloque['fin'] }}
                            </p>

                            @if ($esLibre)
                                <div class="mt-2.5 pt-2.5 border-t border-emerald-200/60 flex items-center justify-between">
                                    <span class="text-[10px] text-emerald-800 font-medium">Horario disponible</span>
                                    <a href="{{ route('reservas.create', ['cancha_id' => $cancha->id, 'fecha' => $fecha]) }}" 
                                       class="text-xs font-extrabold text-emerald-700 hover:text-emerald-900 hover:underline">
                                        Elegir →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="rounded-xl bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 sm:col-span-2 lg:col-span-4">
                            ✨ Toda la jornada (08:00 a 23:00) está completamente libre para esta cancha.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @empty
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
            No hay canchas registradas en el sistema.
        </div>
    @endforelse
</div>
@endsection
