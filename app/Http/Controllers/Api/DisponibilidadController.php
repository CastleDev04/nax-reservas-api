<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DisponibilidadService;
use Illuminate\Http\Request;

class DisponibilidadController extends Controller
{
    private DisponibilidadService $service;

    public function __construct(DisponibilidadService $service)
    {
        $this->service = $service;
    }

    public function horariosDisponibles(Request $request)
    {
        $data = $request->validate([
            'negocio_id' => 'required|exists:negocios,id',
            'fecha' => 'required|date',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id'
        ]);

        $horarios = $this->service->obtenerHorariosDisponibles($data);

        return response()->json($horarios);
    }

    public function validarDisponibilidad(Request $request)
    {
        $data = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:servicios,id'
        ]);

        $disponible = $this->service->validarDisponibilidad($data);

        return response()->json([
            'disponible' => $disponible
        ]);
    }
}