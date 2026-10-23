@extends('layouts.app')
@section('title', 'Catálogo de Canchas Deportivas')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400 animate-ping"></span>
                <span>Instalaciones Oficiales</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl lg:text-5xl">
                Encuentra tu cancha perfecta
            </h1>
            <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed">
                Superficies reglamentarias de Fútbol 5, Básquetbol y Voleibol con iluminación profesional y tarifas transparentes.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <a href="{{ route('canchas.disponibilidad') }}" 
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-5 py-3 text-sm font-extrabold text-pitch-950 shadow-md shadow-emerald-900/30 hover:from-emerald-400 hover:to-emerald-500 transition">
                <span>📅 Ver horarios en vivo</span>
                <span aria-hidden="true">→</span>
            </a>
            @auth
                @if (!auth()->user()->esAdministrador())
                    <a href="{{ route('reservas.create') }}" 
                       class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur hover:bg-white/20 transition">
                        <span>＋ Nueva reserva</span>
                    </a>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- Interactive Search & Sports Filter Bar -->
<div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <!-- Sport Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2" id="filtros-deporte">
        <button type="button" 
                data-filtro="todos" 
                class="btn-filtro-activo rounded-xl bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-sm transition">
            Todos ({{ $canchas->count() }})
        </button>
        <button type="button" 
                data-filtro="futbol" 
                class="btn-filtro rounded-xl bg-white border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50/60 transition">
            ⚽ Fútbol 5
        </button>
        <button type="button" 
                data-filtro="basquet" 
                class="btn-filtro rounded-xl bg-white border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50/60 transition">
            🏀 Básquetbol
        </button>
        <button type="button" 
                data-filtro="voley" 
                class="btn-filtro rounded-xl bg-white border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50/60 transition">
            🏐 Voleibol
        </button>
    </div>

    <!-- Search Input Field -->
    <div class="relative w-full sm:w-80">
        <label for="filtro-canchas" class="sr-only">Buscar cancha</label>
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input id="filtro-canchas" 
               type="search" 
               placeholder="Buscar por cancha o deporte..." 
               class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-xs font-semibold text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
    </div>
</div>

<!-- Results Counter -->
<div class="mt-4 flex items-center justify-between text-xs font-bold text-slate-500">
    <span id="contador-resultados">Mostrando {{ $canchas->count() }} canchas activas</span>
    <span class="hidden sm:inline">Tarifas por hora en Bolivianos (Bs) · Atención de 08:00 a 23:00</span>
</div>

<!-- Canchas Grid -->
<div id="lista-canchas" class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($canchas as $cancha)
        @php
            $nombreNorm = mb_strtolower($cancha->deporte->nombre, 'UTF-8');
            $categoriaFiltro = str_contains($nombreNorm, 'squet') || str_contains($nombreNorm, 'bas') 
                ? 'basquet' 
                : (str_contains($nombreNorm, 'ley') || str_contains($nombreNorm, 'vol') ? 'voley' : 'futbol');
        @endphp

        <article data-categoria="{{ $categoriaFiltro }}" 
                 data-busqueda="{{ mb_strtolower($cancha->nombre.' '.$cancha->deporte->nombre.' '.$cancha->descripcion, 'UTF-8') }}" 
                 class="sport-card flex flex-col overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-sm transition">
            
            <!-- Court Image Header -->
            <a href="{{ route('canchas.show', $cancha) }}" class="relative block h-56 w-full overflow-hidden bg-slate-100 group">
                <img src="{{ $cancha->imagen_url }}" 
                     alt="Instalaciones de {{ $cancha->nombre }}" 
                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                
                <div class="absolute inset-0 bg-gradient-to-t from-pitch-950/80 via-transparent to-black/20"></div>

                <!-- Floating Sport Pill -->
                <span class="absolute top-4 left-4 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-3 py-1.5 text-xs font-black text-slate-900 shadow-md backdrop-blur">
                    <span>{{ $cancha->icono }}</span>
                    <span>{{ $cancha->deporte->nombre }}</span>
                </span>

                <!-- Active Status Pill -->
                <span class="absolute top-4 right-4 inline-flex items-center gap-1 rounded-full bg-emerald-950/80 px-2.5 py-1 text-[11px] font-bold text-volt-300 border border-emerald-500/30 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-volt-400"></span>
                    <span>Disponible</span>
                </span>

                <!-- Bottom Surface Tag -->
                <div class="absolute bottom-3.5 left-4 right-4 flex items-center justify-between text-xs text-white">
                    <span class="font-bold text-emerald-200">{{ $cancha->superficie }}</span>
                    <span class="text-xs text-slate-300">08:00 - 23:00</span>
                </div>
            </a>

            <!-- Court Details & Pricing -->
            <div class="flex-1 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="font-display text-xl font-extrabold text-slate-900">
                        <a href="{{ route('canchas.show', $cancha) }}" class="hover:text-emerald-700 transition">
                            {{ $cancha->nombre }}
                        </a>
                    </h2>

                    <p class="mt-2 text-xs leading-5 text-slate-500 line-clamp-2">
                        {{ $cancha->descripcion ?: 'Cancha reglamentaria con excelente iluminación y mantenimiento permanente, lista para tus partidos y entrenamientos.' }}
                    </p>

                    <!-- Dual Tariff Badge Box -->
                    <div class="mt-4 grid grid-cols-2 gap-2.5 rounded-2xl bg-slate-50 p-3.5 border border-slate-100 text-xs">
                        <div class="border-r border-slate-200/80 pr-2">
                            <span class="text-slate-500 block font-semibold">☀️ Diurna (<18h)</span>
                            <strong class="mt-1 block font-black text-slate-900 text-sm">
                                Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}
                                <span class="text-[10px] font-normal text-slate-500">/h</span>
                            </strong>
                        </div>
                        <div class="pl-2">
                            <span class="text-slate-500 block font-semibold">🌙 Nocturna (≥18h)</span>
                            <strong class="mt-1 block font-black text-emerald-800 text-sm">
                                Bs {{ number_format((float) $cancha->tarifa_nocturna, 2) }}
                                <span class="text-[10px] font-normal text-slate-500">/h</span>
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Dual Action Buttons -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center gap-2.5">
                    <a href="{{ route('canchas.show', $cancha) }}" 
                       class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-center text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-800 transition">
                        Ver detalle y horarios
                    </a>

                    <a href="{{ route('reservas.create', ['cancha_id' => $cancha->id]) }}" 
                       class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-center text-xs font-extrabold text-white shadow-sm hover:bg-emerald-700 transition flex items-center justify-center gap-1">
                        <span>Reservar</span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500 sm:col-span-2 lg:col-span-3">
            <span class="text-4xl">🏟️</span>
            <p class="mt-3 font-extrabold text-base text-slate-800">Todavía no hay canchas activas</p>
            <p class="mt-1 text-xs text-slate-500">Estamos preparando nuevas instalaciones para ti.</p>
        </div>
    @endforelse
</div>

<!-- Empty Results Notice -->
<div id="sin-resultados" class="mt-8 hidden rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
    <span class="text-3xl">🔍</span>
    <h3 class="mt-3 font-display text-lg font-extrabold text-slate-900">No encontramos canchas con esa búsqueda</h3>
    <p class="mt-1 text-xs text-slate-500">Prueba con otro término o limpia los filtros para ver todas las opciones.</p>
    <button id="limpiar-filtros" type="button" class="mt-4 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition">
        Restablecer búsqueda
    </button>
</div>

<!-- Interactive Search & Filtering Logic -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('filtro-canchas');
    const botonesFiltro = document.querySelectorAll('#filtros-deporte button');
    const tarjetas = document.querySelectorAll('#lista-canchas article');
    const mensajeSinResultados = document.getElementById('sin-resultados');
    const contador = document.getElementById('contador-resultados');
    const btnLimpiar = document.getElementById('limpiar-filtros');

    let categoriaActual = 'todos';

    function filtrarCanchas() {
        const query = (inputBusqueda.value || '').trim().toLowerCase();
        let visibles = 0;

        tarjetas.forEach(tarjeta => {
            const categoriaTarjeta = tarjeta.dataset.categoria;
            const textoBusqueda = tarjeta.dataset.busqueda;

            const coincideCategoria = (categoriaActual === 'todos') || (categoriaTarjeta === categoriaActual);
            const coincideTexto = !query || textoBusqueda.includes(query);

            const visible = coincideCategoria && coincideTexto;
            tarjeta.classList.toggle('hidden', !visible);
            if (visible) visibles++;
        });

        if (mensajeSinResultados) {
            mensajeSinResultados.classList.toggle('hidden', visibles !== 0);
        }

        if (contador) {
            contador.textContent = `Mostrando ${visibles} ${visibles === 1 ? 'cancha disponible' : 'canchas disponibles'}`;
        }
    }

    botonesFiltro.forEach(btn => {
        btn.addEventListener('click', () => {
            botonesFiltro.forEach(b => {
                b.className = 'btn-filtro rounded-xl bg-white border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50/60 transition';
            });
            btn.className = 'btn-filtro-activo rounded-xl bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-sm transition';

            categoriaActual = btn.dataset.filtro;
            filtrarCanchas();
        });
    });

    inputBusqueda?.addEventListener('input', filtrarCanchas);

    btnLimpiar?.addEventListener('click', () => {
        inputBusqueda.value = '';
        categoriaActual = 'todos';
        botonesFiltro.forEach((b, i) => {
            if (i === 0) {
                b.className = 'btn-filtro-activo rounded-xl bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-sm transition';
            } else {
                b.className = 'btn-filtro rounded-xl bg-white border border-slate-200 px-4 py-2 text-xs font-bold text-slate-700 hover:border-emerald-300 hover:bg-emerald-50/60 transition';
            }
        });
        filtrarCanchas();
    });
});
</script>
@endsection
