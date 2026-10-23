@extends('layouts.app')
@section('title', 'Crear cuenta')
@section('content')
<section class="mx-auto max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
    <p class="text-sm font-bold uppercase tracking-wider text-emerald-700">Cuenta de cliente</p>
    <h1 class="mt-2 text-3xl font-extrabold">Regístrate para reservar</h1>
    <p class="mt-3 text-sm text-slate-500">Usaremos tus datos para asociar tus reservas personales.</p>
    <form method="POST" action="{{ route('register.store') }}" class="mt-7 grid gap-5 sm:grid-cols-2">
        @csrf
        <label class="block text-sm font-semibold sm:col-span-2">Nombre completo
            <input name="name" type="text" autocomplete="name" required maxlength="255" value="{{ old('name') }}" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold">Correo electrónico
            <input name="email" type="email" autocomplete="email" required value="{{ old('email') }}" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold">Carnet de identidad
            <input name="ci" type="text" required maxlength="15" value="{{ old('ci') }}" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold sm:col-span-2">Teléfono (opcional)
            <input name="telefono" type="tel" inputmode="numeric" pattern="[67][0-9]{7}" maxlength="8" value="{{ old('telefono') }}" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold">Contraseña
            <input name="password" type="password" autocomplete="new-password" required minlength="8" class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <label class="block text-sm font-semibold">Confirmar contraseña
            <input name="password_confirmation" type="password" autocomplete="new-password" required class="mt-2 w-full rounded-xl border-slate-300">
        </label>
        <button class="rounded-xl bg-emerald-700 px-5 py-3 font-bold text-white hover:bg-emerald-800 sm:col-span-2">Crear cuenta de cliente</button>
    </form>
    <p class="mt-6 text-center text-sm text-slate-500">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-bold text-emerald-700">Inicia sesión</a></p>
</section>
@endsection
