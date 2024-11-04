<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
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
        form input[type="tel"],
        form input[type="number"],
        form select {
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
        <h2>Agregar Usuario</h2>
        <form action="index.php?page=usuarios_store" method="POST">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <label>Dirección:</label>
            <input type="text" name="direccion" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" required>

            <label>Correo Electrónico:</label>
            <input type="email" name="correo_electronico" required>

            <label>Tipo de Usuario:</label>
            <select name="tipo" required>
                <option value="Externo">Externo</option>
                <option value="Estudiante">Estudiante</option>
                <option value="Docente">Docente</option>
            </select>

            <!-- Campos adicionales según el tipo de usuario -->
            <div id="campos_adicionales"></div>

            <button type="submit" class="btn">Guardar</button>
        </form>
    </div>

    <script>
    // Cambia los campos según el tipo seleccionado
    document.querySelector('select[name="tipo"]').addEventListener('change', function() {
        const tipo = this.value;
        const campos = document.getElementById('campos_adicionales');
        campos.innerHTML = '';

        if (tipo === 'Estudiante' || tipo === 'Docente') {
            let carreraSelect = '<label>Carrera:</label><select name="id_carrera" required>';
            carreraSelect += '<option value="">Seleccione una carrera</option>';
            <?php foreach ($carreras as $carrera): ?>
                carreraSelect += '<option value="<?php echo $carrera['id_carrera']; ?>"><?php echo $carrera['nombre_carrera']; ?></option>';
            <?php endforeach; ?>
            carreraSelect += '</select>';

            if (tipo === 'Estudiante') {
                campos.innerHTML = carreraSelect + '<label>Semestre:</label><input type="number" name="semestre" required><label>RU:</label><input type="text" name="ru" required>';
            } else if (tipo === 'Docente') {
                campos.innerHTML = carreraSelect + '<label>Cargo:</label><input type="text" name="cargo" required>';
            }
        }
    });
    </script>
</body>
</html>
