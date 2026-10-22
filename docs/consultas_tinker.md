# Consultas para Tinker

Ejecuta cada comando desde la raÃ­z del proyecto. Las consultas de prueba que crean registros usan transacciones y las revierten para conservar los datos de ejemplo.

## 1. Integridad: CI Ãºnico

**Comando:**

```powershell
php artisan tinker --% --execute="$cliente = \App\Models\Cliente::firstOrFail(); try { \App\Models\Cliente::create([\"ci\" => $cliente->ci, \"nombre\" => \"CI duplicado de prueba\"]); } catch (\Illuminate\Database\QueryException $e) { dump($e->errorInfo[0]); }"
```

**Resultado esperado:** muestra `23505`, el SQLSTATE de violaciÃ³n de unicidad en PostgreSQL.

**Integridad que valida:** la base de datos impide registrar dos clientes con el mismo CI.

**Resultado:**


## 2. Integridad: clave forÃ¡nea

**Comando:**

```powershell
php artisan tinker --% --execute="$clienteId = \App\Models\Cliente::value(\"id\"); try { \Illuminate\Support\Facades\DB::table(\"reservas\")->insert([\"cliente_id\" => $clienteId, \"cancha_id\" => PHP_INT_MAX, \"fecha\" => \"2099-01-01\", \"hora_inicio\" => \"10:00:00\", \"duracion_horas\" => 1, \"hora_fin\" => \"11:00:00\", \"monto\" => 90, \"estado\" => \"confirmada\", \"created_at\" => now(), \"updated_at\" => now()]); } catch (\Illuminate\Database\QueryException $e) { dump($e->errorInfo[0]); }"
```

**Resultado esperado:** muestra `23503`, el SQLSTATE de violaciÃ³n de clave forÃ¡nea en PostgreSQL.

**Integridad que valida:** la FK impide reservar una cancha inexistente. `DB::table` omite los eventos y reglas del modelo para probar directamente la restricciÃ³n de la base.

**Resultado:**


## 3. Integridad: no permitir solapes

**Comando:**

```powershell
php artisan tinker --% --execute="$existente = \App\Models\Reserva::confirmadas()->firstOrFail(); try { \App\Models\Reserva::create([\"cliente_id\" => $existente->cliente_id, \"cancha_id\" => $existente->cancha_id, \"fecha\" => $existente->fecha->toDateString(), \"hora_inicio\" => $existente->hora_inicio, \"duracion_horas\" => $existente->duracion_horas]); } catch (\DomainException $e) { dump(get_class($e), $e->getMessage()); }"
```

**Resultado esperado:** muestra `DomainException` y el mensaje de que la cancha ya tiene una reserva confirmada en ese horario.

**Integridad que valida:** la regla del modelo detecta intervalos superpuestos; una restricciÃ³n `unique` no puede representar esta condiciÃ³n.

**Resultado:**


## 4. Regla de negocio: cancelaciÃ³n con 24 horas de anticipaciÃ³n

**Comando:**

```powershell
php artisan tinker --% --execute="$clienteId = \App\Models\Cliente::value(\"id\"); $inicioCercano = now()->copy()->startOfDay(); if (now()->hour < 7) { $inicioCercano->setTime(8, 0); } elseif (now()->hour <= 20) { $inicioCercano = now()->copy()->startOfHour()->addHours(2); } else { $inicioCercano->addDay()->setTime(8, 0); } $canchaCercana = \App\Models\Cancha::query()->whereDoesntHave(\"reservas\", fn ($q) => $q->confirmadas()->whereDate(\"fecha\", $inicioCercano->toDateString())->where(\"hora_inicio\", \"<\", $inicioCercano->copy()->addHour()->format(\"H:i:s\"))->where(\"hora_fin\", \">\", $inicioCercano->format(\"H:i:s\")))->firstOrFail(); \Illuminate\Support\Facades\DB::beginTransaction(); try { $cercana = \App\Models\Reserva::create([\"cliente_id\" => $clienteId, \"cancha_id\" => $canchaCercana->id, \"fecha\" => $inicioCercano->toDateString(), \"hora_inicio\" => $inicioCercano->format(\"H:i:s\"), \"duracion_horas\" => 1]); try { $cercana->cancelar(); } catch (\DomainException $e) { dump(get_class($e), $e->getMessage()); } } finally { \Illuminate\Support\Facades\DB::rollBack(); } $inicioLejano = now()->copy()->addDays(2)->setTime(10, 0); $canchaLejana = \App\Models\Cancha::query()->whereDoesntHave(\"reservas\", fn ($q) => $q->confirmadas()->whereDate(\"fecha\", $inicioLejano->toDateString())->where(\"hora_inicio\", \"<\", \"11:00:00\")->where(\"hora_fin\", \">\", \"10:00:00\"))->firstOrFail(); \Illuminate\Support\Facades\DB::beginTransaction(); try { $lejana = \App\Models\Reserva::create([\"cliente_id\" => $clienteId, \"cancha_id\" => $canchaLejana->id, \"fecha\" => $inicioLejano->toDateString(), \"hora_inicio\" => \"10:00:00\", \"duracion_horas\" => 1]); $lejana->cancelar(); dump($lejana->estado, $lejana->cancelada_en !== null); } finally { \Illuminate\Support\Facades\DB::rollBack(); }"
```

**Resultado esperado:** el primer intento muestra `DomainException`; el segundo muestra `cancelada` y `true`. La transacciÃ³n de cada prueba se revierte.

**Integridad que valida:** solo se permite cancelar una reserva confirmada cuando faltan al menos 24 horas para su inicio.

**Resultado:**


## 5. Relaciones: reservas de una cancha en una fecha

**Comando:**

```powershell
php artisan tinker --% --execute="$primera = \App\Models\Reserva::firstOrFail(); $reservas = \App\Models\Reserva::with([\"cliente\", \"cancha.deporte\"])->delDia($primera->fecha)->deCancha($primera->cancha_id)->get(); dump($reservas);"
```

**Resultado esperado:** una colecciÃ³n de reservas del dÃ­a para la primera cancha, con cliente, cancha y deporte cargados.

**Integridad que valida:** las relaciones `belongsTo` y la carga anticipada permiten recorrer las referencias sin consultas individuales por cada reserva.

**Resultado:**


## 6. Collections: cantidad de reservas por cancha

**Comando:**

```powershell
php artisan tinker --% --execute="$grupos = \App\Models\Reserva::with(\"cancha\")->get()->groupBy(\"cancha_id\")->map(fn ($reservas) => [\"cancha\" => $reservas->first()->cancha->nombre, \"cantidad\" => $reservas->count()]); dump($grupos);"
```

**Resultado esperado:** una colecciÃ³n agrupada por cancha con el nombre y la cantidad de reservas de cada una.

**Integridad que valida:** la colecciÃ³n relaciona cada reserva con su cancha y permite resumir los registros por clave forÃ¡nea.

**Resultado:**


## 7. Collections: clientes con tres o mÃ¡s reservas

**Comando:**

```powershell
php artisan tinker --% --execute="$clientes = \App\Models\Cliente::withCount(\"reservas\")->get()->filter(fn ($cliente) => $cliente->reservas_count >= 3)->map(fn ($cliente) => [\"nombre\" => $cliente->nombre, \"reservas\" => $cliente->reservas_count])->values(); dump($clientes);"
```

**Resultado esperado:** una colecciÃ³n con los clientes que tienen al menos tres reservas y su cantidad.

**Integridad que valida:** `withCount` cuenta las reservas asociadas por cliente y la colecciÃ³n filtra usando ese conteo.

**Resultado:**


## 8. AgregaciÃ³n: ingresos confirmados por deporte

**Comando:**

```powershell
php artisan tinker --% --execute="$ingresos = \Illuminate\Support\Facades\DB::table(\"reservas\")->join(\"canchas\", \"reservas.cancha_id\", \"=\", \"canchas.id\")->join(\"deportes\", \"canchas.deporte_id\", \"=\", \"deportes.id\")->where(\"reservas.estado\", \"confirmada\")->select(\"deportes.id\", \"deportes.nombre\")->selectRaw(\"SUM(reservas.monto) AS ingresos\")->groupBy(\"deportes.id\", \"deportes.nombre\")->get(); dump($ingresos);"
```

**Resultado esperado:** una fila por deporte con la suma de los montos de sus reservas confirmadas.

**Integridad que valida:** las uniones siguen las FK de reserva a cancha y cancha a deporte; las columnas no agregadas seleccionadas aparecen en `groupBy`, como requiere PostgreSQL.

**Resultado:**


## 9. AgregaciÃ³n con HAVING: canchas con mÃ¡s de diez reservas

**Comando:**

```powershell
php artisan tinker --% --execute="$canchas = \App\Models\Cancha::query()->join(\"reservas\", \"canchas.id\", \"=\", \"reservas.cancha_id\")->where(\"reservas.estado\", \"confirmada\")->select(\"canchas.id\", \"canchas.nombre\")->selectRaw(\"COUNT(*) AS cantidad_reservas, AVG(reservas.monto) AS promedio_monto\")->groupBy(\"canchas.id\", \"canchas.nombre\")->havingRaw(\"count(*) > 10\")->get(); dump($canchas);"
```

**Resultado esperado:** canchas con mÃ¡s de diez reservas confirmadas, junto con el conteo y el promedio de monto.

**Integridad que valida:** `HAVING` filtra grupos agregados con una expresiÃ³n compatible con PostgreSQL, sin depender de un alias en esa clÃ¡usula.

**Resultado:**


## 10. Regla de negocio: tarifa diurna y nocturna

**Comando:**

```powershell
php artisan tinker --% --execute="$fecha = now()->copy()->addDays(7)->toDateString(); $cancha = \App\Models\Cancha::query()->whereDoesntHave(\"reservas\", function ($q) use ($fecha) { $q->confirmadas()->whereDate(\"fecha\", $fecha)->where(function ($q) { $q->where(function ($q) { $q->where(\"hora_inicio\", \"<\", \"11:00:00\")->where(\"hora_fin\", \">\", \"10:00:00\"); })->orWhere(function ($q) { $q->where(\"hora_inicio\", \"<\", \"20:00:00\")->where(\"hora_fin\", \">\", \"19:00:00\"); }); }); })->firstOrFail(); $clienteId = \App\Models\Cliente::value(\"id\"); \Illuminate\Support\Facades\DB::beginTransaction(); try { $diurna = \App\Models\Reserva::create([\"cliente_id\" => $clienteId, \"cancha_id\" => $cancha->id, \"fecha\" => $fecha, \"hora_inicio\" => \"10:00:00\", \"duracion_horas\" => 1]); $nocturna = \App\Models\Reserva::create([\"cliente_id\" => $clienteId, \"cancha_id\" => $cancha->id, \"fecha\" => $fecha, \"hora_inicio\" => \"19:00:00\", \"duracion_horas\" => 1]); dump([\"monto_diurno\" => $diurna->monto, \"tarifa_diurna\" => $cancha->tarifa_diurna, \"monto_nocturno\" => $nocturna->monto, \"tarifa_nocturna\" => $cancha->tarifa_nocturna]); } finally { \Illuminate\Support\Facades\DB::rollBack(); }"
```

**Resultado esperado:** el monto diurno coincide con `tarifa_diurna` y el nocturno con `tarifa_nocturna` de la misma cancha. La transacciÃ³n se revierte.

**Integridad que valida:** el evento de creaciÃ³n calcula el monto segÃºn la tarifa vigente al inicio de cada hora reservada.

**Resultado:**


