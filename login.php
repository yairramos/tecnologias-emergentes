<?php
session_start();
require_once 'config/db.php';

// Lógica de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario existe en la base de datos
    // Verificar si el usuario existe en la base de datos
$sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$correo_electronico]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar la contraseña y autenticar al usuario
if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['tipo_usuario'] = $usuario['tipo_usuario']; // 'usuario' o 'personal'

    // Redirigir según el tipo de usuario
    if ($usuario['tipo_usuario'] == 'personal') {
        header('Location: index.php?page=libros'); // Redirigir al personal
    } else {
        header('Location: index.php?page=libros_usuario'); // Redirigir a los usuarios
    }
    exit();
} else {
    $error = "Correo o contraseña incorrectos.";
}

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
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
        .login-container {
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

        input[type="email"],
        input[type="password"] {
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
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo_electronico" required>

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
    
</body>


</html>
