@extends('layouts.app')
@section('title', 'Inicio · Alquiler y Reservas de Canchas Deportivas')

@section('content')
<!-- =========================================================================
     HERO SECTION
     ========================================================================= -->
<section class="relative isolate overflow-hidden rounded-[2.5rem] bg-pitch-950 text-white shadow-2xl shadow-emerald-950/30">
    <!-- Hero Background Image with Atmospheric Sports Gradient Overlay -->
    <div class="absolute inset-0 -z-20">
        <img src="{{ asset('images/hero.jpg') }}" 
             alt="Instalaciones deportivas JuegaYa" 
             class="h-full w-full object-cover object-center scale-105 opacity-40">
    </div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-pitch-950 via-pitch-950/90 to-pitch-950/50"></div>
    <div class="absolute -top-32 -right-32 -z-10 h-96 w-96 rounded-full bg-emerald-500/20 blur-3xl" aria-hidden="true"></div>
    <div class="absolute -bottom-32 -left-32 -z-10 h-96 w-96 rounded-full bg-volt-500/15 blur-3xl" aria-hidden="true"></div>

    <div class="grid gap-12 px-6 py-12 sm:px-10 sm:py-16 lg:grid-cols-12 lg:px-14 lg:py-20 items-center">
        <!-- Hero Text & Primary CTAs (Col 7) -->
        <div class="lg:col-span-7 flex flex-col justify-center">
            
            <div class="inline-flex w-fit items-center gap-2.5 rounded-full border border-emerald-400/30 bg-emerald-950/70 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300 backdrop-blur-md shadow-sm">
                <span class="h-2 w-2 rounded-full bg-volt-400 animate-ping"></span>
                <span>JuegaYa · Abierto Hoy</span>
            </div>

            <h1 class="mt-6 text-4xl font-black leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl text-white">
                La pasión se juega aquí. <br>
                <span class="bg-gradient-to-r from-emerald-300 via-emerald-400 to-volt-400 bg-clip-text text-transparent">
                    Reserva tu cancha en segundos.
                </span>
            </h1>

            <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-300 sm:text-lg">
                Fútbol 5, Básquetbol y Voleibol con instalaciones de nivel competitivo en nuestro complejo. Revisa la disponibilidad en tiempo real, elige tu horario ideal y asegura tu próximo partido.
            </p>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col gap-3.5 sm:flex-row sm:items-center">
                @guest
                    <a href="{{ route('canchas.index') }}" 
                       class="group inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-emerald-400 via-emerald-500 to-emerald-400 bg-[length:200%_auto] px-7 py-4 text-center font-extrabold text-pitch-950 shadow-lg shadow-emerald-500/25 transition-all duration-300 hover:bg-right hover:scale-[1.02] focus-visible:ring-2 focus-visible:ring-emerald-400">
                        <span>Reservar una cancha</span>
                        <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-6 py-4 text-center font-bold text-white backdrop-blur-md transition hover:bg-white/20 hover:border-white/40 focus-visible:ring-2 focus-visible:ring-white">
                        <span>Crear mi cuenta</span>
                    </a>
                @else
                    @if (auth()->user()->esAdministrador())
                        <a href="{{ route('admin.reservas.del_dia') }}" 
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-400 to-volt-400 px-7 py-4 text-center font-extrabold text-pitch-950 shadow-lg shadow-emerald-500/25 transition hover:scale-[1.02]">
                            <span>Abrir agenda del día</span>
                            <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('admin.canchas.index') }}" 
                           class="inline-flex items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-6 py-4 text-center font-bold text-white backdrop-blur-md transition hover:bg-white/20">
                            <span>Gestionar canchas</span>
                        </a>
                    @else
                        <a href="{{ route('reservas.create') }}" 
                           class="inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-emerald-400 to-volt-400 px-7 py-4 text-center font-extrabold text-pitch-950 shadow-lg shadow-emerald-500/25 transition hover:scale-[1.02]">
                            <span>Nueva reserva</span>
                            <span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('cliente.reservas.index') }}" 
                           class="inline-flex items-center justify-center rounded-2xl border border-white/25 bg-white/10 px-6 py-4 text-center font-bold text-white backdrop-blur-md transition hover:bg-white/20">
                            <span>Mis reservas</span>
                        </a>
                    @endif
                @endguest
            </div>

            <!-- Quick Links -->
            <div class="mt-6 flex items-center gap-4 text-sm font-semibold text-emerald-300">
                <a href="{{ route('canchas.disponibilidad') }}" class="inline-flex items-center gap-1.5 hover:text-white transition underline decoration-emerald-400/40 underline-offset-4">
                    <span>📅 Consultar horarios disponibles en vivo</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <!-- Trust Metrics / Badges -->
            <div class="mt-10 grid grid-cols-2 gap-4 border-t border-white/10 pt-6 sm:grid-cols-4">
                <div>
                    <span class="block text-2xl font-black text-white sm:text-3xl">3</span>
                    <span class="text-xs font-semibold text-slate-300">Deportes Oficiales</span>
                </div>
                <div>
                    <span class="block text-2xl font-black text-white sm:text-3xl">9</span>
                    <span class="text-xs font-semibold text-slate-300">Canchas Equipadas</span>
                </div>
                <div>
                    <span class="block text-2xl font-black text-white sm:text-3xl">15 h</span>
                    <span class="text-xs font-semibold text-slate-300">Jornada Continua</span>
                </div>
                <div>
                    <span class="block text-2xl font-black text-volt-400 sm:text-3xl">WhatsApp</span>
                    <span class="text-xs font-semibold text-slate-300">Confirmación Directa</span>
                </div>
            </div>
        </div>

        <!-- Hero Sports Mini Cards Showcase (Col 5) -->
        <div class="lg:col-span-5 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-1">
            
            <!-- Sport 1: Fútbol 5 Preview -->
            <div class="group relative overflow-hidden rounded-3xl border border-white/15 bg-pitch-900/60 p-4 backdrop-blur-md transition hover:border-emerald-400/60 hover:bg-pitch-900/80">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/futbol.jpg') }}" 
                         alt="Fútbol 5" 
                         class="h-16 w-20 rounded-2xl object-cover shadow-md group-hover:scale-105 transition duration-300">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-lg font-extrabold text-white">Fútbol 5</span>
                            <span class="rounded-full bg-emerald-500/20 px-2.5 py-0.5 text-[11px] font-bold text-emerald-300">3 canchas</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">Césped sintético 50mm con iluminación LED</p>
                    </div>
                </div>
            </div>

            <!-- Sport 2: Básquetbol Preview -->
            <div class="group relative overflow-hidden rounded-3xl border border-white/15 bg-pitch-900/60 p-4 backdrop-blur-md transition hover:border-emerald-400/60 hover:bg-pitch-900/80">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/basquet.jpg') }}" 
                         alt="Básquetbol" 
                         class="h-16 w-20 rounded-2xl object-cover shadow-md group-hover:scale-105 transition duration-300">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-lg font-extrabold text-white">Básquetbol</span>
                            <span class="rounded-full bg-amber-500/20 px-2.5 py-0.5 text-[11px] font-bold text-amber-300">3 canchas</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">Piso de parquet amortiguado y tableros Pro</p>
                    </div>
                </div>
            </div>

            <!-- Sport 3: Voleibol Preview -->
            <div class="group relative overflow-hidden rounded-3xl border border-white/15 bg-pitch-900/60 p-4 backdrop-blur-md transition hover:border-emerald-400/60 hover:bg-pitch-900/80">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/voley.jpg') }}" 
                         alt="Voleibol" 
                         class="h-16 w-20 rounded-2xl object-cover shadow-md group-hover:scale-105 transition duration-300">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-lg font-extrabold text-white">Voleibol</span>
                            <span class="rounded-full bg-sky-500/20 px-2.5 py-0.5 text-[11px] font-bold text-sky-300">2 canchas</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-0.5">Piso deportivo sintético y red oficial tensada</p>
                    </div>
                </div>
            </div>

            <!-- Operating Hours Notice Badge -->
            <div class="flex items-center justify-between rounded-2xl border border-emerald-500/25 bg-emerald-950/40 px-5 py-3.5 backdrop-blur-md text-xs font-semibold text-emerald-200">
                <div class="flex items-center gap-2">
                    <span class="text-base">🕒</span>
                    <span>Tarifas diferenciadas: Diurna (<18h) y Nocturna (≥18h)</span>
                </div>
                <span class="rounded-md bg-volt-400/20 px-2 py-0.5 text-[11px] font-black text-volt-400">Desde 08:00</span>
            </div>

        </div>
    </div>
</section>

<!-- =========================================================================
     SECTION 2: 3 SPORTS DISCIPLINES SHOWCASE
     ========================================================================= -->
<section class="mt-20">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-emerald-700">
                <span class="h-1.5 w-6 rounded-full bg-emerald-600"></span>
                <span>Disciplinas Disponibles</span>
            </div>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-white sm:text-4xl">
                ¿Qué te gustaría jugar hoy?
            </h2>
        </div>
        <p class="text-sm font-medium text-slate-500 max-w-sm sm:text-right">
            Instalaciones reglamentarias adaptadas a partidos casuales, entrenamientos y campeonatos.
        </p>
    </div>

    <!-- Sports 3 Columns Grid -->
    <div class="mt-8 grid gap-7 lg:grid-cols-3">
        @foreach ($deportes as $deporte)
            @php
                $nombreNorm = mb_strtolower($deporte->nombre, 'UTF-8');
                $esBasquet = str_contains($nombreNorm, 'squet') || str_contains($nombreNorm, 'bas');
                $esVoley = str_contains($nombreNorm, 'ley') || str_contains($nombreNorm, 'vol');
                $esFutbol = !$esBasquet && !$esVoley;
                
                $imagenDeporte = $esBasquet ? asset('images/basquet.jpg') : ($esVoley ? asset('images/voley.jpg') : asset('images/futbol.jpg'));
                $iconoDeporte = $esBasquet ? '🏀' : ($esVoley ? '🏐' : '⚽');
                $superficieDeporte = $esBasquet ? 'Parquet Flotante Amortiguado' : ($esVoley ? 'Piso Deportivo Sintético Pro' : 'Césped Sintético Monofilamento');
                $canchasCount = $deporte->canchas->count();
                $tarifaMinima = $deporte->canchas->min('tarifa_diurna') ?? 50;
            @endphp

            <article class="sport-card flex flex-col overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm">
                <!-- Sport Card Photo Header -->
                <div class="relative h-56 w-full overflow-hidden bg-slate-100">
                    <img src="{{ $imagenDeporte }}" 
                         alt="{{ $deporte->nombre }}" 
                         class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-pitch-950/80 via-pitch-950/20 to-transparent"></div>
                    
                    <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-black text-slate-900 shadow-md backdrop-blur">
                        <span>{{ $iconoDeporte }}</span>
                        <span>{{ $deporte->nombre }}</span>
                    </span>

                    <span class="absolute top-4 right-4 rounded-full bg-pitch-900/80 px-3 py-1 text-xs font-bold text-emerald-300 backdrop-blur border border-emerald-500/30">
                        {{ $canchasCount }} {{ $canchasCount === 1 ? 'cancha' : 'canchas' }}
                    </span>

                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-300 block">Superficie reglamentaria</span>
                        <span class="text-sm font-bold text-white block">{{ $superficieDeporte }}</span>
                    </div>
                </div>

                <!-- Sport Card Body -->
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-baseline justify-between border-b border-slate-100 pb-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tarifa desde</span>
                            <span class="text-lg font-black text-emerald-700">Bs {{ number_format((float) $tarifaMinima, 2) }} <span class="text-xs font-medium text-slate-500">/ hora</span></span>
                        </div>

                        <!-- Canchas List inside Sport -->
                        <div class="mt-4 space-y-2.5">
                            @forelse ($deporte->canchas as $cancha)
                                <a href="{{ route('canchas.show', $cancha) }}" 
                                   class="group flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/70 p-3 transition hover:border-emerald-300 hover:bg-emerald-50/60">
                                    <div>
                                        <span class="text-sm font-extrabold text-slate-800 group-hover:text-emerald-900">{{ $cancha->nombre }}</span>
                                        <span class="block text-[11px] text-slate-500">
                                            Diurna: <strong>Bs {{ number_format((float) $cancha->tarifa_diurna, 0) }}</strong> · Nocturna: <strong>Bs {{ number_format((float) $cancha->tarifa_nocturna, 0) }}</strong>
                                        </span>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-700 group-hover:translate-x-1 transition-transform">Ver →</span>
                                </a>
                            @empty
                                <p class="text-xs text-slate-500 py-2">Próximamente nuevas canchas disponibles.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Card Footer CTA -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-3">
                        <a href="{{ route('canchas.index') }}" 
                           class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 py-3 text-center text-xs font-extrabold text-white transition hover:bg-emerald-700">
                            <span>Ver todas las canchas de {{ $deporte->nombre }}</span>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

<!-- =========================================================================
     SECTION 3: HOW IT WORKS / PROCESO DE RESERVA
     ========================================================================= -->
<section class="mt-20 rounded-[2.5rem] bg-gradient-to-br from-pitch-900 via-pitch-950 to-pitch-900 p-8 sm:p-12 lg:p-16 text-white shadow-xl shadow-emerald-950/20">
    <div class="max-w-2xl">
        <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-emerald-400">
            <span class="h-1.5 w-6 rounded-full bg-volt-400"></span>
            <span>Rápido, Simple y Transparente</span>
        </span>
        <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
            Reserva tu partido en 3 simples pasos
        </h2>
        <p class="mt-3 text-sm sm:text-base text-slate-300">
            Diseñamos un flujo intuitivo para que elijas tu horario y asegures la cancha sin complicaciones.
        </p>
    </div>

    <!-- Steps Grid -->
    <div class="mt-12 grid gap-8 md:grid-cols-3">
        <!-- Step 1 -->
        <div class="relative rounded-3xl border border-white/10 bg-white/[0.05] p-6 backdrop-blur-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-pitch-950 font-black text-xl shadow-lg shadow-emerald-500/25">
                1
            </span>
            <h3 class="mt-5 font-display text-xl font-extrabold">Elige fecha y horario</h3>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Selecciona el deporte que practicarás, el día que te convenga y la hora deseada entre las 08:00 y las 23:00.
            </p>
            <span class="mt-4 inline-block text-xs font-bold text-emerald-300">Turnos de 1 a 4 horas</span>
        </div>

        <!-- Step 2 -->
        <div class="relative rounded-3xl border border-white/10 bg-white/[0.05] p-6 backdrop-blur-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-volt-400 text-pitch-950 font-black text-xl shadow-lg shadow-volt-400/25">
                2
            </span>
            <h3 class="mt-5 font-display text-xl font-extrabold">Selecciona tu cancha</h3>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                El sistema filtra automáticamente las canchas disponibles sin solapamientos y te muestra el monto exacto a pagar.
            </p>
            <span class="mt-4 inline-block text-xs font-bold text-volt-300">Tarifas diurnas y nocturnas claras</span>
        </div>

        <!-- Step 3 -->
        <div class="relative rounded-3xl border border-white/10 bg-white/[0.05] p-6 backdrop-blur-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-400 text-pitch-950 font-black text-xl shadow-lg shadow-emerald-400/25">
                3
            </span>
            <h3 class="mt-5 font-display text-xl font-extrabold">Coordina por WhatsApp</h3>
            <p class="mt-2 text-sm text-slate-300 leading-relaxed">
                Tu solicitud queda guardada y se genera un mensaje de WhatsApp directo para validar tu comprobante de pago de inmediato.
            </p>
            <span class="mt-4 inline-block text-xs font-bold text-emerald-300">Confirmación ágil por administración</span>
        </div>
    </div>

    <!-- Step CTA Bar -->
    <div class="mt-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-white/10 pt-8">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⚡</span>
            <p class="text-sm font-semibold text-emerald-100">
                ¿Quieres consultar antes de registrarte? Revisa la agenda libre del día.
            </p>
        </div>
        <a href="{{ route('canchas.disponibilidad') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-black text-pitch-950 transition hover:bg-emerald-50">
            <span>Ver disponibilidad general</span>
            <span aria-hidden="true">→</span>
        </a>
    </div>
</section>

<!-- =========================================================================
     SECTION 4: ADVANTAGES / POR QUÉ ELEGIR JUEGAYA
     ========================================================================= -->
<section class="mt-20">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-black uppercase tracking-[0.2em] text-emerald-700">Ventajas del Complejo</span>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-white sm:text-4xl">
            Todo lo necesario para tu mejor partido
        </h2>
        <p class="mt-3 text-sm text-slate-500">
            Instalaciones pensadas para la comodidad, seguridad y disfrute de los jugadores.
        </p>
    </div>

    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Advantage 1 -->
        <div class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-2xl text-emerald-700">
                💡
            </div>
            <h3 class="mt-4 font-display text-lg font-extrabold text-slate-900">Iluminación LED Pro</h3>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Proyectores de alta potencia distribuidos uniformemente para eliminar sombras en tus partidos nocturnos.
            </p>
        </div>

        <!-- Advantage 2 -->
        <div class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-2xl text-emerald-700">
                🚿
            </div>
            <h3 class="mt-4 font-display text-lg font-extrabold text-slate-900">Vestuarios & Duchas</h3>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Instalaciones sanitarias limpias, agua caliente y casilleros seguros para que juegues con total tranquilidad.
            </p>
        </div>

        <!-- Advantage 3 -->
        <div class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-2xl text-emerald-700">
                🚗
            </div>
            <h3 class="mt-4 font-display text-lg font-extrabold text-slate-900">Parqueo Privado</h3>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Estacionamiento exclusivo para jugadores dentro del recinto deportivo con vigilancia y fácil acceso.
            </p>
        </div>

        <!-- Advantage 4 -->
        <div class="sport-card rounded-3xl border border-slate-200/80 bg-white p-6 shadow-sm">
            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-2xl text-emerald-700">
                🛡️
            </div>
            <h3 class="mt-4 font-display text-lg font-extrabold text-slate-900">Juego Limpio & Seguridad</h3>
            <p class="mt-2 text-xs leading-5 text-slate-500">
                Ambiente familiar y deportivo, con botiquín de primeros auxilios y personal de atención permanente.
            </p>
        </div>
    </div>
</section>

<!-- =========================================================================
     SECTION 5: FINAL HIGH-CONVERSION CTA
     ========================================================================= -->
<section class="mt-20 overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-emerald-700 via-emerald-800 to-pitch-950 p-8 sm:p-12 text-white shadow-2xl shadow-emerald-950/20">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <div class="max-w-xl">
            <span class="text-xs font-black uppercase tracking-[0.2em] text-volt-300">¿Listo para jugar?</span>
            <h2 class="mt-2 text-3xl font-black sm:text-4xl tracking-tight">
                Arma el equipo y asegura tu cancha hoy
            </h2>
            <p class="mt-3 text-sm text-emerald-100/80 leading-relaxed">
                Los mejores horarios de la tarde y la noche suelen ocuparse rápido. Elige tu cancha y no te quedes sin jugar.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3.5 shrink-0">
            @guest
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-7 py-4 font-black text-emerald-950 shadow-lg shadow-pitch-950/20 hover:bg-emerald-50 transition">
                    <span>Crear cuenta gratis</span>
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('canchas.index') }}" 
                   class="inline-flex items-center justify-center rounded-2xl border border-white/30 bg-white/10 px-6 py-4 font-bold text-white backdrop-blur hover:bg-white/20 transition">
                    <span>Explorar catálogo</span>
                </a>
            @else
                <a href="{{ route('reservas.create') }}" 
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-7 py-4 font-black text-emerald-950 shadow-lg shadow-pitch-950/20 hover:bg-emerald-50 transition">
                    <span>Comenzar mi reserva</span>
                    <span aria-hidden="true">→</span>
                </a>
            @endguest
        </div>
    </div>
</section>
@endsection
