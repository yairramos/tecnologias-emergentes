<?php
require_once 'controllers/LibrosController.php';
require_once 'controllers/UsuariosController.php';
require_once 'controllers/ReservacionesController.php';
require_once 'controllers/PrestamosController.php';
require_once 'controllers/InformesController.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'libros';

switch ($page) {
    // Rutas para el controlador de Libros
    case 'libros':
        $controller = new LibrosController();
        $controller->index();
        break;
    case 'libros_create':
        $controller = new LibrosController();
        $controller->create();
        break;
    case 'libros_store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new LibrosController();
            $controller->store($_POST);
        }
        break;
    case 'libros_edit':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $controller = new LibrosController();
            $controller->edit($id);
        }
        break;
    case 'libros_update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $controller = new LibrosController();
            $controller->update($id, $_POST);
        }
        break;
    case 'libros_delete':
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $controller = new LibrosController();
            $controller->delete($id);
        }
        break;

    // Rutas para Usuarios
case 'usuarios':
    $controller = new UsuariosController();
    $controller->index();
    break;
case 'usuarios_create':
    $controller = new UsuariosController();
    $controller->create();
    break;
case 'usuarios_store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new UsuariosController();
        $controller->store($_POST);
    }
    break;
case 'usuarios_edit':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->edit($id);
    }
    break;
case 'usuarios_update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->update($id, $_POST);
    }
    break;
case 'usuarios_delete':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->delete($id);
    }
    break;


    // Rutas para Reservaciones
case 'reservaciones':
    $controller = new ReservacionesController();
    $controller->index();
    break;
case 'reservaciones_create':
    $controller = new ReservacionesController();
    $controller->create();
    break;
case 'reservaciones_store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new ReservacionesController();
        $controller->store($_POST);
    }
    break;
case 'reservaciones_edit':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new ReservacionesController();
        $controller->edit($id);
    }
    break;
case 'reservaciones_update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new ReservacionesController();
        $controller->update($id, $_POST);
    }
    break;
case 'reservaciones_delete':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new ReservacionesController();
        $controller->delete($id);
    }
    break;


    // Caso por defecto para rutas no encontradas
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;

    // Rutas para el controlador de Préstamos
    case 'prestamos':
    $controller = new PrestamosController();
    $controller->index();
    break;

    // Rutas para el controlador de Reservaciones
    case 'reservaciones':
    $controller = new ReservacionesController();
    $controller->index();
    break;

    // Rutas para el controlador de Reservaciones
    case 'reservaciones':
    $controller = new ReservacionesController();
    $controller->index();
    break;
    
    case 'reservaciones_create':
    $controller = new ReservacionesController();
    $controller->create();
    break;

    case 'reservaciones_store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new ReservacionesController();
        $controller->store($_POST);
    }
    break;

    // Rutas para Usuarios
case 'usuarios':
    $controller = new UsuariosController();
    $controller->index();
    break;
case 'usuarios_create':
    $controller = new UsuariosController();
    $controller->create();
    break;
case 'usuarios_store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new UsuariosController();
        $controller->store($_POST);
    }
    break;
case 'usuarios_edit':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->edit($id);
    }
    break;
case 'usuarios_update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->update($id, $_POST);
    }
    break;
case 'usuarios_delete':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new UsuariosController();
        $controller->delete($id);
    }
    break;

    // Rutas para Préstamos
case 'prestamos':
    $controller = new PrestamosController();
    $controller->index();
    break;
case 'prestamos_create':
    $controller = new PrestamosController();
    $controller->create();
    break;
case 'prestamos_store':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new PrestamosController();
        $controller->store($_POST);
    }
    break;
case 'prestamos_edit':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new PrestamosController();
        $controller->edit($id);
    }
    break;
case 'prestamos_update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new PrestamosController();
        $controller->update($id, $_POST);
    }
    break;
case 'prestamos_delete':
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $controller = new PrestamosController();
        $controller->delete($id);
    }
    break;



}
?>
