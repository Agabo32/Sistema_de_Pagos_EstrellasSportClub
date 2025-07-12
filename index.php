<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrellas Sport Club - Sistema de Pagos</title>
    <!-- Quitar el CDN de Tailwind y usar el archivo local -->
    <link href="assets/css/tailwind.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-star text-yellow-400"></i>
                <h1 class="text-xl font-bold">Estrellas Sport Club</h1>
            </div>
            <div class="flex space-x-4" id="navbar-links">
                <a href="#dashboard" class="nav-link hover:text-blue-200 transition-colors">Dashboard</a>
                <a href="#atletas" class="nav-link hover:text-blue-200 transition-colors">Atletas</a>
                <a href="#pagos" class="nav-link hover:text-blue-200 transition-colors">Pagos</a>
                <a href="#reportes" class="nav-link hover:text-blue-200 transition-colors">Reportes</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <!-- Dashboard Section -->
        <div id="dashboard-section" class="section">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Tasa del Dólar Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tasa del Dólar</p>
                            <p class="text-2xl font-bold text-green-600" id="tasa-actual">$0.00</p>
                        </div>
                        <button onclick="abrirModalTasa()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>

                <!-- Total Atletas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Atletas</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-atletas">0</p>
                        </div>
                    </div>
                </div>

                <!-- Pagos del Mes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pagos del Mes</p>
                            <p class="text-2xl font-bold text-gray-900" id="pagos-mes">0</p>
                        </div>
                    </div>
                </div>

                <!-- Ingresos del Mes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Ingresos del Mes</p>
                            <p class="text-lg font-bold text-gray-900" id="ingresos-mes">$0 / Bs. 0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atletas Section -->
        <div id="atletas-section" class="section hidden">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Gestión de Atletas</h2>
                        <button onclick="abrirModalAtleta()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Nuevo Atleta
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-atletas" class="bg-white divide-y divide-gray-200">
                                <!-- Los atletas se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagos Section -->
        <div id="pagos-section" class="section hidden">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Gestión de Pagos</h2>
                        <button onclick="abrirModalPago()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i>Registrar Pago
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atleta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mes</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-pagos" class="bg-white divide-y divide-gray-200">
                                <!-- Los pagos se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reportes Section -->
        <div id="reportes-section" class="section hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Reporte Mensual -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reporte Mensual</h3>
                    <div class="flex space-x-4 mb-4">
                        <select id="mes-reporte" class="border border-gray-300 rounded-md px-3 py-2">
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
                        <select id="año-reporte" class="border border-gray-300 rounded-md px-3 py-2">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <button onclick="generarReporte()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Generar
                        </button>
                    </div>
                    <div id="reporte-mensual" class="space-y-4">
                        <!-- El reporte se mostrará aquí -->
                    </div>
                </div>

                <!-- Gráfico de Ingresos -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ingresos por Mes</h3>
                    <div id="grafico-ingresos" class="h-64 flex items-center justify-center text-gray-500">
                        Seleccione un año para ver el gráfico
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tasa del Dólar -->
    <div id="modal-tasa" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Actualizar Tasa del Dólar</h3>
                <button onclick="cerrarModalTasa()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-tasa">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Tasa (Bs.)</label>
                    <input type="number" step="0.01" id="nueva-tasa" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalTasa()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Atleta -->
    <div id="modal-atleta" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Nuevo Atleta</h3>
                <button onclick="cerrarModalAtleta()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-atleta">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                        <input type="text" id="nombre-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                        <input type="text" id="apellido-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cédula</label>
                    <input type="text" id="cedula-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <input type="date" id="fecha-nacimiento" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                        <select id="genero-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="text" id="telefono-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                    <textarea id="direccion-atleta" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalAtleta()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pago -->
    <div id="modal-pago" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Registrar Pago</h3>
                <button onclick="cerrarModalPago()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="form-pago">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Atleta</label>
                    <select id="atleta-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                        <option value="">Seleccionar atleta...</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Pago</label>
                        <select id="tipo-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="Inscripción">Inscripción</option>
                            <option value="Mensualidad">Mensualidad</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                        <select id="metodo-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" onchange="calcularMonto()" required>
                            <option value="Divisa">Divisa ($)</option>
                            <option value="Bolivares">Bolívares (Bs.)</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Monto</label>
                    <input type="number" step="0.01" id="monto-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" onchange="calcularMonto()" required>
                    <div id="conversion-info" class="text-sm text-gray-600 mt-1"></div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Pago</label>
                        <input type="date" id="fecha-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mes de Pago</label>
                        <input type="text" id="mes-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Ej: Enero 2025">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea id="observaciones-pago" class="w-full border border-gray-300 rounded-md px-3 py-2" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="cerrarModalPago()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>