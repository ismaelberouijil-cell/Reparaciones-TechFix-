<?php
declare(strict_types=1);

// Zona horaria del servidor
date_default_timezone_set('Europe/Madrid');

// Mostrar errores durante las pruebas
ini_set('display_errors', '1');
error_reporting(E_ALL);

/**
 * Calcula el precio total con un 21% de IVA.
 */
function calcularPresupuesto(float $manoObra, float $recambios): float
{
    $subtotal = $manoObra + $recambios;
    return $subtotal * 1.21;
}
