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
        $registros=$base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
        <a class="agregarUsuario" href="agregarUsuario.php"><input class="btn_add" type="button" value="+AGREGAR"></a>
        <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>   
            <table border="4px" id="tabla__solicitudes">
                <tr>
                    <td>#</td>
                    <td>Nombre usuario</td>
                    <td>Departamento</td>
                    <td>Sucursal</td>
                    <td>Codigo de usuario</td>
                    <td>OPCIONES</td>
                </tr>
                <?php
                    $i=1;
                    foreach($registros as $usuario):?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo $usuario->NOMBRE_USUARIO?></td>
                            <td><?php echo $usuario->FK_ID_DEPARTAMENTO?></td>
                            <td><?php echo $usuario->SUCURSAL?></td>
                            <td><?php echo $usuario->PK_CODIGO_USUARIO?></td>
                            <td>
                                <a href="../views/actualizarUsuario.php?codigoUsuario=<?php echo $usuario->PK_CODIGO_USUARIO?>& nombreUsuario=<?php echo $usuario->NOMBRE_USUARIO?>& departamento=<?php echo $usuario->FK_ID_DEPARTAMENTO?>& sucursal=<?php echo $usuario->SUCURSAL?>& password=<?php echo $usuario->PASSWORD?> & tipoUsuario=<?php echo $usuario->FK_TIPO_USUARIO?>"><input class="btn_update" type="button" value="update"></a>
                                <a href="../crud/eliminar_usuario.php?codigoUsuario=<?php echo $usuario->PK_CODIGO_USUARIO?>"><input class="btn_delete" type="button" value="delete"></a>
                            </td>
                        </tr>
                <?php
                        $i=$i+1;
                    endforeach;
                ?>    
            </table>
        </div>
    </div>
    <footer>
    <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>
</html>