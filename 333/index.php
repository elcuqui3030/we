<?php
// Archivos donde guardaremos el estado y el contador
$archivo_estado = 'estado.txt';
$archivo_contador = 'contador.txt';

// Valores por defecto si no existen los archivos
if (!file_exists($archivo_estado)) {
    file_put_contents($archivo_estado, 'ON'); // Por defecto encendido (alternando)
}
if (!file_exists($archivo_contador)) {
    file_put_contents($archivo_contador, '0');
}

$estado_actual = trim(file_get_contents($archivo_estado));
$paginaA = "online/index.html";
$paginaB = "https://42e10e39-5b2b-4c65-b5e5-9547fbe57ac5-00-5wlik67fy8m1.spock.replit.dev:5000/";

// Si el interruptor está apagado (OFF), manda siempre a la Página A
if ($estado_actual === 'OFF') {
    header("Location: " . $paginaA);
    exit;
}

// Si está encendido (ON), usamos el contador para alternar estrictamente
$fp = fopen($archivo_contador, 'c+');
if (flock($fp, LOCK_EX)) {
    $contador = (int)fread($fp, filesize($archivo_contador) ?: 1);

    // Si es par va a A, si es impar va a B
    if ($contador % 2 === 0) {
        $destino = $paginaA;
    } else {
        $destino = $paginaB;
    }

    // Actualizamos el contador sumándole 1 para la próxima visita
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $contador + 1);
    fflush($fp);
    flock($fp, LOCK_UN);
} else {
    $destino = $paginaA;
}
fclose($fp);

// Redirige al destino correspondiente
header("Location: " . $destino);
exit;
?>
