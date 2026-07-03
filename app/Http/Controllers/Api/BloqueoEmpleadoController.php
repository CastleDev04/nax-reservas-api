<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BloqueoEmpleado;
use Illuminate\Http\Request;

class BloqueoEmpleadoController extends Controller
{
    public function index($empleadoId)
    {
        return BloqueoEmpleado::where('empleado_id', $empleadoId)->get();
    }

    public function store(Request $request, $empleadoId)
    {
        $data = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'motivo' => 'nullable|string'
        ]);

        $bloqueo = BloqueoEmpleado::create([
            'empleado_id' => $empleadoId,
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'motivo' => $data['motivo'] ?? null
        ]);

        return response()->json($bloqueo, 201);
    }

    public function destroy($id)
    {
        BloqueoEmpleado::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Bloqueo eliminado'
        ]);
    }
}