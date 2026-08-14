<?php
session_start();

if (isset($_GET["salir"])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
// sin sesion abierta 
if (!isset($_SESSION["admin_logueado"]) || $_SESSION["admin_logueado"] !== true) {
    header("Location: login.php");
    exit;
}
require_once "../conexion.php";

// productos comprados
$sql = "SELECT p.id AS pedido_id, p.fecha, p.total,
               d.producto_nombre, d.precio_unitario, d.cantidad, d.subtotal
        FROM pedidos p
        INNER JOIN detalle_pedido d ON d.pedido_id = p.id
        ORDER BY p.fecha DESC, p.id DESC";
$resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de pedidos - Google Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<div class="panel-contenedor">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-medium m-0">Pedidos realizados</h2>
        <a href="login.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
    </div>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table align-middle m-0">
                <thead>
                    <tr>
                        <th># Pedido</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Precio unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Total del pedido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= (int) $fila["pedido_id"] ?></td>
                            <td><?= htmlspecialchars($fila["fecha"]) ?></td>
                            <td><?= htmlspecialchars($fila["producto_nombre"]) ?></td>
                            <td>$<?= number_format($fila["precio_unitario"], 2) ?></td>
                            <td><?= (int) $fila["cantidad"] ?></td>
                            <td>$<?= number_format($fila["subtotal"], 2) ?></td>
                            <td>$<?= number_format($fila["total"], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-secondary">Todavía no se ha registrado ningún pedido.</p>
    <?php endif; ?>
</div>
</body>
</html>
