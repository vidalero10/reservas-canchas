@extends('layouts.app')
@section('title', 'Mis reservas')
@section('content')
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div><p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Cuenta de cliente</p><h1 class="mt-2 text-3xl font-extrabold">Mis reservas</h1><p class="mt-2 text-slate-500">Consulta tus horarios y gestiona las cancelaciones.</p></div>
    <a href="{{ route('reservas.create') }}" class="rounded-xl bg-emerald-700 px-5 py-3 text-center font-bold text-white hover:bg-emerald-800">Nueva reserva</a>
</div>
@if (!$cliente)
    <div role="alert" class="mt-7 rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900">Tu cuenta no tiene una ficha de cliente vinculada. Contacta a administración.</div>
@elseif ($reservas->isEmpty())
    <div class="mt-7 rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center"><h2 class="text-xl font-bold">Aún no tienes reservas</h2><p class="mt-2 text-slate-500">Elige una cancha y encuentra un horario disponible.</p><a href="{{ route('reservas.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-700 px-5 py-3 font-bold text-white">Reservar cancha</a></div>
@else
    <section class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-4">Fecha</th><th class="px-5 py-4">Horario</th><th class="px-5 py-4">Cancha</th><th class="px-5 py-4">Estado</th><th class="px-5 py-4">Monto</th><th class="px-5 py-4">Acción</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @foreach ($reservas as $reserva)
                <tr class="hover:bg-slate-50/70">
                    <td class="whitespace-nowrap px-5 py-4 font-semibold">{{ $reserva->fecha->format('d/m/Y') }}</td>
                    <td class="whitespace-nowrap px-5 py-4">{{ substr($reserva->hora_inicio, 0, 5) }}–{{ substr($reserva->hora_fin, 0, 5) }}</td>
                    <td class="px-5 py-4"><span class="font-bold">{{ $reserva->cancha->nombre }}</span><span class="block text-xs text-slate-500">{{ $reserva->cancha->deporte->nombre }}</span></td>
                    <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $reserva->estado === \App\Models\Reserva::ESTADO_CONFIRMADA ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($reserva->estado) }}</span></td>
                    <td class="whitespace-nowrap px-5 py-4 font-bold">Bs {{ number_format((float) $reserva->monto, 2) }}</td>
                    <td class="px-5 py-4">
                        @if ($reserva->estado === \App\Models\Reserva::ESTADO_CONFIRMADA)
                            <form method="POST" action="{{ route('cliente.reservas.cancelar', $reserva) }}" onsubmit="return confirm('¿Cancelar esta reserva?')">@csrf<button class="rounded-lg border border-rose-200 px-3 py-2 font-semibold text-rose-700 hover:bg-rose-50">Cancelar</button></form>
                        @else
                            <span class="text-xs text-slate-400">Sin acciones</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    </section>
    <p class="mt-4 text-sm text-slate-500">Las cancelaciones requieren 24 horas o más de anticipación.</p>
@endif
@endsection
