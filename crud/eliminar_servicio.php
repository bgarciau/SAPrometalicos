<?php 
    include("../php/conexion.php");

    $idServicio=$_GET["idServicio"];

    $base->query("DELETE FROM servicio WHERE id_Servicio='$idServicio'");

    header("location:../views/servicios.php");
?>