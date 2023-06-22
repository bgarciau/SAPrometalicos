<?php
     include("../php/conexion.php"); //incluye la conexion a la bd0
     $numSol = $_GET["numSol"];//toma el numero de la solicitud seleccionada
     $sql = "UPDATE solicitud_compra SET estado_sol=? WHERE pk_num_sol='$numSol'";//ACTUALIZA el estado de la solicitud y el numero SAP
     $solicitud = $base->prepare($sql); //se prepara la sentencia
     $estado_sol = "ABIERTO";
     $solicitud->execute(array($estado_sol));
     if(isset($_GET["xtabla"])){
        header("Location: ../views/".$_GET["lugar"]."?xtabla=tarticulos&estado=".$_GET["estado"]);//manda al usuario a la tabla de solicitudes con una alerta que tiene el numero SAP
     }
     else{
        header("Location: ../views/".$_GET["lugar"]."?estado=".$_GET["estado"]);//manda al usuario a la tabla de solicitudes con una alerta que tiene el numero SAP
     }
?>