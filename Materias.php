<?php
// Archivo para gestionar la conexión a la base de datos
include('connection.php');
$conn = connection();


// Variables para mostrar mensajes de éxito o error
$message = "";

function showMessage($msg) {
    echo "<script>alert('$msg');</script>";
}


// Validar el nombre de la materia
function validateName($name) {
    // Solo letras y acentos permitidos
    return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $name);
}

// Verificar si el nombre de la materia es similar a alguna existente
function isNameSimilar($name, $conn) {
    $stmt = $conn->prepare("SELECT Nombre_Materia FROM Materias WHERE Nombre_Materia LIKE ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verificar si se encontró una materia similar
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $similar = false;
    } else {
        $similar = false;
    }

    $stmt->close();
    return $similar;
}

//Crear materia
if (isset($_POST['create'])) {
    $nombre_materia = $_POST['nombre_materia'];
    $carrera_id = $_POST['carrera_id'];
    $semestre = $_POST['semestre'];

    $conn = connection();

    if (!validateName($nombre_materia)) {
        showMessage("El nombre de la materia solo puede contener letras y acentos.");
    } else {
        // Verificar si el nombre de la materia ya existe
        $stmt_check = $conn->prepare("SELECT ID FROM Materias WHERE Nombre_Materia = ?");
        $stmt_check->bind_param("s", $nombre_materia);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            showMessage("El nombre de la materia ya existe en la base de datos.");
        } else {
            $stmt = $conn->prepare("INSERT INTO Materias (Nombre_Materia, Carrera_ID, Semestre) VALUES (?, ?, ?)");
            
            $stmt->bind_param("sii", $nombre_materia, $carrera_id, $semestre);
            $stmt->execute();
            $stmt->close();
            showMessage("Materia creada exitosamente.");
        }

        $stmt_check->close();
    }

    $conn->close();
}

// Editar materia
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nombre_materia = $_POST['nombre_materia'];
    $carrera_id = $_POST['carrera_id'];
    $semestre = $_POST['semestre'];

    $conn = connection();

    if (!validateName($nombre_materia)) {
        showMessage("El nombre de la materia solo puede contener letras y acentos.");
    } else {
        // Verificar si el nombre de la materia ya existe, excluyendo la actual
        $stmt_check = $conn->prepare("SELECT ID FROM Materias WHERE Nombre_Materia = ? AND ID != ?");
        $stmt_check->bind_param("si", $nombre_materia, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            showMessage("El nombre de la materia ya existe en la base de datos.");
        } else {
            $stmt = $conn->prepare("UPDATE Materias SET Nombre_Materia = ?, Carrera_ID = ?, Semestre = ? WHERE ID = ?");
            $stmt->bind_param("siii", $nombre_materia, $carrera_id, $semestre, $id);
            $stmt->execute();
            $stmt->close();
            showMessage("Materia editada exitosamente.");
        }

        $stmt_check->close();
    }

    $conn->close();
}

// Eliminar materia (actualizar Activo a false)
if (isset($_POST['deactivate'])) {
    $id = $_POST['id'];

    $conn = connection();
    $stmt = $conn->prepare("UPDATE Materias SET Activo = 0 WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    showMessage("Materia desactivada exitosamente.");
}




// Obtener materias y carreras
$conn = connection();
$resultMaterias = $conn->query("SELECT * FROM Materias WHERE Activo = 1");
$materias = $resultMaterias->fetch_all(MYSQLI_ASSOC);
$resultCarreras = $conn->query("SELECT * FROM Carreras");
$carreras = $resultCarreras->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Materias</title>
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
        input[type="text"], select {
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
        <h1>Gestión de Materias</h1>

        <h2>Crear Materia</h2>
        <form method="POST">
            <input type="hidden" name="create" value="1">
            <label for="nombre_materia">Nombre de la Materia:</label>
            <input type="text" id="nombre_materia" name="nombre_materia" required>
            <label for="carrera_id">Carrera:</label>
            <select id="carrera_id" name="carrera_id" required>
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo $carrera['ID']; ?>"><?php echo htmlspecialchars($carrera['Nombre_Carrera']); ?></option>
                <?php endforeach; ?>
            </select>
            <label for="semestre">Semestre:</label>
            <select id="semestre" name="semestre" required>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <input type="submit" value="Crear">
        </form>

        <h2>Materias Actuales</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Semestre</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($materias as $materia): ?>
                <tr>
                    <td><?php echo htmlspecialchars($materia['ID']); ?></td>
                    <td><?php echo htmlspecialchars($materia['Nombre_Materia']); ?></td>
                    <td>
                        <?php 
                            $carrera_nombre = '';
                            foreach ($carreras as $carrera) {
                                if ($carrera['ID'] == $materia['Carrera_ID']) {
                                    $carrera_nombre = $carrera['Nombre_Carrera'];
                                    break;
                                }
                            }
                            echo htmlspecialchars($carrera_nombre);
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($materia['Semestre']); ?></td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $materia['ID']; ?>">
                            <input type="hidden" name="edit" value="1">
                            <input type="text" name="nombre_materia" value="<?php echo htmlspecialchars($materia['Nombre_Materia']); ?>" required>
                            <select name="carrera_id" required>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?php echo $carrera['ID']; ?>" <?php if ($carrera['ID'] == $materia['Carrera_ID']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($carrera['Nombre_Carrera']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="semestre" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == $materia['Semestre']) echo 'selected'; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <input type="submit" value="Editar">
                        </form>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $materia['ID']; ?>">
                            <input type="hidden" name="deactivate" value="1">
                            <input type="submit" value="Eliminar" onclick="return confirm('¿Estás seguro de desactivar esta materia?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>