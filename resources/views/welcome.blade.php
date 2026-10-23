@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <section class="relative overflow-hidden rounded-[2rem] bg-slate-950 px-6 py-10 text-white shadow-xl sm:px-10 sm:py-14 lg:px-14">
        <div class="absolute -right-20 -top-24 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute -bottom-32 right-1/3 h-72 w-72 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="relative grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-emerald-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span> La Paz, Bolivia
                </span>
                <h1 class="mt-6 max-w-3xl text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">Tu pr&oacute;ximo partido empieza aqu&iacute;.</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">Reserva una cancha para f&uacute;tbol 5, b&aacute;squet o v&oacute;ley. Elige el horario y ven a jugar.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('reservas.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3.5 text-sm font-extrabold text-slate-950 transition hover:bg-emerald-300">Reservar ahora <span class="ml-2">&
                    <a href="{{ route('reservas.del_dia') }}" class="inline-flex items-center justify-center rounded-full border border-white/20 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-white/10">Consultar disponibilidad</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div class="rounded-3xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur">
                    <p class="text-3xl font-extrabold text-emerald-300">{{ $deportes->sum(fn ($deporte) => $deporte->canchas->count()) }}</p>
                    <p class="mt-1 text-sm text-slate-300">canchas activas</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/[0.07] p-5 backdrop-blur">
                    <p class="text-3xl font-extrabold text-cyan-300">{{ $deportes->count() }}</p>
                    <p class="mt-1 text-sm text-slate-300">deportes</p>
                </div>
                <a href="{{ route('reportes.ingresos') }}" class="col-span-2 flex items-center justify-between rounded-3xl border border-white/10 bg-white/[0.07] p-5 transition hover:bg-white/10">
                    <span><span class="block font-bold">Panel de ingresos</span><span class="mt-1 block text-sm text-slate-300">Consulta el resumen por deporte</span></span>
                    <span class="text-2xl text-emerald-300">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <section class="mt-12">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Instalaciones</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">Encuentra tu cancha</h2>
            </div>
            <p class="text-sm text-slate-500">Tarifas por hora en bolivianos (Bs)</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($deportes as $deporte)
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 p-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Deporte</p>
                            <h3 class="mt-2 text-xl font-extrabold">{{ $deporte->nombre }}</h3>
                        </div>
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-xl font-black text-emerald-700">{{ str($deporte->nombre)->substr(0, 1) }}</span>
                    </div>
                    <div class="space-y-3 p-5">
                        @forelse ($deporte->canchas as $cancha)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-bold text-slate-900">{{ $cancha->nombre }}</p>
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-800">Disponible</span>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                                    <p class="text-slate-500">Diurna <strong class="ml-1 text-slate-900">Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}</strong></p>
                                    <p class="text-slate-500">Nocturna <strong class="ml-1 text-slate-900">Bs {{ number_format((float) $cancha->tarifa_nocturna, 2) }}</strong></p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-500">No hay canchas activas por el momento.</p>
                        @endforelse
                        <a href="{{ route('reservas.create') }}" class="flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-eme
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500 md:col-span-2 xl:col-span-3">Pronto publicaremos nuestras canchas.</div>
            @endforelse
        </div>
    </section>
@endsection
