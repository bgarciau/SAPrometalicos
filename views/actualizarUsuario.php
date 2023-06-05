<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('head.php')
        ?>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion
    
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

    if (isset($_POST["cambiarC"])) { //Se usa para actualiazr la contraseña 
        $codigoUsuario = $_POST["codigoUsuario"];
        $password = $_POST["password"];
        $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array("cost" => 7)); //cifra la nueva contraseña
    
        $sql = "UPDATE usuario SET pass_usr=:_password WHERE pk_cod_usr=:_codigoUsuario"; //secuencia para actualizar la contraseña del usuario
    
        $resultado = $base->prepare($sql); //prepara la secuencia
    
        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_password" => $pass_cifrado)); //ejecuta la secuencia con los datos 
    
        $cod_usr = $codigoUsuario; //se usa para cargar los datos del usuario nuevamente luego de cambiar su contraseña
    
    } elseif (isset($_POST["actualizarU"])) { //actualiza los datos del usuario
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
    require('header.php');
    $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$cod_usr'")->fetchAll(PDO::FETCH_OBJ); //guarda los datos del usuario para mostrarlos y que puedan ser editados
    foreach ($user as $userr):
        ?>
        <div class="contenedor-carga" id="carga" hidden>
            <img id="centrar-carga" src="../images/carga.gif">
        </div>
        <div class="container py-2" style="min-height: 80vh;" id="principal">
            <div class="text-center">
                <h3>EDITTAR USUARIO</h3>
            </div>
            <form class="p-4" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="f1">
                <div class="mb-3">
                    <label class="form-label">TIPO USUARIO: </label>
                    <select class="form-select" aria-label="Default select example" name="tipoUsuario">
                        <option value=1>USUARIO</option>
                        <option value=2>EMPLEADO</option>
                        <option value=3>ADMINISTRADOR</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">CODIGO USUARIO: </label>
                    <input type="text" class="form-control" name="codigoUsuario" value="<?php echo $userr->pk_cod_usr ?>"
                        autofocus readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">ROL USUARIO: </label>
                    <input type="text" class="form-control" name="rolUsuario" value="<?php echo $userr->rol_usr ?>"
                        autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NOMBRE USUARIO: </label>
                    <input type="text" class="form-control" name="nombreUsuario" value="<?php echo $userr->nom_usr ?>"
                        autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">DEPARTAMENTO: </label>
                    <select class="form-select" aria-label="Default select example" name="departamento">
                        <?php
                        $usrdep = $base->query("SELECT * FROM departamento WHERE pk_dep= '$userr->fk_depart'")->fetchAll(PDO::FETCH_OBJ);
                        foreach ($usrdep as $udep): ?>
                            <option value="<?php echo $udep->pk_dep ?>"><?php echo $udep->nom_dep ?></option>
                            <!-- Se muestra el departamento del usuario y todas las demas opciones -->
                            <?php
                        endforeach;
                        ?>
                        <?php
                        $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
                        foreach ($departamento as $departamentos): ?>
                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?>
                            </option>
                            <?php
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">SUCURSAL: </label>
                    <input type="text" class="form-control" name="sucursal" value="<?php echo $userr->sucursal ?>" autofocus
                        required>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <input type="submit" class="btn btn-success btn-block" name="actualizarU" value="ACTUALIZAR">
                    </div>
                    <div class="col d-flex justify-content-center">
                    <a href="usuarios.php" type="button" class="btn btn-danger btn-block">CANCELAR</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <button type="button" class="btn btn-warning mb-3" id="btnActualizarContraseña">
                            ACTUALIZAR CONTRASEÑA</button>
                    </div>
                </div>
            </form>
            <!-- DIALOG PARA AGREGAR USUARIO -->
    <dialog id="dialogActualizarContraseña" style="min-width: 50%;">
        <div class="card">
            <div class="card-header">
                INGRESAR LA NUEVA CONTRASEÑA:
            </div>
            <form class="p-4" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="f1">
                <div class="mb-3">
                <input type="text" class="form-control" name="codigoUsuario" value="<?php echo $userr->pk_cod_usr ?>"
                        autofocus hidden>
                    <label class="form-label">CONTRASEÑA: </label>
                    <input type="password" class="form-control" id="clave1" name="password" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">CONFIRMAR CONTRASEÑA: </label>
                    <input type="password" class="form-control" id="clave2" name="password2" autofocus required>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <input type="submit" class="btn btn-success btn-block" name="cambiarC" value="ACTUALIZAR" onclick="comprobarClave()">
                    </div>
                    <div class="col d-flex justify-content-center">
                        <input type="button" class="btn btn-danger btn-block" id="btnCerrarContraseña" value="CANCELA">
                    </div>
                </div>
            </form>
        </div>
    </dialog>
        </div>
        <?php
    endforeach;
    require('footer.php')
        ?>
</body>
<script>
    const btnActualizarContraseña = document.getElementById('btnActualizarContraseña');
    const btnCerrarContraseña = document.getElementById('btnCerrarContraseña');
    const dialogActualizarContraseña = document.getElementById('dialogActualizarContraseña');

    btnActualizarContraseña.addEventListener('click', () => {
        dialogActualizarContraseña.showModal();
    });
    btnCerrarContraseña.addEventListener('click', () => {
        dialogActualizarContraseña.close();
    });
</script>
</html>