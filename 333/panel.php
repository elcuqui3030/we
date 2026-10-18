<?php
include 'config.php';
$estado_actual = file_get_contents($estado_archivo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
</head>
<body>
    <h1>Panel de Control</h1>
    <p>Estado actual: <strong><?php echo $estado_actual === 'ON' ? 'Modo Alternando (A y B)' : 'Solo Página A'; ?></strong></p>

    <form action="config.php" method="GET">
        <button type="submit" name="estado" value="OFF">Apagar (Solo Página A)</button>
        <button type="submit" name="estado" value="ON">Encender (Alternar A y B)</button>
    </form>
</body>
</html>