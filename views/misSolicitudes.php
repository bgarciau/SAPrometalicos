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
        <h2>MIS SOLICITUDES</h2>
        <div id="div__tablaSolicitudes">
            <button class="btn_sel">servicios</button>
            <button class="btn_sel  ">articulos</button>
            <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
            <div class="outer_wrapperS">
            <div class="table_wrapperS">
            <input class="inputBuscar" type="search" name="" value="BUSCAR">
                <table id="tabla__solicitudes">
                    <thead>
                        <th>Estado</th>
                        <th>Nombre solicitante</th>
                        <th>Sucursal</th>
                        <th>Departamento</th>
                        <th>Descripcion</th>
                        <th>Fecha necesaria</th>
                        <th>Proveedor</th>
                        <th>Precio info</th>
                        <th>OPCIONES</th>
                    </thead>
                    <?php

                    $i = 1;
                    while ($i <= 40) {
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
                            <td class="opcionesTabla">
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
        </div>
    </div>
    <footer>
    <?php
    require_once('../php/footer.php');
    ?>
    </footer>
</body>
</html>