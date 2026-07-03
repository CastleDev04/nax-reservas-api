<?php

namespace App\Models\Traits;

use App\Models\Scopes\NegocioScope;

trait PerteneceNegocio
{
    public static function bootPerteneceNegocio()
    {
        static::addGlobalScope(new NegocioScope);
    }
}