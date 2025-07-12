<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Registrar el logout para auditoría (opcional)
if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre_usuario'])) {
    // Aquí podrías registrar el logout en un archivo de log o base de datos
    $usuario_id = $_SESSION['usuario_id'];
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $fecha_logout = date('Y-m-d H:i:s');
    
    // Ejemplo de log (puedes implementar según tus necesidades)
    $log_message = "[$fecha_logout] Usuario ID: $usuario_id, Nombre: $nombre_usuario - Cerró sesión\n";
    error_log($log_message, 3, 'logs/auth.log');
}

// Limpiar todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borrar también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Limpiar cualquier cookie de sesión adicional que pueda existir
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Establecer headers de seguridad
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Redirigir al login con mensaje de logout exitoso
header('Location: login.php?logout=success');
exit;
?>
