<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reservas deportivas') | Complejo La Paz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif}h1,h2,h3,.font-display{font-family:'Manrope',sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('inicio') }}" class="flex items-center gap-3" aria-label="Complejo La Paz, inicio">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-emerald-600 text-lg font-extrabold text-white">LP</span>
                <span>
                    <span class="block font-display text-base font-extrabold tracking-tight">Complejo La Paz</span>
                    <span class="block text-xs font-medium text-slate-500">Reservas deportivas</span>
                </span>
            </a>
            <nav class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <a href="{{ route('inicio') }}" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Inicio</a>
                <a href="{{ route('reservas.del_dia') }}" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Reservas del d&iacute;a</a>
                <a href="{{ route('reportes.ingresos') }}" class="rounded-full px-4 py-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">Ingresos</a>
                <a href="{{ route('reservas.create') }}" class="rounded-full bg-emerald-600 px-4 py-2 text-white shadow-sm transition hover:bg-emerald-700">Reservar cancha</a>
            </nav>
        </div>
    </header>

    @if (session('success'))
        <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div role="status" class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-900">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="mx-auto mt-6 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900">
                <p class="font-bold">Revisa los siguientes datos:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p class="font-semibold text-slate-700">Complejo La Paz</p>
            <p>Deporte, encuentro y buen juego.</p>
        </div>
    </footer>
</body>
</html>
