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

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>SOLICITUDES USUARIO</h2>
            <div id="div__tablaSolicitudes">
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <form id="menu">
                        <button class="btn_sel" name="xtabla" value="tservicios">servicios</button>
                        <button class="btn_sel" name="xtabla" value="tarticulos">articulos</button>
                    </form>
                    <?php
                    $xtabla = "tservicios";
                    if (isset($_GET["xtabla"])) {
                        $xtabla = $_GET["xtabla"];
                    }
                    if ($xtabla == "tservicios") { ?>
                        <!-- tabla servicios -->
                        <div class="table_wrapperS">
                            <input class="inputBuscar" type="search" name="" value="BUSCAR">
                            <table id="tabla__solicitudes">
                                <thead>
                                    <th>N° Sol</th>
                                    <th>Estado</th>
                                    <th>Nombre solicitante</th>
                                    <th>Sucursal</th>
                                    <th>Departamento</th>
                                    <th>Corre electronico</th>
                                    <th>Cantidad de sevicios</th>
                                    <th>propietario</th>
                                    <th>Comentarios</th>
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
                                        <td></td>
                                        <td class="opcionesTabla">
                                            <a href="infoSol.php"><input class="btn_info" type="button" value="info"></a>
                                            <a><input class="btn_aceptar" type="button" value="aceptar"></a>
                                            <a><input class="btn_delete" type="button" value="rechazar"></a>
                                        </td>
                                    </tr>
                                <?php
                                    $i = $i + 1;
                                }
                                ?>
                            </table>
                        <?php
                    } else { ?>
                            <!-- tabla articulos -->
                            <div class="table_wrapperS">
                                <input class="inputBuscar" type="search" name="" value="BUSCAR">
                                <table id="tabla__solicitudes">
                                    <thead>
                                        <th>N° Sol</th>
                                        <th>Estado</th>
                                        <th>Nombre solicitante</th>
                                        <th>Sucursal</th>
                                        <th>Departamento</th>
                                        <th>Corre electronico</th>
                                        <th>Cantidad de articulos</th>
                                        <th>propietario</th>
                                        <th>Comentarios</th>
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
                                            <td></td>
                                            <td class="opcionesTabla">
                                                <a href="infoSol.php"><input class="btn_info" type="button" value="info"></a>
                                                <a><input class="btn_aceptar" type="button" value="aceptar"></a>
                                                <a><input class="btn_delete" type="button" value="rechazar"></a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i = $i + 1;
                                    }
                                    ?>
                                </table>
                            <?php
                        } ?>
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