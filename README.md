# Sistema de Reservas (Laravel)

Resumen breve
-----------------
Aplicación Laravel para gestionar reservas/citas por negocio (multi-tenant por dominio). Incluye modelos para Negocio, Empleado, Servicio, Cita, Horarios y Bloqueos, y una API REST para gestionar los recursos centrales.

Características principales
-----------------
- Multi-tenant por dominio: middleware `negocio` detecta el negocio actual según el host.
- Modelos: Negocio, Empleado, Servicio, Cita, Usuario, HorarioEmpleado, BloqueoEmpleado, DetalleCitaServicio y pivot EmpleadoServicio.
- Endpoints API para usuarios, negocios, clientes, citas, servicios, empleados, disponibilidad y agenda.

Requisitos
-----------------
- PHP ^8.3
- Composer
- Node.js + npm (para assets / Vite)
- SQLite o la BD configurada en `config/database.php` (las pruebas usan sqlite in-memory)

Instalación rápida
-----------------
1. Instalar dependencias PHP:

```bash
composer install
```

2. Instalar dependencias JS y compilar (opcional para desarrollo):

```bash
npm install
npm run dev
```

3. Crear archivo de entorno y clave:

```bash
cp .env.example .env
php artisan key:generate
```

4. Ejecutar migraciones:

```bash
php artisan migrate
```

5. Levantar servidor local:

```bash
php artisan serve
```

Archivos importantes
-----------------
- Helper negocio: [app/Helpers/negocio.php](app/Helpers/negocio.php#L1)
- Middleware multi-tenant: [app/Http/Middleware/DetectarNegocio.php](app/Http/Middleware/DetectarNegocio.php#L1)
- Rutas API: [routes/api.php](routes/api.php#L1)
- Modelos clave: [app/Models/Negocio.php](app/Models/Negocio.php#L1), [app/Models/Empleado.php](app/Models/Empleado.php#L1), [app/Models/Servicio.php](app/Models/Servicio.php#L1), [app/Models/Cita.php](app/Models/Cita.php#L1)

Ejecución de tests
-----------------
El proyecto incluye `phpunit.xml` configurado para usar sqlite in-memory. Ejecuta:

```bash
composer test
```

Checks básicos y errores conocidos
-----------------
- Hice un chequeo rápido de sintaxis PHP (`php -l`) en los archivos del repositorio (excluyendo `vendor`) y no se detectaron errores de sintaxis.
- Mensaje de entorno observado: `PHP Warning: Module "mysqli" is already loaded` — esto es un warning del entorno PHP (duplicidad en php.ini), no un error del código. Si deseas, puedo ayudarte a limpiar tu configuración de PHP.
- Hay un comentario `TODO el sistema funciona por dominio` en [routes/api.php](routes/api.php#L1) — indica que el comportamiento multi-tenant depende de tener registros `negocios.dominio` correctos en la base de datos.

Recomendaciones / siguientes pasos
-----------------
- Revisar que los registros en la tabla `negocios` tengan el campo `dominio` correcto para el middleware DetectarNegocio.
- Añadir seeds de ejemplo para negocios/empleados/servicios si quieres probar localmente sin crear datos manualmente.
- Si planeas desplegar, configura correctamente la base de datos, `APP_URL` y certificados/host virtual para cada dominio.

Contacto y contribución
-----------------
Si quieres que escriba seeds, cree ejemplos de peticiones (Postman/Insomnia) o añada documentación de la API (OpenAPI), dime y lo hago.

Licencia
-----------------
Revisa la licencia en `composer.json` (MIT en dependencias base) y adapta según necesites.

---
Generado: documentación inicial del proyecto.
