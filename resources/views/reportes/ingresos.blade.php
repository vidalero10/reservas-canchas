@extends('layouts.app')

@section('title', 'Reporte de ingresos')

@section('content')
    <div>
        <p class="text-sm font-bold uppercase tracking-[0.16em] text-emerald-700">Administraci&oacute;n</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Reporte de ingresos</h1>
        <p class="mt-2 text-slate-500">Ingresos y reservas confirmadas agrupados por deporte.</p>
    </div>

    <section class="mt-8 grid gap-4 sm:grid-cols-2">
        <article class="rounded-3xl bg-slate-950 p-6 text-white shadow-sm">
            <p class="text-sm font-semibold text-slate-300">Ingresos totales</p>
            <p class="mt-3 text-4xl font-extrabold tracking-tight">Bs {{ number_format($ingresosTotales, 2) }}</p>
            <p class="mt-2 text-sm text-slate-400">Suma de reservas confirmadas</p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Reservas confirmadas</p>
            <p class="mt-3 text-4xl font-extrabold tracking-tight text-slate-950">{{ number_format($reservasConfirmadas) }}</p>
            <p class="mt-2 text-sm text-slate-500">En todos los deportes</p>
        </article>
    </section>

    <section class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <h2 class="font-display text-lg font-extrabold">Detalle por deporte</h2>
        </div>
        @if ($reporte->isEmpty())
            <p class="px-6 py-12 text-center text-slate-500">Todav&iacute;a no hay deportes registrados.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[520px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-bold">Deporte</th>
                            <th class="px-6 py-4 text-right font-bold">Reservas confirmadas</th>
                            <th class="px-6 py-4 text-right font-bold">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($reporte as $fila)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-6 py-5 font-bold text-slate-900">{{ $fila->deporte }}</td>
                                <td class="px-6 py-5 text-right text-slate-600">{{ number_format((int) $fila->cantidad_reservas) }}</td>
                                <td class="px-6 py-5 text-right font-extrabold text-slate-950">Bs {{ number_format((float) $fila->ingresos_totales, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 font-extrabold">Total</th>
                            <th class="px-6 py-4 text-right font-extrabold">{{ number_format($reservasConfirmadas) }}</th>
                            <th class="px-6 py-4 text-right font-extrabold">Bs {{ number_format($ingresosTotales, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </section>
    <div class="mt-6"><a href="{{ route('inicio') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900">&larr; Volver al inicio</a></div>
@endsection
