<?php
session_start();
if ($_SESSION['tipo_usuario'] != 'usuario') {
    header('Location: login.php');
    exit;
}

// Mostrar lista de libros
require 'views/libros/index.php';
?>
