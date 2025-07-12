<?php
// Archivo temporal para generar contraseñas hasheadas
// Usar este archivo para generar nuevas contraseñas y luego eliminarlo

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (!empty($password)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<h2>Contraseña hasheada:</h2>";
        echo "<p><strong>Contraseña original:</strong> " . htmlspecialchars($password) . "</p>";
        echo "<p><strong>Hash generado:</strong> " . htmlspecialchars($hash) . "</p>";
        echo "<p><strong>SQL para insertar:</strong></p>";
        echo "<code>INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES ('Nombre Completo', 'usuario', '" . htmlspecialchars($hash) . "', 'Admin');</code>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Contraseñas - Estrellas Sport Club</title>
    <link href="assets/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Generador de Contraseñas</h1>
        
        <form method="POST" class="space-y-4">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña:</label>
                <input 
                    type="text" 
                    id="password" 
                    name="password" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Ingrese la contraseña"
                    required
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors"
            >
                Generar Hash
            </button>
        </form>
        
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>⚠️ Importante:</strong> Este archivo es temporal. 
                Después de generar las contraseñas necesarias, elimine este archivo por seguridad.
            </p>
        </div>
    </div>
</body>
</html> 