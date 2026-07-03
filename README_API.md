# API Backend - Sistema de Reservas

## Base URL

- `http://localhost:8000/api`

> En el servidor local de Laravel, los endpoints de la API están en `routes/api.php`.

---

## Encabezados sugeridos

- `Accept: application/json`
- `Content-Type: application/json`

> No se ha detectado un middleware de autenticación en `routes/api.php`, así que los endpoints parecen estar abiertos por defecto.

---

## Endpoints disponibles

### 1) Negocios

- `GET /api/negocios`
  - Lista todos los negocios.

- `GET /api/negocios/{id}`
  - Devuelve un negocio por su ID.

- `POST /api/negocios`
  - Crea un negocio.
  - Body: acepta los campos presentes en el modelo `Negocio`.

- `PUT /api/negocios/{id}` o `PATCH /api/negocios/{id}`
  - Actualiza un negocio.
  - Body: puede enviar cualquier campo que exista en la tabla `negocios`.

- `DELETE /api/negocios/{id}`
  - Elimina un negocio.

> Nota: `GET /api/negocio/actual` aparece en `routes/api.php`, pero el controlador registrado (`NegocioController::show`) requiere un parámetro `{id}`. En su forma actual puede no funcionar correctamente.

---

### 2) Usuarios

- `GET /api/usuarios`
  - Lista todos los usuarios.

- `GET /api/usuarios/{id}`
  - Devuelve un usuario por su ID.

- `POST /api/usuarios`
  - Crea un usuario nuevo.
  - Body:
    ```json
    {
      "name": "Nombre Completo",
      "email": "correo@example.com",
      "password": "secreto"
    }
    ```
  - Respuesta: devuelve el usuario creado.

- `PUT /api/usuarios/{id}` o `PATCH /api/usuarios/{id}`
  - Actualiza campos de usuario.
  - Body recomendado:
    ```json
    {
      "name": "Nuevo Nombre",
      "email": "nuevo@example.com",
      "telefono": "555123456"
    }
    ```

- `DELETE /api/usuarios/{id}`
  - Elimina el usuario.

---

### 3) Clientes

- `GET /api/clientes`
  - Lista todos los clientes (usuarios con `rol = cliente`).

- `GET /api/clientes/{id}`
  - Devuelve un cliente con sus citas y servicios asociados.

- `GET /api/clientes/{id}/citas`
  - Devuelve las citas del cliente.

- `GET /api/clientes/{id}/estadisticas`
  - Devuelve estadísticas del cliente:
    - `total_citas`
    - `completadas`
    - `canceladas`

Ejemplo de respuesta de estadísticas:

```json
{
  "total_citas": 10,
  "completadas": 7,
  "canceladas": 2
}
```

---

### 4) Servicios

- `GET /api/servicios?negocio_id={id}`
  - Lista servicios activos para el negocio.

- `GET /api/servicios/{id}`
  - Devuelve un servicio por ID.

- `POST /api/servicios`
  - Crea un servicio.
  - Body:
    ```json
    {
      "negocio_id": 1,
      "nombre": "Corte de cabello",
      "descripcion": "Corte y retoque",
      "precio": 120.50,
      "duracion_minutos": 45
    }
    ```
  - Respuesta:
    ```json
    {
      "message": "Servicio creado correctamente",
      "servicio": { /* objeto servicio */ }
    }
    ```

- `PUT /api/servicios/{id}` o `PATCH /api/servicios/{id}`
  - Actualiza datos del servicio.
  - Body recomendado:
    ```json
    {
      "nombre": "corte + lavado",
      "descripcion": "Incluye lavado",
      "precio": 150,
      "duracion_minutos": 60,
      "activo": true
    }
    ```

- `DELETE /api/servicios/{id}`
  - Elimina el servicio.

---

### 5) Empleados

- `GET /api/empleados`
  - Lista empleados con usuario y servicios relacionados.

- `GET /api/empleados/{id}`
  - Devuelve un empleado con `usuario`, `servicios`, `horarios` y `bloqueos`.

- `POST /api/empleados`
  - Crea un empleado.
  - Body:
    ```json
    {
      "negocio_id": 1,
      "usuario_id": null,
      "nombre": "Sara López"
    }
    ```
  - Respuesta: retorna el empleado creado.

- `PUT /api/empleados/{id}` o `PATCH /api/empleados/{id}`
  - Actualiza nombre o estado activo.
  - Body recomendado:
    ```json
    {
      "nombre": "Sara L.",
      "activo": true
    }
    ```

- `DELETE /api/empleados/{id}`
  - Elimina el empleado.

---

### 6) Citas

- `GET /api/citas`
  - Lista todas las citas con sus datos relacionados:
    - `cliente`
    - `empleado`
    - `detalles.servicio`

- `GET /api/citas/{id}`
  - Devuelve una cita específica.

- `POST /api/citas`
  - Crea una cita nueva.
  - Validaciones requeridas:
    - `negocio_id` existe
    - `empleado_id` existe
    - `nombre` requerido
    - `email` válido
    - `fecha` válido
    - `hora_inicio` requerido
    - `servicios` array con IDs existentes
  - Body ejemplo:
    ```json
    {
      "negocio_id": 1,
      "empleado_id": 3,
      "nombre": "Juan Pérez",
      "email": "juan.perez@example.com",
      "telefono": "5551234567",
      "fecha": "2026-06-30",
      "hora_inicio": "10:00",
      "servicios": [1, 2],
      "notas": "Por favor, puntual"
    }
    ```
  - Flujo interno:
    1. Valida disponibilidad con `DisponibilidadService`.
    2. Crea o reutiliza `Usuario` como cliente.
    3. Calcula duración total de servicios.
    4. Crea la cita con `hora_fin` calculada.
    5. Genera `DetalleCitaServicio` por cada servicio.
  - Respuesta exitosa:
    ```json
    {
      "message": "Cita creada correctamente",
      "cita": { /* objeto cita */ }
    }
    ```
  - Si no hay disponibilidad devuelve `422`:
    ```json
    {
      "message": "Horario no disponible"
    }
    ```

- `PUT /api/citas/{id}` o `PATCH /api/citas/{id}`
  - Actualiza campos:
    - `estado`
    - `notas`
  - Body ejemplo:
    ```json
    {
      "estado": "confirmada",
      "notas": "Cliente confirmó"
    }
    ```

- `DELETE /api/citas/{id}`
  - No borra la cita, la marca como `cancelada`.
  - Respuesta:
    ```json
    {
      "message": "Cita cancelada"
    }
    ```

---

### 7) Disponibilidad

- `POST /api/disponibilidad/horarios`
  - Devuelve horarios disponibles para un negocio y servicios.
  - Body:
    ```json
    {
      "negocio_id": 1,
      "fecha": "2026-06-30",
      "servicios": [1, 2]
    }
    ```
  - Respuesta:
    ```json
    ["09:00", "09:15", "09:30", ...]
    ```

- `POST /api/disponibilidad/validar`
  - Valida disponibilidad de un empleado en un horario específico.
  - Body:
    ```json
    {
      "empleado_id": 3,
      "fecha": "2026-06-30",
      "hora_inicio": "10:00",
      "servicios": [1, 2]
    }
    ```
  - Respuesta:
    ```json
    {
      "disponible": true
    }
    ```

> El servicio de disponibilidad usa los modelos `Negocio`, `Empleado`, `Servicio`, `Cita` y `BloqueoEmpleado` para calcular horarios. También chequea horarios del empleado y solapamientos con citas y bloqueos.

---

### 8) Agenda

- `GET /api/agenda/dia?fecha=2026-06-30`
  - Devuelve todas las citas del día.

- `GET /api/agenda/semana?fecha=2026-06-30`
  - Devuelve citas de la semana actual según esa fecha.

- `GET /api/agenda/empleado/{id}?fecha=2026-06-30`
  - Devuelve citas de un empleado en esa fecha.

- `GET /api/agenda/proximas`
  - Devuelve hasta 10 próximas citas desde hoy.

- `GET /api/agenda/resumen-dia?fecha=2026-06-30`
  - Devuelve conteos por estado para el día:
    - `total_citas`
    - `confirmadas`
    - `pendientes`
    - `canceladas`

Ejemplo de respuesta:

```json
{
  "total_citas": 5,
  "confirmadas": 3,
  "pendientes": 1,
  "canceladas": 1
}
```

---

## Datos importantes por modelo

### Usuario

- `name`
- `email`
- `telefono`
- `password`
- `rol`

### Negocio

- `nombre`
- `dominio`
- `activo`
- `direccion`
- `telefono`
- `email`
- `hora_apertura`
- `hora_cierre`

### Servicio

- `negocio_id`
- `nombre`
- `descripcion`
- `precio`
- `duracion_minutos`
- `activo`

### Empleado

- `negocio_id`
- `usuario_id`
- `nombre`
- `activo`

### Cita

- `negocio_id`
- `cliente_id`
- `empleado_id`
- `fecha`
- `hora_inicio`
- `hora_fin`
- `estado`
- `notas`

---

## Validaciones clave

- Los `POST /api/citas` y `POST /api/disponibilidad/*` requieren IDs válidos en `negocio_id`, `empleado_id` y `servicios.*`.
- `servicios.*` debe existir en `servicios.id`.
- `POST /api/citas` valida disponibilidad antes de crear la cita.
- `DELETE /api/citas/{id}` marca la cita como `cancelada`, no la elimina.
- `GET /api/servicios` sólo devuelve servicios activos del negocio.

---

## Notas adicionales

- Los endpoints de `BloqueoEmpleadoController` y `EmpleadoServicioController` existen como controladores, pero no están registrados en `routes/api.php`.
- Si necesitas exponer bloqueos o sincronizar servicios de empleado, debes agregar rutas específicas en `routes/api.php`.
- El negocio actual `GET /api/negocio/actual` está declarado en el archivo, pero la implementación del controlador no coincide con el parámetro esperado.

---

## Pruebas rápidas con Postman

1. `GET /api/negocios`
2. `GET /api/servicios?negocio_id=1`
3. `POST /api/disponibilidad/horarios` con `negocio_id`, `fecha` y `servicios`
4. `POST /api/citas` con el horario obtenido
5. `GET /api/citas/{id}` para revisar la cita
6. `DELETE /api/citas/{id}` para cancelar la cita

---

## Flujo recomendado del backend

1. Obtener negocio y servicios.
2. Consultar disponibilidad (`/api/disponibilidad/horarios`).
3. Validar horario del empleado si quieres un empleado específico (`/api/disponibilidad/validar`).
4. Crear cita con `/api/citas`.
5. Consultar agenda y estadísticas con `/api/agenda/*`.
