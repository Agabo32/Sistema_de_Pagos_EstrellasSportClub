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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="assets/image/1731114751966-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide text-white">Estrellas Sport Club</h1>
                        <p class="text-sm text-white opacity-90">Sistema de Gestión</p>
                    </div>
                </div>
                <div class="flex space-x-2" id="navbar-links">
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

    <!-- Scripts -->
    <script src="assets/js/app.js"></script>
</body>
</html>