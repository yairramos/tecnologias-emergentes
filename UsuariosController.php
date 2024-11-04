<?php
require_once 'config/db.php';

class UsuariosController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Función para listar todos los usuarios
    public function index() {
        $sql = "SELECT persona.*, usuario_externo.tipo_usuario, usuario_interno_estudiante.semestre, usuario_interno_docente.cargo
                FROM persona
                LEFT JOIN usuario_externo ON persona.id_persona = usuario_externo.id_persona
                LEFT JOIN usuario_interno_estudiante ON persona.id_persona = usuario_interno_estudiante.id_persona
                LEFT JOIN usuario_interno_docente ON persona.id_persona = usuario_interno_docente.id_persona";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/usuarios/index.php';
    }

    // Función para mostrar el formulario de creación de un nuevo usuario
    public function create() {
        // Obtener la lista de carreras para que el usuario interno docente pueda seleccionarla
        $sqlCarreras = "SELECT id_carrera, nombre_carrera FROM carrera";
        $stmtCarreras = $this->conn->prepare($sqlCarreras);
        $stmtCarreras->execute();
        $carreras = $stmtCarreras->fetchAll(PDO::FETCH_ASSOC);

        require 'views/usuarios/create.php';
    }

    // Función para almacenar un nuevo usuario
    public function store($data) {
        $sql = "INSERT INTO persona (nombre, apellido, dirección, teléfono, correo_electrónico) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['direccion'],
            $data['telefono'],
            $data['correo_electronico']
        ]);

        $id_persona = $this->conn->lastInsertId();

        // Inserción específica según el tipo de usuario
        if ($data['tipo'] === 'Externo') {
            $tipo_usuario_externo = "Externo"; // Tipo fijo para usuarios externos
            $sqlExterno = "INSERT INTO usuario_externo (id_persona, tipo_usuario) VALUES (?, ?)";
            $stmtExterno = $this->conn->prepare($sqlExterno);
            $stmtExterno->execute([$id_persona, $tipo_usuario_externo]);
        } elseif ($data['tipo'] === 'Estudiante') {
            $sqlEstudiante = "INSERT INTO usuario_interno_estudiante (id_persona, id_carrera, semestre, ru) VALUES (?, ?, ?, ?)";
            $stmtEstudiante = $this->conn->prepare($sqlEstudiante);
            $stmtEstudiante->execute([$id_persona, $data['id_carrera'], $data['semestre'], $data['ru']]);
        } elseif ($data['tipo'] === 'Docente') {
            $sqlDocente = "INSERT INTO usuario_interno_docente (id_persona, id_carrera, cargo) VALUES (?, ?, ?)";
            $stmtDocente = $this->conn->prepare($sqlDocente);
            $stmtDocente->execute([$id_persona, $data['id_carrera'], $data['cargo']]);
        }

        header("Location: index.php?page=usuarios");
    }

    // Función para mostrar el formulario de edición de un usuario
    public function edit($id) {
        // Obtener los datos del usuario específico
        $sql = "SELECT * FROM persona WHERE id_persona = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$usuario) {
            echo "Usuario no encontrado";
            return;
        }
    
        // Obtener la lista de carreras para el caso de estudiantes y docentes
        $sqlCarreras = "SELECT id_carrera, nombre_carrera FROM carrera";
        $stmtCarreras = $this->conn->prepare($sqlCarreras);
        $stmtCarreras->execute();
        $carreras = $stmtCarreras->fetchAll(PDO::FETCH_ASSOC);
    
        require 'views/usuarios/edit.php';
    }
    

    // Función para actualizar un usuario
    public function update($id, $data) {
        $sql = "UPDATE persona SET nombre = ?, apellido = ?, dirección = ?, teléfono = ?, correo_electrónico = ? WHERE id_persona = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['direccion'],
            $data['telefono'],
            $data['correo_electronico'],
            $id
        ]);

        header("Location: index.php?page=usuarios");
    }

    // Función para eliminar un usuario
    public function delete($id) {
        // Verificar si el usuario tiene reservaciones pendientes
        $sqlCheckReservaciones = "SELECT COUNT(*) FROM reservaciones WHERE id_persona = ?";
        $stmtCheck = $this->conn->prepare($sqlCheckReservaciones);
        $stmtCheck->execute([$id]);
        $reservacionesCount = $stmtCheck->fetchColumn();
    
        if ($reservacionesCount > 0) {
            // Mostrar un mensaje de advertencia si hay reservaciones pendientes
            echo "<script>
                    alert('Este usuario tiene reservaciones pendientes y no puede ser eliminado.');
                    window.location.href = 'index.php?page=usuarios';
                  </script>";
            return;
        }
    
        // Eliminar relaciones en usuario_interno_estudiante si existen
        $sqlDeleteEstudiante = "DELETE FROM usuario_interno_estudiante WHERE id_persona = ?";
        $stmtEstudiante = $this->conn->prepare($sqlDeleteEstudiante);
        $stmtEstudiante->execute([$id]);
    
        // Eliminar relaciones en usuario_interno_docente si existen
        $sqlDeleteDocente = "DELETE FROM usuario_interno_docente WHERE id_persona = ?";
        $stmtDocente = $this->conn->prepare($sqlDeleteDocente);
        $stmtDocente->execute([$id]);
    
        // Eliminar relaciones en usuario_externo si existen
        $sqlDeleteExterno = "DELETE FROM usuario_externo WHERE id_persona = ?";
        $stmtExterno = $this->conn->prepare($sqlDeleteExterno);
        $stmtExterno->execute([$id]);
    
        // Luego, eliminar el usuario en la tabla persona
        $sqlDeletePersona = "DELETE FROM persona WHERE id_persona = ?";
        $stmtPersona = $this->conn->prepare($sqlDeletePersona);
        $stmtPersona->execute([$id]);
    
        header("Location: index.php?page=usuarios");
    }
    
    
    
}
?>
