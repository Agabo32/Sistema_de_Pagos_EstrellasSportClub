<?php
session_start();

echo "<h2>Debug - Estado de Sesión</h2>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";

if (isset($_SESSION['usuario_id'])) {
    echo "<p style='color: green;'><strong>✅ Usuario logueado:</strong> " . $_SESSION['nombre_completo'] . "</p>";
    echo "<p><strong>ID:</strong> " . $_SESSION['usuario_id'] . "</p>";
    echo "<p><strong>Usuario:</strong> " . $_SESSION['nombre_usuario'] . "</p>";
    echo "<p><strong>Rol:</strong> " . $_SESSION['rol'] . "</p>";
    
    echo "<hr>";
    echo "<h3>Probar Logout</h3>";
    echo "<a href='logout.php' style='background: #dc2626; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Cerrar Sesión Normal</a>";
    echo "<a href='force_logout.php' style='background: #991b1b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Force Logout</a>";
} else {
    echo "<p style='color: red;'><strong>❌ No hay usuario logueado</strong></p>";
    echo "<p><a href='login.php'>Ir al Login</a></p>";
}

echo "<hr>";
echo "<h3>Variables de Sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Cookies:</h3>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";
?> 