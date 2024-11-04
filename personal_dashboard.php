<?php
session_start();
if ($_SESSION['tipo_usuario'] != 'personal') {
    header('Location: login.php');
    exit;
}

// Mostrar panel de gestión para personal (acceso a todas las funciones)
require 'views/libros/index.php'; // Puedes incluir otras vistas de gestión
?>
