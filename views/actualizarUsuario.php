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
                $codigoUsuario=$_GET["codigoUsuario"];
                $nombreUsuario=$_GET["nombreUsuario"];
                $departamento=$_GET["departamento"];
                $sucursal=$_GET["sucursal"];
                $password=$_GET["password"];
                $tipoUsuario=$_GET["tipoUsuario"];

            } else{
                $codigoUsuario=$_POST["codigoUsuario"];
                $nombreUsuario=$_POST["nombreUsuario"];
                $departamento=$_POST["departamento"];
                $sucursal=$_POST["sucursal"];
                $password=$_POST["password"];
                $tipoUsuario=$_POST["tipoUsuario"];

                $sql="UPDATE usuario SET NOMBRE_USUARIO=:_nombreUsuario, FK_ID_DEPARTAMENTO=:_departamento, SUCURSAL=:_sucursal, PASSWORD=:_password, FK_TIPO_USUARIO=:_tipoUsuario WHERE PK_CODIGO_USUARIO=:_codigoUsuario";
                
                $resultado=$base->prepare($sql);

                $resultado->execute(array(":_codigoUsuario"=>$codigoUsuario,":_nombreUsuario"=>$nombreUsuario,":_departamento"=>$departamento,":_sucursal"=>$sucursal,":_password"=>$password,":_tipoUsuario"=>$tipoUsuario));

                header("location:usuarios.php");
            }

        ?>
        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h2>ACTUALIZAR DATOS USUARIO</h2>
                <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $codigoUsuario?>"><br>
                <label  for="NombreUsuario">Nombre usuario:</label>
                <input class="inputA" type="text" name="nombreUsuario" value="<?php echo $nombreUsuario?>"><br>
                <label for="Departamento">Departamento:</label>
                <input class="inputA" type="text" name="departamento" value="<?php echo $departamento?>"><br>
                <label for="Sucursal">Sucursal:</label>
                <input class="inputA" type="text" name="sucursal" value="<?php echo $sucursal?>"><br>
                <label for="Password">Contraseña:</label>
                <input class="inputA" type="text" name="password" value="<?php echo $password?>"><br>
                <label for="TipoUsuario">Tipo usuario:</label>
                <input class="inputA" type="text" name="tipoUsuario" value="<?php echo $tipoUsuario?>"><br>
                <a><input class="btn_env" type="submit" name="btn_actualizar" value="ACTUALIZAR"></a>
                <a href="usuarios.php"><input class="btn_vol" type="button" value="< VOLVER"></a>  
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