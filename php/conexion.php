<?php
$host = "localhost";
$usuario = "root";
$password = "";
$baseDatos = "tienda_google";

$conexion = new mysqli($host, $usuario, $password, $baseDatos);
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
?>
