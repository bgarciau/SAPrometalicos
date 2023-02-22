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

        $depart=$base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);

        if(isset($_POST["crearU"])){

            $codigoUsuario=$_POST["codigoUsuario"];
            $nombreUsuario=$_POST["nombreUsuario"];
            $rolUsuario=$_POST["rolUsuario"];
            $departamento=$_POST["departamento"];
            $sucursal=$_POST["sucursal"];
            $password=$_POST["password"];
            $pass_cifrado=password_hash($password,PASSWORD_DEFAULT,array("cost"=>7));
            $tipoUsuario=$_POST["tipoUsuario"];
            
            $sql="INSERT INTO usuario (PK_CODIGO_USUARIO,NOMBRE_USUARIO,rol_usuario,FK_ID_DEPARTAMENTO,SUCURSAL,PASSWORD,FK_TIPO_USUARIO) 
            VALUES(:_codigoUsuario,:_nombreUsuario,:_rolUsuario,:_departamento,:_sucursal,:_password,:_tipoUsuario)";

            $resultado=$base->prepare($sql);
            
            $resultado->execute(array(":_codigoUsuario"=>$codigoUsuario,":_nombreUsuario"=>$nombreUsuario,":_rolUsuario"=>$rolUsuario,":_departamento"=>$departamento,":_sucursal"=>$sucursal,":_password"=>$pass_cifrado,":_tipoUsuario"=>$tipoUsuario));
            
            header("location:usuarios.php");
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
        <h2>AGREGAR USUARIO</h2>
                <label for="CodigoUsuario">Codigo usuario:</label>
                <input class="inputA" type="text" id="CodigoUsuario" name="codigoUsuario" placeholder="Codigo usuario"><br>
                <label for="RolUsuario">Rol usuario:</label>
                <input class="inputA" type="text" id="RolUsuario" name="rolUsuario" placeholder="Rol usuario"><br>
                <label for="NombreUsuario">Nombre usuario:</label>
                <input class="inputA" type="text" id="NombreUsuario" name="nombreUsuario" placeholder="Nombre usuario"><br>
                <label for="Departamento">Departamento:</label>
                    <select name="departamento" id="sel__departamento">
                        <?php
                            foreach($depart as $departamentos):?>  
                                <option value="<?php echo $departamentos->NOMBRE_DEPARTAMENTO?>"><?php echo $departamentos->NOMBRE_DEPARTAMENTO?></option>
                        <?php
                            endforeach;
                        ?>   
                        </select><br>
                <label for="Sucursal">Sucursal:</label>
                <input class="inputA" type="text" id="Sucursal" name="sucursal" placeholder="Sucursal"><br>
                <label for="Password">Contraseña:</label>
                <input class="inputA" type="password" id="Password" name="password" placeholder="Contraseña"><br>
                <label for="TipoUsuario">Tipo usuario:</label>
                <select name="tipoUsuario" id="sel__departamento">
                    <option value=1 >Usuario</option>
                    <option value=2>Empleado</option>
                    <option value=3>Administrador</option>
                </select>
                    
                <br>
                <!-- <input class="inputA" type="text" id="TipoUsuario" name="tipoUsuario" placeholder="Tipo usuario"><br> -->
                <a><input class="btn_env" type="submit" value="CREAR USUARIO" name="crearU"></a>
                <a href="usuarios.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
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
   