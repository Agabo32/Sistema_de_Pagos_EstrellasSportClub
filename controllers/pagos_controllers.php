<?php
require_once '../config/database.php';

class PagoController {
    private $conn;
    private $table_name = "pagos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT p.*, a.nombre, a.apellido, a.disciplina 
                  FROM " . $this->table_name . " p 
                  JOIN atletas a ON p.id_atleta = a.id_atleta 
                  ORDER BY p.fecha_pago DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorAtleta($id_atleta) {
        $query = "SELECT p.*, a.nombre, a.apellido, a.disciplina, a.genero, a.cedula 
                  FROM " . $this->table_name . " p 
                  JOIN atletas a ON p.id_atleta = a.id_atleta 
                  WHERE p.id_atleta = ? 
                  ORDER BY p.fecha_pago DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_atleta);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerConFiltros($filtros = []) {
        $query = "SELECT p.*, a.nombre, a.apellido, a.disciplina, a.genero, a.cedula 
                  FROM " . $this->table_name . " p 
                  JOIN atletas a ON p.id_atleta = a.id_atleta 
                  WHERE 1=1";
        
        $params = [];
        $paramIndex = 1;
        
        // Filtro por tipo de pago
        if (!empty($filtros['tipo_pago'])) {
            $query .= " AND p.tipo_pago = ?";
            $params[] = $filtros['tipo_pago'];
        }
        
        // Filtro por método de pago
        if (!empty($filtros['metodo_pago'])) {
            $query .= " AND p.metodo_pago = ?";
            $params[] = $filtros['metodo_pago'];
        }
        
        // Filtro por fecha desde
        if (!empty($filtros['fecha_desde'])) {
            $query .= " AND p.fecha_pago >= ?";
            $params[] = $filtros['fecha_desde'];
        }
        
        // Filtro por fecha hasta
        if (!empty($filtros['fecha_hasta'])) {
            $query .= " AND p.fecha_pago <= ?";
            $params[] = $filtros['fecha_hasta'] . ' 23:59:59';
        }
        
        $query .= " ORDER BY p.fecha_pago DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (id_atleta, tipo_pago,  metodo_pago, monto, fecha_pago, mes_pago, observaciones, referencia) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $resultado = $stmt->execute([
            $datos['id_atleta'],
            $datos['tipo_pago'],
            $datos['metodo_pago'],
            $datos['monto'],
            $datos['fecha_pago'],
            $datos['mes_pago'],
            $datos['observaciones'],
            $datos['referencia']
        ]);
        
        if ($resultado) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function obtenerReporteMensual($mes, $año) {
        $query = "SELECT 
                    SUM(CASE WHEN metodo_pago = 'Divisa' THEN monto ELSE 0 END) as total_divisas,
                    SUM(CASE WHEN metodo_pago = 'Bolivares' THEN monto ELSE 0 END) as total_bolivares,
                    COUNT(*) as total_pagos,
                    tipo_pago
                  FROM " . $this->table_name . " 
                  WHERE MONTH(fecha_pago) = ? AND YEAR(fecha_pago) = ?
                  GROUP BY tipo_pago";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$mes, $año]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerReportePorDisciplina($mes, $año) {
        $query = "SELECT 
                    a.disciplina,
                    SUM(CASE WHEN p.metodo_pago = 'Divisa' THEN p.monto ELSE 0 END) as total_divisas,
                    SUM(CASE WHEN p.metodo_pago = 'Bolivares' THEN p.monto ELSE 0 END) as total_bolivares,
                    COUNT(*) as total_pagos
                  FROM " . $this->table_name . " p
                  JOIN atletas a ON p.id_atleta = a.id_atleta
                  WHERE MONTH(p.fecha_pago) = ? AND YEAR(p.fecha_pago) = ?
                  GROUP BY a.disciplina
                  ORDER BY SUM(CASE WHEN p.metodo_pago = 'Divisa' THEN p.monto ELSE 0 END) + SUM(CASE WHEN p.metodo_pago = 'Bolivares' THEN p.monto ELSE 0 END) DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$mes, $año]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIngresosPorMes($año) {
        $query = "SELECT 
                    MONTH(fecha_pago) as mes,
                    SUM(CASE WHEN metodo_pago = 'Divisa' THEN monto ELSE 0 END) as divisas,
                    SUM(CASE WHEN metodo_pago = 'Bolivares' THEN monto ELSE 0 END) as bolivares
                  FROM " . $this->table_name . " 
                  WHERE YEAR(fecha_pago) = ?
                  GROUP BY MONTH(fecha_pago)
                  ORDER BY mes";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$año]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIngresosPorDisciplinaAnual($año) {
        $query = "SELECT 
                    a.disciplina,
                    SUM(CASE WHEN p.metodo_pago = 'Divisa' THEN p.monto ELSE 0 END) as divisas,
                    SUM(CASE WHEN p.metodo_pago = 'Bolivares' THEN p.monto ELSE 0 END) as bolivares,
                    COUNT(*) as total_pagos
                  FROM " . $this->table_name . " p
                  JOIN atletas a ON p.id_atleta = a.id_atleta
                  WHERE YEAR(p.fecha_pago) = ?
                  GROUP BY a.disciplina
                  ORDER BY divisas + bolivares DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$año]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>