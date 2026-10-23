@extends('layouts.app')
@section('title', 'Reservar cancha')
@section('content')
<div class="mx-auto max-w-3xl">
    <p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Nueva reserva</p>
    <h1 class="mt-2 text-3xl font-extrabold">Aparta tu horario</h1>
    <p class="mt-2 text-slate-500">Elige una cancha activa y un horario entre las 08:00 y las 23:00.</p>
    @if (!$cliente)
        <div role="alert" class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900">Tu cuenta todavía no tiene ficha de cliente asociada. Contacta a administración.</div>
    @else
        <div class="mt-6 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-900">Reserva a nombre de <strong>{{ $cliente->nombre }}</strong>.</div>
        <form method="POST" action="{{ route('reservas.store') }}" class="mt-5 space-y-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8">
            @csrf
            <label class="block text-sm font-semibold">Cancha
                <select name="cancha_id" required class="mt-2 w-full rounded-xl border-slate-300">
                    <option value="">Selecciona una cancha</option>
                    @foreach ($canchas as $cancha)
                        <option value="{{ $cancha->id }}" @selected(old('cancha_id') == $cancha->id)>{{ $cancha->nombre }} · {{ $cancha->deporte->nombre }} · Bs {{ number_format((float) $cancha->tarifa_diurna, 2) }}/{{ number_format((float) $cancha->tarifa_nocturna, 2) }} por hora (día/noche)</option>
                    @endforeach
                </select>
            </label>
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block text-sm font-semibold">Fecha
                    <input type="date" name="fecha" min="{{ now()->toDateString() }}" value="{{ old('fecha') }}" required class="mt-2 w-full rounded-xl border-slate-300">
                </label>
                <label class="block text-sm font-semibold">Hora de inicio
                    <input type="time" name="hora_inicio" min="08:00" max="22:00" value="{{ old('hora_inicio') }}" required class="mt-2 w-full rounded-xl border-slate-300">
                </label>
            </div>
            <label class="block text-sm font-semibold">Duración
                <select name="duracion_horas" required class="mt-2 w-full rounded-xl border-slate-300">
                    <option value="">Selecciona duración</option>
                    @foreach (range(1, 4) as $horas)
                        <option value="{{ $horas }}" @selected(old('duracion_horas') == $horas)>{{ $horas }} {{ $horas === 1 ? 'hora' : 'horas' }}</option>
                    @endforeach
                </select>
            </label>
            <p class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">El monto se calcula según la tarifa de cada hora. Las reservas que excedan el horario de atención serán rechazadas.</p>
            <button class="w-full rounded-xl bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800">Confirmar reserva</button>
        </form>
    @endif
</div>
@endsection
