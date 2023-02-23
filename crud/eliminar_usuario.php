<?php 
    include("../php/conexion.php");

    $codigoUsuario=$_GET["codigoUsuario"];

    $base->query("DELETE FROM usuario WHERE pk_cod_usr='$codigoUsuario'");

    header("location:../views/usuarios.php");
?>