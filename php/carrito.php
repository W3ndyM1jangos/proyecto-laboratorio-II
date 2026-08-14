<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}
// Agregar un producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "agregar") {
    $nombre = trim($_POST["nombre"] ?? "");
    $precio = (float) ($_POST["precio"] ?? 0);
    $imagen = trim($_POST["imagen"] ?? "");

    if ($nombre !== "" && $precio > 0 && $precio <= 5000) {
        // Si el producto ya estaba en el carrito le agrega lo que se desea coomprar 
        if (isset($_SESSION["carrito"][$nombre])) {
            $_SESSION["carrito"][$nombre]["cantidad"]++;
        } else {
            $_SESSION["carrito"][$nombre] = [
                "precio" => $precio,
                "cantidad" => 1,
                "imagen" => $imagen
            ];
        }
    }
    $volver = $_POST["volver"] ?? "";
    header("Location: " . ($volver !== "" ? "../" . $volver : "carrito.php"));
    exit;
}
// sumar o restar la cantidad de un producto ya agregado
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "sumar") {
    $nombre = $_POST["nombre"] ?? "";
    if (isset($_SESSION["carrito"][$nombre])) {
        $_SESSION["carrito"][$nombre]["cantidad"]++;
    }
    header("Location: carrito.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "restar") {
    $nombre = $_POST["nombre"] ?? "";
    if (isset($_SESSION["carrito"][$nombre])) {
        $_SESSION["carrito"][$nombre]["cantidad"]--;
        if ($_SESSION["carrito"][$nombre]["cantidad"] <= 0) {
            unset($_SESSION["carrito"][$nombre]);
        }
    }
    header("Location: carrito.php");
    exit;
}
// elimina producto
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "quitar") {
    $nombre = $_POST["nombre"] ?? "";
    unset($_SESSION["carrito"][$nombre]);
    header("Location: carrito.php");
    exit;
}
// guarda el pedido y su detalle en la base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "finalizar") {
    if (count($_SESSION["carrito"]) > 0) {
        $total = 0;
        foreach ($_SESSION["carrito"] as $item) {
            $total += $item["precio"] * $item["cantidad"];
        }
        $stmt = $conexion->prepare("INSERT INTO pedidos (total) VALUES (?)");
        $stmt->bind_param("d", $total);
        $stmt->execute();
        $pedidoId = $stmt->insert_id;
        $stmt->close();

        $stmtDetalle = $conexion->prepare(
            "INSERT INTO detalle_pedido (pedido_id, producto_nombre, precio_unitario, cantidad, subtotal)
             VALUES (?, ?, ?, ?, ?)"
        );
        foreach ($_SESSION["carrito"] as $nombre => $item) {
            $subtotal = $item["precio"] * $item["cantidad"];
            $stmtDetalle->bind_param(
                "isdid",
                $pedidoId,
                $nombre,
                $item["precio"],
                $item["cantidad"],
                $subtotal
            );
            $stmtDetalle->execute();
        }
        $stmtDetalle->close();
        $_SESSION["carrito"] = [];
        $_SESSION["mensaje"] = "¡Tu pedido #$pedidoId se registró correctamente!";
    }
    header("Location: carrito.php");
    exit;
}
$total = 0;
foreach ($_SESSION["carrito"] as $item) {
    $total += $item["precio"] * $item["cantidad"];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart Google Store-</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/carrito.css">
</head>
<body>
<div class="barra-superior-azul"></div>
<nav class="navbar-carrito">
    <a href="../index.html"><img src="../img/icono.png" alt="Google" width="28" height="28"></a>
    <span class="titulo-carrito">Cart</span>
</nav>
<div class="carrito-contenedor">
    <?php if (isset($_SESSION["mensaje"])): ?>
        <div class="confirmacion-pedido">
            <div class="confirmacion-icono">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <p class="confirmacion-titulo"><?= htmlspecialchars($_SESSION["mensaje"]) ?></p>
                <p class="confirmacion-subtitulo">Gracias por tu compra en Google Store.</p>
            </div>
        </div>
        <?php unset($_SESSION["mensaje"]); ?>
    <?php endif; ?>

    <?php if (count($_SESSION["carrito"]) === 0): ?>
        <div class="carrito-vacio">
            <h2>Your cart is empty</h2>
            <a href="../index.html" class="btn-continuar">Continue shopping</a>
        </div>

        <div class="tarjeta-guardado">
            <h5>Saved for later</h5>
            <div class="tarjeta-guardado-cuerpo">
                <p class="fw-medium mb-1">No items saved yet</p>
                <p class="text-secondary mb-0">Add items you're not buying today to this list</p>
            </div>
        </div>
    <?php else: ?>
        <h4 class="fw-medium">Cart</h4>
        <div class="tarjeta-guardado p-0">
            <table class="table align-middle m-0">
                <thead>
                    <tr>
                        <th></th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION["carrito"] as $nombre => $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item["imagen"])): ?>
                                    <img src="../<?= htmlspecialchars($item["imagen"]) ?>" alt="<?= htmlspecialchars($nombre) ?>" class="img-carrito">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($nombre) ?></td>
                            <td>$<?= number_format($item["precio"], 2) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <form action="carrito.php" method="POST" class="m-0">
                                        <input type="hidden" name="accion" value="restar">
                                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                                    </form>
                                    <span><?= (int) $item["cantidad"] ?></span>
                                    <form action="carrito.php" method="POST" class="m-0">
                                        <input type="hidden" name="accion" value="sumar">
                                        <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>$<?= number_format($item["precio"] * $item["cantidad"], 2) ?></td>
                            <td>
                                <form action="carrito.php" method="POST" class="m-0">
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($nombre) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h5 class="m-0">Total: $<?= number_format($total, 2) ?></h5>
            <form action="carrito.php" method="POST">
                <input type="hidden" name="accion" value="finalizar">
                <button type="submit" class="btn-continuar btn-continuar-lleno">Finalizar compra</button>
            </form>
        </div>
    <?php endif; ?>
</div>
</body>
</html>