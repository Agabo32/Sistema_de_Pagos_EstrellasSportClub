// Variables globales
let tasaActual = 0;
let atletas = [];
let pagos = [];
let atletaEditando = null;
let pagosAtletaCompletos = []; // Variable para almacenar todos los pagos del atleta

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
    
    // Establecer estado activo inicial en el navbar
    actualizarEstadoActivo('dashboard');
    
    // Ajustar navbar según el tamaño de pantalla inicial
    adjustNavbarForScreenSize();
}

function configurarNavegacion() {
    // Configurar enlaces de navegación para desktop y móvil
    const navLinks = document.querySelectorAll('.nav-link, .nav-link-mobile');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href').substring(1);
            mostrarSeccion(target);
            
            // Actualizar estado activo
            actualizarEstadoActivo(target);
        });
    });
}

function actualizarEstadoActivo(seccionActiva) {
    // Remover clase activa de todos los enlaces
    const allNavLinks = document.querySelectorAll('.nav-link, .nav-link-mobile');
    allNavLinks.forEach(link => {
        link.classList.remove('active');
    });
    
    // Agregar clase activa al enlace correspondiente
    const activeLinks = document.querySelectorAll(`[href="#${seccionActiva}"]`);
    activeLinks.forEach(link => {
        link.classList.add('active');
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
                <button onclick="verPagosAtleta(${atleta.id_atleta}, '${atleta.nombre} ${atleta.apellido}')" class="text-purple-600 hover:text-purple-900 mr-3 transition-colors" title="Ver Pagos">
                    <i class="fas fa-money-bill-wave"></i>
                </button>
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
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="generarReciboExistente(${JSON.stringify(pago).replace(/"/g, '&quot;')})" class="text-green-600 hover:text-green-900 transition-colors" title="Generar Recibo">
                    <i class="fas fa-receipt"></i>
                </button>
            </td>
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

// Funciones para el modal de pagos del atleta
async function verPagosAtleta(idAtleta, nombreAtleta) {
    try {
        const response = await fetch(`views/pagos.php?id_atleta=${idAtleta}`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('nombre-atleta-pagos').textContent = nombreAtleta;
            pagosAtletaCompletos = data.pagos; // Almacenar todos los pagos
            mostrarPagosAtleta(pagosAtletaCompletos);
            document.getElementById('modal-pagos-atleta').classList.remove('hidden');
            document.getElementById('modal-pagos-atleta').classList.add('flex');
        } else {
            mostrarNotificacion('Error al cargar los pagos del atleta', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion('Error de conexión al cargar los pagos', 'error');
    }
}

function mostrarPagosAtleta(pagos) {
    const tbody = document.getElementById('tabla-pagos-atleta');
    tbody.innerHTML = '';
    
    let totalDivisas = 0;
    let totalBolivares = 0;
    
    if (pagos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <div>No hay pagos registrados para este atleta</div>
                </td>
            </tr>
        `;
    } else {
        pagos.forEach(pago => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors';
            
            const metodoBadge = pago.metodo_pago === 'Divisa' ? 
                '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Divisa</span>' :
                '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Bolívares</span>';
            
            const tipoBadge = pago.tipo_pago === 'Inscripción' ?
                '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">Inscripción</span>' :
                '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">Mensualidad</span>';
            
            const generoBadge = pago.genero === 'M' ?
                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">M</span>' :
                '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800 border border-pink-200">F</span>';
            
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-900">${pago.nombre} ${pago.apellido}</div>
                    <div class="text-sm text-gray-500">${pago.disciplina} ${generoBadge} - ${pago.cedula}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${tipoBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap">${metodoBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    ${pago.metodo_pago === 'Divisa' ? '$' : 'Bs. '}${parseFloat(pago.monto).toFixed(2)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatearFecha(pago.fecha_pago)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pago.mes_pago || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pago.referencia || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pago.observaciones || 'N/A'}</td>
            `;
            tbody.appendChild(tr);
            
            // Calcular totales
            if (pago.metodo_pago === 'Divisa') {
                totalDivisas += parseFloat(pago.monto);
            } else {
                totalBolivares += parseFloat(pago.monto);
            }
        });
    }
    
    // Actualizar totales
    document.getElementById('total-divisas-atleta').textContent = `$${totalDivisas.toFixed(2)}`;
    document.getElementById('total-bolivares-atleta').textContent = `Bs. ${totalBolivares.toFixed(2)}`;
    document.getElementById('total-pagos-atleta').textContent = pagos.length;
}

function cerrarModalPagosAtleta() {
    document.getElementById('modal-pagos-atleta').classList.add('hidden');
    document.getElementById('modal-pagos-atleta').classList.remove('flex');
}

// Funciones de filtrado para atletas
function filtrarAtletas() {
    const filtroGenero = document.getElementById('filtro-genero-atletas').value;
    const filtroDisciplina = document.getElementById('filtro-disciplina-atletas').value;
    const filtroCedula = document.getElementById('filtro-cedula-atletas').value.toLowerCase();
    
    let atletasFiltrados = atletas.filter(atleta => {
        // Filtro por género
        if (filtroGenero && atleta.genero !== filtroGenero) {
            return false;
        }
        
        // Filtro por disciplina
        if (filtroDisciplina && atleta.disciplina !== filtroDisciplina) {
            return false;
        }
        
        // Filtro por cédula
        if (filtroCedula && !atleta.cedula.toLowerCase().includes(filtroCedula)) {
            return false;
        }
        
        return true;
    });
    
    mostrarAtletasFiltrados(atletasFiltrados);
}

function mostrarAtletasFiltrados(atletasFiltrados) {
    const tbody = document.getElementById('tabla-atletas');
    tbody.innerHTML = '';
    
    atletasFiltrados.forEach(atleta => {
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
                <button onclick="verPagosAtleta(${atleta.id_atleta}, '${atleta.nombre} ${atleta.apellido}')" class="text-purple-600 hover:text-purple-900 mr-3 transition-colors" title="Ver Pagos">
                    <i class="fas fa-money-bill-wave"></i>
                </button>
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

function limpiarFiltrosAtletas() {
    document.getElementById('filtro-genero-atletas').value = '';
    document.getElementById('filtro-disciplina-atletas').value = '';
    document.getElementById('filtro-cedula-atletas').value = '';
    mostrarAtletas();
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
            
            // Generar recibo después de registrar el pago exitosamente
            generarRecibo(datos, data.pago_id);
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
    confirmarAccion('¿Estás seguro de que deseas eliminar este atleta? Esta acción no se puede deshacer.', async () => {
        mostrarLoading('Eliminando atleta...');
        
        try {
            const response = await fetch('views/atletas.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_atleta: id })
            });
            
            const data = await response.json();
            cerrarLoading();
            
            if (data.success) {
                await cargarAtletas();
                cargarDashboard();
                mostrarNotificacion('Atleta eliminado correctamente', 'success');
            } else {
                mostrarNotificacion('Error al eliminar el atleta: ' + (data.message || 'Error desconocido'), 'error');
            }
        } catch (error) {
            cerrarLoading();
            console.error('Error:', error);
            mostrarNotificacion('Error de conexión al eliminar el atleta', 'error');
        }
    });
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

// Función mejorada con SweetAlert2
function mostrarNotificacion(mensaje, tipo) {
    const configuracion = {
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        customClass: {
            popup: 'swal2-toast'
        }
    };

    switch(tipo) {
        case 'success':
            Swal.fire({
                ...configuracion,
                icon: 'success',
                title: mensaje,
                background: '#10b981',
                color: '#ffffff',
                iconColor: '#ffffff'
            });
            break;
        case 'error':
            Swal.fire({
                ...configuracion,
                icon: 'error',
                title: mensaje,
                background: '#ef4444',
                color: '#ffffff',
                iconColor: '#ffffff'
            });
            break;
        case 'warning':
            Swal.fire({
                ...configuracion,
                icon: 'warning',
                title: mensaje,
                background: '#f59e0b',
                color: '#ffffff',
                iconColor: '#ffffff'
            });
            break;
        case 'info':
            Swal.fire({
                ...configuracion,
                icon: 'info',
                title: mensaje,
                background: '#3b82f6',
                color: '#ffffff',
                iconColor: '#ffffff'
            });
            break;
        default:
            Swal.fire({
                ...configuracion,
                icon: 'info',
                title: mensaje,
                background: '#6b7280',
                color: '#ffffff',
                iconColor: '#ffffff'
            });
    }
}

// Función para confirmaciones con SweetAlert2
function confirmarAccion(mensaje, callback, titulo = '¿Estás seguro?') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Función para mostrar loading con SweetAlert2
function mostrarLoading(mensaje = 'Procesando...') {
    Swal.fire({
        title: mensaje,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Función para cerrar loading
function cerrarLoading() {
    Swal.close();
}

// Función para mostrar alertas de éxito con SweetAlert2
function mostrarExito(mensaje, titulo = '¡Éxito!') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'success',
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Entendido',
        customClass: {
            confirmButton: 'swal2-success-btn'
        }
    });
}

// Función para mostrar alertas de error con SweetAlert2
function mostrarError(mensaje, titulo = 'Error') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'error',
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Entendido',
        customClass: {
            confirmButton: 'swal2-error-btn'
        }
    });
}

// Función para mostrar alertas de información con SweetAlert2
function mostrarInfo(mensaje, titulo = 'Información') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'info',
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'Entendido',
        customClass: {
            confirmButton: 'swal2-info-btn'
        }
    });
}

// Función para mostrar alertas de advertencia con SweetAlert2
function mostrarAdvertencia(mensaje, titulo = 'Advertencia') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'warning',
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Entendido',
        customClass: {
            confirmButton: 'swal2-warning-btn'
        }
    });
}

// Función para mostrar alertas de pregunta con SweetAlert2
function mostrarPregunta(mensaje, titulo = 'Confirmación') {
    return Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        reverseButtons: true,
        customClass: {
            confirmButton: 'swal2-question-confirm',
            cancelButton: 'swal2-question-cancel'
        }
    });
}

// Función para mostrar alertas de carga con SweetAlert2
function mostrarCarga(mensaje = 'Cargando...', titulo = 'Por favor espera') {
    Swal.fire({
        title: titulo,
        text: mensaje,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
        customClass: {
            popup: 'swal2-loading'
        }
    });
}

// Función para mostrar alertas de formulario con SweetAlert2
function mostrarFormulario(titulo, html, callback) {
    Swal.fire({
        title: titulo,
        html: html,
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        preConfirm: () => {
            return callback();
        },
        customClass: {
            popup: 'swal2-form',
            confirmButton: 'swal2-form-confirm',
            cancelButton: 'swal2-form-cancel'
        }
    });
}

// Funciones de filtrado para pagos del atleta
function filtrarPagosAtleta() {
    const filtroTipo = document.getElementById('filtro-tipo-pago-atleta').value;
    const filtroMetodo = document.getElementById('filtro-metodo-pago-atleta').value;
    const filtroFechaDesde = document.getElementById('filtro-fecha-desde-atleta').value;
    const filtroFechaHasta = document.getElementById('filtro-fecha-hasta-atleta').value;
    
    let pagosFiltrados = pagosAtletaCompletos.filter(pago => {
        // Filtro por tipo de pago
        if (filtroTipo && pago.tipo_pago !== filtroTipo) {
            return false;
        }
        
        // Filtro por método de pago
        if (filtroMetodo && pago.metodo_pago !== filtroMetodo) {
            return false;
        }
        
        // Filtro por fecha desde
        if (filtroFechaDesde) {
            const fechaPago = new Date(pago.fecha_pago);
            const fechaDesde = new Date(filtroFechaDesde);
            if (fechaPago < fechaDesde) {
                return false;
            }
        }
        
        // Filtro por fecha hasta
        if (filtroFechaHasta) {
            const fechaPago = new Date(pago.fecha_pago);
            const fechaHasta = new Date(filtroFechaHasta);
            fechaHasta.setHours(23, 59, 59, 999); // Incluir todo el día
            if (fechaPago > fechaHasta) {
                return false;
            }
        }
        
        return true;
    });
    
    mostrarPagosAtleta(pagosFiltrados);
}

function limpiarFiltrosPagosAtleta() {
    document.getElementById('filtro-tipo-pago-atleta').value = '';
    document.getElementById('filtro-metodo-pago-atleta').value = '';
    document.getElementById('filtro-fecha-desde-atleta').value = '';
    document.getElementById('filtro-fecha-hasta-atleta').value = '';
    mostrarPagosAtleta(pagosAtletaCompletos);
}

// Funciones de filtrado para la tabla principal de pagos
async function filtrarPagos() {
    const filtroTipo = document.getElementById('filtro-tipo-pago').value;
    const filtroMetodo = document.getElementById('filtro-metodo-pago').value;
    const filtroFechaDesde = document.getElementById('filtro-fecha-desde').value;
    const filtroFechaHasta = document.getElementById('filtro-fecha-hasta').value;
    
    // Construir URL con filtros
    let url = 'views/pagos.php?';
    const params = [];
    
    if (filtroTipo) params.push(`tipo_pago=${encodeURIComponent(filtroTipo)}`);
    if (filtroMetodo) params.push(`metodo_pago=${encodeURIComponent(filtroMetodo)}`);
    if (filtroFechaDesde) params.push(`fecha_desde=${encodeURIComponent(filtroFechaDesde)}`);
    if (filtroFechaHasta) params.push(`fecha_hasta=${encodeURIComponent(filtroFechaHasta)}`);
    
    url += params.join('&');
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.success) {
            mostrarPagosFiltrados(data.pagos);
        } else {
            console.error('Error al filtrar pagos:', data.message);
            mostrarNotificacion('Error al filtrar pagos', 'error');
        }
    } catch (error) {
        console.error('Error de conexión al filtrar pagos:', error);
        mostrarNotificacion('Error de conexión al filtrar pagos', 'error');
    }
}

function mostrarPagosFiltrados(pagosFiltrados) {
    const tbody = document.getElementById('tabla-pagos');
    tbody.innerHTML = '';
    
    if (pagosFiltrados.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                    <div>No se encontraron pagos con los filtros aplicados</div>
                </td>
            </tr>
        `;
        return;
    }
    
    pagosFiltrados.forEach(pago => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition-colors';
        
        const metodoBadge = pago.metodo_pago === 'Divisa' ? 
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Divisa</span>' :
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Bolívares</span>';
        
        const tipoBadge = pago.tipo_pago === 'Inscripción' ?
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">Inscripción</span>' :
            '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">Mensualidad</span>';
        
        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">${pago.nombre} ${pago.apellido}</div>
                <div class="text-sm text-gray-500">${pago.disciplina} - ${pago.cedula}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${tipoBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap">${metodoBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${pago.metodo_pago === 'Divisa' ? '$' : 'Bs. '}${parseFloat(pago.monto).toFixed(2)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatearFecha(pago.fecha_pago)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pago.mes_pago || 'N/A'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="generarReciboExistente(${JSON.stringify(pago).replace(/"/g, '&quot;')})" class="text-green-600 hover:text-green-900 transition-colors" title="Generar Recibo">
                    <i class="fas fa-receipt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function limpiarFiltrosPagos() {
    document.getElementById('filtro-tipo-pago').value = '';
    document.getElementById('filtro-metodo-pago').value = '';
    document.getElementById('filtro-fecha-desde').value = '';
    document.getElementById('filtro-fecha-hasta').value = '';
    
    try {
        const response = await fetch('views/pagos.php');
        const data = await response.json();
        
        if (data.success) {
            pagos = data.pagos; // Actualizar la variable global
            mostrarPagos();
        } else {
            console.error('Error al limpiar filtros:', data.message);
            mostrarNotificacion('Error al limpiar filtros', 'error');
        }
    } catch (error) {
        console.error('Error de conexión al limpiar filtros:', error);
        mostrarNotificacion('Error de conexión al limpiar filtros', 'error');
    }
}

// Funciones para el sistema de recibos
function generarRecibo(datosPago, pagoId) {
    // Buscar información del atleta
    const atleta = atletas.find(a => a.id_atleta == datosPago.id_atleta);
    if (!atleta) {
        mostrarNotificacion('Error: No se encontró información del atleta', 'error');
        return;
    }
    
    // Llenar el recibo con los datos
    document.getElementById('fecha-recibo').textContent = formatearFecha(new Date().toISOString());
    document.getElementById('nombre-atleta-recibo').textContent = `${atleta.nombre} ${atleta.apellido}`;
    document.getElementById('cedula-atleta-recibo').textContent = atleta.cedula;
    document.getElementById('disciplina-atleta-recibo').textContent = atleta.disciplina || 'N/A';
    document.getElementById('telefono-atleta-recibo').textContent = atleta.telefono || 'N/A';
    
    document.getElementById('tipo-pago-recibo').textContent = datosPago.tipo_pago;
    document.getElementById('metodo-pago-recibo').textContent = datosPago.metodo_pago;
    document.getElementById('monto-recibo').textContent = `${datosPago.metodo_pago === 'Divisa' ? '$' : 'Bs. '}${datosPago.monto.toFixed(2)}`;
    document.getElementById('fecha-pago-recibo').textContent = formatearFecha(datosPago.fecha_pago);
    document.getElementById('mes-pago-recibo').textContent = datosPago.mes_pago || 'N/A';
    document.getElementById('referencia-recibo').textContent = datosPago.referencia || 'N/A';
    document.getElementById('observaciones-recibo').textContent = datosPago.observaciones || 'N/A';
    
    // Información de conversión
    document.getElementById('tasa-recibo').textContent = `Bs. ${tasaActual.toFixed(2)}`;
    
    if (datosPago.metodo_pago === 'Divisa') {
        const bolivares = datosPago.monto * tasaActual;
        document.getElementById('conversion-recibo').textContent = `Equivale a: Bs. ${bolivares.toFixed(2)}`;
    } else {
        const divisas = datosPago.monto / tasaActual;
        document.getElementById('conversion-recibo').textContent = `Equivale a: $${divisas.toFixed(2)}`;
    }
    
    // Fecha de generación y ID de transacción
    document.getElementById('fecha-generacion-recibo').textContent = new Date().toLocaleString('es-ES');
    document.getElementById('id-transaccion-recibo').textContent = pagoId || 'N/A';
    
    // Mostrar el modal del recibo
    document.getElementById('modal-recibo').classList.remove('hidden');
    document.getElementById('modal-recibo').classList.add('flex');
}

function generarReciboExistente(pago) {
    // Buscar información del atleta
    const atleta = atletas.find(a => a.id_atleta == pago.id_atleta);
    if (!atleta) {
        mostrarNotificacion('Error: No se encontró información del atleta', 'error');
        return;
    }
    
    // Llenar el recibo con los datos del pago existente
    document.getElementById('fecha-recibo').textContent = formatearFecha(new Date().toISOString());
    document.getElementById('nombre-atleta-recibo').textContent = `${atleta.nombre} ${atleta.apellido}`;
    document.getElementById('cedula-atleta-recibo').textContent = atleta.cedula;
    document.getElementById('disciplina-atleta-recibo').textContent = atleta.disciplina || 'N/A';
    document.getElementById('telefono-atleta-recibo').textContent = atleta.telefono || 'N/A';
    
    document.getElementById('tipo-pago-recibo').textContent = pago.tipo_pago;
    document.getElementById('metodo-pago-recibo').textContent = pago.metodo_pago;
    document.getElementById('monto-recibo').textContent = `${pago.metodo_pago === 'Divisa' ? '$' : 'Bs. '}${parseFloat(pago.monto).toFixed(2)}`;
    document.getElementById('fecha-pago-recibo').textContent = formatearFecha(pago.fecha_pago);
    document.getElementById('mes-pago-recibo').textContent = pago.mes_pago || 'N/A';
    document.getElementById('referencia-recibo').textContent = pago.referencia || 'N/A';
    document.getElementById('observaciones-recibo').textContent = pago.observaciones || 'N/A';
    
    // Información de conversión
    document.getElementById('tasa-recibo').textContent = `Bs. ${tasaActual.toFixed(2)}`;
    
    if (pago.metodo_pago === 'Divisa') {
        const bolivares = parseFloat(pago.monto) * tasaActual;
        document.getElementById('conversion-recibo').textContent = `Equivale a: Bs. ${bolivares.toFixed(2)}`;
    } else {
        const divisas = parseFloat(pago.monto) / tasaActual;
        document.getElementById('conversion-recibo').textContent = `Equivale a: $${divisas.toFixed(2)}`;
    }
    
    // Fecha de generación y ID de transacción
    document.getElementById('fecha-generacion-recibo').textContent = new Date().toLocaleString('es-ES');
    document.getElementById('id-transaccion-recibo').textContent = pago.id_pago || 'N/A';
    
    // Mostrar el modal del recibo
    document.getElementById('modal-recibo').classList.remove('hidden');
    document.getElementById('modal-recibo').classList.add('flex');
}

function cerrarModalRecibo() {
    document.getElementById('modal-recibo').classList.add('hidden');
    document.getElementById('modal-recibo').classList.remove('flex');
}

function imprimirRecibo() {
    // Crear una nueva ventana para imprimir
    const contenidoRecibo = document.getElementById('contenido-recibo').innerHTML;
    const ventanaImpresion = window.open('', '_blank', 'width=800,height=600');
    
    ventanaImpresion.document.write(`
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recibo de Pago - Estrellas Sport Club</title>
            <link href="assets/css/tailwind.css" rel="stylesheet">
            <style>
                @media print {
                    body { margin: 0; padding: 20px; }
                    .no-print { display: none !important; }
                    .recibo-container { 
                        max-width: 100% !important; 
                        margin: 0 !important; 
                        padding: 20px !important;
                        border: 2px solid #000 !important;
                    }
                }
                .recibo-container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 2px solid #333;
                    border-radius: 8px;
                    background: white;
                }
                .header-recibo {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 15px;
                }
                .logo-container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 10px;
                }
                .logo-container img {
                    height: 60px;
                    width: auto;
                    margin-right: 15px;
                }
                .titulo-recibo {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                    margin: 10px 0;
                }
                .seccion-recibo {
                    margin-bottom: 20px;
                }
                .seccion-titulo {
                    font-size: 18px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 5px;
                }
                .campo-recibo {
                    display: flex;
                    justify-content: space-between;
                    padding: 5px 0;
                    border-bottom: 1px solid #eee;
                }
                .campo-label {
                    font-weight: bold;
                    color: #555;
                }
                .campo-valor {
                    font-weight: 500;
                    color: #333;
                }
                .monto-destacado {
                    font-size: 20px;
                    font-weight: bold;
                    color: #2563eb;
                }
                .conversion-info {
                    background: #f8f9fa;
                    padding: 10px;
                    border-radius: 5px;
                    margin: 15px 0;
                }
                .footer-recibo {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #333;
                }
                .btn-imprimir {
                    background: #059669;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    margin: 20px 0;
                }
                .btn-imprimir:hover {
                    background: #047857;
                }
            </style>
        </head>
        <body>
            <div class="recibo-container">
                ${contenidoRecibo}
            </div>
            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <button class="btn-imprimir" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir Recibo
                </button>
                <button class="btn-imprimir" onclick="window.close()" style="background: #dc2626; margin-left: 10px;">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </body>
        </html>
    `);
    
    ventanaImpresion.document.close();
}

function descargarReciboPNG() {
    // Mostrar indicador de carga
    const notificacionCarga = mostrarNotificacion('Generando imagen PNG...', 'success');
    
    const elementoRecibo = document.getElementById('contenido-recibo');
    
    // Configuración para html2canvas
    const opciones = {
        scale: 3, // Mejor calidad para PNG
        useCORS: true, // Permitir imágenes externas
        allowTaint: true,
        backgroundColor: '#ffffff',
        width: elementoRecibo.offsetWidth,
        height: elementoRecibo.offsetHeight,
        scrollX: 0,
        scrollY: 0,
        logging: false,
        letterRendering: true,
        foreignObjectRendering: false
    };
    
    html2canvas(elementoRecibo, opciones).then(canvas => {
        // Crear enlace de descarga
        const enlace = document.createElement('a');
        const fecha = new Date().toISOString().split('T')[0];
        const hora = new Date().toTimeString().split(' ')[0].replace(/:/g, '-');
        const nombreAtleta = document.getElementById('nombre-atleta-recibo').textContent.replace(/\s+/g, '_');
        const tipoPago = document.getElementById('tipo-pago-recibo').textContent.toLowerCase();
        enlace.download = `recibo_${tipoPago}_${nombreAtleta}_${fecha}_${hora}.png`;
        enlace.href = canvas.toDataURL('image/png');
        
        // Simular clic para descargar
        document.body.appendChild(enlace);
        enlace.click();
        document.body.removeChild(enlace);
        
        // Remover notificación de carga
        if (notificacionCarga && notificacionCarga.parentElement) {
            notificacionCarga.remove();
        }
        mostrarNotificacion('Recibo descargado en PNG exitosamente', 'success');
    }).catch(error => {
        console.error('Error al generar PNG:', error);
        // Remover notificación de carga
        if (notificacionCarga && notificacionCarga.parentElement) {
            notificacionCarga.remove();
        }
        mostrarNotificacion('Error al generar la imagen PNG', 'error');
    });
}

function descargarReciboJPG() {
    // Mostrar indicador de carga
    const notificacionCarga = mostrarNotificacion('Generando imagen JPG...', 'success');
    
    const elementoRecibo = document.getElementById('contenido-recibo');
    
    // Configuración para html2canvas
    const opciones = {
        scale: 2, // Calidad balanceada para JPG
        useCORS: true, // Permitir imágenes externas
        allowTaint: true,
        backgroundColor: '#ffffff',
        width: elementoRecibo.offsetWidth,
        height: elementoRecibo.offsetHeight,
        scrollX: 0,
        scrollY: 0,
        logging: false,
        letterRendering: true,
        foreignObjectRendering: false
    };
    
    html2canvas(elementoRecibo, opciones).then(canvas => {
        // Crear enlace de descarga
        const enlace = document.createElement('a');
        const fecha = new Date().toISOString().split('T')[0];
        const hora = new Date().toTimeString().split(' ')[0].replace(/:/g, '-');
        const nombreAtleta = document.getElementById('nombre-atleta-recibo').textContent.replace(/\s+/g, '_');
        const tipoPago = document.getElementById('tipo-pago-recibo').textContent.toLowerCase();
        enlace.download = `recibo_${tipoPago}_${nombreAtleta}_${fecha}_${hora}.jpg`;
        enlace.href = canvas.toDataURL('image/jpeg', 0.95); // Alta calidad para JPG
        
        // Simular clic para descargar
        document.body.appendChild(enlace);
        enlace.click();
        document.body.removeChild(enlace);
        
        // Remover notificación de carga
        if (notificacionCarga && notificacionCarga.parentElement) {
            notificacionCarga.remove();
        }
        mostrarNotificacion('Recibo descargado en JPG exitosamente', 'success');
    }).catch(error => {
        console.error('Error al generar JPG:', error);
        // Remover notificación de carga
        if (notificacionCarga && notificacionCarga.parentElement) {
            notificacionCarga.remove();
        }
        mostrarNotificacion('Error al generar la imagen JPG', 'error');
    });
}