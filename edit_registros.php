<?php

include("connection.php");
$con = connection();

$id=$_POST["id"];
$nombre = $_POST['nombre'];
$sexo = $_POST['sexo'];
$matricula = $_POST['matricula'];
$carrera = $_POST['carrera'];
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$unidad = $_POST['unidad'];
$tema = $_POST['tema'];
$soluciono = $_POST['soluciono'];
$profesor = $_POST['profesor'];
$asesor = $_POST['asesor'];


$sql="UPDATE registros SET nombre='$nombre', sexo='$sexo', matricula='$matricula',
             carrera='$carrera', fecha='$fecha', hora_inicio='$hora_inicio', hora_fin='$hora_fin',
             unidad='$unidad', tema='$tema', soluciono='$soluciono', profesor='$profesor',
             asesor='$asesor' WHERE id='$id'";
             
$query = mysqli_query($con, $sql);

if($query){
    Header("Location: index.php");
}else{

}

?>