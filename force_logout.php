<?php
// Forzar el cierre de sesión de manera más agresiva
session_start();

// Guardar información para logs
$usuario_info = null;
if (isset($_SESSION['usuario_id'])) {
    $usuario_info = [
        'id' => $_SESSION['usuario_id'],
        'nombre' => $_SESSION['nombre_usuario'] ?? 'Desconocido',
        'fecha' => date('Y-m-d H:i:s')
    ];
}

// Limpiar variables de sesión
$_SESSION = array();

// Destruir sesión
session_destroy();

// Limpiar cookies de manera más agresiva
$cookies = $_COOKIE;
foreach($cookies as $cookie_name => $cookie_value) {
    setcookie($cookie_name, '', time() - 3600, '/');
    setcookie($cookie_name, '', time() - 3600);
}

// Limpiar cookies específicas
$specific_cookies = ['PHPSESSID', 'usuario_id', 'nombre_usuario', 'rol', 'nombre_completo'];
foreach($specific_cookies as $cookie) {
    setcookie($cookie, '', time() - 3600, '/');
    setcookie($cookie, '', time() - 3600);
}

// Registrar logout
if ($usuario_info) {
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    $log_message = "[{$usuario_info['fecha']}] FORCE LOGOUT - Usuario ID: {$usuario_info['id']}, Nombre: {$usuario_info['nombre']}\n";
    error_log($log_message, 3, 'logs/auth.log');
}

// Headers de seguridad
header('Cache-Control: no-cache, no-store, must-revalidate, private, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Redirigir al login
header('Location: login.php?logout=success&force=1');
exit;
?> 