<?php
// Forzar el inicio de sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Guardar información del usuario antes de limpiar (para logs)
$usuario_info = null;
if (isset($_SESSION['usuario_id'])) {
    $usuario_info = [
        'id' => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['nombre_usuario'] ?? 'Desconocido',
        'fecha' => date('Y-m-d H:i:s')
    ];
}

// Limpiar TODAS las variables de sesión
$_SESSION = array();

// Destruir la sesión completamente
session_destroy();

// Limpiar cookies de sesión de manera más agresiva
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Limpiar cookies específicas de manera más agresiva
setcookie('PHPSESSID', '', time() - 3600, '/');
setcookie('usuario_id', '', time() - 3600, '/');
setcookie('nombre_usuario', '', time() - 3600, '/');
setcookie('rol', '', time() - 3600, '/');
setcookie('nombre_completo', '', time() - 3600, '/');

// Limpiar cookies con diferentes dominios y paths
setcookie('PHPSESSID', '', time() - 3600);
setcookie('usuario_id', '', time() - 3600);
setcookie('nombre_usuario', '', time() - 3600);

// Registrar logout si hay información del usuario
if ($usuario_info) {
    // Crear directorio de logs si no existe
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    $log_message = "[{$usuario_info['fecha']}] Usuario ID: {$usuario_info['id']}, Nombre: {$usuario_info['nombre']} - Cerró sesión\n";
    error_log($log_message, 3, 'logs/auth.log');
}

// Headers de seguridad más estrictos
header('Cache-Control: no-cache, no-store, must-revalidate, private, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// Redirigir al login con parámetros adicionales
header('Location: login.php?logout=success&t=' . time());
exit;
?>
