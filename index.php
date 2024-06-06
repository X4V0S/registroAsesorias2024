<?php
include('connection.php');

$con = connection();

$sql = "SELECT * FROM registros";
$query = mysqli_query($con, $sql);


// Obtener materias y carreras
$connUA = connection();
$resultMaterias = $connUA->query("SELECT * FROM Materias WHERE Activo = 1");
$materias = $resultMaterias->fetch_all(MYSQLI_ASSOC);
$resultCarreras = $connUA->query("SELECT * FROM Carreras");
$carreras = $resultCarreras->fetch_all(MYSQLI_ASSOC);
$connUA->close();

// Obtener profesores
$connPROF = connection();
$result = $connPROF->query("SELECT * FROM Profesores WHERE Activo = TRUE ");
$profesores = $result->fetch_all(MYSQLI_ASSOC);
$connPROF->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styles.css">
    <title>REGISTRO DE ASESORIAS</title>

    <script>
        function actualizarMinimoHoraFin() {
            // Obtener el valor del campo "Hora - Inicio"
            const horaInicioInput = document.getElementById("hora_inicio");
            const horaInicio = horaInicioInput.value;

            // Establecer el valor mínimo del campo "Hora - Fin"
            const horaFinInput = document.getElementById("hora_fin");
            horaFinInput.min = horaInicio;
        }
    </script>

</head>
<body>
    <div class="users-form">
        <form action="insert_user.php" method="POST">
            <h1>Registro de asesoria</h1>

            <label>Nombre del alumno:</label>
            <input required type="text" name="nombre">

            <label>Sexo:</label>
            <select required name="sexo">
                <option value="H">H</option>
                <option value="M">M</option>
            </select>

            <label>Matricula:</label>
            <input required type="number" name="matricula">

            <label>Carrera:</label>
            <select required name="carrera">
                <option>LA</option>
                <option>LCC</option>
                <option>LF</option>
                <option>LM</option>
                <option>LMAD</option>
                <option>LSTI</option>
            </select>

            <label>Fecha:</label>
            <input required type="date" name="fecha">
            
            <label>Hora - Inicio:</label>
            <input required type="time" name="hora_inicio" min="08:00" max="20:00"
            id="hora_inicio" onchange="actualizarMinimoHoraFin()">
            
            <label>Hora - Fin:</label>
            <input required type="time" name="hora_fin" max="20:00"
            id="hora_fin">
            
            <label>Unidad de Aprendizaje:</label>
            <select name="unidad">  
                <?php foreach ($materias as $materia): ?>
                <option><?php echo htmlspecialchars($materia['Nombre_Materia']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Tema:</label>
            <input required type="text" name="tema">
            
            <label>Soluciono?:</label>
            <select required name="soluciono">
                <option value="SI">SI</option>
                <option value="NO">NO</option>
            </select>

            <label>Profesor:</label>
            <select name="unidad">  
                <?php foreach ($profesores as $profesor): ?>
                <option><?php echo htmlspecialchars($profesor['Nombre_Profesor']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Asesor:</label>
            <input required type="text" name="asesor">

            <input type="submit" value="Agregar asesoria">
        </form>
    </div>

    <div class="users-table">
        <h2>Asesorias registradas</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Sexo</th>
                    <th>Matricula</th>
                    <th>Carrera</th>
                    <th>Fecha</th>
                    <th>Inicio</th>
                    <th>Termino</th>
                    <th>Unidad</th>
                    <th>Tema</th>
                    <th>Soluciono</th>
                    <th>Profesor</th>
                    <th>Asesor</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = mysqli_fetch_array($query)): ?>
                <tr>
                    <th> <?= $row['id'] ?> </th>
                    <th> <?= $row['nombre'] ?> </th>
                    <th> <?= $row['sexo'] ?> </th>
                    <th> <?= $row['matricula'] ?> </th>
                    <th> <?= $row['carrera'] ?> </th>
                    <th> <?= $row['fecha'] ?> </th>
                    <th> <?= $row['hora_inicio'] ?> </th>
                    <th> <?= $row['hora_fin'] ?> </th>
                    <th> <?= $row['unidad'] ?> </th>
                    <th> <?= $row['tema'] ?> </th>
                    <th> <?= $row['soluciono'] ?> </th>
                    <th> <?= $row['profesor'] ?> </th>
                    <th> <?= $row['asesor'] ?> </th>
                    <th><a href="update.php?id=<?= $row['id'] ?>" class="users-table--edit">Editar</a></th>
                    <th><a href="delete.php?id=<?= $row['id'] ?>" class="users-table--delete" >Eliminar</a></th>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>