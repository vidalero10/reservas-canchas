@extends('layouts.app')

@section('title', 'Agenda del día')

@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div><p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Administración</p><h1 class="mt-2 text-3xl font-extrabold">Agenda del día</h1><p class="mt-2 text-slate-500">Reservas confirmadas por cancha y horario.</p></div>
    <a href="{{ route('admin.canchas.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-bold text-slate-700 hover:bg-slate-50">Gestionar canchas</a>
</div>
<form method="GET" action="{{ route('admin.reservas.del_dia') }}" class="mt-7 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-[1fr_1fr_auto] sm:items-end">
    <label class="block text-sm font-semibold text-slate-700">Fecha<input type="date" name="fecha" value="{{ $fecha }}" class="mt-2 w-full rounded-xl border-slate-300 focus:border-emerald-600 focus:ring-emerald-600"></label>
    <label class="block text-sm font-semibold text-slate-700">Cancha<select name="cancha_id" class="mt-2 w-full rounded-xl border-slate-300 focus:border-emerald-600 focus:ring-emerald-600"><option value="">Todas las canchas</option>@foreach ($canchas as $cancha)<option value="{{ $cancha->id }}" @selected((string) $canchaId === (string) $cancha->id)>{{ $cancha->nombre }} · {{ $cancha->deporte->nombre }}</option>@endforeach</select></label>
    <button class="rounded-xl bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800">Consultar</button>
</form>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    @if ($reservas->isEmpty())<p class="px-6 py-12 text-center text-slate-500">No hay reservas confirmadas para esta fecha.</p>
    @else<div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-4">Hora</th><th class="px-5 py-4">Cancha</th><th class="px-5 py-4">Cliente</th><th class="px-5 py-4">Duración</th><th class="px-5 py-4">Monto</th><th class="px-5 py-4">Acción</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach ($reservas as $reserva)<tr><td class="whitespace-nowrap px-5 py-4 font-bold">{{ substr($reserva->hora_inicio, 0, 5) }}–{{ substr($reserva->hora_fin, 0, 5) }}</td><td class="px-5 py-4">{{ $reserva->cancha->nombre }}<span class="block text-xs text-slate-500">{{ $reserva->cancha->deporte->nombre }}</span></td><td class="px-5 py-4">{{ $reserva->cliente->nombre }}</td><td class="px-5 py-4">{{ $reserva->duracion_horas }} h</td><td class="px-5 py-4 font-semibold">Bs {{ number_format((float) $reserva->monto, 2) }}</td><td class="px-5 py-4"><form method="POST" action="{{ route('admin.reservas.cancelar', $reserva) }}" onsubmit="return confirm('¿Cancelar esta reserva?')">@csrf<button class="rounded-lg border border-rose-200 px-3 py-2 font-semibold text-rose-700 hover:bg-rose-50">Cancelar</button></form></td></tr>@endforeach</tbody></table></div>@endif
</div>
@endsection
