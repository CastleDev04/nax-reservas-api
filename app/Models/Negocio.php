<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $table = 'negocios';

    protected $fillable = [
        'nombre',
        'dominio',
        'activo',
        'direccion',
        'telefono',
        'email',
        'hora_apertura',
        'hora_cierre'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
