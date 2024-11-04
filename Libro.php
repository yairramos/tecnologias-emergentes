<?php
require_once 'config/db.php';

class Libro {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM libros";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $query = "INSERT INTO libros (título, editorial, año_publicación, estado_libro) VALUES (:titulo, :editorial, :anio_publicacion, :estado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':editorial', $data['editorial']);
        $stmt->bindParam(':anio_publicacion', $data['anio_publicacion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->execute();
    }

    public function find($id) {
        $query = "SELECT * FROM libros WHERE id_libro = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE libros SET título = :titulo, editorial = :editorial, año_publicación = :anio_publicacion, estado_libro = :estado WHERE id_libro = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':editorial', $data['editorial']);
        $stmt->bindParam(':anio_publicacion', $data['anio_publicacion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM libros WHERE id_libro = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>
