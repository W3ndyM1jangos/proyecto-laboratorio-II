<?php
$mensaje = "⚠️ ESTA PÁGINA ESTÁ HECHA ÚNICAMENTE CON FINES EDUCATIVOS ⚠️";
$url_inicio = "index.html";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Aviso Educativo</title>
  <link rel="stylesheet" href="css/aviso.css">
</head>
<body>

  <div class="box">
    <marquee class="rojo"><?php echo $mensaje; ?></marquee>
    <a href="<?php echo $url_inicio; ?>" class="btn">Ingresar al sitio</a>
  </div>

</body>
</html>
