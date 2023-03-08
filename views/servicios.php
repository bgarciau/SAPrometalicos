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
    include("../php/SAP.php");

    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->fk_tipo_usr;
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }

    $serv = $base->query("SELECT * FROM arse WHERE tipo_arse= 'servicio'")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>SERVICIOS</h2>
            <div id="div__tablaSolicitudes">
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <div class="table_wrapperS">
                        <input class="inputBuscar" type="search" name="" value="BUSCAR">
                        <table border="4px" id="tabla__usuarios">
                            <thead>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Cuenta Mayor</th>
                                <th>UEN</th>
                                <th>Linea</th>
                                <th>Sublinea</th>
                            </thead>
                            <?php
                            $i = 1;
                            foreach ($respuestaServicios->value as $item): ?>
                                <tr>
                                    <td>
                                        <?php echo $i ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->Code" . PHP_EOL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->Name" . PHP_EOL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_CuentaCosto" . PHP_EOL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_UEN" . PHP_EOL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_Linea" . PHP_EOL;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_SubLinea" . PHP_EOL;
                                        ?>
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