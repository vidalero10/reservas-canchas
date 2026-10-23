# Conectar JuegaYa a Supabase

La aplicación usa Laravel con el driver `pgsql`. Supabase ofrece PostgreSQL administrado, así que no hace falta instalar un SDK de Supabase para que Laravel consulte y guarde los datos. pgAdmin es solo una interfaz para administrar bases de datos; no es parte de la conexión de la aplicación.

## Configuración

1. En el panel de Supabase, abre tu proyecto y selecciona **Connect**.
2. Copia los datos de conexión PostgreSQL (host, puerto, base de datos y usuario). Si tu red no admite conexiones directas IPv6, usa el pooler de Supabase y sus valores de host, puerto y usuario.
3. En el `.env` local, reemplaza los valores de PostgreSQL por los de Supabase:

   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=host-copiado-de-supabase
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=usuario-copiado-de-supabase
   DB_PASSWORD=tu-contrasena-de-base-de-datos
   DB_SSLMODE=require
   ```

   Con el pooler, usa exactamente el puerto y el usuario que muestra Supabase. No uses la URL pública del sitio ni la clave `anon`/`service_role`; Laravel necesita las credenciales PostgreSQL.

4. Limpia la configuración cacheada y crea las tablas:

   ```sh
   php artisan config:clear
   php artisan migrate --seed
   ```

Las credenciales son privadas: guarda los valores reales solo en `.env` o en las variables secretas del entorno de despliegue. No los subas al repositorio.

## Servicios auxiliares

La configuración actual guarda sesiones, caché y trabajos en la misma base de datos (`SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`). Las migraciones del proyecto crean las tablas necesarias. Si prefieres no guardarlos en Supabase, cambia esos drivers a `file`, `array` y `sync`, respectivamente.
