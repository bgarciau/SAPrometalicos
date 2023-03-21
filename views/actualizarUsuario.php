<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/modals.css">
</head>

<body>

    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");

    if (isset($_POST["cambiarC"])) {
        $codigoUsuario = $_POST["codigoUsuario"];
        $password = $_POST["password"];
        $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array("cost" => 7));

        $sql = "UPDATE usuario SET pass_usr=:_password WHERE pk_cod_usr=:_codigoUsuario";

        $resultado = $base->prepare($sql);

        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_password" => $pass_cifrado));

        $cod_usr = $codigoUsuario;
    } elseif (isset($_POST["btn_actualizar"])) {
        $codigoUsuario = $_POST["codigoUsuario"];
        $nombreUsuario = $_POST["nombreUsuario"];
        $rolUsuario = $_POST["rolUsuario"];
        $departamento = $_POST["departamento"];
        $sucursal = $_POST["sucursal"];
        $tipoUsuario = $_POST["tipoUsuario"];

        $sql = "UPDATE usuario SET nom_usr=:_nombreUsuario,rol_usr=:_rolUsuario,fk_depart=:_departamento, sucursal=:_sucursal, fk_tipo_usr=:_tipoUsuario WHERE pk_cod_usr=:_codigoUsuario";

        $resultado = $base->prepare($sql);

        $resultado->execute(array(":_codigoUsuario" => $codigoUsuario, ":_nombreUsuario" => $nombreUsuario, ":_rolUsuario" => $rolUsuario, ":_departamento" => $departamento, ":_sucursal" => $sucursal, ":_tipoUsuario" => $tipoUsuario));
        $cod_usr = $codigoUsuario;
        
    } else {
        $cod_usr = $_GET["codigoUsuario"];
    }

    $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$cod_usr'")->fetchAll(PDO::FETCH_OBJ);
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
                    <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $userr->pk_cod_usr ?>"><br>
                    <label class="label2" for="NombreUsuario">Nombre usuario:</label>
                    <input class="inputA" type="text" name="nombreUsuario" value="<?php echo $userr->nom_usr ?>"><br>
                    <label class="label2" for="RolUsuario">Rol usuario:</label>
                    <input class="inputA" type="text" name="rolUsuario" value="<?php echo $userr->rol_usr ?>"><br>
                    <label class="label2" for="Departamento">Departamento:</label>
                    <select name="departamento" id="datosFormu">
                        <?php
                        $usrdep = $base->query("SELECT * FROM departamento WHERE pk_dep= '$userr->fk_depart'")->fetchAll(PDO::FETCH_OBJ); foreach ($usrdep as $udep): ?>
                            <option value="<?php echo $udep->pk_dep ?>"><?php echo $udep->nom_dep ?></option>
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
                    <input class="inputA" type="text" name="sucursal" value="<?php echo $userr->sucursal ?>"><br>
                    <label class="label2" for="TipoUsuario">Tipo usuario:</label>
                    <select name="tipoUsuario" id="datosFormu">
                        <option value=1>usuario</option>
                        <option value=2>empleado</option>
                        <option value=3>administrador</option>
                    </select>
                    <br>
                    <a><input class="btn_env3" type="submit" name="btn_actualizar" value="ACTUALIZAR"></a><br>
                </form>
                <button class="btn_env3" type="button" id="btn_abrir_modal">Cambiar contraseña</button><br>
                <dialog id="modal">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="f1">
                        <h2>CAMBIAR CONTRASEÑA</h2>
                        <input class="inputA" type="hidden" name="codigoUsuario" value="<?php echo $cod_usr ?>"><br>
                        <label class="label2" for="Password">Contraseña:</label>
                    <input class="inputA" type="password" id="clave1" name="password" value="<?php if (isset($_POST['password'])) {
                        echo $_POST['password'];
                    } ?>" required><br>
                    <label class="label2" for="Password">Confirmar Contraseña:</label>
                    <input class="inputA" type="password" id="clave2" name="password2" required><br>
                        <a><input class="btn_env4" type="submit" value="CAMBIAR CONTRASEÑA" name="cambiarC" onclick="comprobarClave()"></a>
                        <button class="btn_env4" type="button" id="btn_cerrar_modal">Cancelar</button>
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
        </div>
        <?php
    endforeach;
    ?>
</body>

</html>