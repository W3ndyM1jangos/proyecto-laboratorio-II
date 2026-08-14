<?php
session_start();
header("Content-Type: application/json");

$cantidad = 0;
if (isset($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $item) {
        $cantidad += $item["cantidad"];
    }
}

echo json_encode(["cantidad" => $cantidad]);
