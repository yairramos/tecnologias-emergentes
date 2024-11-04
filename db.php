<?php
$host = 'localhost';
$dbname = 'proyecto'; // Cambia 'nombre_base_de_datos' por el nombre de tu base de datos
$username = 'root';
$password = ''; // En XAMPP, el usuario 'root' usualmente no tiene contraseña

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
