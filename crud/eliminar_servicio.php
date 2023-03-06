<?php 
    include("../php/conexion.php");

    $idServicio=$_GET["idServicio"];

    $base->query("DELETE FROM arse WHERE pk_cod_arse='$idServicio'");

    header("location:../views/servicios.php");
?>