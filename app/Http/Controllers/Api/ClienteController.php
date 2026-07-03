<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Cita;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return Usuario::where('rol', 'cliente')
            ->withCount('citas')
            ->get();
    }

    public function show($id)
    {
        return Usuario::where('rol', 'cliente')
            ->with(['citas.detalles.servicio'])
            ->findOrFail($id);
    }

    public function citas($id)
    {
        return Cita::where('cliente_id', $id)
            ->with(['empleado', 'detalles.servicio'])
            ->latest()
            ->get();
    }

    public function estadisticas($id)
    {
        $totalCitas = Cita::where('cliente_id', $id)->count();

        $completadas = Cita::where('cliente_id', $id)
            ->where('estado', 'finalizada')
            ->count();

        $canceladas = Cita::where('cliente_id', $id)
            ->where('estado', 'cancelada')
            ->count();

        return response()->json([
            'total_citas' => $totalCitas,
            'completadas' => $completadas,
            'canceladas' => $canceladas
        ]);
    }
}