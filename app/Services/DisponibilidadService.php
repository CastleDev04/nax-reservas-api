<?php

namespace App\Services;

use App\Models\Negocio;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Cita;
use App\Models\BloqueoEmpleado;
use Carbon\Carbon;

class DisponibilidadService
{
    private int $intervalo = 15;

    public function obtenerHorariosDisponibles(array $data): array
{
    $negocio = Negocio::findOrFail($data['negocio_id']);
    $fecha = Carbon::parse($data['fecha']);

    $duracionTotal = $this->calcularDuracionServicios($data['servicios']);

    $empleados = $this->obtenerEmpleados($negocio->id, $data['servicios']);

    $apertura = Carbon::parse($fecha->format('Y-m-d') . ' ' . $negocio->hora_apertura);
    $cierre = Carbon::parse($fecha->format('Y-m-d') . ' ' . $negocio->hora_cierre);

    $slots = [];

    $horaActual = $apertura->copy();

    while ($horaActual->copy()->addMinutes($duracionTotal)->lte($cierre)) {

        $inicio = $horaActual->copy();
        $fin = $inicio->copy()->addMinutes($duracionTotal);

        $enabled = false;

        foreach ($empleados as $empleado) {

            $horario = $this->obtenerHorarioEmpleado($empleado, $fecha);

            if (!$horario) {
                continue;
            }

            $inicioHorario = Carbon::parse($fecha->format('Y-m-d') . ' ' . $horario->hora_inicio);
            $finHorario = Carbon::parse($fecha->format('Y-m-d') . ' ' . $horario->hora_fin);

            if ($inicio->lt($inicioHorario) || $fin->gt($finHorario)) {
                continue;
            }

            if ($this->empleadoDisponible($empleado, $fecha, $inicio, $fin)) {
                $enabled = true;
                break;
            }
        }

        $slots[] = [
            'time' => $inicio->format('H:i'),
            'enabled' => $enabled
        ];

        $horaActual->addMinutes($this->intervalo);
    }

    return $slots;
}

    public function validarDisponibilidad(array $data): bool
    {
        $empleado = Empleado::findOrFail($data['empleado_id']);
        $fecha = Carbon::parse($data['fecha']);

        $duracion = $this->calcularDuracionServicios($data['servicios']);

        $inicio = Carbon::parse($fecha->format('Y-m-d') . ' ' . $data['hora_inicio']);
        $fin = $inicio->copy()->addMinutes($duracion);

        return $this->empleadoDisponible($empleado, $fecha, $inicio, $fin);
    }

    private function calcularDuracionServicios(array $servicios): int
    {
        return Servicio::whereIn('id', $servicios)->sum('duracion_minutos');
    }

    private function obtenerEmpleados(int $negocioId, array $servicios)
    {
        return Empleado::with('servicios', 'horarios', 'bloqueos')
            ->where('negocio_id', $negocioId)
            ->where('activo', true)
            ->get()
            ->filter(function ($empleado) use ($servicios) {

                $serviciosEmpleado = $empleado->servicios->pluck('id')->toArray();

                return empty(array_diff($servicios, $serviciosEmpleado));
            });
    }

    private function obtenerHorarioEmpleado($empleado, $fecha)
    {
        return $empleado->horarios
            ->where('dia_semana', $fecha->dayOfWeek)
            ->where('activo', true)
            ->first();
    }

    private function empleadoDisponible($empleado, Carbon $fecha, Carbon $inicio, Carbon $fin): bool
    {
        $bloqueado = BloqueoEmpleado::where('empleado_id', $empleado->id)
            ->where('fecha_inicio', '<', $fin)
            ->where('fecha_fin', '>', $inicio)
            ->exists();

        if ($bloqueado) return false;

        $ocupado = Cita::where('empleado_id', $empleado->id)
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('hora_inicio', '<', $fin->format('H:i:s'))
                  ->where('hora_fin', '>', $inicio->format('H:i:s'));
            })
            ->exists();

        return !$ocupado;
    }
}