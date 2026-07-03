<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function dia(Request $request)
    {
        $fecha = Carbon::parse($request->fecha);

        return Cita::with(['cliente', 'empleado', 'detalles.servicio'])
            ->whereDate('fecha', $fecha)
            ->orderBy('hora_inicio')
            ->get();
    }

    public function semana(Request $request)
    {
        $inicio = Carbon::parse($request->fecha)->startOfWeek();
        $fin = Carbon::parse($request->fecha)->endOfWeek();

        return Cita::with(['cliente', 'empleado'])
            ->whereBetween('fecha', [$inicio, $fin])
            ->orderBy('fecha')
            ->get();
    }

    public function empleado($id, Request $request)
    {
        return Cita::with(['cliente', 'detalles.servicio'])
            ->where('empleado_id', $id)
            ->whereDate('fecha', $request->fecha)
            ->orderBy('hora_inicio')
            ->get();
    }

    public function proximas()
    {
        return Cita::with(['cliente', 'empleado'])
            ->where('fecha', '>=', Carbon::today())
            ->orderBy('fecha')
            ->limit(10)
            ->get();
    }

    public function resumenDia(Request $request)
    {
        $fecha = Carbon::parse($request->fecha);

        return [
            'total_citas' => Cita::whereDate('fecha', $fecha)->count(),
            'confirmadas' => Cita::whereDate('fecha', $fecha)
                ->where('estado', 'confirmada')
                ->count(),
            'pendientes' => Cita::whereDate('fecha', $fecha)
                ->where('estado', 'pendiente')
                ->count(),
            'canceladas' => Cita::whereDate('fecha', $fecha)
                ->where('estado', 'cancelada')
                ->count(),
        ];
    }
}