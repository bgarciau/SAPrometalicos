<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Actualizar usuario</title>
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css"> <!-- estilo para el contenido de la pagina -->
    <link rel="stylesheet" href="../css/modals.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <!-- Se usa el estilo para el cuadro emergente del cambio de contraseña -->
</head>

<body>

    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //si el usuario no se ha registrado lo manda para el inicio de sesion
    
        header("location:../index.php");
    }

    include("../php/conexion.php"); //incluye la conexion a la base de datos
    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->tipo_usuario; //Sacamos el tipo de usuario de la sesion para saber si es administrador y si no lo es lo mandamos a hacer solicitud
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }

    if (isset($_POST["cambiarC"])) { //Se usa para actualiazr la contraseña 
        $codigoUsuario = $_POST["codigoUsuario"];
        $password = $_POST["password"];
        $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array("cost" => 7)); //cifra la nueva contraseña
    
        $sql = "UPDATE usuario SET pass_usr=:_password WHERE pk_cod_usr=:_codigoUsuario"; //secuencia para actualizar la contraseña del usuario
    
        $resultado = $base->prepare($sql); //prepara la secuencia
    
        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_password" => $pass_cifrado)); //ejecuta la secuencia con los datos 
    
        $cod_usr = $codigoUsuario; //se usa para cargar los datos del usuario nuevamente luego de cambiar su contraseña
    } elseif (isset($_POST["btn_actualizar"])) { //actualiza los datos del usuario
        $codigoUsuario = $_POST["codigoUsuario"];
        $nombreUsuario = $_POST["nombreUsuario"];
        $rolUsuario = $_POST["rolUsuario"];
        $departamento = $_POST["departamento"];
        $sucursal = $_POST["sucursal"];
        $tipoUsuario = $_POST["tipoUsuario"];

        //secuencia para actualizar algunos datos del usuario
        $sql = "UPDATE usuario SET nom_usr=:_nombreUsuario,rol_usr=:_rolUsuario,fk_depart=:_departamento, sucursal=:_sucursal, tipo_usuario=:_tipoUsuario WHERE pk_cod_usr=:_codigoUsuario";

        $resultado = $base->prepare($sql); //prepara la secuencia
    
        //ejecuta la secuencia con los datos requeridos
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
                require_once('../php/header.php'); //carga el header de la pagina
                ?>
            </header>
            <div class="contenedor" id="carga" hidden>
                <img id="centrar-carga" src="../images/carga10.gif">
            </div>
            <div class="contenedor" id="principal"> <!-- contenido entre el header y el footer -->
                <div id="div_agregar_usuario">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <!-- formulario para agregar usuario -->
                        <h2>ACTUALIZAR DATOS USUARIO</h2>
                        <input class="inputUsuarios" type="hidden" name="codigoUsuario"
                            value="<?php echo $userr->pk_cod_usr ?>"><br>
                        <!-- Se llaman el codigo del usuario pero se pone el hidden porque este codigo es unico y no se puede modificar -->
                        <label class="label_usuario" for="NombreUsuario">Nombre usuario:</label>
                        <input class="inputUsuarios" type="text" name="nombreUsuario"
                            value="<?php echo $userr->nom_usr ?>"><br> <!-- Se llama el codigo del usuario -->
                        <label class="label_usuario" for="RolUsuario">Rol usuario:</label>
                        <input class="inputUsuarios" type="text" name="rolUsuario"
                            value="<?php echo $userr->rol_usr ?>"><br> <!-- Se llama el rol del usuario -->
                        <label class="label_usuario" for="Departamento">Departamento:</label>
                        <select name="departamento" class="select_formulario2">
                            <?php
                            $usrdep = $base->query("SELECT * FROM departamento WHERE pk_dep= '$userr->fk_depart'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($usrdep as $udep): ?>
                                <option value="<?php echo $udep->pk_dep ?>"><?php echo $udep->nom_dep ?></option>
                                <!-- Se muestra el departamento del usuario y todas las demas opciones -->
                                <?php
                            endforeach;
                            ?>
                            <?php
                            $depart = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($depart as $departamentos): ?>
                                <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?>
                                </option>
                                <?php
                            endforeach;
                            ?>
                        </select><br>
                        <label class="label_usuario" for="Sucursal">Sucursal:</label>
                        <input class="inputUsuarios" type="text" name="sucursal" value="<?php echo $userr->sucursal ?>"><br>
                        <!-- Se muestra la sucursal del usuario -->
                        <label class="label_usuario" for="TipoUsuario">Tipo usuario:</label>
                        <select name="tipoUsuario" class="select_formulario2">
                            <!-- Opcion segun el usuario seleccionado -->
                            <?php
                            if ($userr->tipo_usuario == 1) {
                                ?>
                                <option value=1>usuario</option>

                                <?php
                            }
                            if ($userr->tipo_usuario == 2) {
                                ?>
                                <option value=2>empleado</option>
                                <?php
                            }
                            if ($userr->tipo_usuario == 3) {
                                ?>
                                <option value=3>administrador</option>
                                <?php
                            }
                            ?>
                            <!-- ---------------------------------- -->
                            <option value=1>usuario</option>
                            <option value=2>empleado</option>
                            <option value=3>administrador</option>
                        </select>
                        <br>
                        <a><input class="btn_cambiar_contraseña" type="submit" name="btn_actualizar"
                                value="ACTUALIZAR"></a><br>
                    </form>
                    <button class="btn_cambiar_contraseña" type="button" id="btn_abrir_modal">Cambiar
                        contraseña</button><br>
                    <!-- Este boton abre el dialog que es un cuadro emergente, este se usa para cambiar la contraseña -->
                    <dialog id="modal">
                        <!-- Este dialog es el recuadro emergente, y solo se ve cuando se le da al boton este contenido -->
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="f1">
                            <!-- Este formulario se usa para el cambio de contraseña -->
                            <h2>CAMBIAR CONTRASEÑA</h2>
                            <input class="inputUsuarios" type="hidden" name="codigoUsuario"
                                value="<?php echo $cod_usr ?>"><br>
                            <label class="label_usuario" for="Password">Contraseña:</label>
                            <input class="inputUsuarios" type="password" id="clave1" name="password" value="<?php if (isset($_POST['password'])) { //este campo vuelve a c argar la contraseña si por algun motivo la contraseña no era igual
                                        echo $_POST['password'];
                                    } ?>" required><br>
                            <label class="label_usuario" for="Password">Confirmar Contraseña:</label>
                            <input class="inputUsuarios" type="password" id="clave2" name="password2" required><br>
                            <input class="btn_contraseña" type="button" value="CAMBIAR CONTRASEÑA" id="cambiar"
                                onclick="comprobarClave()">
                            <!-- Este boton se manda los datos del formulario, y tambien comprueba si la contraseña es la misma -->
                            <button class="btn_contraseña" type="button" id="btn_cerrar_modal">Cancelar</button>
                            <!-- botoon para cerrrar el dialog -->
                            <input class="btn_contraseña" type="submit" value="CAMBIAR CONTRASEÑA" name="cambiarC"
                                id="cambiarC" hidden>
                        </form>
                        <script>
                            function pantallaCarga() {
                                $('#principal').fadeOut();
                                $('#carga').prop("hidden", false);
                            }
                            function comprobarClave() { //funcion que comparar ambas claves y verificar que sean iguales
                                let clave1 = document.f1.clave1.value
                                let clave2 = document.f1.clave2.value

                                if (clave1 == clave2) { //si las claves son iguales envia el formulario
                                    console.log("las claves son iguales")
                                    document.getElementById('cambiarC').click();

                                } else {//si las claves son diferentes muestra una alera
                                    alert("Las dos claves son distintas...\nvuelva a intentarlo")

                                }
                            }
                        </script>
                    </dialog>
                    <a href="usuarios.php"><input class="btn_volver" type="button" value="< VOLVER"></a>
                    <!-- boton para volver -->
                    <script src="../js/java.js"></script>
                    <!-- Se carga este javascript para poder abrir el dialog cuando se le de al boton de cambiar contraseña -->
                </div>
            </div>
            <footer>
                <?php
                require_once('../php/footer.php'); //carga el foooter de la pagina
                ?>
            </footer>
        </div>
        <?php
    endforeach;
    ?>
</body>

</html>