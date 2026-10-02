<?php
declare(strict_types=1);

require_once 'config.php';
require_once 'OrdenTrabajo.php';

// ----------------------------------------------------
// 1. Validación de la petición
// ----------------------------------------------------

$numero = filter_input(INPUT_GET, 'cant', FILTER_VALIDATE_INT);

if ($numero === false || $numero === null || $numero < 1) {
    http_response_code(400);
    exit('Error 400: el número de solicitud debe ser un entero positivo mayor que cero.');
}

// ----------------------------------------------------
// 2. Datos del cliente
// ----------------------------------------------------

$cliente = $_GET['usuario'] ?? 'Cliente Anónimo';
$cliente = (string) $cliente;

$clienteMayusculas = mb_strtoupper($cliente, 'UTF-8');
$longitudCliente = mb_strlen($clienteMayusculas, 'UTF-8');

// ----------------------------------------------------
// 3. Catálogo de recambios
// ----------------------------------------------------

$recambios = [
    ['nombre' => 'Pantalla iPhone', 'precio' => 120.00, 'stock' => 3],
    ['nombre' => 'Batería Samsung', 'precio' => 55.00, 'stock' => 5],
    ['nombre' => 'Conector USB-C', 'precio' => 20.00, 'stock' => 0],
    ['nombre' => 'Placa base Xiaomi', 'precio' => 150.00, 'stock' => 2],
    ['nombre' => 'Cámara móvil', 'precio' => 70.00, 'stock' => 1],
];

// Recargo de almacenamiento del 10%
$recargo = 1.10;

foreach ($recambios as &$pieza) {
    $pieza['precio'] = $pieza['precio'] * $recargo;
}
unset($pieza);

// ----------------------------------------------------
// 4. Filtrar piezas que tienen stock
// ----------------------------------------------------

$recambiosDisponibles = array_filter(
    $recambios,
    fn(array $pieza): bool => $pieza['stock'] > 0
);

// Valor total del almacén disponible
$valorAlmacen = array_sum(
    array_map(
        fn(array $pieza): float => $pieza['precio'] * $pieza['stock'],
        $recambiosDisponibles
    )
);

// ----------------------------------------------------
// 5. Paginación
// ----------------------------------------------------

$pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$pagina = ($pagina === false || $pagina === null) ? 1 : $pagina;

$porPagina = 2;
$totalPiezas = count($recambiosDisponibles);
$totalPaginas = max(1, (int) ceil($totalPiezas / $porPagina));

// Evitamos páginas menores que 1 o mayores que la última
$pagina = max(1, min($pagina, $totalPaginas));

$inicio = ($pagina - 1) * $porPagina;
$piezasPagina = array_slice(array_values($recambiosDisponibles), $inicio, $porPagina);

// ----------------------------------------------------
// 6. Crear orden de trabajo
// ----------------------------------------------------

$orden = new OrdenTrabajo(
    numero: $numero,
    cliente: $clienteMayusculas,
    tipo: TipoReparacion::Pantalla,
    manoObra: 50.00,
    recambios: 120.00
);

// ----------------------------------------------------
// 7. Búfer de salida
// ----------------------------------------------------

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TechFix - Presupuesto</title>
</head>
<body>

<h1>TechFix</h1>

<h2>Resumen del presupuesto</h2>

<p><strong>Número de solicitud:</strong>
    <?= htmlspecialchars((string) $orden->numero, ENT_QUOTES, 'UTF-8') ?>
</p>

<p><strong>Cliente:</strong>
    <?= htmlspecialchars($orden->cliente, ENT_QUOTES, 'UTF-8') ?>
</p>

<p><strong>Longitud del nombre:</strong>
    <?= htmlspecialchars((string) $longitudCliente, ENT_QUOTES, 'UTF-8') ?>
</p>

<p><strong>Tipo de reparación:</strong>
    <?= htmlspecialchars($orden->tipo->descripcion(), ENT_QUOTES, 'UTF-8') ?>
</p>

<p><strong>Mano de obra:</strong>
    <?= number_format($orden->manoObra, 2, ',', '.') ?> €
</p>

<p><strong>Recambios:</strong>
    <?= number_format($orden->recambios, 2, ',', '.') ?> €
</p>

<p><strong>Total con IVA (21%):</strong>
    <?= number_format($orden->total(), 2, ',', '.') ?> €
</p>

<h2>Recambios disponibles</h2>

<table border="1">
    <tr>
        <th>Recambio</th>
        <th>Precio</th>
        <th>Stock</th>
    </tr>

    <?php foreach ($piezasPagina as $pieza): ?>
        <tr>
            <td><?= htmlspecialchars($pieza['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format($pieza['precio'], 2, ',', '.') ?> €</td>
            <td><?= htmlspecialchars((string) $pieza['stock'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>
    <strong>Valor total del almacén disponible:</strong>
    <?= number_format($valorAlmacen, 2, ',', '.') ?> €
</p>

<p>
    Página <?= $pagina ?> de <?= $totalPaginas ?>
</p>

</body>
</html>
<?php
$html = ob_get_clean();

// Entregamos el HTML generado
echo $html;
