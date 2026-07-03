<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\DetalleCitaServicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CitaController extends Controller
{
    public function index()
    {
        return response()->json(
            Cita::with([
                'cliente',
                'empleado',
                'detalles.servicio'
            ])->latest()->get()
        );
    }

    public function show($id)
    {
        $cita = Cita::with([
            'cliente',
            'empleado',
            'detalles.servicio'
        ])->findOrFail($id);

        return response()->json($cita);
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'negocio_id' => 'required|exists:negocios,id',
        'empleado_id' => 'required|exists:empleados,id',
        'nombre' => 'required|string|max:255',
        'email' => 'required|email',
        'telefono' => 'nullable|string',
        'fecha' => 'required|date',
        'hora_inicio' => 'required',
        'servicios' => 'required|array|min:1',
        'servicios.*' => 'exists:servicios,id',
        'notas' => 'nullable|string'
    ]);

    DB::beginTransaction();

    try {

        // 1. validar disponibilidad
        $disponible = app(\App\Services\DisponibilidadService::class)
            ->validarDisponibilidad($data);

        if (!$disponible) {
            return response()->json([
                'message' => 'Horario no disponible'
            ], 422);
        }

        // 2. crear cliente
        $cliente = Usuario::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['nombre'],
                'telefono' => $data['telefono'],
                'password' => Hash::make('cliente123'),
                'rol' => 'cliente'
            ]
        );

        // 3. calcular duración (puedes dejarlo o moverlo luego al service)
        $servicios = Servicio::whereIn('id', $data['servicios'])->get();

        $duracionTotal = $servicios->sum('duracion_minutos');

        $horaInicio = Carbon::parse($data['fecha'].' '.$data['hora_inicio']);

        $horaFin = $horaInicio->copy()->addMinutes($duracionTotal);

        // 4. crear cita
        $cita = Cita::create([
            'negocio_id' => $data['negocio_id'],
            'cliente_id' => $cliente->id,
            'empleado_id' => $data['empleado_id'],
            'fecha' => $data['fecha'],
            'hora_inicio' => $horaInicio->format('H:i:s'),
            'hora_fin' => $horaFin->format('H:i:s'),
            'estado' => 'pendiente',
            'notas' => $data['notas'] ?? null
        ]);

        // 5. detalles
        foreach ($servicios as $servicio) {
            DetalleCitaServicio::create([
                'cita_id' => $cita->id,
                'servicio_id' => $servicio->id,
                'empleado_id' => $data['empleado_id'],
                'duracion_minutos' => $servicio->duracion_minutos,
                'precio' => $servicio->precio
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Cita creada correctamente',
            'cita' => $cita
        ], 201);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);

        $cita->update(
            $request->only([
                'estado',
                'notas'
            ])
        );

        return response()->json([
            'message' => 'Cita actualizada'
        ]);
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);

        $cita->update([
            'estado' => 'cancelada'
        ]);

        return response()->json([
            'message' => 'Cita cancelada'
        ]);
    }
}