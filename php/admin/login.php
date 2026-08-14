<?php
session_start();

$ADMIN_USUARIO = "admin";
$ADMIN_PASSWORD = "admin123";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($usuario === $ADMIN_USUARIO && $password === $ADMIN_PASSWORD) {
        $_SESSION["admin_logueado"] = true;
        header("Location: panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Google Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body class="pantalla-centrada">
    <div class="login-box">
        <div class="text-center">
            <img src="../../img/icono.png" alt="Google" class="logo-admin">
            <h4 class="fw-medium mt-2">Acceso administrador</h4>
        </div>
        <?php if ($error): ?>
            <div><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" require>
            </div>
            <button type="submit" class="btn btn-google-azul text-white">Ingresar</button>
        </form>
        <div class="text-center mt-3">
            <a href="../../index.html" class="text-secondary small text-decoration-none">Volver a la tienda</a>
        </div>
    </div>
</body>
</html>
