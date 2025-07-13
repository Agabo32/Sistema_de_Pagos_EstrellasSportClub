<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $clave = $_POST['clave'];
    
    if (empty($nombre_usuario) || empty($clave)) {
        $error = 'Por favor complete todos los campos';
    } else {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            
            $query = "SELECT id_usuario, nombre_completo, nombre_usuario, clave_hash, rol FROM usuarios WHERE nombre_usuario = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $nombre_usuario);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($clave, $usuario['clave_hash'])) {
                    // Login exitoso
                    $_SESSION['usuario_id'] = $usuario['id_usuario'];
                    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Usuario o contraseña incorrectos';
                }
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        } catch (PDOException $e) {
            $error = 'Error de conexión. Por favor intente nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Estrellas Sport Club</title>
    <link rel="icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="shortcut icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="apple-touch-icon" href="assets/image/1731114751966-removebg-preview.png">
    <link href="assets/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .login-container {
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
            background: linear-gradient(90deg, #667eea, #764ba2);
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

        /* Mensaje de error */
        .error-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            color: #dc2626;
            font-weight: 500;
        }

        .error-message i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            color: #667eea;
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
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        /* Botón de envío */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Información adicional */
        .form-footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .security-info {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .security-info i {
            margin-right: 0.5rem;
        }

        .additional-info {
            display: flex;
            justify-content: center;
            gap: 2rem;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .info-item {
            display: flex;
            align-items: center;
        }

        .info-item i {
            margin-right: 0.5rem;
            color: #9ca3af;
        }

        /* Estilos para el enlace de registro */
        .register-link a:hover {
            color: #5a67d8;
            transform: translateY(-1px);
        }

        .register-link a:active {
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .login-container {
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
            .login-container {
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

            .additional-info {
                flex-direction: column;
                gap: 0.5rem;
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

            .form-input {
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

    <div class="login-container">
        <!-- Panel de información -->
        <div class="info-panel fade-in">
            <div class="logo-section">
                <img src="assets/image/1731114751966-removebg-preview.png" alt="Logo" class="logo">
                <div>
                    <h1 class="brand-title">Estrellas Sport Club</h1>
                    <p class="brand-subtitle">Sistema de Gestión</p>
                </div>
            </div>

            <h2 class="welcome-text">¡Bienvenido de vuelta!</h2>
            <p class="description">
                Accede a tu cuenta para gestionar atletas, pagos y reportes de manera eficiente y segura.
            </p>

            <div class="features">
                <div class="feature">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sistema seguro y confidencial</span>
                </div>
                <div class="feature">
                    <i class="fas fa-clock"></i>
                    <span>Acceso 24/7 desde cualquier dispositivo</span>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <span>Reportes detallados y análisis en tiempo real</span>
                </div>
                <div class="feature">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Interfaz responsive y fácil de usar</span>
                </div>
            </div>
        </div>

        <!-- Panel del formulario -->
        <div class="form-panel slide-in">
            <div class="form-header">
                <h2 class="form-title">Iniciar Sesión</h2>
                <p class="form-subtitle">Ingresa tus credenciales para continuar</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>



            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre_usuario" class="form-label">Usuario</label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="nombre_usuario" 
                            name="nombre_usuario" 
                            value="<?php echo isset($_POST['nombre_usuario']) ? htmlspecialchars($_POST['nombre_usuario']) : ''; ?>"
                            class="form-input"
                            placeholder="Ingresa tu nombre de usuario"
                            required
                            autocomplete="username"
                        >
                        <i class="fas fa-user input-icon"></i>
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
                            placeholder="Ingresa tu contraseña"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button 
                            type="button" 
                            onclick="togglePassword()" 
                            class="password-toggle"
                            aria-label="Mostrar/ocultar contraseña"
                        >
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>

            <div class="form-footer">
                <div class="security-info">
                    <i class="fas fa-shield-alt"></i>
                    <span>Sistema seguro y confidencial</span>
                </div>
                <div class="additional-info">
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>24/7 Disponible</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-database"></i>
                        <span>Datos Protegidos</span>
                    </div>
                </div>
                <div class="register-link" style="margin-top: 1.5rem; text-align: center;">
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        ¿No tienes una cuenta?
                    </p>
                    <a href="registro.php" style="color: #667eea; text-decoration: none; font-weight: 600; transition: color 0.3s ease; display: inline-flex; align-items: center;">
                        <i class="fas fa-user-plus mr-2"></i>
                        Crear cuenta nueva
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword() {
            const passwordInput = document.getElementById('clave');
            const passwordIcon = document.getElementById('password-icon');
            
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
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Iniciando sesión...';
            submitButton.disabled = true;
            
            // Simular un pequeño delay para mejor UX
            setTimeout(() => {
                // El formulario se enviará normalmente
            }, 500);
        }

        // Función para manejar errores de validación
        function handleInputValidation() {
            const inputs = document.querySelectorAll('input[required]');
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

        // Función para mejorar la accesibilidad del teclado
        function handleKeyboardNavigation() {
            const passwordToggle = document.querySelector('button[onclick="togglePassword()"]');
            
            passwordToggle.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    togglePassword();
                }
            });
        }

        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar mensaje de logout exitoso si existe
            <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
            Swal.fire({
                title: '¡Sesión Cerrada!',
                text: 'Has cerrado tu sesión exitosamente. ¡Hasta pronto!',
                icon: 'success',
                confirmButtonColor: '#667eea',
                confirmButtonText: 'Entendido'
            });
            <?php endif; ?>
            
            // Focus en el primer campo
            const firstInput = document.getElementById('nombre_usuario');
            if (firstInput) {
                firstInput.focus();
            }
            
            // Configurar validación de formulario
            handleInputValidation();
            
            // Configurar navegación por teclado
            handleKeyboardNavigation();
            
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
