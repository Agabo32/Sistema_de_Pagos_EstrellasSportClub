<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../controllers/atletas_controllers.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$controller = new AtletaController();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch($method) {
        case 'GET':
            $atletas = $controller->obtenerTodos();
            echo json_encode(['success' => true, 'atletas' => $atletas]);
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            // Validar que existan todos los campos requeridos
            $campos = ['nombre','apellido','cedula','fecha_nacimiento','genero','telefono','direccion','disciplina'];
            foreach ($campos as $campo) {
                if (!isset($input[$campo])) {
                    echo json_encode(['success' => false, 'message' => 'Falta el campo: ' . $campo]);
                    exit;
                }
            }
            $resultado = $controller->crear($input);
            echo json_encode(['success' => $resultado]);
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id_atleta'];
            $resultado = $controller->actualizar($id, $input);
            echo json_encode(['success' => $resultado]);
            break;
            
        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id_atleta'];
            $resultado = $controller->eliminar($id);
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