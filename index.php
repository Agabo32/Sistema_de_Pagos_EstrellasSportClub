<?php
require_once 'includes/auth.php';
$usuario = obtenerUsuarioActual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrellas Sport Club - Sistema de Gestión</title>
    <link rel="icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="shortcut icon" type="image/png" href="assets/image/1731114751966-removebg-preview.png">
    <link rel="apple-touch-icon" href="assets/image/1731114751966-removebg-preview.png">
    <link href="assets/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
        }
        .nav-link-mobile {
            position: relative;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
            display: block;
            text-decoration: none;
        }
        .nav-link-mobile:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .nav-link-mobile.active {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 600;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }
        
        /* Estilos para navbar dinámico */
        @media (max-width: 1280px) {
            .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 1024px) {
            .nav-link {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
        }
        
        /* Animación suave para el menú móvil */
        #mobile-menu {
            transition: all 0.3s ease-in-out;
            max-height: 0;
            overflow: hidden;
        }
        
        #mobile-menu.show {
            max-height: 500px;
        }
        
        /* Mejorar accesibilidad */
        .nav-link:focus,
        .nav-link-mobile:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }
        
        /* Asegurar que el menú móvil tenga el z-index correcto */
        #mobile-menu {
            z-index: 40;
        }
        
        /* Efecto de desvanecimiento para el menú */
        .mobile-menu-fade {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
        }
        
        .mobile-menu-fade.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Optimización del logo */
        .logo-container img {
            object-fit: contain;
            max-width: 100%;
            height: auto;
        }
        
        /* Ajustes específicos para diferentes tamaños de pantalla */
        @media (max-width: 640px) {
            .logo-container img {
                max-height: 24px;
            }
        }
        
        @media (min-width: 641px) and (max-width: 1024px) {
            .logo-container img {
                max-height: 32px;
            }
        }
        
        @media (min-width: 1025px) {
            .logo-container img {
                max-height: 40px;
            }
        }
        
        /* Estilos personalizados para SweetAlert2 */
        .swal2-toast {
            border-radius: 8px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal2-popup {
            border-radius: 12px !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
        
        .swal2-title {
            font-weight: 600 !important;
            color: #1f2937 !important;
        }
        
        .swal2-html-container {
            color: #6b7280 !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            border-radius: 8px !important;
            font-weight: 500 !important;
            padding: 12px 24px !important;
            transition: all 0.2s ease !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal2-cancel:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .swal2-success-btn {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }
        
        .swal2-error-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
        }
        
        .swal2-info-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
        }
        
        .swal2-warning-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
        }
        
        .swal2-question-confirm {
            background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
        }
        
        .swal2-question-cancel {
            background: linear-gradient(135deg, #6b7280, #4b5563) !important;
        }
        
        .swal2-form-confirm {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }
        
        .swal2-form-cancel {
            background: linear-gradient(135deg, #6b7280, #4b5563) !important;
        }
        
        .swal2-loading {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo y título -->
                <div class="flex items-center space-x-2 sm:space-x-3 logo-container">
                    <img src="assets/image/1731114751966-removebg-preview.png" alt="Logo" class="h-6 w-auto sm:h-8 lg:h-10">
                    <div class="hidden sm:block">
                        <h1 class="text-base sm:text-lg lg:text-2xl font-bold tracking-wide text-white">Estrellas Sport Club</h1>
                        <p class="text-xs sm:text-sm text-white opacity-90">Sistema de Gestión</p>
                    </div>
                    <div class="sm:hidden">
                        <h1 class="text-base font-bold tracking-wide text-white">ESC</h1>
                        <p class="text-xs text-white opacity-90">Sistema</p>
                    </div>
                </div>

                <!-- Enlaces de navegación - Desktop -->
                <div class="hidden xl:flex items-center space-x-4" id="navbar-links-desktop">
                    <div class="flex space-x-2">
                        <a href="#dashboard" class="nav-link">
                            <i class="fas fa-chart-line mr-2"></i>Dashboard
                        </a>
                        <a href="#atletas" class="nav-link">
                            <i class="fas fa-users mr-2"></i>Atletas
                        </a>
                        <a href="#pagos" class="nav-link">
                            <i class="fas fa-credit-card mr-2"></i>Pagos
                        </a>
                        <a href="#reportes" class="nav-link">
                            <i class="fas fa-chart-bar mr-2"></i>Reportes
                        </a>
                    </div>
                    
                    <!-- Información del usuario - Desktop -->
                    <div class="flex items-center space-x-3 border-l border-white border-opacity-30 pl-4">
                        <div class="text-right">
                            <div class="text-sm font-medium text-white"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></div>
                            <div class="text-xs text-white opacity-80"><?php echo htmlspecialchars($usuario['rol']); ?></div>
                        </div>
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-white hover:text-gray-200 transition-colors">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Menú desplegable del usuario -->
                            <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                                <div class="py-2">
                                    <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                        <div class="font-medium"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></div>
                                        <div class="text-gray-500"><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></div>
                                    </div>
                                    <a href="#" onclick="confirmarCerrarSesion()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de menú móvil -->
                <div class="xl:hidden flex items-center space-x-3">
                    <!-- Información del usuario - Móvil -->
                    <div class="hidden md:flex items-center space-x-2 border-l border-white border-opacity-30 pl-3">
                        <div class="text-right">
                            <div class="text-xs font-medium text-white"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></div>
                            <div class="text-xs text-white opacity-80"><?php echo htmlspecialchars($usuario['rol']); ?></div>
                        </div>
                    </div>
                    
                    <!-- Botón de menú hamburguesa -->
                    <button onclick="toggleMobileMenu()" class="text-white hover:text-gray-200 transition-colors focus:outline-none" id="mobile-menu-button" aria-label="Abrir menú de navegación">
                        <i class="fas fa-bars text-xl" id="mobile-menu-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Menú móvil -->
            <div id="mobile-menu" class="xl:hidden hidden mt-4 pb-4 border-t border-white border-opacity-20">
                <div class="flex flex-col space-y-2 pt-4">
                    <a href="#dashboard" class="nav-link-mobile">
                        <i class="fas fa-chart-line mr-3"></i>Dashboard
                    </a>
                    <a href="#atletas" class="nav-link-mobile">
                        <i class="fas fa-users mr-3"></i>Atletas
                    </a>
                    <a href="#pagos" class="nav-link-mobile">
                        <i class="fas fa-credit-card mr-3"></i>Pagos
                    </a>
                    <a href="#reportes" class="nav-link-mobile">
                        <i class="fas fa-chart-bar mr-3"></i>Reportes
                    </a>
                    
                    <!-- Separador -->
                    <div class="border-t border-white border-opacity-20 pt-2 mt-2">
                        <div class="px-4 py-2 text-sm text-white opacity-80">
                            <div class="font-medium"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></div>
                            <div class="text-xs opacity-70"><?php echo htmlspecialchars($usuario['rol']); ?></div>
                        </div>
                        <a href="#" onclick="confirmarCerrarSesion()" class="nav-link-mobile">
                            <i class="fas fa-sign-out-alt mr-3"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="section">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Tasa del Dólar Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Tasa del Dólar</p>
                            <p class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent" id="tasa-actual">$0.00</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-700 shadow-lg">
                            <i class="fas fa-coins text-xl"></i>
                        </div>
                    </div>
                    <button onclick="abrirModalTasa()" class="btn-primary text-white px-3 py-2 rounded-lg text-sm mt-4 w-full">
                        <i class="fas fa-edit mr-2"></i>Actualizar Tasa
                    </button>
                </div>

                <!-- Total Atletas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Total Atletas</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-atletas">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-700 shadow-lg">
                            <i class="fas fa-running text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                        <span>Registrados en el sistema</span>
                    </div>
                </div>

                <!-- Pagos del Mes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Pagos del Mes</p>
                            <p class="text-2xl font-bold text-gray-900" id="pagos-mes">0</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-700 shadow-lg">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i class="fas fa-calendar-check mr-2 text-purple-500"></i>
                        <span>Transacciones procesadas</span>
                    </div>
                </div>

                <!-- Ingresos del Mes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Ingresos del Mes</p>
                            <p class="text-lg font-bold text-gray-900" id="ingresos-mes">$0 / Bs. 0</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-700 shadow-lg">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <i class="fas fa-trending-up mr-2 text-purple-500"></i>
                        <span>Divisas y Bolívares</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atletas Section -->
        <div id="atletas-section" class="section hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Gestión de Atletas</h2>
                        <button onclick="abrirModalAtleta()" class="btn-primary text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Registrar Atleta
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Género</label>
                                <select id="filtro-genero-atletas" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarAtletas()">
                                    <option value="">Todos</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Disciplina</label>
                                <select id="filtro-disciplina-atletas" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarAtletas()">
                                    <option value="">Todas</option>
                                    <option value="Voleibol">Voleibol</option>
                                    <option value="Kickingball">Kickingball</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cédula</label>
                                <input type="text" id="filtro-cedula-atletas" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="Buscar por cédula..." onkeyup="filtrarAtletas()">
                            </div>
                            <div class="flex items-end">
                                <button onclick="limpiarFiltrosAtletas()" class="w-full px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    <i class="fas fa-times mr-2"></i>Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Atleta</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cédula</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Registro</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-atletas" class="bg-white divide-y divide-gray-100">
                                <!-- Los atletas se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagos Section -->
        <div id="pagos-section" class="section hidden">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Gestión de Pagos</h2>
                        <button onclick="abrirModalPago()" class="btn-secondary text-white px-6 py-3 rounded-lg font-medium">
                            <i class="fas fa-plus mr-2"></i>Registrar Pago
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Filtros para pagos -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Filtros de Pagos</h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo de Pago</label>
                                <select id="filtro-tipo-pago" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagos()">
                                    <option value="">Todos</option>
                                    <option value="Inscripción">Inscripción</option>
                                    <option value="Mensualidad">Mensualidad</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Método de Pago</label>
                                <select id="filtro-metodo-pago" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagos()">
                                    <option value="">Todos</option>
                                    <option value="Divisa">Divisa ($)</option>
                                    <option value="Bolivares">Bolívares (Bs.)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Desde</label>
                                <input type="date" id="filtro-fecha-desde" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagos()">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Hasta</label>
                                <input type="date" id="filtro-fecha-hasta" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagos()">
                            </div>
                            <div class="flex items-end">
                                <button onclick="limpiarFiltrosPagos()" class="w-full px-3 py-2 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                    <i class="fas fa-times mr-1"></i>Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Atleta</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Método</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Monto</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mes</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-pagos" class="bg-white divide-y divide-gray-100">
                                <!-- Los pagos se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes Section -->
        <div id="reportes-section" class="section hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Reporte Mensual -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Reporte Mensual</h3>
                    <div class="flex space-x-4 mb-6">
                        <select id="mes-reporte" class="border border-gray-200 rounded-lg px-4 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <select id="año-reporte" class="border border-gray-200 rounded-lg px-4 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <button onclick="generarReporte()" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            Generar
                        </button>
                    </div>
                    <div id="reporte-mensual" class="space-y-4">
                        <!-- El reporte se mostrará aquí -->
                    </div>
                </div>

                <!-- Reporte por Disciplina -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Ingresos por Disciplina</h3>
                    <div id="reporte-disciplina" class="space-y-4">
                        <!-- El reporte por disciplina se mostrará aquí -->
                    </div>
                </div>

                <!-- Gráfico de Ingresos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Ingresos por Mes</h3>
                    <div id="grafico-ingresos" class="h-64 flex items-center justify-center text-gray-500 bg-gray-50 rounded-lg">
                        Seleccione un año para ver el gráfico
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tasa del Dólar -->
    <div id="modal-tasa" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 w-96 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Actualizar Tasa del Dólar</h3>
                <button onclick="cerrarModalTasa()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="form-tasa">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Nueva Tasa (Bs.)</label>
                    <input type="number" step="0.01" id="nueva-tasa" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="cerrarModalTasa()" class="px-6 py-3 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Atleta -->
    <div id="modal-atleta" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 w-[500px] max-h-screen overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Nuevo Atleta</h3>
                <button onclick="cerrarModalAtleta()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="form-atleta">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
                        <input type="text" id="nombre-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido</label>
                        <input type="text" id="apellido-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cédula</label>
                    <input type="text" id="cedula-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <input type="date" id="fecha-nacimiento" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Género</label>
                        <select id="genero-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Disciplina</label>
                    <select id="disciplina-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                        <option value="">Seleccionar disciplina...</option>
                        <option value="Voleibol">Voleibol</option>
                        <option value="Kickingball">Kickingball</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="text" id="telefono-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                    <textarea id="direccion-atleta" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="cerrarModalAtleta()" class="px-6 py-3 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pago -->
    <div id="modal-pago" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 w-[500px] shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Registrar Pago</h3>
                <button onclick="cerrarModalPago()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="form-pago">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Atleta</label>
                    <select id="atleta-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                        <option value="">Seleccionar atleta...</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tipo de Pago</label>
                        <select id="tipo-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                            <option value="Inscripción">Inscripción</option>
                            <option value="Mensualidad">Mensualidad</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Método de Pago</label>
                        <select id="metodo-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" onchange="calcularMonto()" required>
                            <option value="Divisa">Divisa ($)</option>
                            <option value="Bolivares">Bolívares (Bs.)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Monto</label>
                    <input type="number" step="0.01" id="monto-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" onchange="calcularMonto()" required>
                    <div id="conversion-info" class="text-sm text-gray-600 mt-2"></div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Pago</label>
                        <input type="date" id="fecha-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mes de Pago</label>
                        <input type="text" id="mes-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" placeholder="Ej: Enero 2025">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Referencia</label>
                    <input type="text" id="referencia-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" placeholder="Últimos 4 dígitos" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Observaciones</label>
                    <textarea id="observaciones-pago" class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="cerrarModalPago()" class="px-6 py-3 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-secondary text-white px-6 py-3 rounded-lg font-medium">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pagos del Atleta -->
    <div id="modal-pagos-atleta" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 w-[1000px] max-h-[80vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Pagos de <span id="nombre-atleta-pagos"></span></h3>
                <button onclick="cerrarModalPagosAtleta()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="mb-6">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-4 rounded-lg border border-purple-100">
                        <div class="text-sm font-semibold text-purple-700">Total Divisas</div>
                        <div id="total-divisas-atleta" class="text-xl font-bold text-purple-800">$0.00</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg border border-blue-100">
                        <div class="text-sm font-semibold text-blue-700">Total Bolívares</div>
                        <div id="total-bolivares-atleta" class="text-xl font-bold text-blue-800">Bs. 0.00</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-100">
                        <div class="text-sm font-semibold text-green-700">Total Pagos</div>
                        <div id="total-pagos-atleta" class="text-xl font-bold text-green-800">0</div>
                    </div>
                </div>
            </div>

            <!-- Filtros para pagos del atleta -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Filtros de Pagos</h4>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo de Pago</label>
                        <select id="filtro-tipo-pago-atleta" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagosAtleta()">
                            <option value="">Todos</option>
                            <option value="Inscripción">Inscripción</option>
                            <option value="Mensualidad">Mensualidad</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Método de Pago</label>
                        <select id="filtro-metodo-pago-atleta" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagosAtleta()">
                            <option value="">Todos</option>
                            <option value="Divisa">Divisa ($)</option>
                            <option value="Bolivares">Bolívares (Bs.)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Desde</label>
                        <input type="date" id="filtro-fecha-desde-atleta" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagosAtleta()">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Hasta</label>
                        <input type="date" id="filtro-fecha-hasta-atleta" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" onchange="filtrarPagosAtleta()">
                    </div>
                    <div class="flex items-end">
                        <button onclick="limpiarFiltrosPagosAtleta()" class="w-full px-3 py-2 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            <i class="fas fa-times mr-1"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Atleta</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Método</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Monto</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mes</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Referencia</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-pagos-atleta" class="bg-white divide-y divide-gray-100">
                        <!-- Los pagos del atleta se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="cerrarModalPagosAtleta()" class="px-6 py-3 text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Recibo -->
    <div id="modal-recibo" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl p-8 w-[600px] max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Recibo de Pago</h3>
                <div class="flex space-x-2">
                    <button onclick="descargarReciboPNG()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-download mr-2"></i>PNG
                    </button>
                    <button onclick="descargarReciboJPG()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-download mr-2"></i>JPG
                    </button>
                    <button onclick="imprimirRecibo()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i class="fas fa-print mr-2"></i>Imprimir
                    </button>
                    <button onclick="cerrarModalRecibo()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <div id="contenido-recibo" class="bg-white border-2 border-gray-200 rounded-lg p-6" style="min-width: 500px; font-family: Arial, sans-serif;">
                <!-- Encabezado del recibo -->
                <div class="text-center mb-6 border-b-2 border-gray-300 pb-4">
                    <div class="flex items-center justify-center mb-2">
                        <img src="assets/image/1731114751966-removebg-preview.png" alt="Logo" class="h-12 w-auto mr-3">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Estrellas Sport Club</h1>
                            <p class="text-sm text-gray-600">Sistema de Gestión</p>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-gray-700">RECIBO DE PAGO</div>
                    <div class="text-sm text-gray-500">Fecha: <span id="fecha-recibo"></span></div>
                </div>

                <!-- Información del atleta -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b border-gray-200 pb-2">Información del Atleta</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-700">Nombre:</span>
                            <span id="nombre-atleta-recibo" class="ml-2"></span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Cédula:</span>
                            <span id="cedula-atleta-recibo" class="ml-2"></span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Disciplina:</span>
                            <span id="disciplina-atleta-recibo" class="ml-2"></span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700">Teléfono:</span>
                            <span id="telefono-atleta-recibo" class="ml-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Detalles del pago -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b border-gray-200 pb-2">Detalles del Pago</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Tipo de Pago:</span>
                            <span id="tipo-pago-recibo" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Método de Pago:</span>
                            <span id="metodo-pago-recibo" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Monto:</span>
                            <span id="monto-recibo" class="font-bold text-lg"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Fecha de Pago:</span>
                            <span id="fecha-pago-recibo" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Mes de Pago:</span>
                            <span id="mes-pago-recibo" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Referencia:</span>
                            <span id="referencia-recibo" class="font-medium"></span>
                        </div>
                        <div class="flex justify-between items-start py-2 border-b border-gray-100">
                            <span class="font-semibold text-gray-700">Observaciones:</span>
                            <span id="observaciones-recibo" class="font-medium text-right max-w-xs"></span>
                        </div>
                    </div>
                </div>

                <!-- Conversión de moneda -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-700 mb-2">Conversión de Moneda</h4>
                    <div class="text-sm text-gray-600">
                        <div>Tasa del Dólar: <span id="tasa-recibo" class="font-medium"></span></div>
                        <div id="conversion-recibo" class="mt-1"></div>
                    </div>
                </div>

                <!-- Pie del recibo -->
                <div class="text-center pt-6 border-t-2 border-gray-300">
                    <div class="text-sm text-gray-600 mb-4">
                        <p>Este recibo es un comprobante oficial de pago</p>
                        <p>Estrellas Sport Club - Sistema de Gestión</p>
                    </div>
                    <div class="text-xs text-gray-500">
                        <p>Recibo generado el: <span id="fecha-generacion-recibo"></span></p>
                        <p>ID de Transacción: <span id="id-transaccion-recibo"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        // Función para manejar el menú desplegable del usuario
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Función para manejar el menú móvil dinámico
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileIcon = document.getElementById('mobile-menu-icon');
            const mobileButton = document.getElementById('mobile-menu-button');
            
            if (mobileMenu.classList.contains('hidden')) {
                // Abrir menú
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('show');
                mobileIcon.className = 'fas fa-times text-xl';
                mobileButton.setAttribute('aria-expanded', 'true');
                mobileButton.setAttribute('aria-label', 'Cerrar menú de navegación');
            } else {
                // Cerrar menú
                mobileMenu.classList.remove('show');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
                mobileIcon.className = 'fas fa-bars text-xl';
                mobileButton.setAttribute('aria-expanded', 'false');
                mobileButton.setAttribute('aria-label', 'Abrir menú de navegación');
            }
        }

        // Cerrar el menú cuando se hace clic fuera de él
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
            
            if (!userButton && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Cerrar menú móvil al hacer clic en un enlace
        document.addEventListener('DOMContentLoaded', function() {
            const mobileLinks = document.querySelectorAll('.nav-link-mobile');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const mobileMenu = document.getElementById('mobile-menu');
                    const mobileIcon = document.getElementById('mobile-menu-icon');
                    const mobileButton = document.getElementById('mobile-menu-button');
                    
                    mobileMenu.classList.remove('show');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                    mobileIcon.className = 'fas fa-bars text-xl';
                    mobileButton.setAttribute('aria-expanded', 'false');
                    mobileButton.setAttribute('aria-label', 'Abrir menú de navegación');
                });
            });
        });

        // Cerrar menú móvil al redimensionar la ventana
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1280) { // xl breakpoint
                const mobileMenu = document.getElementById('mobile-menu');
                const mobileIcon = document.getElementById('mobile-menu-icon');
                const mobileButton = document.getElementById('mobile-menu-button');
                
                mobileMenu.classList.remove('show');
                mobileMenu.classList.add('hidden');
                mobileIcon.className = 'fas fa-bars text-xl';
                mobileButton.setAttribute('aria-expanded', 'false');
                mobileButton.setAttribute('aria-label', 'Abrir menú de navegación');
            }
        });

        // Detectar cambios de tamaño de pantalla dinámicamente
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Ajustar navbar según el tamaño de pantalla
                adjustNavbarForScreenSize();
            }, 250);
        });

        function adjustNavbarForScreenSize() {
            const width = window.innerWidth;
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileIcon = document.getElementById('mobile-menu-icon');
            const mobileButton = document.getElementById('mobile-menu-button');
            
            if (width >= 1280) {
                // Pantalla grande - cerrar menú móvil
                mobileMenu.classList.remove('show');
                mobileMenu.classList.add('hidden');
                mobileIcon.className = 'fas fa-bars text-xl';
                mobileButton.setAttribute('aria-expanded', 'false');
            }
        }

        // Función para confirmar cierre de sesión
        function confirmarCerrarSesion() {
            // Cerrar menús desplegables
            const userMenu = document.getElementById('user-menu');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (userMenu) userMenu.classList.add('hidden');
            if (mobileMenu) {
                mobileMenu.classList.remove('show');
                mobileMenu.classList.add('hidden');
            }

            // Mostrar confirmación con SweetAlert2
            Swal.fire({
                title: '¿Cerrar Sesión?',
                text: '¿Estás seguro de que deseas cerrar tu sesión?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, Cerrar Sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Cerrando sesión...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Limpiar almacenamiento del navegador
                    try {
                        localStorage.clear();
                        sessionStorage.clear();
                    } catch (e) {
                        console.log('Error limpiando almacenamiento:', e);
                    }
                    
                    // Forzar cierre de sesión con múltiples métodos
                    setTimeout(() => {
                        // Método 1: Usar force_logout.php
                        window.location.replace('force_logout.php?t=' + Date.now());
                        
                        // Método 2: Si el método 1 falla, intentar con logout.php
                        setTimeout(() => {
                            window.location.replace('logout.php?force=1&t=' + Date.now());
                        }, 2000);
                    }, 500);
                }
            });
        }
    </script>
</body>
</html>