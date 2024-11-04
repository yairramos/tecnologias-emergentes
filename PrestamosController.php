<?php
require_once 'config/db.php';

class PrestamosController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Función para listar todos los préstamos
    public function index() {
        $sql = "SELECT prestamos.*, persona.nombre, persona.apellido, libros.titulo 
                FROM prestamos
                JOIN persona ON prestamos.id_persona = persona.id_persona
                JOIN libros ON prestamos.id_libro = libros.id_libro";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/prestamos/index.php';
    }

    // Función para mostrar el formulario de creación de un nuevo préstamo
    public function create() {
        $sqlLibros = "SELECT id_libro, titulo FROM libros";
        $stmtLibros = $this->conn->prepare($sqlLibros);
        $stmtLibros->execute();
        $libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

        $sqlUsuarios = "SELECT id_persona, nombre, apellido FROM persona";
        $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
        $stmtUsuarios->execute();
        $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

        require 'views/prestamos/create.php';
    }

    public function store($data) {
        // Verificar si el libro está reservado
        $sqlCheckEstado = "SELECT estado_libro FROM libros WHERE id_libro = ?";
        $stmtCheck = $this->conn->prepare($sqlCheckEstado);
        $stmtCheck->execute([$data['id_libro']]);
        $estadoActual = $stmtCheck->fetchColumn();
    
        // ID de estado "Reservado" es 1
        $idEstadoReservado = 1;
    
        // Si el estado es "Reservado", eliminar la reserva asociada al libro
        if ($estadoActual == $idEstadoReservado) {
            $sqlDeleteReservacion = "DELETE FROM reservaciones WHERE id_libro = ?";
            $stmtDeleteReservacion = $this->conn->prepare($sqlDeleteReservacion);
            $stmtDeleteReservacion->execute([$data['id_libro']]);
        }
    
        // Crear el préstamo
        $sqlInsertPrestamo = "INSERT INTO prestamos (id_libro, id_persona, fecha_prestamo, fecha_devolucion, estado)
                              VALUES (?, ?, ?, ?, 'Prestado')";
        $stmtPrestamo = $this->conn->prepare($sqlInsertPrestamo);
        $stmtPrestamo->execute([
            $data['id_libro'],
            $data['id_persona'],
            $data['fecha_prestamo'],
            $data['fecha_devolucion']
        ]);
    
        // Actualizar el estado del libro a "Prestado"
        $sqlUpdateLibro = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Prestado') WHERE id_libro = ?";
        $stmtUpdateLibro = $this->conn->prepare($sqlUpdateLibro);
        $stmtUpdateLibro->execute([$data['id_libro']]);
    
        header("Location: index.php?page=prestamos");
    }
    
    

    // Función para mostrar el formulario de edición de un préstamo
    public function edit($id) {
        $sql = "SELECT * FROM prestamos WHERE id_prestamo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestamo) {
            echo "Préstamo no encontrado";
            return;
        }

        // Obtener la lista de libros y usuarios
        $sqlLibros = "SELECT id_libro, titulo FROM libros";
        $stmtLibros = $this->conn->prepare($sqlLibros);
        $stmtLibros->execute();
        $libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

        $sqlUsuarios = "SELECT id_persona, nombre, apellido FROM persona";
        $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
        $stmtUsuarios->execute();
        $usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

        require 'views/prestamos/edit.php';
    }

    // Función para actualizar un préstamo
    public function update($id, $data) {
        $sql = "UPDATE prestamos SET fecha_devolucion = ?, estado = ? WHERE id_prestamo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['fecha_devolucion'],
            $data['estado'],
            $id
        ]);
    
        // Verificar si el libro se devolvió y está en estado "Libre"
        if ($data['estado'] == 'Devuelto') {
            $sqlGetLibro = "SELECT id_libro FROM prestamos WHERE id_prestamo = ?";
            $stmtGetLibro = $this->conn->prepare($sqlGetLibro);
            $stmtGetLibro->execute([$id]);
            $libroId = $stmtGetLibro->fetchColumn();
    
            // Cambiar el estado del libro a "Libre"
            $sqlUpdateLibro = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Libre') WHERE id_libro = ?";
            $stmtUpdateLibro = $this->conn->prepare($sqlUpdateLibro);
            $stmtUpdateLibro->execute([$libroId]);
    
            // Activar cualquier reserva pendiente para este libro
            $sqlUpdateReserva = "UPDATE reservaciones SET estado = 'Reservado' WHERE id_libro = ? AND estado = 'Pendiente' LIMIT 1";
            $stmtUpdateReserva = $this->conn->prepare($sqlUpdateReserva);
            $stmtUpdateReserva->execute([$libroId]);
    
            // Cambiar el estado del libro a "Reservado" si se activó una reserva
            if ($stmtUpdateReserva->rowCount() > 0) {
                $sqlUpdateLibroReservado = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Reservado') WHERE id_libro = ?";
                $stmtUpdateLibroReservado = $this->conn->prepare($sqlUpdateLibroReservado);
                $stmtUpdateLibroReservado->execute([$libroId]);
            }
        }
    
        header("Location: index.php?page=prestamos");
    }
    

    public function delete($id) {
        // Obtener el ID del libro antes de eliminar el préstamo
        $sqlGetLibro = "SELECT id_libro FROM prestamos WHERE id_prestamo = ?";
        $stmtGetLibro = $this->conn->prepare($sqlGetLibro);
        $stmtGetLibro->execute([$id]);
        $libroId = $stmtGetLibro->fetchColumn();
    
        // Eliminar el préstamo
        $sqlDeletePrestamo = "DELETE FROM prestamos WHERE id_prestamo = ?";
        $stmtDelete = $this->conn->prepare($sqlDeletePrestamo);
        $stmtDelete->execute([$id]);
    
        // Cambiar el estado del libro a "Libre" temporalmente
        $sqlUpdateLibroLibre = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Libre') WHERE id_libro = ?";
        $stmtUpdateLibroLibre = $this->conn->prepare($sqlUpdateLibroLibre);
        $stmtUpdateLibroLibre->execute([$libroId]);
    
        // Activar la reserva pendiente (si existe)
        $sqlUpdateReserva = "UPDATE reservaciones SET estado = 'Reservado' WHERE id_libro = ? AND estado = 'Pendiente' LIMIT 1";
        $stmtUpdateReserva = $this->conn->prepare($sqlUpdateReserva);
        $stmtUpdateReserva->execute([$libroId]);
    
        // Si una reserva fue activada, cambiar el estado del libro a "Reservado"
        if ($stmtUpdateReserva->rowCount() > 0) {
            $sqlUpdateLibroReservado = "UPDATE libros SET estado_libro = (SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Reservado') WHERE id_libro = ?";
            $stmtUpdateLibroReservado = $this->conn->prepare($sqlUpdateLibroReservado);
            $stmtUpdateLibroReservado->execute([$libroId]);
        }
    
        header("Location: index.php?page=prestamos");
    }
    
}
