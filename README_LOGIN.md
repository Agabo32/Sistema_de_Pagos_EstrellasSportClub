# Sistema de Login - Estrellas Sport Club

## 📋 Descripción

Sistema de autenticación completo para el Sistema de Gestión de Estrellas Sport Club, compatible con la estructura de base de datos proporcionada.

## 🗄️ Estructura de la Base de Datos

### Tabla: `usuarios`

| Campo | Tipo | Null | Key | Default | Extra |
|-------|------|------|-----|---------|-------|
| id_usuario | int(11) | NO | PRI | NULL | auto_increment |
| nombre_completo | varchar(50) | NO | NULL | | |
| nombre_usuario | varchar(50) | NO | UNI | NULL | | |
| clave_hash | varchar(255) | NO | NULL | | |
| rol | enum('Admin','Asistente') | YES | | Asistente | |

## 🚀 Instalación

### 1. Crear la tabla usuarios

```sql
CREATE TABLE usuarios (
    id_usuario INT(11) NOT NULL AUTO_INCREMENT,
    nombre_completo VARCHAR(50) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    clave_hash VARCHAR(255) NOT NULL,
    rol ENUM('Admin', 'Asistente') DEFAULT 'Asistente',
    PRIMARY KEY (id_usuario)
);
```

### 2. Insertar usuarios por defecto

Ejecutar el archivo `sql/insert_admin.sql` en tu base de datos:

```sql
-- Usuario administrador
INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES 
('Administrador del Sistema', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Usuario asistente
INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES 
('Asistente General', 'asistente', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Asistente');
```

### 3. Credenciales por defecto

- **Administrador:**
  - Usuario: `admin`
  - Contraseña: `admin123`

- **Asistente:**
  - Usuario: `asistente`
  - Contraseña: `asistente123`

## 📁 Archivos del Sistema

### Archivos principales:
- `login.php` - Página de inicio de sesión
- `logout.php` - Cerrar sesión
- `includes/auth.php` - Verificación de autenticación
- `index.php` - Sistema principal (modificado para incluir autenticación)

### Archivos de configuración:
- `sql/insert_admin.sql` - Script para insertar usuarios por defecto
- `generar_password.php` - Generador de contraseñas hasheadas (temporal)

## 🔐 Características de Seguridad

### Autenticación:
- Verificación de sesiones
- Contraseñas hasheadas con `password_hash()`
- Protección contra inyección SQL con prepared statements
- Redirección automática si no está autenticado

### Roles de Usuario:
- **Admin**: Acceso completo al sistema
- **Asistente**: Acceso limitado (puede ser configurado)

### Funciones de Seguridad:
- `tieneRol($rol)` - Verificar rol específico
- `esAdmin()` - Verificar si es administrador
- `esAsistente()` - Verificar si es asistente
- `obtenerUsuarioActual()` - Obtener información del usuario

## 🎨 Características del Diseño

### Login:
- Diseño moderno con gradientes
- Animaciones suaves
- Fondo animado con elementos flotantes
- Formulario con efectos de focus
- Mostrar/ocultar contraseña
- Mensajes de error estilizados

### Sistema Principal:
- Información del usuario en la navegación
- Menú desplegable del usuario
- Botón de cerrar sesión
- Diseño consistente con el sistema existente

## 🔧 Configuración

### 1. Generar nuevas contraseñas

Si necesitas generar nuevas contraseñas hasheadas:

1. Accede a `generar_password.php`
2. Ingresa la contraseña deseada
3. Copia el hash generado
4. **IMPORTANTE**: Elimina el archivo `generar_password.php` después de usarlo

### 2. Modificar roles

Para cambiar el rol de un usuario:

```sql
UPDATE usuarios SET rol = 'Admin' WHERE nombre_usuario = 'usuario';
```

### 3. Agregar nuevos usuarios

```sql
INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES 
('Nombre Completo', 'usuario', 'hash_generado', 'Admin');
```

## 🛡️ Protección de Archivos

### Archivos que requieren autenticación:
- `index.php` - Sistema principal
- Todos los archivos en `views/`
- Todos los archivos en `controllers/`

### Archivos públicos:
- `login.php` - Página de login
- `logout.php` - Cerrar sesión
- `assets/` - Recursos estáticos

## 🔄 Flujo de Autenticación

1. Usuario accede a cualquier página protegida
2. Si no está autenticado, redirige a `login.php`
3. Usuario ingresa credenciales
4. Sistema verifica en la base de datos
5. Si es correcto, crea sesión y redirige al sistema
6. Si es incorrecto, muestra error

## 🚨 Notas de Seguridad

1. **Eliminar archivos temporales**: Después de la instalación, elimina `generar_password.php`
2. **Cambiar contraseñas por defecto**: Cambia las contraseñas de los usuarios por defecto
3. **Configurar HTTPS**: En producción, asegúrate de usar HTTPS
4. **Backup de base de datos**: Mantén respaldos regulares de la base de datos

## 📞 Soporte

Para problemas o consultas sobre el sistema de login, revisa:
1. Configuración de la base de datos
2. Permisos de archivos
3. Configuración de sesiones PHP
4. Logs de errores del servidor 