<?php
include('connection.php');
$conn = connection();
$message = "";

function showMessage($msg) {
    echo "<script>alert('$msg');</script>";
}

if (isset($_POST['create'])) {
    $nombre_asesor = $_POST['nombre_asesor'];
    $carrera_id = $_POST['carrera_id'];
    $matricula = $_POST['matricula'];
    $fecha_ingreso = $_POST['fecha_ingreso'];

    $conn = connection();
    $stmt = $conn->prepare("INSERT INTO Asesores (Nombre_Asesor, Carrera_ID,Matricula, Fecha_Ingreso) VALUES (?, ?,?, ?)");
    $stmt->bind_param("siss", $nombre_asesor, $carrera_id, $matricula, $fecha_ingreso);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Asesor creado exitosamente.");
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nombre_asesor = $_POST['nombre_asesor'];
    $fecha_salida = $_POST['fecha_salida'];

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Asesores SET Nombre_Asesor = ?, Fecha_Salida = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $nombre_asesor, $fecha_salida, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Asesor editado exitosamente.");
}

if (isset($_POST['deactivate'])) {
    $id = $_POST['id'];

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Asesores SET Activo = FALSE WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Asesor desactivado exitosamente.");
}

if (isset($_POST['deactivate_date'])) {
    $fecha_desactivar = $_POST['fecha_desactivar'];
    $mes = date('m', strtotime($fecha_desactivar));
    $anio = date('Y', strtotime($fecha_desactivar));

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Asesores SET Activo = FALSE WHERE MONTH(Fecha_Ingreso) = ? AND YEAR(Fecha_Ingreso) = ?");
    $stmt->bind_param("ii", $mes, $anio);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Asesores del mes $mes del año $anio desactivados exitosamente.");
}

$conn = connection();
$result_materias = $conn->query("SELECT * FROM Asesores WHERE Activo = TRUE ORDER BY Nombre_Asesor");
$asesores = $result_materias->fetch_all(MYSQLI_ASSOC);

$result_carreras = $conn->query("SELECT * FROM Carreras");
$carreras = $result_carreras->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asesores</title>
    <style>
        /* Estilos CSS */
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
        h1, h2 {
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
        input[type="text"], input[type="number"], input[type="date"] {
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
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Asesores</h1>

        <h2>Crear Asesor</h2>
        <form method="POST">
            <input type="hidden" name="create" value="1">
            <label for="nombre_asesor">Nombre del Asesor:</label>
            <input type="text" id="nombre_asesor" name="nombre_asesor" pattern="[A-Za-z ]+" title="Solo se permiten letras y espacios" required>
            <label for="carrera_id">Carrera:</label>
            <select id="carrera_id" name="carrera_id" required>
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo $carrera['ID']; ?>"><?php echo htmlspecialchars($carrera['Nombre_Carrera']); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="matricula" name="matricula" pattern="[0-9]+" title="Solo se permiten números" required>
            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
            <input type="submit" value="Crear">
        </form>

        <h2>Asesores Actuales</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Fecha de Ingreso</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($asesores as $asesor): ?>
            <tr>
                <td><?php echo htmlspecialchars($asesor['ID']); ?></td>
                <td><?php echo htmlspecialchars($asesor['Nombre_Asesor']); ?></td>
                <td><?php echo htmlspecialchars($asesor['Carrera_ID']); ?></td>
                <td><?php echo htmlspecialchars($asesor['Fecha_Ingreso']); ?></td>
                <td><?php echo $asesor['Activo'] ? 'Sí' : 'No'; ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?php echo $asesor['ID']; ?>">
                        <input type="hidden" name="edit" value="1">
                        <input type="text" name="nombre_asesor" value="<?php echo htmlspecialchars($asesor['Nombre_Asesor']); ?>" pattern="[A-Za-z ]+" title="Solo se permiten letras y espacios" required>
                        <input type="date" name="fecha_salida" value="<?php echo date('Y-m-d'); ?>" required>
                        <input type="submit" value="Editar">
                    </form>

            <form method="POST" style="display:inline-block;">
            <input type="hidden" name="id" value="<?php echo $asesor['ID']; ?>">
            <input type="hidden" name="deactivate" value="1">
            <input type="submit" value="Desactivar" onclick="return confirm('¿Estás seguro de desactivar este asesor?');">
        </form>
</td>
</tr>
<?php endforeach; ?>
</table>

<div class="button-group">
    <h2>Desactivar Asesores por Fecha</h2>
    <form method="POST">
        <label for="fecha_desactivar">Fecha:</label>
        <input type="date" id="fecha_desactivar" name="fecha_desactivar" required>
        <input type="hidden" name="deactivate_date" value="1">
        <input type="submit" value="Desactivar Asesores">
    </form>
</div>



</body>
</html>
