<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../controllers/tasa_controllers.php';

$controller = new TasaController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            $tasa = $controller->obtenerTasaActual();
            echo json_encode(['success' => true, 'tasa' => $tasa]);
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $resultado = $controller->actualizarTasa($input['tasa']);
            echo json_encode(['success' => $resultado]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            break;
    }
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>