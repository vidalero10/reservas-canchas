@extends('layouts.app')
@section('title', 'Iniciar Sesión · JuegaYa')

@section('content')
<div class="mx-auto max-w-md py-6 sm:py-10">
    <div class="relative overflow-hidden rounded-[2.5rem] border border-slate-200/80 bg-white p-7 sm:p-10 shadow-xl shadow-slate-900/5">
        <!-- Top Sports Accent Glow -->
        <div class="pointer-events-none absolute -top-24 -right-24 h-48 w-48 rounded-full bg-emerald-500/15 blur-2xl" aria-hidden="true"></div>

        <div class="text-center sm:text-left">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-[0.16em] text-emerald-800">
                <span>🔐</span>
                <span>Portal de Jugadores & Administración</span>
            </div>
            <h1 class="mt-4 font-display text-3xl font-black tracking-tight text-slate-900">
                Bienvenido de vuelta
            </h1>
            <p class="mt-2 text-xs sm:text-sm text-slate-500">
                Ingresa con tu correo registrado para gestionar tus turnos y reservas de cancha.
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Correo Electrónico
                </label>
                <div class="relative">
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           autofocus 
                           value="{{ old('email') }}" 
                           placeholder="tu@correo.com"
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                    Contraseña
                </label>
                <div class="relative">
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           placeholder="••••••••"
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                </div>
            </div>

            <!-- Remember me checkbox -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2.5 font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span>Recordar mi sesión</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-600 to-emerald-700 py-3.5 text-center font-black text-white text-sm shadow-md shadow-emerald-900/20 hover:from-emerald-400 hover:to-emerald-600 hover:shadow-lg transition">
                <span>Ingresar a mi cuenta</span>
                <span aria-hidden="true">→</span>
            </button>
        </form>

        <div class="mt-8 border-t border-slate-100 pt-6 text-center text-xs text-slate-500">
            ¿Aún no tienes cuenta en JuegaYa? 
            <a href="{{ route('register') }}" class="font-black text-emerald-700 hover:text-emerald-800 hover:underline ml-1">
                Regístrate gratis aquí
            </a>
        </div>
    </div>
</div>
@endsection
