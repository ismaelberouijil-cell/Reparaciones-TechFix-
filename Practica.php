<?php
//Configuración del entorno:
// Activar el tipado estricto
declare(strict_types=1);

// Definir la zona horaria oficial del servidor
date_default_timezone_set('Europe/Madrid');

// Configuración para detectar errores en entorno de pruebas
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Validación de la petición:
ob_start();
$numero = $_GET['cant'] ?? null;
$numero = filter_var($numero, FILTER_VALIDATE_INT);
if ($numero < 1 || $numero === false) {
    http_response_code(400);
    exit("Numero invalido");
}


