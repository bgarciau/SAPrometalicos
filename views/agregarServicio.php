<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/hfstyle.css">
</head>
<body>
    <?php

use function PHPSTORM_META\sql_injection_subst;

        session_start();

        if (!isset($_SESSION["usuario"])) {

            header("location:../index.php");
            
        }

        include("../php/conexion.php");
        
        $servicio="servicio";
        if(isset($_POST["crearS"])){

            $descripcionServicio=$_POST["descripcionServicio"];
            
            $sql="INSERT INTO arse (tipo_arse,des_arse) 
            VALUES(:_tipoArse,:_descripcionServicio)";

            $resultado=$base->prepare($sql);
            
            $resultado->execute(array(":_tipoArse"=>$servicio,":_descripcionServicio"=>$descripcionServicio));
            
            header("location:servicios.php");
        }
    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">   
        <div class="contenedor">   
        <div id="div__agregarU">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h2>AGREGAR SERVICIO</h2>
                <label for="descripcionServicio">Descripcion servicio:</label>
                <input class="inputA" type="text" name="descripcionServicio" placeholder="Descripcion del servicio"><br>
                <br>
                <!-- <input class="inputA" type="text" id="TipoUsuario" name="tipoUsuario" placeholder="Tipo usuario"><br> -->
                <a><input class="btn_env" type="submit" value="CREAR SERVICIO" name="crearS"></a>
                <a href="servicios.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
        </div>
        </div>
    </div> 
    </form>
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>