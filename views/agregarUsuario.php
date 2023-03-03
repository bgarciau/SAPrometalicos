<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
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

            if($_POST["password"]==$_POST["password2"])
            {
                $codigoUsuario=$_POST["codigoUsuario"];
                $nombreUsuario=$_POST["nombreUsuario"];
                $rolUsuario=$_POST["rolUsuario"];
                $departamento=$_POST["departamento"];
                $sucursal=$_POST["sucursal"];
                $password=$_POST["password"];
                $pass_cifrado=password_hash($password,PASSWORD_DEFAULT,array("cost"=>7));
                $tipoUsuario=$_POST["tipoUsuario"];
                
                $sql="INSERT INTO usuario (pk_cod_usr,nom_usr,rol_usr,fk_depart,sucursal,pass_usr,fk_tipo_usr) 
                VALUES(:_codigoUsuario,:_nombreUsuario,:_rolUsuario,:_departamento,:_sucursal,:_password,:_tipoUsuario)";

                $resultado=$base->prepare($sql);
                
                $resultado->execute(array(":_codigoUsuario"=>$codigoUsuario,":_nombreUsuario"=>$nombreUsuario,":_rolUsuario"=>$rolUsuario,":_departamento"=>$departamento,":_sucursal"=>$sucursal,":_password"=>$pass_cifrado,":_tipoUsuario"=>$tipoUsuario));
                
                header("location:usuarios.php");
            }else{
                $codigoUsuario=$_POST["codigoUsuario"];
                $nombreUsuario=$_POST["nombreUsuario"];
                $rolUsuario=$_POST["rolUsuario"];
                $departamento=$_POST["departamento"];
                $sucursal=$_POST["sucursal"];
                $password=$_POST["password"];
                $pass_cifrado=password_hash($password,PASSWORD_DEFAULT,array("cost"=>7));
                $tipoUsuario=$_POST["tipoUsuario"];


            }

            
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
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="f1">
        <h2>AGREGAR USUARIO</h2>
                <label for="CodigoUsuario" >Codigo usuario:</label>
                <input class="inputA" type="text" id="CodigoUsuario" name="codigoUsuario" value="<?php if(isset($_POST['codigoUsuario'])){ echo $_POST['codigoUsuario'];} ?>" required><br>
                <label for="RolUsuario">Rol usuario:</label>
                <input class="inputA" type="text" id="RolUsuario" name="rolUsuario" value="<?php if(isset($_POST['rolUsuario'])){ echo $_POST['rolUsuario'];} ?>" required><br>
                <label for="NombreUsuario">Nombre usuario:</label>
                <input class="inputA" type="text" id="NombreUsuario" name="nombreUsuario" value="<?php if(isset($_POST['nombreUsuario'])){ echo $_POST['nombreUsuario'];} ?>" required><br>
                <label for="Departamento">Departamento:</label>
                    <select name="departamento" id="datosFormu" required>
                        <?php
                            if(isset($_POST['departamento'])){
                            $depart2=$_POST['departamento'];
                            $departa=$base->query("SELECT * FROM departamento WHERE pk_dep= '$depart2'")->fetchAll(PDO::FETCH_OBJ);
                            foreach($departa as $departam):
                        
                            ?>  
                               <option value="<?php echo $departam->pk_dep ?>"><?php echo $departam->nom_dep ?></option>
                        <?php
                            endforeach;
                        }
                        ?>           
                        <?php
                            foreach($depart as $departamentos):?>  
                                <option value="<?php echo $departamentos->pk_dep?>"><?php echo $departamentos->nom_dep?></option>
                        <?php
                            endforeach;
                        ?>   
                        </select><br>
                <label for="Sucursal">Sucursal:</label>
                <input class="inputA" type="text" id="Sucursal" name="sucursal" value="<?php if(isset($_POST['sucursal'])){ echo $_POST['sucursal'];} ?>" required><br>
                <label for="Password">Contraseña:</label>
                <input class="inputA" type="password" id="clave1" name="password" value="<?php if(isset($_POST['password'])){ echo $_POST['password'];} ?>" required><br>
                <label for="Password">Confirmar Contraseña:</label>
                <input class="inputA" type="password" id="clave2" name="password2" required><br>
                <label for="TipoUsuario">Tipo usuario:</label>
                <select name="tipoUsuario" id="datosFormu" required>
                <?php
                            if(isset($_POST['tipoUsuario'])){
                            $tuser2=$_POST['tipoUsuario'];
                            $tusr=$base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$tuser2'")->fetchAll(PDO::FETCH_OBJ);
                            foreach($tusr as $tuser):
                        
                            ?>  
                               <option value="<?php echo $tuser->pk_t_usr ?>"><?php echo $tuser->des_usr ?></option>
                        <?php
                            endforeach;
                        }
                        ?> 
            
                    <option value=1 >Usuario</option>
                    <option value=2>Empleado</option>
                    <option value=3>Administrador</option>
                </select>
                    
                <br>
                <!-- <input class="inputA" type="text" id="TipoUsuario" name="tipoUsuario" value="Tipo usuario"><br> -->
                <a><input class="btn_env" type="submit" value="CREAR USUARIO" name="crearU" onclick="comprobarClave()"></a>
                <a href="usuarios.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
        </div>
        </div>
    </div> 
    </form>
    <script>
        function comprobarClave() {
            let clave1 = document.f1.clave1.value
            let clave2 = document.f1.clave2.value

            if (clave1 == clave2) {

                
            } else {
                alert("Las dos claves son distintas...\nvuelva a intentarlo")
                
            }
        }
    </script>
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>
   W