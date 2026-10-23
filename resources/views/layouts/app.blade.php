<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#061612">
    <meta name="description" content="JuegaYa: Reserva y alquiler de canchas reglamentarias de Fútbol 5, Básquetbol y Voleibol en Bolivia.">
    <title>@yield('title', 'Reservas de Canchas Deportivas') | JuegaYa</title>

    <!-- Tailwind CSS (CDN con soporte extendido) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pitch: {
                            950: '#04100c',
                            900: '#071b15',
                            850: '#0c261e',
                            800: '#113329',
                            700: '#087f5b',
                            600: '#059669',
                            500: '#10b981',
                            400: '#34d399',
                            300: '#6ee7b7',
                        },
                        volt: {
                            400: '#a3e635',
                            500: '#84cc16',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        display: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #059669;
            --brand-dark: #071b15;
            --brand-volt: #84cc16;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #06110e;
            background-image: linear-gradient(145deg, #06110e 0%, #0a1a15 48%, #07130f 100%);
            color: #e2e8f0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-display {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }

        /* Ambient sports lighting mesh */
        .ambient-mesh {
            background-image: 
                radial-gradient(circle at 15% 10%, rgba(16, 185, 129, 0.20) 0%, transparent 40%),
                radial-gradient(circle at 85% 15%, rgba(132, 204, 22, 0.14) 0%, transparent 35%),
                radial-gradient(circle at 50% 90%, rgba(5, 150, 105, 0.14) 0%, transparent 50%);
        }

        /* Card lifts and micro-animations */
        .sport-card {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.28s ease;
        }
        .sport-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 40px -15px rgba(5, 46, 36, 0.16);
            border-color: rgba(16, 185, 129, 0.45);
        }

        /* Interactive inputs */
        input:not([type=checkbox]):not([type=radio]):not([type=file]), select, textarea {
            transition: all 0.2s ease;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.18) !important;
            outline: none !important;
        }

        /* Pulse glow animation for status pills */
        @keyframes subtle-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.85; }
        }
        .animate-subtle-pulse {
            animation: subtle-pulse 2.5s infinite ease-in-out;
        }

        /* Glassmorphism accents */
        .glass-dark {
            background: rgba(7, 27, 21, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-light {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        /* Smooth scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #071b15;
        }
        ::-webkit-scrollbar-thumb {
            background: #17634d;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Accessibility: respect reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Ambient sports backdrop mesh -->
    <div class="pointer-events-none fixed inset-0 -z-10 ambient-mesh" aria-hidden="true"></div>

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-pitch-950 via-pitch-900 to-pitch-950 text-emerald-100/90 text-xs py-2 px-4 border-b border-emerald-900/30">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2 font-medium">
                <span class="inline-flex h-2 w-2 rounded-full bg-volt-400 animate-ping" aria-hidden="true"></span>
                <span>JuegaYa · <strong>Atención continua todos los días</strong> de 08:00 a 23:00</span>
            </div>
            <div class="flex items-center gap-4 text-[11px] font-semibold tracking-wider uppercase text-emerald-300">
                <span class="hidden md:inline">⚽ Fútbol 5 · 🏀 Básquetbol · 🏐 Voleibol</span>
                <a href="{{ route('canchas.disponibilidad') }}" class="hover:text-white transition flex items-center gap-1">
                    <span>Ver disponibilidad en vivo</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 border-b border-emerald-400/15 glass-dark shadow-lg shadow-black/20 transition">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
            
            <!-- Logo & Brand -->
            <a href="{{ route('inicio') }}" class="group flex shrink-0 items-center" aria-label="JuegaYa, ir a inicio">
                <img src="{{ asset('images/reserva-logo-transparente.png') }}" alt="JuegaYa" class="h-16 w-28 object-contain transition-transform group-hover:scale-[1.03] sm:h-[4.5rem] sm:w-32">
            </a>

            <!-- Mobile Hamburger Toggle Button -->
            <button id="menu-movil" 
                    type="button" 
                    class="inline-flex items-center justify-center rounded-xl border border-white/15 p-2.5 text-slate-100 hover:bg-white/10 hover:text-emerald-300 transition md:hidden focus-visible:ring-2 focus-visible:ring-emerald-500" 
                    aria-expanded="false" 
                    aria-controls="navegacion"
                    aria-label="Abrir menú de navegación">
                <svg id="icono-menu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Navigation Links -->
            <nav id="navegacion" class="absolute inset-x-0 top-full hidden flex-col gap-1.5 border-b border-white/10 bg-pitch-950/95 p-5 shadow-2xl backdrop-blur-xl md:static md:flex md:flex-row md:items-center md:gap-1 md:border-0 md:bg-transparent md:p-0 md:shadow-none">
                @php
                    $navLinkBase = 'rounded-xl px-3.5 py-2 text-sm font-bold transition-all duration-150 flex items-center gap-1.5';
                @endphp

                <a href="{{ route('inicio') }}" 
                   class="{{ $navLinkBase }} {{ request()->routeIs('inicio') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-700/20' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                    <span>Inicio</span>
                </a>

                <a href="{{ route('canchas.index') }}" 
                   class="{{ $navLinkBase }} {{ request()->routeIs('canchas.index', 'canchas.show') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-700/20' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                    <span>Canchas</span>
                </a>

                <a href="{{ route('canchas.disponibilidad') }}" 
                   class="{{ $navLinkBase }} {{ request()->routeIs('canchas.disponibilidad') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-700/20' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                    <span class="inline-block h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Horarios</span>
                </a>

                @guest
                    <div class="my-2 border-t border-white/10 md:my-0 md:border-0 md:flex md:items-center md:gap-2 md:pl-2">
                        <a href="{{ route('login') }}" 
                           class="rounded-xl px-4 py-2 text-sm font-bold text-slate-200 hover:bg-white/10 hover:text-white transition block text-center">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" 
                           class="mt-2 md:mt-0 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-center text-sm font-extrabold text-white shadow-md shadow-emerald-900/15 hover:from-emerald-500 hover:to-emerald-600 hover:shadow-lg transition">
                            <span>Crear cuenta</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                @else
                    <!-- Authenticated Navigation Options -->
                    <div class="my-2 border-t border-white/10 pt-2 md:my-0 md:border-t-0 md:pt-0 md:flex md:items-center md:gap-1.5 md:pl-2">
                        @if (auth()->user()->esAdministrador())
                            <a href="{{ route('admin.reservas.del_dia') }}" 
                               class="{{ $navLinkBase }} {{ request()->routeIs('admin.reservas.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                                <span>Agenda Admin</span>
                            </a>
                            <a href="{{ route('admin.canchas.index') }}" 
                               class="{{ $navLinkBase }} {{ request()->routeIs('admin.canchas.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                                <span>Gestión Canchas</span>
                            </a>
                            <a href="{{ route('admin.reportes.ingresos') }}" 
                               class="{{ $navLinkBase }} {{ request()->routeIs('admin.reportes.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                                <span>Ingresos</span>
                            </a>
                        @else
                            <a href="{{ route('cliente.reservas.index') }}" 
                               class="{{ $navLinkBase }} {{ request()->routeIs('cliente.reservas.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-200 hover:bg-white/10 hover:text-emerald-300' }}">
                                <span>Mis Reservas</span>
                            </a>
                            <a href="{{ route('reservas.create') }}" 
                               class="flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-extrabold text-white shadow-md shadow-emerald-800/15 hover:bg-emerald-500 transition">
                                <span>＋ Reservar</span>
                            </a>
                        @endif

                        <!-- User Profile Capsule & Logout -->
                        <div class="mt-3 flex items-center justify-between gap-2.5 rounded-2xl bg-white/5 p-1.5 border border-white/10 md:mt-0 md:ml-2">
                            <div class="flex items-center gap-2 pl-1.5">
                                <span class="grid h-8 w-8 place-items-center rounded-xl bg-emerald-600 text-xs font-black text-white shadow-sm">
                                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                                </span>
                                <div class="flex flex-col">
                                    <span class="max-w-[120px] truncate text-xs font-extrabold text-white leading-tight">
                                        {{ auth()->user()->name }}
                                    </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300">
                                        {{ auth()->user()->esAdministrador() ? 'Admin' : 'Cliente' }}
                                    </span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="rounded-xl border border-white/15 bg-white/5 px-2.5 py-1.5 text-xs font-bold text-slate-200 transition hover:border-rose-400/40 hover:bg-rose-500/15 hover:text-rose-200 focus-visible:ring-2 focus-visible:ring-rose-500" 
                                        title="Cerrar sesión">
                                    Salir
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </nav>
        </div>
    </header>

    <!-- Global Flash Notification Messages -->
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div role="status" class="mt-5 flex items-center gap-3.5 rounded-2xl border border-emerald-300 bg-emerald-50/90 p-4 text-emerald-950 shadow-sm backdrop-blur">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-600 text-white font-black text-sm">✓</span>
                <div class="flex-1 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mt-5 flex flex-col gap-2 rounded-2xl border border-rose-200 bg-rose-50/95 p-4 text-rose-950 shadow-sm backdrop-blur">
                <div class="flex items-center gap-2 font-extrabold text-sm text-rose-800">
                    <span class="grid h-6 w-6 place-items-center rounded-full bg-rose-600 text-white text-xs">!</span>
                    <span>Revisa los siguientes campos:</span>
                </div>
                <ul class="ml-8 list-disc text-sm space-y-1 text-rose-900">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Blade Section -->
    <main class="flex-1 mx-auto w-full max-w-7xl px-4 py-8 text-slate-100 sm:px-6 sm:py-10 lg:px-8">
        @yield('content')
    </main>

    <!-- Professional Sports Complex Footer -->
    <footer class="mt-auto border-t border-pitch-900 bg-pitch-950 text-white">
        <!-- Top Footer Column Grid -->
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-4 sm:grid-cols-2">
                
                <!-- Col 1: Identity & Guarantee -->
                <div class="space-y-4">
                    <a href="{{ route('inicio') }}" class="inline-flex items-center rounded-xl border border-white/10 px-3 py-2">
                        <span class="font-display text-xl font-black tracking-tight text-white">Juega<span class="text-emerald-400">Ya</span></span>
                    </a>
                    <p class="text-sm leading-6 text-slate-300">
                        Infraestructura deportiva de alto nivel con canchas reglamentarias para partidos entre amigos, entrenamientos, torneos y academias.
                    </p>
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-300">
                        <span class="h-2 w-2 rounded-full bg-volt-400"></span>
                        <span>08:00 – 23:00 · Horario de juego continuo</span>
                    </div>
                </div>

                <!-- Col 2: Deportes Disponibles -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400">Deportes Oficiales</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-300">
                        <li class="flex items-center gap-2.5">
                            <span class="text-base">⚽</span>
                            <span><strong>Fútbol 5</strong> · Césped sintético FIFA</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-base">🏀</span>
                            <span><strong>Básquetbol</strong> · Parquet amortiguado</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="text-base">🏐</span>
                            <span><strong>Voleibol</strong> · Piso deportivo profesional</span>
                        </li>
                        <li class="pt-1">
                            <span class="inline-block rounded-lg bg-emerald-900/60 px-3 py-1.5 text-xs font-bold text-emerald-300 border border-emerald-800/60">
                                Iluminación LED Nocturna
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Enlaces Rápidos -->
                <div>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400">Enlaces Rápidos</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-slate-300">
                        <li><a href="{{ route('inicio') }}" class="hover:text-emerald-300 transition">Inicio</a></li>
                        <li><a href="{{ route('canchas.index') }}" class="hover:text-emerald-300 transition">Catálogo de canchas</a></li>
                        <li><a href="{{ route('canchas.disponibilidad') }}" class="hover:text-emerald-300 transition">Horarios y disponibilidad</a></li>
                        @auth
                            @if (!auth()->user()->esAdministrador())
                                <li><a href="{{ route('reservas.create') }}" class="hover:text-emerald-300 transition">Reservar cancha</a></li>
                                <li><a href="{{ route('cliente.reservas.index') }}" class="hover:text-emerald-300 transition">Mis reservas</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-emerald-300 transition">Iniciar sesión</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-300 transition">Crear cuenta de cliente</a></li>
                        @endauth
                    </ul>
                </div>

                <!-- Col 4: Contacto & Reserva WhatsApp -->
                <div class="space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-emerald-400">Contacto & Confirmación</h3>
                    <p class="text-sm text-slate-300 leading-6">
                        Confirmamos y coordinamos tus pagos ágilmente vía WhatsApp en minutos.
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', (string) config('services.whatsapp.reservas', '59170000000')) }}" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-extrabold text-white shadow-lg shadow-emerald-950/40 hover:bg-emerald-500 transition">
                        <span>💬 WhatsApp de Reservas</span>
                    </a>
                    <div class="text-xs text-slate-400 pt-1">
                        Complejo deportivo · Bolivia
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Academic Meta -->
            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-emerald-950/80 pt-8 text-center sm:flex-row sm:text-left text-xs text-slate-400">
                <p>© {{ date('Y') }} JuegaYa. Todos los derechos reservados. Plataforma de reservas deportivas.</p>
                <div class="flex items-center gap-4">
                    <span class="rounded-full bg-emerald-900/50 px-3 py-1 font-semibold text-emerald-300 border border-emerald-800/40">
                        Tecnología Web I · Laravel MVC
                    </span>
                    <span>Pasión · Disciplina · Juego Limpio</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const botonMenu = document.getElementById('menu-movil');
            const navegacion = document.getElementById('navegacion');
            
            botonMenu?.addEventListener('click', () => {
                const abierto = botonMenu.getAttribute('aria-expanded') === 'true';
                botonMenu.setAttribute('aria-expanded', String(!abierto));
                navegacion.classList.toggle('hidden', abierto);
                navegacion.classList.toggle('flex', !abierto);
            });
        });
    </script>
</body>
</html>
