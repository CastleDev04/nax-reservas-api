<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HorarioEmpleado;
use Illuminate\Http\Request;

class HorarioEmpleadoController extends Controller
{
    public function index($empleadoId)
    {
        return HorarioEmpleado::where('empleado_id', $empleadoId)->get();
    }

    public function store(Request $request, $empleadoId)
    {
        $data = $request->validate([
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required',
            'hora_fin' => 'required',
            'activo' => 'boolean'
        ]);

        $horario = HorarioEmpleado::create([
            'empleado_id' => $empleadoId,
            'dia_semana' => $data['dia_semana'],
            'hora_inicio' => $data['hora_inicio'],
            'hora_fin' => $data['hora_fin'],
            'activo' => $data['activo'] ?? true
        ]);

        return response()->json($horario, 201);
    }

    public function update(Request $request, $id)
    {
        $horario = HorarioEmpleado::findOrFail($id);

        $horario->update(
            $request->only([
                'dia_semana',
                'hora_inicio',
                'hora_fin',
                'activo'
            ])
        );

        return response()->json([
            'message' => 'Horario actualizado'
        ]);
    }

    public function destroy($id)
    {
        HorarioEmpleado::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Horario eliminado'
        ]);
    }
}