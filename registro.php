<?php
require_once 'config/db.php';

// Lógica para registrar un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verificar si el correo ya está registrado
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$correo_electronico]);

    if ($stmt->rowCount() > 0) {
        $error = "El correo electrónico ya está registrado.";
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, correo_electronico, contrasena, tipo_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$nombre, $correo_electronico, $contrasena, $tipo_usuario])) {
            // Redirigir al inicio de sesión después del registro exitoso
            header('Location: login.php');
            exit;
        } else {
            $error = "Error al registrar el usuario. Intente nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contenedor del formulario */
        .registro-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <h2>Registro de Usuario</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo_electronico" required>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>

            <label>Tipo de Usuario:</label>
            <select name="tipo_usuario" required>
                <option value="usuario">Usuario</option>
                <option value="personal">Personal</option>
            </select>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>
</html>
