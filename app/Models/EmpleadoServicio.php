<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoServicio extends Model
{
    protected $table = 'empleado_servicio';

    protected $fillable = [
        'empleado_id',
        'servicio_id'
    ];

    public function empleado()
    {
        return $this->belongsTo(
            Empleado::class,
            'empleado_id'
        );
    }

    public function servicio()
    {
        return $this->belongsTo(
            Servicio::class,
            'servicio_id'
        );
    }
}