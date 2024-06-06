<?php
// Archivo para gestionar la conexión a la base de datos
include('connection.php');
$conn = connection();

// Variables para mostrar mensajes de éxito
$message = "";

function showMessage($msg) {
    echo "<script>alert('$msg');</script>";
}

// Crear profesor
if (isset($_POST['create'])) {
    $nombre_profesor = $_POST['nombre_profesor'];

    $conn = connection();
    $stmt = $conn->prepare("INSERT INTO Profesores (Nombre_Profesor) VALUES (?)");
    $stmt->bind_param("s", $nombre_profesor);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Profesor creado exitosamente.");
}

// Editar profesor
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nombre_profesor = $_POST['nombre_profesor'];

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Profesores SET Nombre_Profesor = ? WHERE ID = ?");
    $stmt->bind_param("si", $nombre_profesor, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Profesor editado exitosamente.");
}

// Desactivar profesor (actualizar Activo a false)
if (isset($_POST['deactivate'])) {
    $id = $_POST['id'];

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Profesores SET Activo = 0 WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Profesor desactivado exitosamente.");
}

// Obtener profesores
$conn = connection();
$result = $conn->query("SELECT * FROM Profesores WHERE Activo = TRUE ");
$profesores = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            margin: 5px 0;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Profesores</h1>

        <h2>Crear Profesor</h2>
        <form method="POST">
            <input type="hidden" name="create" value="1">
            <label for="nombre_profesor">Nombre del Profesor:</label>
            <input type="text" id="nombre_profesor" name="nombre_profesor" required>
            <input type="submit" value="Crear">
        </form>

        <h2>Profesores Actuales</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($profesores as $profesor): ?>
            <tr>
                <td><?php echo htmlspecialchars($profesor['ID']); ?></td>
                <td><?php echo htmlspecialchars($profesor['Nombre_Profesor']); ?></td>
                <td><?php echo $profesor['Activo'] ? 'Sí' : 'No'; ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $profesor['ID']; ?>">
                        <input type="hidden" name="edit" value="1">
                        <input type="text" name="nombre_profesor" value="<?php echo htmlspecialchars($profesor['Nombre_Profesor']); ?>" required>
                        <input type="submit" value="Editar">
                    </form>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $profesor['ID']; ?>">
                        <input type="hidden" name="deactivate" value="1">
                        <input type="submit" value="Eliminar" onclick="return confirm('¿Estás seguro de desactivar este profesor?');">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>