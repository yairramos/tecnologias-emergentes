<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        /* Contenedor */
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Título */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Estilo de formulario */
        form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Botón de acción */
        .btn {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            margin-top: 15px;
            background-color: #4CAF50;
            display: block;
            width: 100%;
            text-align: center;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form action="index.php?page=usuarios_update" method="POST">
            <input type="hidden" name="id" value="<?php echo $usuario['id_persona']; ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>

            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?php echo $usuario['dirección']; ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo $usuario['teléfono']; ?>" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo_electronico" value="<?php echo $usuario['correo_electrónico']; ?>" required>

            <button type="submit" class="btn">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
