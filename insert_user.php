<?php
include('connection.php');
$con = connection();

$id = null;
$nombre = $_POST['nombre'];
$sexo = $_POST['sexo'];
$matricula = $_POST['matricula'];
$carrera = $_POST['carrera'];
$fecha = $_POST['fecha'];
$hora_termino = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$unidad = $_POST['unidad'];
$tema = $_POST['tema'];
$soluciono = $_POST['soluciono'];
$profesor = $_POST['profesor'];
$asesor = $_POST['asesor'];

$sql = "INSERT INTO registros VALUES(
    '$id', '$nombre', '$sexo', '$matricula', '$carrera', '$fecha', 
    '$hora_termino', '$hora_fin', '$unidad', '$tema', '$soluciono', '$profesor', 
    '$asesor')";

$query = mysqli_query($con, $sql);

if($query){
    Header("Location: index.php");
};

?>