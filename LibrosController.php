<?php
require_once 'config/db.php';

class LibrosController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Cargar la vista principal de libros con la lista de libros
    public function index() {
        $sql = "SELECT libros.*, 
                       carrera.nombre_carrera, 
                       estados_libro.nombre_estado, 
                       autor.nombre_autor, 
                       categorias.nombre_categoria
                FROM libros
                LEFT JOIN carrera ON libros.id_carrera = carrera.id_carrera
                LEFT JOIN estados_libro ON libros.estado_libro = estados_libro.id_estado
                LEFT JOIN autor ON libros.id_autor = autor.id_autor
                LEFT JOIN categorias ON libros.id_categoria = categorias.id_categoria";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Verificar si se obtienen libros
        if (empty($libros)) {
            echo "No se encontraron libros.";
        }
    
        // Pasar los datos a la vista
        require 'views/libros/index.php';
    }
    
    
    
    
    public function create() {
        // Obtener la lista de carreras
        $sqlCarreras = "SELECT id_carrera, nombre_carrera FROM carrera";
        $stmtCarreras = $this->conn->prepare($sqlCarreras);
        $stmtCarreras->execute();
        $carreras = $stmtCarreras->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener la lista de estados de libros
        $sqlEstados = "SELECT id_estado, nombre_estado FROM estados_libro";
        $stmtEstados = $this->conn->prepare($sqlEstados);
        $stmtEstados->execute();
        $estados = $stmtEstados->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener la lista de autores
        $sqlAutores = "SELECT id_autor, nombre_autor FROM autor";
        $stmtAutores = $this->conn->prepare($sqlAutores);
        $stmtAutores->execute();
        $autores = $stmtAutores->fetchAll(PDO::FETCH_ASSOC);
    
        // Obtener la lista de categorías
        $sqlCategorias = "SELECT id_categoria, nombre_categoria FROM categorias";
        $stmtCategorias = $this->conn->prepare($sqlCategorias);
        $stmtCategorias->execute();
        $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
    
        // Pasar los datos a la vista
        require 'views/libros/create.php';
    }
    
    

    public function store($data) {
        // Obtener el ID del estado "Libre" de la tabla estados_libro
        $sqlGetLibre = "SELECT id_estado FROM estados_libro WHERE nombre_estado = 'Libre'";
        $stmtLibre = $this->conn->prepare($sqlGetLibre);
        $stmtLibre->execute();
        $estadoLibreId = $stmtLibre->fetchColumn();
    
        // Determinar si id_carrera debe ser null
        $id_carrera = !empty($data['id_carrera']) ? $data['id_carrera'] : null;
    
        // Insertar el nuevo libro con el estado "Libre"
        $sql = "INSERT INTO libros (titulo, editorial, año_publicacion, estado_libro, id_carrera)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['titulo'],
            $data['editorial'],
            $data['anio_publicacion'],
            $estadoLibreId, // Estado predeterminado "Libre"
            $id_carrera
        ]);
    
        header("Location: index.php?page=libros");
    }
    
    public function edit($id) {
        // Obtener los datos del libro junto con el nombre de la carrera y el estado
        $sqlLibro = "SELECT libros.*, carrera.nombre_carrera, estados_libro.nombre_estado, autor.id_autor, categorias.id_categoria 
                     FROM libros
                     LEFT JOIN carrera ON libros.id_carrera = carrera.id_carrera
                     LEFT JOIN estados_libro ON libros.estado_libro = estados_libro.id_estado
                     LEFT JOIN autor ON libros.id_autor = autor.id_autor
                     LEFT JOIN categorias ON libros.id_categoria = categorias.id_categoria
                     WHERE libros.id_libro = ?";
        $stmtLibro = $this->conn->prepare($sqlLibro);
        $stmtLibro->execute([$id]);
        $libro = $stmtLibro->fetch(PDO::FETCH_ASSOC);
    
        // Agrega esto para depuración temporal
        var_dump($libro);
        
        // Obtener todas las carreras, autores y categorías como ya está
        // ...
    }
    
    

    public function update($id, $data) {
        $sql = "UPDATE libros 
                SET titulo = ?, editorial = ?, año_publicacion = ?, id_carrera = ?, id_autor = ?, id_categoria = ?
                WHERE id_libro = ?";
        $stmt = $this->conn->prepare($sql);
    
        // Verificar si se seleccionó una carrera, autor y categoría; si no, asignar NULL
        $id_carrera = !empty($data['id_carrera']) ? $data['id_carrera'] : null;
        $id_autor = !empty($data['id_autor']) ? $data['id_autor'] : null;
        $id_categoria = !empty($data['id_categoria']) ? $data['id_categoria'] : null;
    
        $stmt->execute([
            $data['titulo'],
            $data['editorial'],
            $data['anio_publicacion'],
            $id_carrera,
            $id_autor,      // Asegurarse de que el autor está siendo guardado correctamente
            $id_categoria,  // Asegurarse de que la categoría está siendo guardada correctamente
            $id
        ]);
    
        header("Location: index.php?page=libros");
    }
    
    

    public function delete($id) {
        // Verificar si el libro tiene préstamos pendientes
        $sqlCheckPrestamos = "SELECT COUNT(*) FROM prestamos WHERE id_libro = ?";
        $stmtCheckPrestamos = $this->conn->prepare($sqlCheckPrestamos);
        $stmtCheckPrestamos->execute([$id]);
        $prestamosCount = $stmtCheckPrestamos->fetchColumn();
    
        // Verificar si el libro tiene reservaciones pendientes
        $sqlCheckReservaciones = "SELECT COUNT(*) FROM reservaciones WHERE id_libro = ?";
        $stmtCheckReservaciones = $this->conn->prepare($sqlCheckReservaciones);
        $stmtCheckReservaciones->execute([$id]);
        $reservacionesCount = $stmtCheckReservaciones->fetchColumn();
    
        // Construir un mensaje específico en función de las condiciones
        if ($prestamosCount > 0 || $reservacionesCount > 0) {
            $mensaje = "No se puede eliminar este libro porque tiene ";
    
            if ($prestamosCount > 0) {
                $mensaje .= "un préstamo pendiente";
            }
            if ($reservacionesCount > 0) {
                $mensaje .= ($prestamosCount > 0 ? " y " : "") . "una reservación pendiente";
            }
    
            // Mostrar el mensaje específico en una alerta
            echo "<script>
                    alert('$mensaje.');
                    window.location.href = 'index.php?page=libros';
                  </script>";
            return;
        }
    
        // Si no hay préstamos ni reservaciones, proceder a eliminar el libro
        $sqlDeleteLibro = "DELETE FROM libros WHERE id_libro = ?";
        $stmtLibro = $this->conn->prepare($sqlDeleteLibro);
        $stmtLibro->execute([$id]);
    
        header("Location: index.php?page=libros");
    }
    
    
}
?>
