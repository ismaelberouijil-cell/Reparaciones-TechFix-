<?php
declare(strict_types=1);
$Usuario = $_GET['usuario'] ?? "Cliente Anonimo";

$NombreMayuscula = mb_strtoupper($Usuario);

$ContadorNumerico = mb_strlen($NombreMayuscula);


