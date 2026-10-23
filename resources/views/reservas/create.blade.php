@extends('layouts.app')
@section('title', 'Nueva Reserva de Cancha')

@section('content')
<div class="mx-auto max-w-4xl">
    
    <!-- Top Progress & Section Title -->
    <div class="text-center sm:text-left">
        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-100/60 px-3.5 py-1.5 text-xs font-black uppercase tracking-[0.18em] text-emerald-800">
            <span class="h-2 w-2 rounded-full bg-emerald-600 animate-ping"></span>
            <span>Proceso de Reserva en 2 Pasos</span>
        </div>
        <h1 class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">
            Reserva tu horario deportivo
        </h1>
        <p class="mt-2 text-sm text-slate-500">
            Primero indica qué deporte, fecha y horario deseas. El sistema buscará las canchas que realmente están libres para ti.
        </p>
    </div>

    @if (!$cliente)
        <div role="alert" class="mt-8 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-amber-900 flex items-start gap-4 shadow-sm">
            <span class="text-2xl">⚠️</span>
            <div>
                <strong class="font-bold text-base block">Ficha de cliente requerida</strong>
                <p class="text-xs sm:text-sm mt-1 leading-relaxed">
                    Tu cuenta aún no tiene una ficha de cliente asociada para registrar reservas. Por favor contacta al administrador del complejo para regularizar tus datos.
                </p>
            </div>
        </div>
    @else
        <!-- Client Identification Capsule -->
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/70 p-4 shadow-sm backdrop-blur">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-600 text-white font-black text-sm shadow-sm">
                    {{ mb_substr($cliente->nombre, 0, 1) }}
                </span>
                <div>
                    <span class="text-xs text-slate-500 block">Reserva a nombre de:</span>
                    <strong class="text-sm font-black text-slate-900">{{ $cliente->nombre }}</strong>
                </div>
            </div>
            <div class="text-xs font-bold text-emerald-800 bg-white/80 px-3 py-1.5 rounded-xl border border-emerald-100 w-fit">
                CI: {{ $cliente->ci }} · {{ $cliente->telefono ? 'Tel: ' . $cliente->telefono : 'Sin teléfono' }}
            </div>
        </div>

        <!-- Master Booking Form -->
        <form id="form-reserva" 
              method="POST" 
              action="{{ route('reservas.store') }}" 
              data-url="{{ route('reservas.canchas_disponibles') }}" 
              class="mt-6 space-y-8 rounded-[2.5rem] border border-slate-200/80 bg-white p-6 sm:p-10 shadow-lg shadow-slate-900/5">
            @csrf

            <!-- STEP 1: PARAMETERS -->
            <section class="space-y-6">
                <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-pitch-900 text-white font-black text-sm shadow-md shadow-pitch-950/20">
                        1
                    </span>
                    <div>
                        <h2 class="font-display text-xl font-black text-slate-900">Configura tu partido</h2>
                        <p class="text-xs text-slate-500">Selecciona deporte, fecha y rango de horas deseado.</p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <!-- Deporte -->
                    <div>
                        <label for="deporte_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                            Deporte <span class="text-rose-600">*</span>
                        </label>
                        <select id="deporte_id" 
                                name="deporte_id" 
                                required 
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            <option value="">Selecciona una disciplina</option>
                            @foreach ($deportes as $deporte)
                                <option value="{{ $deporte->id }}" @selected((string) old('deporte_id', $deporteInicial) === (string) $deporte->id)>
                                    {{ $deporte->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label for="fecha" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                            Fecha de Juego <span class="text-rose-600">*</span>
                        </label>
                        <input id="fecha" 
                               type="date" 
                               name="fecha" 
                               min="{{ now()->toDateString() }}" 
                               value="{{ old('fecha', request('fecha', now()->toDateString())) }}" 
                               required 
                               class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <!-- Hora de Inicio -->
                    <div>
                        <label for="hora_inicio" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                            Hora de Inicio (08:00 a 22:00) <span class="text-rose-600">*</span>
                        </label>
                        <input id="hora_inicio" 
                               type="time" 
                               name="hora_inicio" 
                               min="08:00" 
                               max="22:00"
                               step="1800"
                               value="{{ old('hora_inicio', '18:00') }}" 
                               required 
                               class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <!-- Duración -->
                    <div>
                        <label for="duracion_horas" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-2">
                            Duración en Horas <span class="text-rose-600">*</span>
                        </label>
                        <select id="duracion_horas" 
                                name="duracion_horas" 
                                required 
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-bold text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                            <option value="">Selecciona duración</option>
                            <option value="1" @selected(old('duracion_horas', 1) == 1)>1 hora de partido</option>
                            <option value="2" @selected(old('duracion_horas') == 2)>2 horas de partido</option>
                            <option value="3" @selected(old('duracion_horas') == 3)>3 horas de partido</option>
                            <option value="4" @selected(old('duracion_horas') == 4)>4 horas (máximo permitido)</option>
                        </select>
                    </div>
                </div>

                <!-- Guidance Info Box -->
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 flex items-start gap-3 text-xs text-slate-500 leading-relaxed">
                    <span class="text-base text-emerald-700">💡</span>
                    <div>
                        <strong>Cálculo inteligente de tarifas:</strong> Las horas antes de las 18:00 se computan con tarifa diurna; las horas a partir de las 18:00 se computan con tarifa nocturna (con reflectores LED). El complejo atiende hasta las 23:00.
                    </div>
                </div>

                <!-- Search Button -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                    <button id="buscar-canchas" 
                            type="button" 
                            disabled 
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-7 py-3.5 text-sm font-extrabold text-white shadow-md shadow-emerald-700/20 enabled:hover:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        <span id="spinner-busqueda" class="hidden animate-spin">⏳</span>
                        <span>Buscar canchas disponibles</span>
                        <span aria-hidden="true">🔍</span>
                    </button>

                    <div id="estado-busqueda" 
                         role="status" 
                         aria-live="polite" 
                         class="text-xs font-bold text-slate-600 bg-slate-100 px-4 py-2.5 rounded-xl border border-slate-200/80">
                        Completa los 4 campos para buscar disponibilidad.
                    </div>
                </div>
            </section>

            <!-- STEP 2: RESULTS (Hidden until searched) -->
            <section id="seccion-canchas" class="hidden border-t border-slate-100 pt-8 space-y-6">
                <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-pitch-900 text-white font-black text-sm shadow-md shadow-pitch-950/20">
                        2
                    </span>
                    <div>
                        <h2 class="font-display text-xl font-black text-slate-900">Elige tu cancha disponible</h2>
                        <p class="text-xs text-slate-500">Selecciona la instalación que prefieras para tu equipo.</p>
                    </div>
                </div>

                <!-- Dynamic Courts Results Grid -->
                <div id="resultados-canchas" class="grid gap-5 md:grid-cols-2"></div>
            </section>

            <!-- STEP 3: SUBMIT / PROCEED -->
            <div class="border-t border-slate-100 pt-6">
                <button id="confirmar-reserva" 
                        type="submit" 
                        disabled 
                        class="w-full flex items-center justify-center gap-3 rounded-2xl bg-slate-300 py-4 text-center font-black text-white text-base shadow-sm disabled:cursor-not-allowed enabled:bg-gradient-to-r enabled:from-emerald-500 enabled:to-emerald-600 enabled:hover:from-emerald-400 enabled:hover:to-emerald-500 enabled:shadow-lg enabled:shadow-emerald-950/20 transition">
                    <span>Continuar y Coordinar vía WhatsApp</span>
                    <span aria-hidden="true">💬 →</span>
                </button>
                <p class="mt-3 text-center text-xs text-slate-400">
                    Al confirmar, tu reserva quedará registrada como pendiente y serás redirigido a WhatsApp para validar tu comprobante.
                </p>
            </div>
        </form>
    @endif
</div>

@if ($cliente)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-reserva');
    const deporte = document.getElementById('deporte_id');
    const fecha = document.getElementById('fecha');
    const hora = document.getElementById('hora_inicio');
    const duracion = document.getElementById('duracion_horas');
    const buscar = document.getElementById('buscar-canchas');
    const confirmar = document.getElementById('confirmar-reserva');
    const mensaje = document.getElementById('estado-busqueda');
    const seccion = document.getElementById('seccion-canchas');
    const resultados = document.getElementById('resultados-canchas');
    const spinner = document.getElementById('spinner-busqueda');
    const campos = [deporte, fecha, hora, duracion];

    function actualizarBusqueda() {
        const todosLlenos = campos.every(c => c && c.value);
        buscar.disabled = !todosLlenos;
        confirmar.disabled = true;
        seccion.classList.add('hidden');
        resultados.replaceChildren();

        if (todosLlenos) {
            mensaje.textContent = 'Parámetros listos. Haz clic en "Buscar canchas disponibles".';
            mensaje.className = 'text-xs font-bold text-emerald-800 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200';
        } else {
            mensaje.textContent = 'Completa los 4 campos para buscar disponibilidad.';
            mensaje.className = 'text-xs font-bold text-slate-600 bg-slate-100 px-4 py-2.5 rounded-xl border border-slate-200/80';
        }
    }

    function crearTarjeta(cancha) {
        const tarjeta = document.createElement('label');
        tarjeta.className = 'group relative flex cursor-pointer gap-4 rounded-3xl border-2 border-slate-200/90 bg-white p-4 transition-all duration-200 hover:border-emerald-400 hover:shadow-md has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 has-[:checked]:ring-4 has-[:checked]:ring-emerald-500/15';

        // Radio button (styled & accessible)
        const radioWrapper = document.createElement('div');
        radioWrapper.className = 'pt-1';
        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'cancha_id';
        radio.value = cancha.id;
        radio.required = true;
        radio.className = 'h-5 w-5 accent-emerald-600 cursor-pointer';
        radio.addEventListener('change', () => {
            confirmar.disabled = false;
        });
        radioWrapper.appendChild(radio);
        tarjeta.appendChild(radioWrapper);

        // Court Image Thumbnail
        const foto = document.createElement('img');
        foto.src = cancha.imagen || '/images/futbol.jpg';
        foto.alt = `Foto de ${cancha.nombre}`;
        foto.className = 'h-24 w-28 shrink-0 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition';
        tarjeta.appendChild(foto);

        // Content
        const cuerpo = document.createElement('div');
        cuerpo.className = 'min-w-0 flex-1 flex flex-col justify-between';

        const topInfo = document.createElement('div');
        const titulo = document.createElement('h3');
        titulo.className = 'font-display text-base font-black text-slate-900 group-hover:text-emerald-800';
        titulo.textContent = cancha.nombre;
        topInfo.appendChild(titulo);

        if (cancha.superficie) {
            const superficie = document.createElement('span');
            superficie.className = 'inline-block text-[11px] font-bold text-emerald-700';
            superficie.textContent = cancha.superficie;
            topInfo.appendChild(superficie);
        }

        if (cancha.descripcion) {
            const desc = document.createElement('p');
            desc.className = 'mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed';
            desc.textContent = cancha.descripcion;
            topInfo.appendChild(desc);
        }
        cuerpo.appendChild(topInfo);

        // Price badge
        const precioBadge = document.createElement('div');
        precioBadge.className = 'mt-3 flex items-center justify-between border-t border-slate-100 pt-2';
        precioBadge.innerHTML = `
            <span class="text-[11px] font-semibold text-slate-500">Monto total estimado:</span>
            <span class="text-sm font-black text-emerald-700">Bs ${Number(cancha.monto_estimado).toFixed(2)}</span>
        `;
        cuerpo.appendChild(precioBadge);

        tarjeta.appendChild(cuerpo);
        return tarjeta;
    }

    campos.forEach(campo => {
        campo?.addEventListener('change', actualizarBusqueda);
    });

    buscar?.addEventListener('click', async () => {
        const consulta = new URLSearchParams({
            deporte_id: deporte.value,
            fecha: fecha.value,
            hora_inicio: hora.value,
            duracion_horas: duracion.value,
        });

        buscar.disabled = true;
        confirmar.disabled = true;
        resultados.replaceChildren();
        seccion.classList.remove('hidden');
        spinner?.classList.remove('hidden');
        mensaje.textContent = 'Consultando disponibilidad en vivo...';
        mensaje.className = 'text-xs font-bold text-emerald-800 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200';

        try {
            const respuesta = await fetch(`${formulario.dataset.url}?${consulta}`, {
                headers: { 'Accept': 'application/json' },
            });
            const datos = await respuesta.json();

            if (!respuesta.ok) {
                throw new Error(datos.message || 'No se pudo consultar ese horario. Recuerda que la atención es de 08:00 a 23:00.');
            }

            if (!datos.canchas || datos.canchas.length === 0) {
                mensaje.textContent = '❌ No encontramos canchas disponibles para esos datos. Intenta con otra hora o fecha.';
                mensaje.className = 'text-xs font-bold text-rose-800 bg-rose-50 px-4 py-2.5 rounded-xl border border-rose-200';
                
                const vacio = document.createElement('div');
                vacio.className = 'col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-xs text-slate-500';
                vacio.textContent = 'Todas las canchas de este deporte ya se encuentran reservadas para el horario seleccionado.';
                resultados.appendChild(vacio);
                return;
            }

            datos.canchas.forEach(cancha => {
                resultados.appendChild(crearTarjeta(cancha));
            });

            const cant = datos.canchas.length;
            mensaje.textContent = `✓ ${cant} ${cant === 1 ? 'cancha libre encontrada' : 'canchas libres encontradas'}. Elige una para continuar.`;
            mensaje.className = 'text-xs font-bold text-emerald-800 bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-200';

        } catch (error) {
            mensaje.textContent = `⚠️ ${error.message || 'Ocurrió un problema al buscar disponibilidad.'}`;
            mensaje.className = 'text-xs font-bold text-rose-800 bg-rose-50 px-4 py-2.5 rounded-xl border border-rose-200';
        } finally {
            spinner?.classList.add('hidden');
            buscar.disabled = !campos.every(c => c && c.value);
        }
    });

    // Run initial check if prefilled
    actualizarBusqueda();
    if (campos.every(c => c && c.value)) {
        buscar.click();
    }
});
</script>
@endif
@endsection
