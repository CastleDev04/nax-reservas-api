<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCitaServicio extends Model
{
    protected $table = 'detalle_cita_servicios';

    protected $fillable = [
        'cita_id',
        'servicio_id',
        'empleado_id',
        'duracion_minutos',
        'precio'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}