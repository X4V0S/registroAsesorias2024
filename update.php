<?php 
    include("connection.php");
    $con=connection();

    $id=$_GET['id'];

    $sql="SELECT * FROM registros WHERE id='$id'";
    $query=mysqli_query($con, $sql);

    $row=mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/styles.css" rel="stylesheet">
        <title>Editar registro</title>
        
    </head>
    <body>
        <div class="users-form">
            <form action="edit_registros.php" method="POST">
                <input type="hidden" name="id" value="<?= $row['id']?>">

                <label>Nombre del alumno:</label>
                <input required type="text" name="nombre"
                value="<?= $row['nombre'] ?>">

                <label>Sexo:</label>
                <select required name="sexo"
                value="<?= $row['sexo'] ?>">
                    <option value="H">H</option>
                    <option value="M">M</option>
                </select>

                <label>Matricula:</label>
                <input required type="number" name="matricula"
                value="<?= $row['matricula'] ?>">

                <label>Carrera:</label>
                <select required name="carrera"
                value="<?= $row['carrera'] ?>">
                    <option>LA</option>
                    <option>LCC</option>
                    <option>LF</option>
                    <option>LM</option>
                    <option>LMAD</option>
                    <option>LSTI</option>
                </select>

                <label>Fecha:</label>
                <input required type="date" name="fecha"
                value="<?= $row['fecha'] ?>">
                
                <label>Hora - Inicio:</label>
                <input required type="time" name="hora_inicio" min="08:00" max="20:00"
                value="<?= $row['hora_inicio'] ?>">
                
                <label>Hora - Fin:</label>
                <input required type="time" name="hora_fin" min={values.hora_inicio} max="20:00"
                value="<?= $row['hora_fin'] ?>">
                
                <label>Unidad de Aprendizaje:</label>
                <input required type="text" name="unidad"
                value="<?= $row['unidad'] ?>">

                <label>Tema:</label>
                <input required type="text" name="tema"
                value="<?= $row['tema'] ?>">
                
                <label>Soluciono?:</label>
                <select required name="soluciono"
                value="<?= $row['soluciono'] ?>">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>

                <label>Profesor:</label>
                <input required type="text" name="profesor"
                value="<?= $row['profesor'] ?>">

                <label>Asesor:</label>
                <input required type="text" name="asesor"
                value="<?= $row['asesor'] ?>">

                <input type="submit" value="Actualizar">
            </form>
        </div>
    </body>