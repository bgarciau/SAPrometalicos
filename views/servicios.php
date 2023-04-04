<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="icon" type="image/png" href="../images/fav.png"/>     <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }
    include("../php/conexion.php");
    include("../php/SAP.php"); //Se usa para poder usar los valores obtenidos del SAP
    $respuestaServicios= servicios($sesion);
    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->tipo_usuario; //Sacamos el tipo de usuario de la sesion para saber si es administrador y si no lo es lo mandamos a hacer solicitud
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }
    ?>
    <div class="base">
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
        <div class="contenedor">
            <h2>SERVICIOS</h2>
            <div id="div_tablas">
                <div id="div_boton_volver">
                    <a href="hacerSolicitud.php"><input class="btn_volver" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <div class="table_wrapperS">
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
                            $i = 1; //numero de articulo en la tabla
                            foreach ($respuestaServicios->value as $item): ?> <!-- se toma el respuestaServicios del SAP.php y se usa para llenar la tabla con los servicios obtenidos -->
                                <tr>
                                    <td>
                                        <?php echo $i ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->Code" . PHP_EOL;//se llama el valor del codigo del servicio con el nombre que lo relaciona en el SAP 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->Name" . PHP_EOL; //se llama el valor del nombre del servicio con el nombre que lo relaciona en el SAP 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_CuentaCosto" . PHP_EOL; //se llama el valor de la cuenta de costo del servicio con el nombre que lo relaciona en el SAP 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_UEN" . PHP_EOL; //se llama el valor uen del servicio con el nombre que lo relaciona en el SAP 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_Linea" . PHP_EOL;//se llama el valor de la linea del servicio con el nombre que lo relaciona en el SAP 
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "$item->U_SubLinea" . PHP_EOL; //se llama el valor de la sublinea del servicio con el nombre que lo relaciona en el SAP 
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
        <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
    </div>
</body>

</html>