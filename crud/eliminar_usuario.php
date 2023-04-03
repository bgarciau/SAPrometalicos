<?php 
    include("../php/conexion.php");

    $codigoUsuario=$_GET["codigoUsuario"]; //se guarda el codigo de usuario enviado por url desde usuarios.php 

    $base->query("DELETE FROM usuario WHERE pk_cod_usr='$codigoUsuario'"); //Elimina el usario segun su codigo, este codigo es unico

    header("location:../views/usuarios.php");
?>