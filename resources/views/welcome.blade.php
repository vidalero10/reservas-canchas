@extends('layouts.app')
@section('title', 'Inicio')
@section('content')
<section class="overflow-hidden rounded-[2rem] bg-slate-950 text-white shadow-xl">
    <div class="grid gap-10 p-7 sm:p-10 lg:grid-cols-[1.2fr_.8fr] lg:p-14">
        <div class="flex flex-col justify-center">
            <p class="text-sm font-bold uppercase tracking-[.18em] text-emerald-300">Complejo deportivo · La Paz</p>
            <h1 class="mt-4 max-w-2xl text-4xl font-extrabold leading-tight sm:text-5xl">Tu próximo partido empieza aquí.</h1>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-300">Encuentra una cancha, revisa sus tarifas y reserva tu horario con facilidad.</p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                @guest
                    <a href="{{ route('register') }}" class="rounded-xl bg-emerald-500 px-6 py-3 text-center font-bold text-white hover:bg-emerald-400">Crear cuenta para reservar</a>
                    <a href="{{ route('login') }}" class="rounded-xl border border-white/20 px-6 py-3 text-center font-bold hover:bg-white/10">Iniciar sesión</a>
                @else
                    @if (auth()->user()->esAdministrador())
                        <a href="{{ route('admin.reservas.del_dia') }}" class="rounded-xl bg-emerald-500 px-6 py-3 text-center font-bold hover:bg-emerald-400">Abrir agenda</a>
                    @else
                        <a href="{{ route('reservas.create') }}" class="rounded-xl bg-emerald-500 px-6 py-3 text-center font-bold hover:bg-emerald-400">Reservar una cancha</a>
                    @endif
                @endguest
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 self-center">
            <div class="rounded-3xl border border-white/10 bg-white/[.07] p-5"><p class="text-3xl font-extrabold">08–23</p><p class="mt-1 text-sm text-slate-300">Horario de atención</p></div>
            <div class="rounded-3xl border border-white/10 bg-white/[.07] p-5"><p class="text-3xl font-extrabold">3</p><p class="mt-1 text-sm text-slate-300">Tipos de deporte</p></div>
            <div class="col-span-2 rounded-3xl border border-white/10 bg-white/[.07] p-5"><p class="font-bold">Fútbol 5, básquet y vóley</p><p class="mt-1 text-sm text-slate-300">Espacios para jugar y compartir.</p></div>
        </div>
    </div>
</section>
<section class="mt-12">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Nuestras instalaciones</p><h2 class="mt-2 text-3xl font-extrabold">Elige tu cancha</h2></div><p class="text-sm text-slate-500">Tarifas por hora · Montos en bolivianos</p></div>
    <div class="mt-6 grid gap-5 lg:grid-cols-3">
        @forelse ($deportes as $deporte)
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 p-6"><p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Deporte</p><h3 class="mt-1 text-xl font-extrabold">{{ $deporte->nombre }}</h3></div>
                <div class="space-y-3 p-5">
                    @forelse ($deporte->canchas as $cancha)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3"><p class="font-bold">{{ $cancha->nombre }}</p><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">Disponible</span></div>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm"><p class="text-slate-500">Diurna <strong class="block text-slate-900">Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}/h</strong></p><p class="text-slate-500">Nocturna <strong class="block text-slate-900">Bs {{ number_format((float) $cancha->tarifa_nocturna, 2) }}/h</strong></p></div>
                        </div>
                    @empty
                        <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">No hay canchas disponibles para este deporte.</p>
                    @endforelse
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-slate-500 lg:col-span-3">Todavía no hay canchas disponibles.</div>
        @endforelse
    </div>
</section>
@endsection
