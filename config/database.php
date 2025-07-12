<?php
class Database {
    private $host = 'localhost';
    private $db_name = '12-07-2025';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Lanzar excepción en vez de imprimir para que el controlador pueda capturarla
            throw new Exception("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>