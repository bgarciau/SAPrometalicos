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

        $depart=$base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);

        if(isset($_POST["cambiarC"])){  
            $codigoUsuario=$_POST["codigoUsuario"];
            $password=$_POST["password"];
            $pass_cifrado=password_hash($password,PASSWORD_DEFAULT,array("cost"=>7));
            
            $sql="UPDATE usuario SET PASSWORD=:_password WHERE PK_CODIGO_USUARIO=:_codigoUsuario";

            $resultado=$base->prepare($sql);
            
            $resultado->execute(array(":_codigoUsuario"=>$codigoUsuario,":_password"=>$pass_cifrado));
            
            header("location:Usuarios.php");
        }
        if(!isset($_POST["btn_actualizar"])){
            $codigoUsuario=$_GET["codigoUsuario"];
            $nombreUsuario=$_GET["nombreUsuario"];
            $rolUsuario=$_GET["rolUsuario"];
            $departamento=$_GET["departamento"];
            $sucursal=$_GET["sucursal"];
            // $password=$_GET["password"];
            $tipoUsuario=$_GET["tipoUsuario"];

        } 
        elseif(isset($_POST["btn_actualizar"])){
            $codigoUsuario=$_POST["codigoUsuario"];
            $nombreUsuario=$_POST["nombreUsuario"];
            $rolUsuario=$_POST["rolUsuario"];
            $departamento=$_POST["departamento"];
            $sucursal=$_POST["sucursal"];
            // $password=$_POST["password"];
            $tipoUsuario=$_POST["tipoUsuario"];

            $sql="UPDATE usuario SET NOMBRE_USUARIO=:_nombreUsuario,rol_usuario=:_rolUsuario,FK_ID_DEPARTAMENTO=:_departamento, SUCURSAL=:_sucursal, FK_TIPO_USUARIO=:_tipoUsuario WHERE PK_CODIGO_USUARIO=:_codigoUsuario";
            
            $resultado=$base->prepare($sql);

            $resultado->execute(array(":_codigoUsuario"=>$codigoUsuario,":_nombreUsuario"=>$nombreUsuario,":_rolUsuario"=>$rolUsuario,":_departamento"=>$departamento,":_sucursal"=>$sucursal,":_tipoUsuario"=>$tipoUsuario));

            header("location:usuarios.php");
        }



    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">   
        <div id="div__agregarU">
        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h2>ACTUALIZAR DATOS USUARIO</h2>
                <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $codigoUsuario?>"><br>
                <label  for="NombreUsuario">Nombre usuario:</label>
                <input class="inputA" type="text" name="nombreUsuario" value="<?php echo $nombreUsuario?>"><br>
                <label  for="RolUsuario">Rol usuario:</label>
                <input class="inputA" type="text" name="rolUsuario" value="<?php echo $rolUsuario?>"><br>
                <label for="Departamento">Departamento:</label>
                    <select name="departamento" id="sel__departamento">
                        <option value="<?php echo $departamento?>"><?php echo $departamento?></option> 
                        <?php
        
                            foreach($depart as $departamentos):?>  
                                <option value="<?php echo $departamentos->NOMBRE_DEPARTAMENTO?>"><?php echo $departamentos->NOMBRE_DEPARTAMENTO?></option>
                        <?php
                            endforeach;
                        ?>   
                    </select><br>
                <label for="Sucursal">Sucursal:</label>
                <input class="inputA" type="text" name="sucursal" value="<?php echo $sucursal?>"><br>
                <label for="TipoUsuario">Tipo usuario:</label>
                <select name="tipoUsuario" id="sel__departamento">
                    <option value=1 >Usuario</option>
                    <option value=2>Empleado</option>
                    <option value=3>Administrador</option>
                </select>
                <br>
                <a><input class="btn_env" type="submit" name="btn_actualizar" value="ACTUALIZAR"></a><br> 
        </form>
        <button class="btn_env" type="button" id="btn_abrir_modal">Cambiar contraseña</button><br>
                <dialog id="modal">
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <h2>CAMBIAR CONTRASEÑA</h2>
                        <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $codigoUsuario?>"><br>
                        <label for="Password">Contraseña:</label>
                        <input class="inputA" type="password" name="password" value=""><br>
                        <a><input class="btn_env" type="submit" value="CAMBIAR CONTRASEÑA" name="cambiarC"></a>
                        <button class="btn_env" type="button" id="btn_cerrar_modal">Cancelar</button>
                    </form>
                </dialog>
                <a href="usuarios.php"><input class="btn_vol" type="button" value="< VOLVER"></a> 
                <script src="../js/java.js"></script>
        </div>
    </div> 
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>