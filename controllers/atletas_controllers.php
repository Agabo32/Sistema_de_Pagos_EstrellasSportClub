<?php
require_once '../config/database.php';

class AtletaController {
    private $conn;
    private $table_name = "atletas";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_atleta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (nombre, apellido, cedula, fecha_nacimiento, genero, telefono, direccion, disciplina) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $datos['nombre'],
                $datos['apellido'],
                $datos['cedula'],
                $datos['fecha_nacimiento'],
                $datos['genero'],
                $datos['telefono'],
                $datos['direccion'],
                $datos['disciplina']
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception('Error al guardar atleta: ' . $e->getMessage());
        }
    }

    public function actualizar($id, $datos) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre=?, apellido=?, cedula=?, fecha_nacimiento=?, genero=?, telefono=?, direccion=?, disciplina=? 
                  WHERE id_atleta=?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $datos['nombre'],
            $datos['apellido'],
            $datos['cedula'],
            $datos['fecha_nacimiento'],
            $datos['genero'],
            $datos['telefono'],
            $datos['direccion'],
            $datos['disciplina'],
            $id
        ]);
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_atleta = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>