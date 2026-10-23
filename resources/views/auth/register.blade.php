@extends('layouts.app')
@section('title', 'Crear Cuenta de Cliente · JuegaYa')

@section('content')
<div class="mx-auto max-w-xl py-6 sm:py-10">
    <div class="relative overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white p-7 sm:p-10 shadow-xl shadow-slate-900/5">
        <!-- Top Sports Accent Glow -->
        <div class="pointer-events-none absolute -top-24 -right-24 h-48 w-48 rounded-full bg-emerald-500/15 blur-2xl" aria-hidden="true"></div>

        <div class="text-center sm:text-left">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-emerald-800">
                <span>⚽</span>
                <span>Registro de Jugador</span>
            </div>
            <h1 class="mt-4 font-display text-3xl font-black tracking-tight text-slate-900">
                Crea tu cuenta de cliente
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-500">
                Tus datos nos permitirán asociar tus reservas de cancha y coordinar tus comprobantes de pago de forma segura.
            </p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="mt-8 grid gap-5 sm:grid-cols-2">
            @csrf

            <!-- Nombre Completo -->
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Nombre Completo <span class="text-rose-600">*</span>
                </label>
                <input id="name" 
                       name="name" 
                       type="text" 
                       autocomplete="name" 
                       required 
                       maxlength="255" 
                       value="{{ old('name') }}" 
                       placeholder="Ej. Juan Pérez Ramos"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Correo Electrónico <span class="text-rose-600">*</span>
                </label>
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email" 
                       required 
                       value="{{ old('email') }}" 
                       placeholder="tu@correo.com"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Carnet de Identidad -->
            <div>
                <label for="ci" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Carnet de Identidad (CI) <span class="text-rose-600">*</span>
                </label>
                <input id="ci" 
                       name="ci" 
                       type="text" 
                       required 
                       maxlength="15" 
                       value="{{ old('ci') }}" 
                       placeholder="Ej. 1234567 LP"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Teléfono (opcional) -->
            <div class="sm:col-span-2">
                <label for="telefono" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Teléfono Celular / WhatsApp <span class="text-slate-400 font-normal">(Opcional, 8 dígitos)</span>
                </label>
                <input id="telefono" 
                       name="telefono" 
                       type="tel" 
                       inputmode="numeric" 
                       pattern="[67][0-9]{7}" 
                       maxlength="8" 
                       value="{{ old('telefono') }}" 
                       placeholder="Ej. 71234567"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Contraseña <span class="text-rose-600">*</span>
                </label>
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="new-password" 
                       required 
                       minlength="8" 
                       placeholder="Mínimo 8 caracteres"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Confirmar Contraseña -->
            <div>
                <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Confirmar Contraseña <span class="text-rose-600">*</span>
                </label>
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       autocomplete="new-password" 
                       required 
                       placeholder="Repite la contraseña"
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
            </div>

            <!-- Submit Button -->
            <div class="sm:col-span-2 pt-2">
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700 py-4 text-center font-black text-white text-sm shadow-md shadow-emerald-900/20 hover:from-emerald-400 hover:to-emerald-600 hover:shadow-lg transition">
                    <span>Crear mi cuenta de cliente</span>
                    <span aria-hidden="true">→</span>
                </button>
            </div>
        </form>

        <div class="mt-8 border-t border-slate-100 pt-6 text-center text-xs text-slate-500">
            ¿Ya tienes una cuenta registrada? 
            <a href="{{ route('login') }}" class="font-black text-emerald-700 hover:text-emerald-800 hover:underline ml-1">
                Inicia sesión aquí
            </a>
        </div>
    </div>
</div>
@endsection
