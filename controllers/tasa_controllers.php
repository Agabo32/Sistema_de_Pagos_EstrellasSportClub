<?php
require_once '../config/database.php';

class TasaController {
    private $conn;
    private $table_name = "tasa_dolar";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTasaActual() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_actualizacion DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarTasa($nueva_tasa) {
        $query = "INSERT INTO " . $this->table_name . " (tasa) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nueva_tasa]);
    }

    public function obtenerHistorial() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha_actualizacion DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>