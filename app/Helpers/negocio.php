<?php

if (!function_exists('negocioActual')) {
    function negocioActual()
{
    return app()->has('negocio_actual')
        ? app('negocio_actual')
        : null;
}
}