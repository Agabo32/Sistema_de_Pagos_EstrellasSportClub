<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $nombre_completo = trim($_POST['nombre_completo']);
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];
    $rol = $_POST['rol'];
    
    $errors = [];
    
    // Validaciones
    if (empty($nombre_completo)) {
        $errors[] = 'El nombre completo es requerido';
    }
    
    if (empty($nombre_usuario)) {
        $errors[] = 'El nombre de usuario es requerido';
    } elseif (strlen($nombre_usuario) < 3) {
        $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres';
    }
    
    if (empty($clave)) {
        $errors[] = 'La contraseña es requerida';
    } elseif (strlen($clave) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    if ($clave !== $confirmar_clave) {
        $errors[] = 'Las contraseñas no coinciden';
    }
    
    if (empty($rol)) {
        $errors[] = 'Debe seleccionar un rol';
    }
    
    // Si no hay errores, proceder con el registro
    if (empty($errors)) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            // Verificar si el usuario ya existe
            $checkQuery = "SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(1, $nombre_usuario);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                $errors[] = 'El nombre de usuario ya está en uso';
            } else {
                // Insertar nuevo usuario
                $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
                
                $insertQuery = "INSERT INTO usuarios (nombre_completo, nombre_usuario, clave_hash, rol) VALUES (?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bindParam(1, $nombre_completo);
                $insertStmt->bindParam(2, $nombre_usuario);
                $insertStmt->bindParam(3, $clave_hash);
                $insertStmt->bindParam(4, $rol);
                
                if ($insertStmt->execute()) {
                    $success = 'Usuario registrado exitosamente. Ya puedes iniciar sesión.';
                } else {
                    $errors[] = 'Error al registrar el usuario. Por favor intente nuevamente.';
                }
            }
        } catch (PDOException $e) {
            $errors[] = 'Error de conexión. Por favor intente nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Estrellas Sport Club</title>
    <link rel="icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="shortcut icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="apple-touch-icon" href="assets/image/1731114751966-removebg-preview.png">
    <link href="assets/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Fondo animado */
        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite linear;
        }

        .floating-shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: -5s;
        }

        .floating-shape:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            animation-delay: -10s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.3;
            }
            100% {
                transform: translateY(0px) rotate(360deg);
                opacity: 0.7;
            }
        }

        /* Contenedor principal */
        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        /* Panel izquierdo - Información */
        .info-panel {
            color: white;
            text-align: left;
        }

        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 1.5rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .brand-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .welcome-text {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            margin-top: 3rem;
        }

        .description {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .features {
            display: grid;
            gap: 1rem;
        }

        .feature {
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .feature i {
            width: 24px;
            margin-right: 1rem;
            color: #fbbf24;
        }

        /* Panel derecho - Formulario */
        .form-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .form-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        /* Mensajes de error y éxito */
        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #dc2626;
            font-weight: 500;
        }

        .success-message {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 1px solid #6ee7b7;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #059669;
            font-weight: 500;
        }

        /* Campos de formulario */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-input:focus + .input-icon {
            color: #10b981;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

        /* Select de rol */
        .form-select {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            transform: translateY(-1px);
        }

        /* Botón de envío */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Enlace de regreso */
        .back-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .back-link a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #059669;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .register-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 1rem;
            }

            .info-panel {
                text-align: center;
                order: 2;
            }

            .form-panel {
                order: 1;
                padding: 2rem;
            }

            .brand-title {
                font-size: 2rem;
            }

            .welcome-text {
                margin-top: 1rem;
            }
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 1rem;
            }

            .form-panel {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .brand-title {
                font-size: 1.75rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .logo {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                padding: 1rem;
            }

            .brand-title {
                font-size: 1.5rem;
            }

            .form-title {
                font-size: 1.25rem;
            }

            .form-input, .form-select {
                padding: 0.875rem 0.875rem 0.875rem 2.5rem;
            }

            .input-icon {
                left: 0.875rem;
            }
        }

        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Fondo animado -->
    <div class="background-animation">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <div class="register-container">
        <!-- Panel de información -->
        <div class="info-panel fade-in">
            <div class="logo-section">
                <img src="assets/image/1731114751966-removebg-preview.png" alt="Logo" class="logo">
                <div>
                    <h1 class="brand-title">Estrellas Sport Club</h1>
                    <p class="brand-subtitle">Sistema de Gestión</p>
                </div>
            </div>

            <h2 class="welcome-text">¡Únete a nuestro equipo!</h2>
            <p class="description">
                Crea tu cuenta para acceder al sistema de gestión de atletas, pagos y reportes.
            </p>

            <div class="features">
                <div class="feature">
                    <i class="fas fa-user-plus"></i>
                    <span>Registro rápido y sencillo</span>
                </div>
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Datos protegidos y seguros</span>
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <span>Acceso a todas las funcionalidades</span>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <span>Disponible 24/7</span>
                </div>
            </div>
        </div>

        <!-- Panel del formulario -->
        <div class="form-panel slide-in">
            <div class="form-header">
                <h2 class="form-title">Registro de Usuario</h2>
                <p class="form-subtitle">Completa los datos para crear tu cuenta</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($errors as $error): ?>
                            <li><i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="nombre_completo" 
                            name="nombre_completo" 
                            value="<?php echo isset($_POST['nombre_completo']) ? htmlspecialchars($_POST['nombre_completo']) : ''; ?>"
                            class="form-input"
                            placeholder="Ingresa tu nombre completo"
                            required
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="nombre_usuario" 
                            name="nombre_usuario" 
                            value="<?php echo isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : ''; ?>"
                            class="form-input"
                            placeholder="Ingresa un nombre de usuario"
                            required
                        >
                        <i class="fas fa-at input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="clave" class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="clave" 
                            name="clave" 
                            class="form-input"
                            placeholder="Ingresa una contraseña (mín. 6 caracteres)"
                            required
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button 
                            type="button" 
                            onclick="togglePassword('clave')" 
                            class="password-toggle"
                            aria-label="Mostrar/ocultar contraseña"
                        >
                            <i id="password-icon-clave" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmar_clave" class="form-label">Confirmar Contraseña</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="confirmar_clave" 
                            name="confirmar_clave" 
                            class="form-input"
                            placeholder="Confirma tu contraseña"
                            required
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button 
                            type="button" 
                            onclick="togglePassword('confirmar_clave')" 
                            class="password-toggle"
                            aria-label="Mostrar/ocultar contraseña"
                        >
                            <i id="password-icon-confirmar_clave" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="rol" class="form-label">Rol</label>
                    <div class="input-wrapper">
                        <select id="rol" name="rol" class="form-select" required>
                            <option value="">Selecciona un rol</option>
                            <option value="Admin" <?php echo (isset($_POST['rol']) && $_POST['rol'] === 'Admin') ? 'selected' : ''; ?>>Administrador</option>
                            <option value="Asistente" <?php echo (isset($_POST['rol']) && $_POST['rol'] === 'Asistente') ? 'selected' : ''; ?>>Asistente</option>
                        </select>
                        <i class="fas fa-user-tag input-icon"></i>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus mr-2"></i>
                    Registrar Usuario
                </button>
            </form>

            <div class="back-link">
                <a href="login.php">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById('password-icon-' + fieldId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
                passwordIcon.setAttribute('aria-label', 'Ocultar contraseña');
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
                passwordIcon.setAttribute('aria-label', 'Mostrar contraseña');
            }
        }

        // Función para manejar el envío del formulario
        function handleFormSubmit(event) {
            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Mostrar estado de carga
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registrando...';
            submitButton.disabled = true;
            
            // Simular un pequeño delay para mejor UX
            setTimeout(() => {
                // El formulario se enviará normalmente
            }, 500);
        }

        // Función para manejar errores de validación
        function handleInputValidation() {
            const inputs = document.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('border-red-300');
                    } else {
                        this.classList.remove('border-red-300');
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-300');
                    }
                });
            });
        }

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            // Focus en el primer campo
            const firstInput = document.getElementById('nombre_completo');
            if (firstInput) {
                firstInput.focus();
            }
            
            // Configurar validación de formulario
            handleInputValidation();
            
            // Configurar envío del formulario
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }
        });

        // Prevenir zoom en dispositivos móviles al hacer doble tap
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);
    </script>
</body>
</html> 