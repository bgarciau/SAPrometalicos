<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
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
        $userx = $Tusuario->fk_tipo_usr;
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }

    $usuar = $base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>USUARIOS</h2>
            <div id="div__tablaSolicitudes">
                <div id="div__agregar">
                    <a class="agregarUsuario" href="agregarUsuario.php"><input class="btn_add" type="button"
                            value="+AGREGAR"></a>
                </div>
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <div class="table_wrapperS">
                        <input class="inputBuscar" type="search" name="" value="BUSCAR">
                        <table border="4px" id="tabla__usuarios">
                            <thead>
                                <th>#</th>
                                <th>Codigo de usuario</th>
                                <th>Nombre usuario</th>
                                <th>Rol usuario</th>
                                <th>Departamento</th>
                                <th>Sucursal</th>
                                <th>OPCIONES</th>
                            </thead>
                            <?php
                            $i = 1;
                            foreach ($usuar as $usuario): ?>
                                <tr>
                                    <td>
                                        <?php echo $i ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario->pk_cod_usr ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario->nom_usr ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario->rol_usr ?>
                                    </td>
                                    <td>
                                        <?php
                                          $depart = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usuario->fk_depart'")->fetchAll(PDO::FETCH_OBJ);
                                          foreach ($depart as $departt){
                                            echo $departt->nom_dep;
                                          }
                                           ?>
                                    </td>
                                    <td>
                                        <?php echo $usuario->sucursal ?>
                                    </td>
                                    <td class="opcionesTabla">
                                        <a
                                            href="actualizarUsuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><input
                                                class="btn_update" type="button" value="update"></a>
                                        <a
                                            href="../crud/eliminar_usuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><input
                                                class="btn_delete" type="button" value="delete"></a>
                                    </td>
                                </tr>
                                <?php
                                $i = $i + 1;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>

</html>