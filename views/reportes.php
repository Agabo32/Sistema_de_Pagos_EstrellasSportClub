<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../controllers/pagos_controllers.php';

$controller = new PagoController();

try {
    if (isset($_GET['mes']) && isset($_GET['año'])) {
        $mes = $_GET['mes'];
        $año = $_GET['año'];
        $reporte = $controller->obtenerReporteMensual($mes, $año);
        $reporte_disciplina = $controller->obtenerReportePorDisciplina($mes, $año);
        echo json_encode(['success' => true, 'reporte' => $reporte, 'reporte_disciplina' => $reporte_disciplina]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Parámetros faltantes']);
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>