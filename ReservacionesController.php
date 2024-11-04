<?php
require_once 'config/db.php';

class ReservacionesController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function update($id, $data) {
        $sql = "UPDATE reservaciones SET id_libro = ?, id_persona = ?, fecha_reservacion = ?, fecha_vencimiento = ?, estado = ? 
                WHERE id_reservacion = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['id_libro'],
            $data['id_persona'],
            $data['fecha_reservacion'], // Corregido a fecha_reservacion sin acentos
            $data['fecha_vencimiento'],
            $data['estado'],
            $id
        ]);

        header("Location: index.php?page=reservaciones");
    }

    public function create() {
        // Obtener la lista de libros disponibles
        $sqlLibros = "SELECT id_libro, titulo FROM libros";
        $stmtLibros = $this->conn->prepare($sqlLibros);
        $stmtLibros->execute();
        $libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

        // Obtener la lista de usuarios
        $sqlUsuarios = "SELECT id_persona, nombre, apellido FROM persona";
        $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
        $stmtUsuarios->execute();
        $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

        require 'views/reservaciones/create.php';
    }

    public function edit($id) {
        $sql = "SELECT * FROM reservaciones WHERE id_reservacion = ?"; // Confirma el nombre exacto
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $reservacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservacion) {
            echo "Reservación no encontrada";
            return;
        }

        // Obtener la lista de libros y usuarios para el formulario de edición
        $sqlLibros = "SELECT id_libro, titulo FROM libros";
        $stmtLibros = $this->conn->prepare($sqlLibros);
        $stmtLibros->execute();
        $libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

        $sqlUsuarios = "SELECT id_persona, nombre, apellido FROM persona";
        $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
        $stmtUsuarios->execute();
        $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

        require 'views/reservaciones/edit.php';
    }

    public function delete($id) {
        // Obtener el ID del libro antes de eliminar la reservación
        $sqlGetLibro = "SELECT id_libro FROM reservaciones WHERE id_reservacion = ?"; // Confirma el nombre exacto
        $stmtGetLibro = $this->conn->prepare($sqlGetLibro);
        $stmtGetLibro->execute([$id]);
        $libroId = $stmtGetLibro->fetchColumn();

        // Eliminar la reservación
        $sqlDeleteReservacion = "DELETE FROM reservaciones WHERE id_reservacion = ?"; // Confirma el nombre exacto
        $stmtDelete = $this->conn->prepare($sqlDeleteReservacion);
        $stmtDelete->execute([$id]);

        // Cambiar el estado del libro a "Libre"
        $sqlUpdateLibro = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Libre') WHERE id_libro = ?";
        $stmtUpdateLibro = $this->conn->prepare($sqlUpdateLibro);
        $stmtUpdateLibro->execute([$libroId]);

        header("Location: index.php?page=reservaciones");
    }

    public function store($data) {
        // Verificar el estado actual del libro
        $sqlCheckEstado = "SELECT estado_libro FROM libros WHERE id_libro = ?";
        $stmtCheck = $this->conn->prepare($sqlCheckEstado);
        $stmtCheck->execute([$data['id_libro']]);
        $estadoActual = $stmtCheck->fetchColumn();
    
        // Determinar el estado de la reserva
        $estadoReserva = ($estadoActual == 2) ? 'Reservado' : 'Pendiente'; // 2 es el ID para "Libre"
    
        // Crear la reservación
        $sqlInsertReserva = "INSERT INTO reservaciones (id_libro, id_persona, fecha_reservacion, fecha_vencimiento, estado)
                             VALUES (?, ?, ?, ?, ?)";
        $stmtReserva = $this->conn->prepare($sqlInsertReserva);
        $stmtReserva->execute([
            $data['id_libro'],
            $data['id_persona'],
            $data['fecha_reservacion'],
            $data['fecha_vencimiento'],
            $estadoReserva
        ]);
    
        // Si el libro está libre, actualizar su estado a "Reservado" directamente
        if ($estadoReserva == 'Reservado') {
            $sqlUpdateLibro = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Reservado') WHERE id_libro = ?";
            $stmtUpdateLibro = $this->conn->prepare($sqlUpdateLibro);
            $stmtUpdateLibro->execute([$data['id_libro']]);
        }
    
        header("Location: index.php?page=reservaciones");
    }
    
    
    
    

    public function index() {
        $sql = "SELECT reservaciones.*, persona.nombre, persona.apellido, libros.titulo
                FROM reservaciones
                JOIN persona ON reservaciones.id_persona = persona.id_persona
                JOIN libros ON reservaciones.id_libro = libros.id_libro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $reservaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/reservaciones/index.php';
    }
}
