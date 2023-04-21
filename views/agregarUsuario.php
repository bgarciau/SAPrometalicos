<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agregar usuario</title>
    <link rel="icon" type="image/png" href="../images/fav.png" />     <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php

    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");
    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->tipo_usuario; //Sacamos el tipo de usuario de la sesion para saber si es administrador y si no lo es lo mandamos a hacer solicitud
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }

    $depart = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);

    if (isset($_POST["crearU"])) { //se usa cuando se manda el formulario con los datos del usuario
    
        if ($_POST["password"] == $_POST["password2"]) { //si las claves son iguales crea el usuario en la base de datos
            $codigoUsuario = $_POST["codigoUsuario"];
            $nombreUsuario = $_POST["nombreUsuario"];
            $rolUsuario = $_POST["rolUsuario"];
            $departamento = $_POST["departamento"];
            $sucursal = $_POST["sucursal"];
            $password = $_POST["password"];
            $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array("cost" => 7)); //Encripta la clave 
            $tipoUsuario = $_POST["tipoUsuario"];

            $sql = "INSERT INTO usuario (pk_cod_usr,nom_usr,rol_usr,fk_depart,sucursal,pass_usr,tipo_usuario) 
                VALUES(:_codigoUsuario,:_nombreUsuario,:_rolUsuario,:_departamento,:_sucursal,:_password,:_tipoUsuario)";

            $resultado = $base->prepare($sql); //Prepara una sentencia SQL para ser ejecutada por el método execute
    
            $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_nombreUsuario" => $nombreUsuario, ":_rolUsuario" => $rolUsuario, ":_departamento" => $departamento, ":_sucursal" => $sucursal, ":_password" => $pass_cifrado, ":_tipoUsuario" => $tipoUsuario)); //se ejecuta el resultado y se definen las variables que se van a enviar a la base de datos
    
            header("location:usuarios.php"); //lo manda a la lista de usuarios
        } else { //si las claves no son iguales les hace un post a las variables para volverlas a cargar e intentar nuevamente la contraseña
            $codigoUsuario = $_POST["codigoUsuario"];
            $nombreUsuario = $_POST["nombreUsuario"];
            $rolUsuario = $_POST["rolUsuario"];
            $departamento = $_POST["departamento"];
            $sucursal = $_POST["sucursal"];
            $password = $_POST["password"];
            $tipoUsuario = $_POST["tipoUsuario"];
        }
    }
    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php');
            ?>
        </header>
        <div class="contenedor">
            <div id="div_agregar_usuario">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="f1">
                    <!-- Se usa el php_self para hacer el action del form en el mismo archivo php   -->
                    <h2>AGREGAR USUARIO</h2>
                    <label class="label_usuario" for="CodigoUsuario">Codigo usuario:</label>
                    <input class="inputUsuarios" type="text" id="CodigoUsuario" name="codigoUsuario" value="<?php if (isset($_POST['codigoUsuario'])) { //la condicion en el value se usa para saber si ya se habia hechoo un post en el formulario, si este fallo se vuelven a cargar los datos para que se vuelva a intentar el formulario
                            echo $_POST['codigoUsuario'];
                        } ?>" required><br>
                    <label class="label_usuario" for="RolUsuario">Rol usuario:</label>
                    <input class="inputUsuarios" type="text" id="RolUsuario" name="rolUsuario" value="<?php if (isset($_POST['rolUsuario'])) {
                        echo $_POST['rolUsuario'];
                    } ?>" required><br>
                    <label class="label_usuario" for="NombreUsuario">Nombre usuario:</label>
                    <input class="inputUsuarios" type="text" id="NombreUsuario" name="nombreUsuario" value="<?php if (isset($_POST['nombreUsuario'])) {
                        echo $_POST['nombreUsuario'];
                    } ?>" required><br>
                    <label class="label_usuario" for="Departamento">Departamento:</label>
                    <select name="departamento" class="select_formulario" required>
                        <?php
                        if (isset($_POST['departamento'])) {
                            $depart2 = $_POST['departamento'];
                            $departa = $base->query("SELECT * FROM departamento WHERE pk_dep= '$depart2'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($departa as $departam):

                                ?>
                                <option value="<?php echo $departam->pk_dep ?>"><?php echo $departam->nom_dep ?></option>
                                <?php
                            endforeach;
                        }
                        ?>
                        <?php foreach ($depart as $departamentos): ?>
                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?>
                            </option>
                            <?php
                        endforeach;
                        ?>
                    </select><br>
                    <label class="label_usuario" for="Sucursal">Sucursal:</label>
                    <input class="inputUsuarios" type="text" id="Sucursal" name="sucursal" value="<?php if (isset($_POST['sucursal'])) {
                        echo $_POST['sucursal'];
                    } ?>" required><br>
                    <label class="label_usuario" for="Password">Contraseña:</label>
                    <input class="inputUsuarios" type="password" id="clave1" name="password" value="<?php if (isset($_POST['password'])) {
                        echo $_POST['password'];
                    } ?>" required><br>
                    <label class="label_usuario" for="Password">Confirmar Contraseña:</label>
                    <input class="inputUsuarios" type="password" id="clave2" name="password2" required><br>
                    <label class="label_usuario" for="TipoUsuario">Tipo usuario:</label>
                    <select name="tipoUsuario" id="select_formulario" class="select_formulario" required>
                        <option value=1>Usuario</option>
                        <option value=2>Empleado</option>
                        <option value=3>Administrador</option>
                    </select>

                    <br><br>
                    <a><input class="btn_crear_usuario" type="submit" value="CREAR USUARIO" name="crearU"
                            onclick="comprobarClave()"></a><!-- Este boton envia los datos del nuevo usuario y tambien verifica si las claves son iguales y si no lo son carga nuevamente los datos dejando el campo de la contraseña -->
                   <br>
                            <a href="usuarios.php"><input class="btn_volver" type="button" value="< VOLVER"></a>
                    <!-- Vuelve al listado de usuarios sin guardar datos -->
            </div>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php');
            ?>
        </footer>
    </div>
    </form>
    <script>
        function comprobarClave() { //funcion para coomprobar si las claves son iguales
            let clave1 = document.f1.clave1.value
            let clave2 = document.f1.clave2.value

            if (clave1 == clave2) {


            } else {
                alert("Las dos claves son distintas...\nvuelva a intentarlo") //si las calves son diferentes saca una alerta

            }
        }
    </script>
</body>

</html>
W