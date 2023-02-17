<?php 
    include("../php/conexion.php");

    $codigoUsuario=$_GET["codigoUsuario"];

    $base->query("DELETE FROM usuario WHERE PK_CODIGO_USUARIO='$codigoUsuario'");

    header("location:../views/usuarios.php");
?>