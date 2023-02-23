<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
        session_start();

        if (!isset($_SESSION["usuario"])) {

            header("location:../index.php");
        }
        
        include("../php/conexion.php");

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">   
        <div id="div__agregarU">
        <?php 


            if(!isset($_POST["btn_actualizar"])){
                $idServicio=$_GET["idServicio"];
                $descripcionServicio=$_GET["descripcionServicio"];

            } else{
                $idServicio=$_POST["idServicio"];
                $descripcionServicio=$_POST["descripcionServicio"];

                $sql="UPDATE arse SET des_arse=:_descripcionServicio WHERE pk_cod_arse=:_idServicio";
                
                $resultado=$base->prepare($sql);

                $resultado->execute(array(":_idServicio"=>$idServicio,":_descripcionServicio"=>$descripcionServicio));

                header("location:servicios.php");
            }

        ?>
        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h2>ACTUALIZAR SERVICIOS</h2>
                <input class="inputA" type="hidden" name="idServicio" value="<?php echo $idServicio?>"><br>
                <label  for="descripcionServicio">Descripcion Servicio:</label>
                <input class="inputA" type="text" name="descripcionServicio" value="<?php echo $descripcionServicio?>"><br>
                <a><input class="btn_env" type="submit" name="btn_actualizar" value="ACTUALIZAR"></a>
                <a href="servicios.php"><input class="btn_vol" type="button" value="< VOLVER"></a>  
        </form>
        </div>
    </div> 
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>