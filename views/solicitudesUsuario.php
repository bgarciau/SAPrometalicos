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
        <h2>SOLICITUDES USUARIOS</h2>
        <div class="contenedor">
            <button class="btn_sel">servicios</button>
            <button class="btn_sel  ">articulos</button>
            <a class="salir" href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
            <table border="4px" id="tabla__solicitudes">
                
                <tr>
                    <td>Estado</td>
                    <td>Nombre solicitante</td>
                    <td>Sucursal</td>
                    <td>Departamento</td>
                    <td>Descripcion</td>
                    <td>Fecha necesaria</td>
                    <td>Proveedor</td>
                    <td>Precio info</td>
                    <td>OPCIONES</td>
                </tr>
                <?php

                $i = 1;
                while ($i <= 25) {
                ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <a><input class="btn_info" type="button" value="info"></a>
                            <a><input class="btn_aceptar" type="button" value="aceptar"></a>
                            <a><input class="btn_delete" type="button" value="rechazar"></a>
                        </td>
                    </tr>
                    <?php
                    $i = $i + 1;
                }
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