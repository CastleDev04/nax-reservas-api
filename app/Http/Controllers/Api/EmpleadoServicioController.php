<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoServicioController extends Controller
{
    public function index($empleadoId)
    {
        $empleado = Empleado::with('servicios')->findOrFail($empleadoId);

        return response()->json($empleado->servicios);
    }

    public function sync(Request $request, $empleadoId)
    {
        $data = $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id'
        ]);

        $empleado = Empleado::findOrFail($empleadoId);

        $empleado->servicios()->sync($data['servicios']);

        return response()->json([
            'message' => 'Servicios asignados correctamente'
        ]);
    }
}