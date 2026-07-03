<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicios';

    protected $fillable = [
        'negocio_id',
        'nombre',
        'descripcion',
        'precio',
        'duracion_minutos',
        'activo'
    ];

    public function empleados()
{
    return $this->belongsToMany(
        Empleado::class,
        'empleado_servicio',
        'servicio_id',
        'empleado_id'
    );
}

    public function negocio()
    {
        return $this->belongsTo(Negocio::class);
    }
}