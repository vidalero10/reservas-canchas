@extends('layouts.app')

@section('title', 'Reservas del d&iacute;a')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Agenda</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Reservas del d&iacute;a</h1>
            <p class="mt-2 text-slate-500">Consulta horarios confirmados o filtra por cancha.</p>
        </div>
        <a href="{{ route('reservas.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-700">+ Nueva reserva</a>
    </div>

    <form method="GET" action="{{ route('reservas.del_dia') }}" class="mt-8 grid gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-[1fr_1fr_auto] sm:items-end">
        <div>
            <label for="fecha" class="mb-2 block text-sm font-bold text-slate-700">Fecha</label>
            <input id="fecha" name="fecha" type="date" value="{{ $fecha }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        </div>
        <div>
            <label for="cancha_id" class="mb-2 block text-sm font-bold text-slate-700">Cancha</label>
            <select id="cancha_id" name="cancha_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                <option value="">Todas las canchas</option>
                @foreach ($canchas as $cancha)
                    <option value="{{ $cancha->id }}" @selected((string) $canchaId === (string) $cancha->id)>{{ $cancha->nombre }} &mdash; {{ $cancha->deporte->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-xl bg-slate-900 px-6 py-3 font-bold text-white transition hover:bg-slate-700">Aplicar filtros</button>
    </form>

    <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <h2 class="font-display text-lg font-extrabold">Agenda para {{ \Carbon\Carbon::parse($fecha)->translatedFormat('d M Y') }}</h2>
            <span class="text-sm text-slate-500">{{ $reservas->count() }} {{ $reservas->count() === 1 ? 'reserva confirmada' : 'reservas confirmadas' }}</span>
        </div>
        @if ($reservas->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-2xl">!</div>
                <p class="mt-4 font-bold text-slate-800">No hay reservas confirmadas con estos filtros.</p>
                <p class="mt-1 text-sm text-slate-500">Puedes elegir otra fecha o registrar una nueva reserva.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-bold">Horario</th>
                            <th class="px-6 py-4 font-bold">Cancha</th>
                            <th class="px-6 py-4 font-bold">Cliente</th>
                            <th class="px-6 py-4 font-bold">Duraci&oacute;n</th>
                            <th class="px-6 py-4 font-bold">Monto</th>
                            <th class="px-6 py-4 text-right font-bold">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($reservas as $reserva)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-6 py-4 font-bold text-slate-900">{{ substr($reserva->hora_inicio, 0, 5) }}-{{ substr($reserva->hora_fin, 0, 5) }}</td>
                                <td class="px-6 py-4"><span class="block font-bold">{{ $reserva->cancha->nombre }}</span><span class="mt-1 block text-xs text-slate-500">{{ $reserva->cancha->deporte->nombre }}</span></td>
                                <td class="px-6 py-4"><span class="block font-medium">{{ $reserva->cliente->nombre }}</span><span class="mt-1 block text-xs text-slate-500">CI {{ $reserva->cliente->ci }}</span></td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-600">{{ $reserva->duracion_horas }} h</td>
                                <td class="whitespace-nowrap px-6 py-4 font-bold text-slate-900">Bs {{ number_format((float) $reserva->monto, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form method="POST" action="{{ route('reservas.cancelar', $reserva) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full border border-rose-200 px-4 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-50" title="Solo se puede cancelar con 24 horas o m&aacute;s de anticipaci&oacute;n
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
    <p class="mt-4 text-sm text-slate-500">Las cancelaciones deben realizarse con al menos 24 horas de anticipaci&oacute;n.</p>
@endsection
