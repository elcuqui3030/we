<?php
$estado_archivo = 'estado.txt';

if (!file_exists($estado_archivo)) {
    file_put_contents($estado_archivo, 'OFF'); // Apagado por defecto
}

if (isset($_GET['estado'])) {
    $nuevo_estado = $_GET['estado'] === 'ON' ? 'ON' : 'OFF';
    file_put_contents($estado_archivo, $nuevo_estado);
    header("Location: panel.php");
    exit;
}
?>