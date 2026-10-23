@extends('layouts.app')
@section('title', 'Gestión de Canchas Deportivas · Administración')

@section('content')
<!-- Header Banner -->
<div class="relative isolate overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 p-7 sm:p-10 text-white shadow-xl shadow-emerald-950/20 border border-white/10">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-950/80 px-3.5 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">
                <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                <span>Administración de Instalaciones</span>
            </div>
            <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                Catálogo de Canchas
            </h1>
            <p class="mt-2 text-sm text-slate-300 max-w-xl">
                Configura tarifas por turno diurno/nocturno, actualiza especificaciones y controla la disponibilidad pública.
            </p>
        </div>

        <a href="{{ route('admin.canchas.create') }}" 
           class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3.5 text-xs font-black text-pitch-950 shadow-md shadow-emerald-900/30 hover:from-emerald-400 hover:to-emerald-500 transition">
            <span>＋ Agregar Nueva Cancha</span>
        </a>
    </div>
</div>

<!-- Metrics Cards -->
<div class="mt-8 grid gap-4 sm:grid-cols-3">
    <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Canchas</span>
        <p class="mt-2 font-display text-3xl font-black text-slate-900">{{ $canchas->count() }}</p>
        <span class="text-[11px] text-slate-400 mt-1 block">Instalaciones en el recinto</span>
    </div>

    <div class="rounded-3xl border border-emerald-200/80 bg-emerald-50/70 p-5 shadow-sm">
        <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Activas para Reserva</span>
        <p class="mt-2 font-display text-3xl font-black text-emerald-950">{{ $canchas->where('activa', true)->count() }}</p>
        <span class="text-[11px] text-emerald-700 mt-1 block">Visibles en catálogo público</span>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">En Mantenimiento</span>
        <p class="mt-2 font-display text-3xl font-black text-slate-500">{{ $canchas->where('activa', false)->count() }}</p>
        <span class="text-[11px] text-slate-400 mt-1 block">Desactivadas temporalmente</span>
    </div>
</div>

<!-- Live Filter Search Bar -->
<div class="mt-6">
    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
            🔍
        </span>
        <input id="filtro-admin" 
               type="search" 
               placeholder="Filtrar por nombre de cancha, disciplina (Fútbol, Básquet, Vóley) o estado..." 
               class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-xs font-semibold text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
    </div>
</div>

<!-- Courts Management Table -->
<div class="mt-6 overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] text-left text-xs sm:text-sm">
            <thead class="border-b border-slate-100 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="px-6 py-4">Cancha Deportiva</th>
                    <th class="px-6 py-4">Deporte</th>
                    <th class="px-6 py-4">Tarifa Diurna</th>
                    <th class="px-6 py-4">Tarifa Nocturna</th>
                    <th class="px-6 py-4">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($canchas as $cancha)
                    <tr data-fila="{{ mb_strtolower($cancha->nombre.' '.$cancha->deporte->nombre.' '.($cancha->activa ? 'activa disponible' : 'inactiva pausada'), 'UTF-8') }}" 
                        class="transition hover:bg-slate-50/70">
                        
                        <!-- Cancha -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $cancha->imagen_url }}" 
                                     alt="{{ $cancha->nombre }}" 
                                     class="h-11 w-14 rounded-xl object-cover shadow-sm">
                                <div>
                                    <strong class="font-extrabold text-slate-900 block text-sm">
                                        {{ $cancha->nombre }}
                                    </strong>
                                    <span class="text-[11px] text-slate-400">
                                        {{ $cancha->superficie }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Deporte -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">
                                <span>{{ $cancha->icono }}</span>
                                <span>{{ $cancha->deporte->nombre }}</span>
                            </span>
                        </td>

                        <!-- Diurna -->
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-black text-slate-900">
                                Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}
                            </span>
                            <span class="text-[10px] text-slate-400 block">< 18:00</span>
                        </td>

                        <!-- Nocturna -->
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="font-black text-emerald-800">
                                Bs {{ number_format((float) $cancha->tarifa_nocturna, 2) }}
                            </span>
                            <span class="text-[10px] text-slate-400 block">≥ 18:00</span>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            @if ($cancha->activa)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-900">
                                    <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                                    <span>● Activa</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    <span>○ Inactiva</span>
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                <a href="{{ route('admin.canchas.edit', $cancha) }}" 
                                   class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50 hover:border-emerald-300 transition">
                                    ✏ Editar
                                </a>

                                <form method="POST" 
                                      action="{{ route('admin.canchas.estado', $cancha) }}" 
                                      onsubmit="return confirm('¿Seguro que deseas cambiar el estado de disponibilidad de esta cancha?')" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                                        {{ $cancha->activa ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-500">
                            Todavía no hay canchas registradas en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filtro = document.getElementById('filtro-admin');
    filtro?.addEventListener('input', () => {
        const q = (filtro.value || '').trim().toLowerCase();
        document.querySelectorAll('[data-fila]').forEach(fila => {
            const texto = fila.dataset.fila || '';
            fila.classList.toggle('hidden', !texto.includes(q));
        });
    });
});
</script>
@endsection
