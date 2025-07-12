<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../controllers/pagos_controllers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$controller = new PagoController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            if (isset($_GET['id_atleta'])) {
                $id_atleta = $_GET['id_atleta'];
                $pagos = $controller->obtenerPorAtleta($id_atleta);
                echo json_encode(['success' => true, 'pagos' => $pagos]);
            } else {
                // Verificar si hay filtros
                $filtros = [];
                if (!empty($_GET['tipo_pago'])) {
                    $filtros['tipo_pago'] = $_GET['tipo_pago'];
                }
                if (!empty($_GET['metodo_pago'])) {
                    $filtros['metodo_pago'] = $_GET['metodo_pago'];
                }
                if (!empty($_GET['fecha_desde'])) {
                    $filtros['fecha_desde'] = $_GET['fecha_desde'];
                }
                if (!empty($_GET['fecha_hasta'])) {
                    $filtros['fecha_hasta'] = $_GET['fecha_hasta'];
                }
                
                if (!empty($filtros)) {
                    $pagos = $controller->obtenerConFiltros($filtros);
                } else {
            $pagos = $controller->obtenerTodos();
                }
            echo json_encode(['success' => true, 'pagos' => $pagos]);
            }
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            // Validar que existan todos los campos requeridos
            $campos = ['id_atleta','tipo_pago','metodo_pago','monto','fecha_pago','mes_pago','observaciones','referencia'];
            foreach ($campos as $campo) {
                if (!isset($input[$campo])) {
                    echo json_encode(['success' => false, 'message' => 'Falta el campo: ' . $campo]);
                    exit;
                }
            }
            $pago_id = $controller->crear($input);
            if ($pago_id) {
                echo json_encode(['success' => true, 'pago_id' => $pago_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al crear el pago']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            break;
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>