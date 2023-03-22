<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/modals.css">  <!-- Se usa para el cuadro emergente del cambio de contraseña -->
</head>
<body>

    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");

    if (isset($_POST["cambiarC"])) { //Se usa para actualiazr la contraseña 
        $codigoUsuario = $_POST["codigoUsuario"];
        $password = $_POST["password"];
        $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array("cost" => 7));

        $sql = "UPDATE usuario SET pass_usr=:_password WHERE pk_cod_usr=:_codigoUsuario"; //secuencia para actualizar la contraseña del usuario

        $resultado = $base->prepare($sql);

        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_password" => $pass_cifrado));

        $cod_usr = $codigoUsuario; //se usa para cargar los datos del usuario nuevamente luego de cambiar su contraseña
    } elseif (isset($_POST["btn_actualizar"])) { //se usa para actualizar los datos del usuario
        $codigoUsuario = $_POST["codigoUsuario"];
        $nombreUsuario = $_POST["nombreUsuario"];
        $rolUsuario = $_POST["rolUsuario"];
        $departamento = $_POST["departamento"];
        $sucursal = $_POST["sucursal"];
        $tipoUsuario = $_POST["tipoUsuario"];

        $sql = "UPDATE usuario SET nom_usr=:_nombreUsuario,rol_usr=:_rolUsuario,fk_depart=:_departamento, sucursal=:_sucursal, fk_tipo_usr=:_tipoUsuario WHERE pk_cod_usr=:_codigoUsuario";

        $resultado = $base->prepare($sql);

        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_nombreUsuario" => $nombreUsuario, ":_rolUsuario" => $rolUsuario, ":_departamento" => $departamento, ":_sucursal" => $sucursal, ":_tipoUsuario" => $tipoUsuario));
        $cod_usr = $codigoUsuario; //se usa para cargar los nuevos datos del usuario
        
    } else {
        $cod_usr = $_GET["codigoUsuario"]; //se usa para cargar los datos del usuario si no se ha cambiado nada
    }

    $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$cod_usr'")->fetchAll(PDO::FETCH_OBJ); //guarda los datos del usuario para mostrarlos y que puedan ser editados
    foreach ($user as $userr): ?>
        <div class="base">
        <header>
            <?php
            require_once('../php/header.php');
            ?>
        </header>
        <div class="contenedor">
            <div id="div__agregarU">
                <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h2>ACTUALIZAR DATOS USUARIO</h2>
                    <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $userr->pk_cod_usr ?>"><br>   <!-- Se llaman el codigo del usuario pero se pone el hidden porque este codigo es unico y no se puede modificar -->
                    <label class="label2" for="NombreUsuario">Nombre usuario:</label>
                    <input class="inputA" type="text" name="nombreUsuario" value="<?php echo $userr->nom_usr ?>"><br> <!-- Se llama el codigo del usuario -->
                    <label class="label2" for="RolUsuario">Rol usuario:</label>
                    <input class="inputA" type="text" name="rolUsuario" value="<?php echo $userr->rol_usr ?>"><br> <!-- Se llama el rol del usuario -->
                    <label class="label2" for="Departamento">Departamento:</label>
                    <select name="departamento" id="datosFormu">
                        <?php
                        $usrdep = $base->query("SELECT * FROM departamento WHERE pk_dep= '$userr->fk_depart'")->fetchAll(PDO::FETCH_OBJ); foreach ($usrdep as $udep): ?>
                            <option value="<?php echo $udep->pk_dep ?>"><?php echo $udep->nom_dep ?></option> <!-- Se muestra el departamento del usuario y todas las demas opciones -->
                            <?php
                        endforeach;
                        ?>
                        <?php
                        $depart = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ); foreach ($depart as $departamentos): ?>
                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                            <?php
                        endforeach;
                        ?>
                    </select><br>
                    <label class="label2" for="Sucursal">Sucursal:</label>
                    <input class="inputA" type="text" name="sucursal" value="<?php echo $userr->sucursal ?>"><br> <!-- Se muestra la sucursal del usuario -->
                    <label class="label2" for="TipoUsuario">Tipo usuario:</label>
                    <select name="tipoUsuario" id="datosFormu">
                        <option value=1>usuario</option>
                        <option value=2>empleado</option>
                        <option value=3>administrador</option>
                    </select>
                    <br>
                    <a><input class="btn_env3" type="submit" name="btn_actualizar" value="ACTUALIZAR"></a><br>
                </form>
                <button class="btn_env3" type="button" id="btn_abrir_modal">Cambiar contraseña</button><br> <!-- Este boton abre el dialog que es un cuadro emergente, este se usa para cambiar la contraseña -->
                <dialog id="modal"> <!-- Este dialog es el recuadro emergente, y solo se ve cuando se le da al boton este contenido -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="f1"> <!-- Este formulario se usa para el cambio de contraseña -->
                        <h2>CAMBIAR CONTRASEÑA</h2>
                        <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $cod_usr ?>"><br>
                        <label class="label2" for="Password">Contraseña:</label>
                    <input class="inputA" type="password" id="clave1" name="password" value="<?php if (isset($_POST['password'])) { //este campo vuelve a c argar la contraseña si por algun motivo la contraseña no era igual
                        echo $_POST['password'];
                    } ?>" required><br>
                    <label class="label2" for="Password">Confirmar Contraseña:</label>
                    <input class="inputA" type="password" id="clave2" name="password2" required><br>
                        <a><input class="btn_env4" type="submit" value="CAMBIAR CONTRASEÑA" name="cambiarC" onclick="comprobarClave()"></a> <!-- Este boton se manda los datos del formulario, y tambien comprueba si la contraseña es la misma -->
                        <button class="btn_env4" type="button" id="btn_cerrar_modal">Cancelar</button> <!-- botoon para cerrrar el dialog -->
                    </form>
                    <script>
        function comprobarClave() { //funcion que compara ambas claves para verificar que sean iguales
            let clave1 = document.f1.clave1.value
            let clave2 = document.f1.clave2.value

            if (clave1 == clave2) {


            } else {
                alert("Las dos claves son distintas...\nvuelva a intentarlo")

            }
        }
    </script>
                </dialog>
                <a href="usuarios.php"><input class="btn_vol" type="button" value="< VOLVER"></a> <!-- boton para volver -->
                <script src="../js/java.js"></script> <!-- Se carga este javascript para poder abrir el dialog cuando se le de al boton de cambiar contraseña -->
            </div>
        </div>
            <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
        </div>
        <?php
    endforeach;
    ?>
</body>

</html>