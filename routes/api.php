<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
    use App\Http\Controllers\Api\PagoCitaController;

use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\NegocioController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\EmpleadoServicioController;
use App\Http\Controllers\Api\HorarioEmpleadoController;
use App\Http\Controllers\Api\BloqueoEmpleadoController;
use App\Http\Controllers\Api\DisponibilidadController;
use App\Http\Controllers\Api\AgendaController;
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| 🔐 SAAS MIDDLEWARE (MULTI-TENANT)
|--------------------------------------------------------------------------
| TODO el sistema funciona por dominio
|--------------------------------------------------------------------------
*/
Route::post('/login',[AuthController::class,'login']);


/*
    |--------------------------------------------------------------------------
    | EMPLEADOS - SERVICIOS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | CITAS
    |--------------------------------------------------------------------------
    */
    Route::apiResource('citas', CitaController::class);

    /*
    |--------------------------------------------------------------------------
    | SERVICIOS
    |--------------------------------------------------------------------------
    */
    Route::apiResource('servicios', ServicioController::class);

    /*
    |--------------------------------------------------------------------------
    | EMPLEADOS
    |--------------------------------------------------------------------------
    */
    Route::apiResource('empleados', EmpleadoController::class);

    /*
    |--------------------------------------------------------------------------
    | EMPLEADOS
    |--------------------------------------------------------------------------
    */

    Route::get('empleados/{empleado}/horarios', [HorarioEmpleadoController::class, 'index']);
    Route::post('empleados/{empleado}/horarios', [HorarioEmpleadoController::class, 'store']);
    Route::put('empleados/horarios/{id}', [HorarioEmpleadoController::class, 'update']);
    Route::delete('empleados/horarios/{id}', [HorarioEmpleadoController::class, 'destroy']);

    Route::get('empleados/{empleado}/bloqueos', [BloqueoEmpleadoController::class, 'index']);
    Route::post('empleados/{empleado}/bloqueos', [BloqueoEmpleadoController::class, 'store']);
    Route::delete('empleados/{empleado}/bloqueos/{id}', [BloqueoEmpleadoController::class, 'destroy']);


    Route::get('/empleados/{empleado}/servicios', [EmpleadoServicioController::class, 'index']);
    Route::post('/empleados/{empleado}/servicios', [EmpleadoServicioController::class, 'sync']);
    /*
    |--------------------------------------------------------------------------
    | DISPONIBILIDAD (CORE DEL SISTEMA)
    |--------------------------------------------------------------------------
    */
    Route::post('disponibilidad/horarios', [
        DisponibilidadController::class,
        'horariosDisponibles'
    ]);

    Route::post('disponibilidad/validar', [
        DisponibilidadController::class,
        'validarDisponibilidad'
    ]);

Route::apiResource('negocios', NegocioController::class);
Route::middleware('auth:sanctum')->group(function(){

    Route::post('/logout',[AuthController::class,'logout']);
    Route::apiResource('usuarios', UsuarioController::class);

    Route::get('/me',[AuthController::class,'me']);
//Route::middleware(['negocio'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | NEGOCIO ACTUAL
    |--------------------------------------------------------------------------
    */
    Route::get('/negocio/actual', [NegocioController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | USUARIOS (ADMIN GLOBAL O FUTURO MULTI-ROL)
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | NEGOCIOS (solo admin global)
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */
    Route::get('clientes', [ClienteController::class, 'index']);
    Route::get('clientes/{id}', [ClienteController::class, 'show']);
    Route::get('clientes/{id}/citas', [ClienteController::class, 'citas']);
    Route::get('clientes/{id}/estadisticas', [ClienteController::class, 'estadisticas']);

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('dashboard/resumen', [DashboardController::class, 'resumen']);
    Route::get('dashboard/ingresos', [DashboardController::class, 'ingresos']);
    Route::get('dashboard/servicios-top', [DashboardController::class, 'serviciosTop']);

    

    /*
    |--------------------------------------------------------------------------
    | AGENDA (CALENDARIO TIPO GOOGLE)
    |--------------------------------------------------------------------------
    */
    Route::get('agenda/dia', [AgendaController::class, 'dia']);
    Route::get('agenda/semana', [AgendaController::class, 'semana']);
    Route::get('agenda/empleado/{id}', [AgendaController::class, 'empleado']);
    Route::get('agenda/proximas', [AgendaController::class, 'proximas']);
    Route::get('agenda/resumen-dia', [AgendaController::class, 'resumenDia']);



Route::get('/citas/{id}/pagos', [PagoCitaController::class, 'index']);
Route::post('/citas/{id}/pagos', [PagoCitaController::class, 'store']);
Route::put('/pagos/{id}', [PagoCitaController::class, 'update']);
Route::delete('/pagos/{id}', [PagoCitaController::class, 'destroy']);
//});

});