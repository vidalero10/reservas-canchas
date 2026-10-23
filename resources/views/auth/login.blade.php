@extends('layouts.app')
@section('title', 'Iniciar sesión')
@section('content')
<section class="mx-auto max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
    <p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Bienvenido de vuelta</p>
    <h1 class="mt-2 text-3xl font-extrabold">Inicia sesión</h1>
    <p class="mt-3 text-sm leading-6 text-slate-500">Ingresa a tu cuenta para gestionar tus reservas.</p>
    <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5">
        @csrf
        <label class="block text-sm font-semibold">Correo electrónico
            <input name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold">Contraseña
            <input name="password" type="password" autocomplete="current-password" required class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="flex items-center gap-3 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">Recordarme
        </label>
        <button class="w-full rounded-xl bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800">Iniciar sesión</button>
    </form>
    <p class="mt-6 text-center text-sm text-slate-500">¿No tienes cuenta? <a href="{{ route('register') }}" class="font-bold text-emerald-700">Regístrate</a></p>
</section>
@endsection
