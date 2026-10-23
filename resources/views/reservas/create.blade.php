@extends('layouts.app')

@section('title', 'Crear reserva')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('inicio') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">&larr; Volver al inicio</a>
        <div class="mt-5 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
            <div class="mb-8">
                <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Nueva reserva</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Separa tu horario</h1>
                <p class="mt-3 text-slate-500">Elige el cliente, la cancha y el horario. Atendemos de 08:00 a 23:00.</p>
            </div>

            <form method="POST" action="{{ route('reservas.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="cliente_id" class="mb-2 block text-sm font-bold text-slate-700">Cliente</label>
                    <select id="cliente_id" name="cliente_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>{{ $cliente->nombre }} &middot; CI {{ $cliente->ci }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="cancha_id" class="mb-2 block text-sm font-bold text-slate-700">Cancha</label>
                    <select id="cancha_id" name="cancha_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                        <option value="">Selecciona una cancha</option>
                        @foreach ($canchas as $cancha)
                            <option value="{{ $cancha->id }}" @selected(old('cancha_id') == $cancha->id)>{{ $cancha->nombre }} &mdash; {{ $cancha->deporte->nombre }} (Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}/hora diurna)</option>
                        @endforeach
                    </select>
                    @if ($canchas->isEmpty())
                        <p class="mt-2 text-sm text-amber-700">No hay canchas activas disponibles en este momento.</p>
                    @endif
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-1">
                        <label for="fecha" class="mb-2 block text-sm font-bold text-slate-700">Fecha</label>
                        <input id="fecha" name="fecha" type="date" required min="{{ now()->toDateString() }}" value="{{ old('fecha', now()->toDateString()) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:borde
                    </div>
                    <div class="sm:col-span-1">
                        <label for="hora_inicio" class="mb-2 block text-sm font-bold text-slate-700">Hora de inicio</label>
                        <input id="hora_inicio" name="hora_inicio" type="time" required min="08:00" max="22:00" step="3600" value="{{ old('hora_inicio', '18:00') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus
                    </div>
                    <div class="sm:col-span-1">
                        <label for="duracion_horas" class="mb-2 block text-sm font-bold text-slate-700">Duraci&oacute;n</label>
                        <select id="duracion_horas" name="duracion_horas" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                            @foreach (range(1, 4) as $duracion)
                                <option value="{{ $duracion }}" @selected(old('duracion_horas', 1) == $duracion)>{{ $duracion }} {{ $duracion === 1 ? 'hora' : 'horas' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                    La hora de finalizaci&oacute;n no puede pasar de las 23:00. El total se calcula autom&aacute;ticamente seg&uacute;n la tarifa diurna o nocturna.
                </div>

                <button type="submit" @disabled($canchas->isEmpty() || $clientes->isEmpty()) class="flex w-full items-center justify-center rounded-full bg-emerald-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-emerald-70
            </form>
        </div>
    </div>
@endsection
