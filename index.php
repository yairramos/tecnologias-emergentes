<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        /* Título */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Estilos de la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #333;
            color: white;
            text-transform: uppercase;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Botones */
        .btn {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
        }

        .btn-add {
            background-color: #4CAF50; /* Verde */
            margin-bottom: 10px;
            display: inline-block;
        }

        .btn-edit {
            background-color: #ffc107; /* Amarillo */
        }

        .btn-delete {
            background-color: #f44336; /* Rojo */
        }

        .btn-add:hover {
            background-color: #45a049;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        /* Contenedor para centrar */
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Título de la sección -->
        <h2>Lista de Usuarios</h2>

        <!-- Botón para agregar nuevo usuario -->
        <a href="index.php?page=usuarios_create" class="btn btn-add">Agregar Usuario</a>

        <!-- Tabla de usuarios -->
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Tipo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['apellido']; ?></td>
                    <td><?php echo $usuario['dirección']; ?></td>
                    <td><?php echo $usuario['teléfono']; ?></td>
                    <td><?php echo $usuario['correo_electrónico']; ?></td>
                    <td><?php echo $usuario['tipo_usuario'] ?? ($usuario['semestre'] ? 'Estudiante' : 'Docente'); ?></td>
                    <td>
                        <a href="index.php?page=usuarios_edit&id=<?php echo $usuario['id_persona']; ?>" class="btn btn-edit">Editar</a>
                        <a href="index.php?page=usuarios_delete&id=<?php echo $usuario['id_persona']; ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
