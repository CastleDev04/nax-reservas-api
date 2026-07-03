<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Empleado;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function resumen()
    {
        $hoy = Carbon::today();

        return response()->json([
            'citas_hoy' => Cita::whereDate('fecha', $hoy)->count(),
            'ingresos_hoy' => Cita::whereDate('fecha', $hoy)
                ->where('estado', 'finalizada')
                ->sum('total') ?? 0,
            'empleados_activos' => Empleado::where('activo', true)->count(),
            'servicios_activos' => Servicio::where('activo', true)->count()
        ]);
    }

    public function ingresos()
    {
        return Cita::where('estado', 'finalizada')
            ->selectRaw('DATE(fecha) as dia, SUM(total) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();
    }

    public function serviciosTop()
    {
        return Servicio::withCount(['detalles as veces_usado'])
            ->orderByDesc('veces_usado')
            ->limit(5)
            ->get();
    }
}