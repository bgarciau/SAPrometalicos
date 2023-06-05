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

    require('header.php');
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>USUARIOS</h3>
            <button class="btn btn-success btn-sm mb-3" id="btnAgregarUsuario"><i class="bi bi-plus-square-dotted">
                    AGREGAR</i></button>
        </div>
        <div class="overflow-x-scroll" id="tArticulos">
            <table class="table table-bordered table-striped table-hover" id="tablaUsuarios">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Codigo de usuario</th>
                        <th>Nombre usuario</th>
                        <th>Rol usuario</th>
                        <th>Departamento</th>
                        <th>Sucursal</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuar = $base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ); //se llaman todos los usuarios de la base de datos
                    $i = 1; //numero de usuario
                    foreach ($usuar as $usuario): ?>
                        <!-- se usan los datos del usuario para llenar la tabla -->
                        <tr>
                            <td>
                                <?php echo $i ?> <!-- numero de usuario en la tabla -->
                            </td>
                            <td>
                                <?php echo $usuario->pk_cod_usr ?> <!-- Codigo del usuario -->
                            </td>
                            <td>
                                <?php echo $usuario->nom_usr ?> <!-- Nombre del usuario -->
                            </td>
                            <td>
                                <?php echo $usuario->rol_usr ?> <!-- rol del usuario -->
                            </td>
                            <td>
                                <?php
                                $depart = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usuario->fk_depart'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($depart as $departt) {
                                    echo $departt->nom_dep;
                                }
                                ?> <!--  Departamento del usuario -->
                            </td>
                            <td>
                                <?php echo $usuario->sucursal ?> <!-- Sucursal del usuario -->
                            </td>
                            <td class="opcionesTabla"> <!-- Clase para centrar los botones -->
                                <a class="text-warning"
                                    href="actualizarUsuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><i
                                        class="bi bi-pencil-square">EDITAR</i></a>
                                <!-- Boton para actualizar los datos del usuario, mandando su id -->
                                <a onclick="return confirm('Estas seguro de eliminar?');" class="text-danger"
                                    href="../crud/eliminar_usuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><i
                                        class="bi bi-trash-fill">BORRAR</i></a>
                                <!-- Boton para eliminar usuario segun la id seleccionada -->
                            </td>
                        </tr>
                        <?php
                        $i = $i + 1; //se suma uno por cada usuario
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- DIALOG PARA AGREGAR USUARIO -->
    <dialog id="dialogAgregarUsuario" style="min-width: 50%;">
        <div class="card">
            <div class="card-header">
                INGRESAR USUARIO:
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
                    <input type="text" class="form-control" name="codigoUsuario" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ROL USUARIO: </label>
                    <input type="text" class="form-control" name="rolUsuario" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NOMBRE USUARIO: </label>
                    <input type="text" class="form-control" name="nombreUsuario" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">DEPARTAMENTO: </label>
                    <select class="form-select" aria-label="Default select example" name="departamento">
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
                    <input type="text" class="form-control" name="sucursal" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">CONTRASEÑA: </label>
                    <input type="password" class="form-control" id="clave1" name="password" autofocus required>
                </div>
                <div class="mb-3">
                    <label class="form-label">CONFIRMAR CONTRASEÑA: </label>
                    <input type="password" class="form-control" id="clave2" name="password2" autofocus required>
                </div>
                <div class="row">
                    <div class="col d-flex justify-content-center">
                        <input type="submit" class="btn btn-success btn-block" name="crearU" value="AGREGAR" onclick="comprobarClave()">
                    </div>
                    <div class="col d-flex justify-content-center">
                        <input type="button" class="btn btn-danger btn-block" id="btnCerrarUsuario" value="CANCELAR">
                    </div>
                </div>
            </form>
        </div>
    </dialog>
    </div>
    <?php
    require('footer.php')
        ?>
</body>
<script>
    $('#tablaUsuarios').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
    });
    // DIALOG PARA AGREGAR USUARIOS
    const btnAgregarUsuario = document.getElementById('btnAgregarUsuario');
    const btnCerrarUsuario = document.getElementById('btnCerrarUsuario');
    const dialogAgregarUsuario = document.getElementById('dialogAgregarUsuario');

    btnAgregarUsuario.addEventListener('click', () => {
        dialogAgregarUsuario.showModal();
    });
    btnCerrarUsuario.addEventListener('click', () => {
        dialogAgregarUsuario.close();
    });

    // COMPRUEBA CLAVE
    function comprobarClave() { //funcion para coomprobar si las claves son iguales
        let clave1 = document.f1.clave1.value
        let clave2 = document.f1.clave2.value

        if (clave1 == clave2) {


        } else {
            alert("Las dos claves son distintas...\nvuelva a intentarlo") //si las calves son diferentes saca una alerta

        }
    }
</script>

</html>