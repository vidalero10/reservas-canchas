@extends('layouts.app')
@section('title', $cancha->nombre . ' · ' . $cancha->deporte->nombre)

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="Ruta de navegación" class="mb-6 flex items-center gap-2 text-xs font-semibold text-slate-500">
    <a href="{{ route('inicio') }}" class="hover:text-emerald-700 transition">Inicio</a>
    <span class="text-slate-300">/</span>
    <a href="{{ route('canchas.index') }}" class="hover:text-emerald-700 transition">Canchas</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-900 font-bold">{{ $cancha->nombre }}</span>
</nav>

<!-- Main Court Showcase Card -->
<article class="overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white shadow-sm">
    <!-- Court Photo Header Banner -->
    <div class="relative h-72 sm:h-96 w-full overflow-hidden bg-pitch-950">
        <img src="{{ $cancha->imagen_url }}" 
             alt="Fotografía de {{ $cancha->nombre }}" 
             class="h-full w-full object-cover">
        
        <div class="absolute inset-0 bg-gradient-to-t from-pitch-950 via-pitch-950/30 to-black/30"></div>

        <!-- Floating Badges Top -->
        <div class="absolute top-6 left-6 right-6 flex items-center justify-between">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/95 px-4 py-2 text-xs font-black text-slate-900 shadow-lg backdrop-blur">
                <span class="text-base">{{ $cancha->icono }}</span>
                <span>{{ $cancha->deporte->nombre }}</span>
            </span>

            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-950/80 px-3 py-1.5 text-xs font-bold text-volt-300 border border-emerald-500/30 backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                <span>Cancha Activa</span>
            </span>
        </div>

        <!-- Floating Info Bottom -->
        <div class="absolute bottom-6 left-6 right-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 text-white">
            <div>
                <span class="text-xs font-black uppercase tracking-[0.2em] text-emerald-300 block">JuegaYa</span>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mt-1">{{ $cancha->nombre }}</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-xl bg-pitch-900/80 px-3.5 py-1.5 text-xs font-bold text-emerald-200 border border-emerald-500/20 backdrop-blur">
                    {{ $cancha->superficie }}
                </span>
            </div>
        </div>
    </div>

    <!-- Details and Pricing Grid -->
    <div class="grid gap-10 p-6 sm:p-10 lg:grid-cols-12">
        <!-- Left: Description and Technical Specs (Col 8) -->
        <div class="lg:col-span-8 space-y-6">
            <div>
                <span class="text-xs font-black uppercase tracking-[0.2em] text-emerald-700">Presentación de la Cancha</span>
                <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900">Instalación Reglamentaria</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600 whitespace-pre-line">
                    {{ $cancha->descripcion ?: 'Espacio deportivo acondicionado bajo normativas oficiales, con iluminación nocturna uniforme y mantenimiento diario para brindarte la mejor experiencia de juego en JuegaYa.' }}
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid gap-4 sm:grid-cols-2 border-t border-slate-100 pt-6">
                <div class="flex items-start gap-3.5 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-lg text-emerald-800">🏟️</span>
                    <div>
                        <strong class="text-xs font-extrabold text-slate-900 block">Superficie Oficial</strong>
                        <span class="text-xs text-slate-500">{{ $cancha->superficie }} de alta durabilidad y absorción de impacto.</span>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-lg text-emerald-800">💡</span>
                    <div>
                        <strong class="text-xs font-extrabold text-slate-900 block">Iluminación LED Pro</strong>
                        <span class="text-xs text-slate-500">Reflectores profesionales para partidos nocturnos nítidos sin encandilamiento.</span>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-lg text-emerald-800">⏱️</span>
                    <div>
                        <strong class="text-xs font-extrabold text-slate-900 block">Horario de Juego</strong>
                        <span class="text-xs text-slate-500">Todos los días, 08:00 a 23:00. Turnos continuos de 1 a 4 horas.</span>
                    </div>
                </div>

                <div class="flex items-start gap-3.5 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-100 text-lg text-emerald-800">🚿</span>
                    <div>
                        <strong class="text-xs font-extrabold text-slate-900 block">Servicios del Complejo</strong>
                        <span class="text-xs text-slate-500">Acceso a vestuarios, duchas con agua caliente y parqueo privado.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Sticky Tariff Card (Col 4) -->
        <aside class="lg:col-span-4">
            <div class="sticky top-24 rounded-3xl border border-emerald-950/10 bg-gradient-to-br from-pitch-950 via-pitch-900 to-pitch-950 p-6 sm:p-7 text-white shadow-xl shadow-emerald-950/20">
                <span class="text-xs font-black uppercase tracking-[0.2em] text-volt-300">Tarifas Transparentes</span>
                <h3 class="mt-1 font-display text-xl font-black text-white">Tarifas por hora</h3>
                
                <div class="mt-6 space-y-3.5">
                    <!-- Day Rate -->
                    <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <div>
                            <span class="text-xs font-bold text-slate-300 block">☀️ Horario Diurno</span>
                            <span class="text-[11px] text-emerald-300">08:00 a 18:00</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-white">Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}</span>
                            <span class="text-[10px] text-slate-400 block">por hora</span>
                        </div>
                    </div>

                    <!-- Night Rate -->
                    <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-950/50 p-4 backdrop-blur">
                        <div>
                            <span class="text-xs font-bold text-volt-300 block">🌙 Horario Nocturno</span>
                            <span class="text-[11px] text-slate-300">18:00 a 23:00 (con luz)</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-volt-300">Bs {{ number_format((float) $cancha->tarifa_nocturna, 2) }}</span>
                            <span class="text-[10px] text-slate-400 block">por hora</span>
                        </div>
                    </div>
                </div>

                <!-- Booking CTA Button -->
                <a href="{{ route('reservas.create', ['cancha_id' => $cancha->id]) }}" 
                   class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-400 bg-[length:200%_auto] py-4 text-center font-black text-pitch-950 shadow-lg shadow-emerald-500/25 transition hover:bg-right hover:scale-[1.02]">
                    <span>Reservar esta cancha</span>
                    <span aria-hidden="true">→</span>
                </a>

                <p class="mt-4 text-center text-[11px] text-slate-400">
                    El monto exacto se calcula sumando las horas según tarifa diurna o nocturna.
                </p>
            </div>
        </aside>
    </div>
</article>

<!-- Daily Agenda & Schedule Timeline -->
<section class="mt-12 rounded-[2.5rem] border border-slate-200/80 bg-white p-6 sm:p-10 shadow-sm">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between border-b border-slate-100 pb-8">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>Agenda en Tiempo Real</span>
            </div>
            <h2 class="mt-2 text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
                Disponibilidad para {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Los tramos en verde están libres para reservar; los ocupados corresponden a reservas confirmadas o en verificación.
            </p>
        </div>

        <!-- Date Selector Form -->
        <form method="GET" action="{{ route('canchas.show', $cancha) }}" class="flex flex-wrap items-end gap-2.5">
            <div>
                <label for="fecha" class="block text-xs font-bold text-slate-600 mb-1">Elegir otra fecha:</label>
                <input id="fecha" 
                       type="date" 
                       name="fecha" 
                       min="{{ now()->toDateString() }}" 
                       value="{{ $fecha }}" 
                       required 
                       class="rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-xs font-semibold text-slate-800 shadow-sm">
            </div>
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition">
                Consultar fecha
            </button>
            <button type="submit" name="siguiente_dia" value="1" class="rounded-xl border border-emerald-600 bg-emerald-50 px-4 py-2 text-xs font-extrabold text-emerald-900 hover:bg-emerald-100 transition">
                Día siguiente →
            </button>
        </form>
    </div>

    <!-- Timeline Blocks Grid -->
    <div class="mt-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($bloques as $bloque)
                @php
                    $esLibre = ($bloque['tipo'] === 'Libre');
                    $esPendiente = ($bloque['tipo'] === 'Pendiente de pago');
                @endphp

                <div class="rounded-2xl p-4 border transition {{ $esLibre ? 'border-emerald-200 bg-emerald-50/70 hover:border-emerald-400' : ($esPendiente ? 'border-amber-200 bg-amber-50/80' : 'border-rose-200 bg-rose-50/70') }}">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wider {{ $esLibre ? 'text-emerald-800' : ($esPendiente ? 'text-amber-900' : 'text-rose-800') }}">
                            <span class="h-2 w-2 rounded-full {{ $esLibre ? 'bg-emerald-500' : ($esPendiente ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                            <span>{{ $bloque['tipo'] }}</span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-400">
                            {{ $esLibre ? 'Disponible' : 'No disponible' }}
                        </span>
                    </div>

                    <p class="mt-2 text-xl font-black text-slate-900 font-display">
                        {{ $bloque['inicio'] }} – {{ $bloque['fin'] }}
                    </p>

                    @if ($esLibre)
                        <div class="mt-3 pt-3 border-t border-emerald-200/60 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-800">Tramo sin reservas</span>
                            <a href="{{ route('reservas.create', ['cancha_id' => $cancha->id, 'fecha' => $fecha]) }}" 
                               class="rounded-lg bg-emerald-600 px-2.5 py-1 text-[11px] font-bold text-white hover:bg-emerald-700 transition">
                                Elegir horario →
                            </a>
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-t border-slate-200/60 text-[11px] text-slate-500">
                            {{ $esPendiente ? 'En proceso de verificación de pago.' : 'Horario ocupado con reserva confirmada.' }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl bg-emerald-50 p-6 text-center text-emerald-900 font-semibold text-sm sm:col-span-2 lg:col-span-4">
                    ✨ Toda la jornada (08:00 a 23:00) está completamente libre para esta fecha.
                </div>
            @endforelse
        </div>

        <!-- Legend and Notice -->
        <div class="mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs text-slate-500">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-md bg-emerald-500"></span>
                    <strong>Verde:</strong> Horario libre
                </span>
                <span class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-md bg-amber-500"></span>
                    <strong>Amarillo:</strong> Pendiente de pago
                </span>
                <span class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-md bg-rose-500"></span>
                    <strong>Rojo:</strong> Reservada
                </span>
            </div>
            <div>Jornada oficial: 08:00 a 23:00 · JuegaYa</div>
        </div>
    </div>
</section>
@endsection
