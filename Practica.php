<?php

// Activar el tipado estricto
declare(strict_types=1);

// Definir la zona horaria oficial del servidor
date_default_timezone_set('Europe/Madrid');

// Configuración para detectar errores en entorno de pruebas
ini_set('display_errors', '1');
error_reporting(E_ALL);
ob_start();
