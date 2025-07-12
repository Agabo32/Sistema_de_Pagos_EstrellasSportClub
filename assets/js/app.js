// Variables globales
let tasaActual = 0;
let atletas = [];
let pagos = [];
let atletaEditando = null;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    inicializarApp();
    configurarNavegacion();
    configurarFormularios();
    cargarDatos();
});

function inicializarApp() {
    // Establecer fecha actual en el formulario de pago
    document.getElementById('fecha-pago').value = new Date().toISOString().split('T')[0];
    
    // Establecer mes y año actual en reportes
    const ahora = new Date();
    document.getElementById('mes-reporte').value = ahora.getMonth() + 1;
    document.getElementById('año-reporte').value = ahora.getFullYear();
}

function configurarNavegacion() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href').substring(1);
            mostrarSeccion(target);
        });
    });
}

function mostrarSeccion(seccion) {
    // Ocultar todas las secciones
    const secciones = document.querySelectorAll('.section');
    secciones.forEach(s => s.classList.add('hidden'));
    
    // Mostrar la sección seleccionada
    const seccionTarget = document.getElementById(seccion + '-section');
    if (seccionTarget) {
        seccionTarget.classList.remove('hidden');
    }
    
    // Cargar datos específicos de la sección
    switch(seccion) {
        case 'dashboard':
            cargarDashboard();
            break;
        case 'atletas':
            cargarAtletas();
            break;
        case 'pagos':
            cargarPagos();
            break;
        case 'reportes':
            cargarReportes();
            break;
    }
}

function configurarFormularios() {
    // Formulario de tasa
    document.getElementById('form-tasa').addEventListener('submit', function(e) {
        e.preventDefault();
        actualizarTasa();
    });
    
    // Formulario de atleta
    document.getElementById('form-atleta').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarAtleta();
    });
    
    // Formulario de pago
    document.getElementById('form-pago').addEventListener('submit', function(e) {
        e.preventDefault();
        guardarPago();
    });
}

// Funciones de carga de datos
async function cargarDatos() {
    await cargarTasaActual();
    await cargarAtletas();
    await cargarPagos();
    cargarDashboard();
}

async function cargarTasaActual() {
    try {
        const response = await fetch('views/tasa.php');
        const data = await response.json();
        if (data.success) {
            tasaActual = parseFloat(data.tasa.tasa);
            document.getElementById('tasa-actual').textContent = `Bs. ${tasaActual.toFixed(2)}`;
        }
    } catch (error) {
        console.error('Error al cargar la tasa:', error);
        tasaActual = 36.50; // Valor por defecto
        document.getElementById('tasa-actual').textContent = `Bs. ${tasaActual.toFixed(2)}`;
    }
}

async function cargarAtletas() {
    try {
        const response = await fetch('views/atletas.php');
        const data = await response.json();
        
        if (data.success) {
            atletas = data.atletas;
            mostrarAtletas();
            cargarSelectAtletas();
        } else {
            console.error('Error en respuesta:', data.message);
            mostrarNotificacion('Error al cargar atletas: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error al cargar atletas:', error);
        mostrarNotificacion('Error de conexión al cargar atletas', 'error');
    }
}

async function cargarPagos() {
    try {
        const response = await fetch('views/pagos.php');
        const data = await response.json();
        if (data.success) {
            pagos = data.pagos;
            mostrarPagos();
        }
    } catch (error) {
        console.error('Error al cargar pagos:', error);
    }
}

function cargarDashboard() {
    // Total atletas
    document.getElementById('total-atletas').textContent = atletas.length;
    
    // Pagos del mes actual
    const mesActual = new Date().getMonth() + 1;
    const añoActual = new Date().getFullYear();
    const pagosMes = pagos.filter(pago => {
        const fechaPago = new Date(pago.fecha_pago);
        return fechaPago.getMonth() + 1 === mesActual && fechaPago.getFullYear() === añoActual;
    });
    
    document.getElementById('pagos-mes').textContent = pagosMes.length;
    
    // Ingresos del mes
    let totalDivisas = 0;
    let totalBolivares = 0;
    
    pagosMes.forEach(pago => {
        if (pago.metodo_pago === 'Divisa') {
            totalDivisas += parseFloat(pago.monto);
        } else {
            totalBolivares += parseFloat(pago.monto);
        }
    });
    
    document.getElementById('ingresos-mes').textContent = 
        `$${totalDivisas.toFixed(2)} / Bs. ${totalBolivares.toFixed(2)}`;
}

function mostrarAtletas() {
    const tbody = document.getElementById('tabla-atletas');
    tbody.innerHTML = '';
    
    atletas.forEach(atleta => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition-colors';
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">${atleta.nombre} ${atleta.apellido}</div>
                <div class="text-sm text-gray-500">${atleta.disciplina || 'N/A'} - ${atleta.genero === 'M' ? 'Masculino' : 'Femenino'}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${atleta.cedula}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${atleta.telefono || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatearFecha(atleta.fecha_registro)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editarAtleta(${atleta.id_atleta})" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="eliminarAtleta(${atleta.id_atleta})" class="text-red-600 hover:text-red-900 transition-colors" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function mostrarPagos() {
    const tbody = document.getElementById('tabla-pagos');
    tbody.innerHTML = '';
    
    pagos.forEach(pago => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition-colors';
        const metodoBadge = pago.metodo_pago === 'Divisa' ? 
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Divisa</span>' :
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Bolívares</span>';
        
        const tipoBadge = pago.tipo_pago === 'Inscripción' ?
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">Inscripción</span>' :
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">Mensualidad</span>';
        
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${pago.nombre} ${pago.apellido}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${tipoBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">${metodoBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${pago.metodo_pago === 'Divisa' ? '$' : 'Bs. '}${parseFloat(pago.monto).toFixed(2)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatearFecha(pago.fecha_pago)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pago.mes_pago || 'N/A'}</td>
        `;
        tbody.appendChild(tr);
    });
}

function cargarSelectAtletas() {
    const select = document.getElementById('atleta-pago');
    select.innerHTML = '<option value="">Seleccionar atleta...</option>';
    
    atletas.forEach(atleta => {
        const option = document.createElement('option');
        option.value = atleta.id_atleta;
        option.textContent = `${atleta.nombre} ${atleta.apellido} - ${atleta.cedula}`;
        select.appendChild(option);
    });
}

// Funciones de modales
function abrirModalTasa() {
    document.getElementById('modal-tasa').classList.remove('hidden');
    document.getElementById('modal-tasa').classList.add('flex');
    document.getElementById('nueva-tasa').value = tasaActual;
}

function cerrarModalTasa() {
    document.getElementById('modal-tasa').classList.add('hidden');
    document.getElementById('modal-tasa').classList.remove('flex');
}

function abrirModalAtleta() {
    atletaEditando = null;
    document.getElementById('modal-atleta').classList.remove('hidden');
    document.getElementById('modal-atleta').classList.add('flex');
    limpiarFormularioAtleta();
    // Cambiar título del modal
    document.querySelector('#modal-atleta h3').textContent = 'Nuevo Atleta';
}

function cerrarModalAtleta() {
    document.getElementById('modal-atleta').classList.add('hidden');
    document.getElementById('modal-atleta').classList.remove('flex');
    atletaEditando = null;
}

function abrirModalPago() {
    document.getElementById('modal-pago').classList.remove('hidden');
    document.getElementById('modal-pago').classList.add('flex');
    limpiarFormularioPago();
}

function cerrarModalPago() {
    document.getElementById('modal-pago').classList.add('hidden');
    document.getElementById('modal-pago').classList.remove('flex');
}

// Funciones de guardado
async function actualizarTasa() {
    const nuevaTasa = document.getElementById('nueva-tasa').value;
    
    if (!nuevaTasa || nuevaTasa <= 0) {
        mostrarNotificacion('Por favor ingrese una tasa válida', 'error');
        return;
    }
    
    try {
        const response = await fetch('views/tasa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tasa: nuevaTasa })
        });
        
        const data = await response.json();
        if (data.success) {
            tasaActual = parseFloat(nuevaTasa);
            document.getElementById('tasa-actual').textContent = `Bs. ${tasaActual.toFixed(2)}`;
            cerrarModalTasa();
            mostrarNotificacion('Tasa actualizada correctamente', 'success');
        } else {
            mostrarNotificacion('Error al actualizar la tasa: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión al actualizar la tasa', 'error');
    }
}

async function guardarAtleta() {
    // Validar campos requeridos
    const nombre = document.getElementById('nombre-atleta').value.trim();
    const apellido = document.getElementById('apellido-atleta').value.trim();
    const cedula = document.getElementById('cedula-atleta').value.trim();
    const disciplina = document.getElementById('disciplina-atleta').value;
    
    if (!nombre || !apellido || !cedula || !disciplina) {
        mostrarNotificacion('Por favor complete todos los campos requeridos (Nombre, Apellido, Cédula y Disciplina)', 'error');
        return;
    }
    
    const datos = {
        nombre: nombre,
        apellido: apellido,
        cedula: cedula,
        fecha_nacimiento: document.getElementById('fecha-nacimiento').value || '',
        genero: document.getElementById('genero-atleta').value,
        telefono: document.getElementById('telefono-atleta').value.trim() || '',
        direccion: document.getElementById('direccion-atleta').value.trim() || '',
        disciplina: disciplina
    };
    
    try {
        let url = 'views/atletas.php';
        let method = 'POST';
        
        if (atletaEditando) {
            datos.id_atleta = atletaEditando;
            method = 'PUT';
        }
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datos)
        });
        
        const data = await response.json();
        
        if (data.success) {
            await cargarAtletas();
            cargarDashboard();
            cerrarModalAtleta();
            mostrarNotificacion(
                atletaEditando ? 'Atleta actualizado correctamente' : 'Atleta guardado correctamente', 
                'success'
            );
        } else {
            mostrarNotificacion('Error al guardar el atleta: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión al guardar el atleta', 'error');
    }
}

async function guardarPago() {
    // Validar campos requeridos
    const idAtleta = document.getElementById('atleta-pago').value;
    const tipoPago = document.getElementById('tipo-pago').value;
    const metodoPago = document.getElementById('metodo-pago').value;
    const monto = document.getElementById('monto-pago').value;
    const fechaPago = document.getElementById('fecha-pago').value;
    
    if (!idAtleta || !tipoPago || !metodoPago || !monto || !fechaPago) {
        mostrarNotificacion('Por favor complete todos los campos requeridos', 'error');
        return;
    }
    
    if (parseFloat(monto) <= 0) {
        mostrarNotificacion('El monto debe ser mayor a 0', 'error');
        return;
    }
    
    const datos = {
        id_atleta: idAtleta,
        tipo_pago: tipoPago,
        metodo_pago: metodoPago,
        monto: parseFloat(monto),
        fecha_pago: fechaPago,
        mes_pago: document.getElementById('mes-pago').value.trim() || '',
        observaciones: document.getElementById('observaciones-pago').value.trim() || '',
        referencia: document.getElementById('referencia-pago').value.trim() || ''
    };
    
    try {
        const response = await fetch('views/pagos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(datos)
        });
        
        const data = await response.json();
        if (data.success) {
            await cargarPagos();
            cargarDashboard();
            cerrarModalPago();
            mostrarNotificacion('Pago registrado correctamente', 'success');
        } else {
            mostrarNotificacion('Error al registrar el pago: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión al registrar el pago', 'error');
    }
}

// Funciones de edición y eliminación
function editarAtleta(id) {
    const atleta = atletas.find(a => a.id_atleta == id);
    if (!atleta) {
        mostrarNotificacion('Atleta no encontrado', 'error');
        return;
    }
    
    atletaEditando = id;
    
    // Llenar el formulario con los datos del atleta
    document.getElementById('nombre-atleta').value = atleta.nombre || '';
    document.getElementById('apellido-atleta').value = atleta.apellido || '';
    document.getElementById('cedula-atleta').value = atleta.cedula || '';
    document.getElementById('disciplina-atleta').value = atleta.disciplina || '';
    document.getElementById('fecha-nacimiento').value = atleta.fecha_nacimiento || '';
    document.getElementById('genero-atleta').value = atleta.genero || 'M';
    document.getElementById('telefono-atleta').value = atleta.telefono || '';
    document.getElementById('direccion-atleta').value = atleta.direccion || '';
    
    // Cambiar el título del modal
    document.querySelector('#modal-atleta h3').textContent = 'Editar Atleta';
    
    // Abrir el modal
    document.getElementById('modal-atleta').classList.remove('hidden');
    document.getElementById('modal-atleta').classList.add('flex');
}

async function eliminarAtleta(id) {
    if (!confirm('¿Está seguro de eliminar este atleta? Esta acción no se puede deshacer.')) {
        return;
    }
    
    try {
        const response = await fetch('views/atletas.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_atleta: id })
        });
        
        const data = await response.json();
        if (data.success) {
            await cargarAtletas();
            cargarDashboard();
            mostrarNotificacion('Atleta eliminado correctamente', 'success');
        } else {
            mostrarNotificacion('Error al eliminar el atleta: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión al eliminar el atleta', 'error');
    }
}

// Funciones de cálculo
function calcularMonto() {
    const metodo = document.getElementById('metodo-pago').value;
    const monto = parseFloat(document.getElementById('monto-pago').value) || 0;
    const infoDiv = document.getElementById('conversion-info');
    
    if (monto > 0 && tasaActual > 0) {
        if (metodo === 'Divisa') {
            const bolivares = monto * tasaActual;
            infoDiv.textContent = `Equivale a: Bs. ${bolivares.toFixed(2)}`;
        } else if (metodo === 'Bolivares') {
            const divisas = monto / tasaActual;
            infoDiv.textContent = `Equivale a: $${divisas.toFixed(2)}`;
        }
    } else {
        infoDiv.textContent = '';
    }
}

// Funciones de reportes
async function generarReporte() {
    const mes = document.getElementById('mes-reporte').value;
    const año = document.getElementById('año-reporte').value;
    
    try {
        const response = await fetch(`views/reportes.php?mes=${mes}&año=${año}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        
        if (data.success) {
            mostrarReporteMensual(data.reporte, mes, año);
            mostrarReporteDisciplina(data.reporte_disciplina, mes, año);
        } else {
            console.error('Error en respuesta del servidor:', data.message);
            mostrarNotificacion('Error al generar reporte: ' + (data.message || 'Error desconocido'), 'error');
        }
    } catch (error) {
        console.error('Error al generar reporte:', error);
        mostrarNotificacion('Error de conexión al generar reporte', 'error');
    }
}

function mostrarReporteMensual(reporte, mes, año) {
    const container = document.getElementById('reporte-mensual');
    const nombreMes = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][mes];
    
    let html = `<h4 class="font-semibold text-lg mb-3">${nombreMes} ${año}</h4>`;
    
    if (reporte.length === 0) {
        html += '<p class="text-gray-500">No hay pagos registrados para este mes.</p>';
    } else {
        reporte.forEach(item => {
            html += `
                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                    <h5 class="font-medium text-gray-800">${item.tipo_pago}</h5>
                    <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                        <div>
                            <span class="text-gray-600">Divisas:</span>
                            <span class="font-medium">$${parseFloat(item.total_divisas || 0).toFixed(2)}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Bolívares:</span>
                            <span class="font-medium">Bs. ${parseFloat(item.total_bolivares || 0).toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="mt-1 text-sm text-gray-600">
                        Total de pagos: ${item.total_pagos}
                    </div>
                </div>
            `;
        });
    }
    
    container.innerHTML = html;
}

function mostrarReporteDisciplina(reporte, mes, año) {
    const container = document.getElementById('reporte-disciplina');
    const nombreMes = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][mes];

    let html = `<h4 class="font-semibold text-lg mb-3">Ingresos por Disciplina - ${nombreMes} ${año}</h4>`;

    if (!reporte || reporte.length === 0) {
        html += '<p class="text-gray-500">No hay ingresos registrados por disciplina para este mes.</p>';
    } else {
        reporte.forEach(item => {
            html += `
                <div class="bg-gray-50 rounded-lg p-4 mb-3">
                    <h5 class="font-medium text-gray-800">${item.disciplina}</h5>
                    <div class="grid grid-cols-2 gap-4 mt-2 text-sm">
                        <div>
                            <span class="text-gray-600">Divisas:</span>
                            <span class="font-medium">$${parseFloat(item.total_divisas || 0).toFixed(2)}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Bolívares:</span>
                            <span class="font-medium">Bs. ${parseFloat(item.total_bolivares || 0).toFixed(2)}</span>
                        </div>
                    </div>
                    <div class="mt-1 text-sm text-gray-600">
                        Total de pagos: ${item.total_pagos}
                    </div>
                </div>
            `;
        });
    }

    container.innerHTML = html;
}

function cargarReportes() {
    try {
        // Generar automáticamente el reporte del mes actual
        const mesActual = new Date().getMonth() + 1;
        const añoActual = new Date().getFullYear();
        
        // Establecer los valores en los selects
        document.getElementById('mes-reporte').value = mesActual;
        document.getElementById('año-reporte').value = añoActual;
        
        // Generar el reporte automáticamente
        generarReporte().catch(error => {
            console.error('Error en cargarReportes:', error);
        });
    } catch (error) {
        console.error('Error al cargar reportes:', error);
    }
}

// Funciones auxiliares
function formatearFecha(fecha) {
    if (!fecha) return 'N/A';
    return new Date(fecha).toLocaleDateString('es-ES');
}

function limpiarFormularioAtleta() {
    document.getElementById('form-atleta').reset();
    document.getElementById('genero-atleta').value = 'M';
}

function limpiarFormularioPago() {
    document.getElementById('form-pago').reset();
    document.getElementById('fecha-pago').value = new Date().toISOString().split('T')[0];
    document.getElementById('conversion-info').textContent = '';
}

function mostrarNotificacion(mensaje, tipo) {
    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    
    notificacion.innerHTML = `
        <div class="flex items-center justify-between">
            <span>${mensaje}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notificacion);
    
    // Remover después de 5 segundos
    setTimeout(() => {
        if (notificacion.parentElement) {
            notificacion.remove();
        }
    }, 5000);
}