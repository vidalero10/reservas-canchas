@extends('layouts.app')
@section('title', ($cancha->exists ? 'Editar Cancha: ' . $cancha->nombre : 'Crear Nueva Cancha') . ' · Administración')

@section('content')
<div class="mx-auto max-w-3xl py-4 sm:py-8">
    
    <!-- Top Breadcrumbs -->
    <nav class="mb-4 flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('admin.canchas.index') }}" class="text-emerald-700 hover:underline">Gestión de Canchas</a>
        <span>/</span>
        <span class="text-slate-800">{{ $cancha->exists ? 'Editar ' . $cancha->nombre : 'Nueva Cancha' }}</span>
    </nav>

    <!-- Header Title -->
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-emerald-800">
                <span>⚙️</span>
                <span>Panel Administrativo</span>
            </div>
            <h1 class="mt-3 font-display text-3xl font-black text-slate-900">
                {{ $cancha->exists ? 'Editar Cancha' : 'Nueva Cancha Deportiva' }}
            </h1>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Configura los datos técnicos, tarifas por hora e imágenes que se mostrarán en la plataforma pública.
            </p>
        </div>
    </div>

    <!-- Main Form Card -->
    <form method="POST" 
          enctype="multipart/form-data" 
          action="{{ $cancha->exists ? route('admin.canchas.update', $cancha) : route('admin.canchas.store') }}" 
          class="mt-8 space-y-6 rounded-[2.5rem] border border-slate-200/80 bg-white p-6 sm:p-10 shadow-lg shadow-slate-900/5">
        @csrf
        @if ($cancha->exists)
            @method('PUT')
        @endif

        <!-- Deporte -->
        <div>
            <label for="deporte_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                Disciplina Deportiva <span class="text-rose-600">*</span>
            </label>
            <select id="deporte_id" 
                    name="deporte_id" 
                    required 
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                <option value="">Selecciona un deporte</option>
                @foreach ($deportes as $deporte)
                    <option value="{{ $deporte->id }}" @selected((string) old('deporte_id', $cancha->deporte_id) === (string) $deporte->id)>
                        {{ $deporte->nombre }}
                    </option>
                @endforeach
            </select>
            @error('deporte_id')
                <span class="mt-1.5 block text-xs font-bold text-rose-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nombre -->
        <div>
            <label for="nombre" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                Nombre de la Cancha <span class="text-rose-600">*</span>
            </label>
            <input id="nombre" 
                   name="nombre" 
                   type="text" 
                   value="{{ old('nombre', $cancha->nombre) }}" 
                   required 
                   maxlength="255" 
                   placeholder="Ej. Cancha 1, Cancha Principal..." 
                   class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            @error('nombre')
                <span class="mt-1.5 block text-xs font-bold text-rose-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Descripción -->
        <div>
            <label for="descripcion" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                Descripción & Características Técnicas
            </label>
            <textarea id="descripcion" 
                      name="descripcion" 
                      rows="4" 
                      maxlength="2000" 
                      placeholder="Describe la superficie (césped 50mm, parquet amortiguado), iluminación LED, equipamiento, dimensiones o servicios disponibles." 
                      class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">{{ old('descripcion', $cancha->descripcion) }}</textarea>
            @error('descripcion')
                <span class="mt-1.5 block text-xs font-bold text-rose-600">{{ $message }}</span>
            @enderror
        </div>

        <!-- Foto de la cancha -->
        <div class="border-t border-slate-100 pt-6">
            <label for="imagen" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1">
                Fotografía de la Instalación
            </label>
            <p class="text-xs text-slate-400 mb-3">Formatos admitidos: JPG, PNG o WebP (hasta 5 MB). Si se deja vacío, se utilizará la fotografía oficial por defecto del deporte.</p>
            
            <input id="imagen" 
                   type="file" 
                   name="imagen" 
                   accept="image/jpeg,image/png,image/webp" 
                   class="block w-full rounded-2xl border border-slate-300 bg-slate-50 p-3 text-xs text-slate-700 file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-xs file:font-black file:text-white hover:file:bg-emerald-700">
            
            @error('imagen')
                <span class="mt-1.5 block text-xs font-bold text-rose-600">{{ $message }}</span>
            @enderror

            @if ($cancha->exists)
                <div class="mt-4 flex items-center gap-4 rounded-2xl bg-slate-50 p-3 border border-slate-200">
                    <img src="{{ $cancha->imagen_url }}" 
                         alt="Foto actual de {{ $cancha->nombre }}" 
                         class="h-16 w-24 rounded-xl object-cover shadow-sm">
                    <div class="text-xs">
                        <strong class="font-bold text-slate-800 block">Fotografía actual en uso:</strong>
                        <span class="text-slate-500">{{ $cancha->imagen ? 'Archivo personalizado subido' : 'Fotografía reglamentaria del deporte' }}</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tarifas Diurna y Nocturna -->
        <div class="border-t border-slate-100 pt-6">
            <span class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-3">
                Tarifas por Hora (Montos en Bolivianos - Bs)
            </span>
            <div class="grid gap-5 sm:grid-cols-2">
                <!-- Tarifa Diurna -->
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-200">
                    <label for="tarifa_diurna" class="block text-xs font-bold text-slate-700 mb-1">
                        ☀️ Tarifa Diurna (< 18:00) <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs font-black text-slate-400">
                            Bs
                        </span>
                        <input id="tarifa_diurna" 
                               name="tarifa_diurna" 
                               type="number" 
                               value="{{ old('tarifa_diurna', $cancha->tarifa_diurna) }}" 
                               required 
                               min="0" 
                               step="0.01" 
                               placeholder="Ej. 80.00" 
                               class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm font-black text-slate-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    @error('tarifa_diurna')
                        <span class="mt-1 block text-xs font-bold text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tarifa Nocturna -->
                <div class="rounded-2xl bg-emerald-50/70 p-4 border border-emerald-200">
                    <label for="tarifa_nocturna" class="block text-xs font-bold text-emerald-900 mb-1">
                        🌙 Tarifa Nocturna (≥ 18:00 con luz) <span class="text-rose-600">*</span>
                    </label>
                    <div class="relative mt-2">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs font-black text-emerald-700">
                            Bs
                        </span>
                        <input id="tarifa_nocturna" 
                               name="tarifa_nocturna" 
                               type="number" 
                               value="{{ old('tarifa_nocturna', $cancha->tarifa_nocturna) }}" 
                               required 
                               min="0" 
                               step="0.01" 
                               placeholder="Ej. 120.00" 
                               class="w-full rounded-xl border border-emerald-300 bg-white py-2.5 pl-10 pr-3 text-sm font-black text-emerald-900 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>
                    @error('tarifa_nocturna')
                        <span class="mt-1 block text-xs font-bold text-rose-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Estado Activa -->
        <div class="border-t border-slate-100 pt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" 
                       name="activa" 
                       value="1" 
                       @checked(old('activa', $cancha->exists ? $cancha->activa : true)) 
                       class="h-5 w-5 rounded-lg border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <div>
                    <strong class="text-xs font-bold text-slate-800 block">Cancha activa para reservas del público</strong>
                    <span class="text-[11px] text-slate-400">Si desmarcas esta opción, la cancha no aparecerá disponible en el catálogo de clientes.</span>
                </div>
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 border-t border-slate-100 pt-6">
            <a href="{{ route('admin.canchas.index') }}" 
               class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-center text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                Cancelar y Volver
            </a>
            <button type="submit" 
                    class="rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-7 py-3 text-center text-xs font-black text-white shadow-md shadow-emerald-900/20 hover:from-emerald-400 hover:to-emerald-500 transition">
                {{ $cancha->exists ? 'Guardar Cambios' : 'Registrar Cancha' }}
            </button>
        </div>
    </form>
</div>
@endsection