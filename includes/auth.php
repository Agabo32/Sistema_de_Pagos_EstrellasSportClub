<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

// Función para verificar si el usuario tiene un rol específico
function tieneRol($rol) {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === $rol;
}

// Función para verificar si el usuario es administrador
function esAdmin() {
    return tieneRol('Admin');
}

// Función para verificar si el usuario es asistente
function esAsistente() {
    return tieneRol('Asistente');
}

// Función para obtener información del usuario actual
function obtenerUsuarioActual() {
    return [
        'id' => $_SESSION['usuario_id'] ?? null,
        'nombre_completo' => $_SESSION['nombre_completo'] ?? null,
        'nombre_usuario' => $_SESSION['nombre_usuario'] ?? null,
        'rol' => $_SESSION['rol'] ?? null
    ];
}
?> 