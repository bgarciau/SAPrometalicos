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

    include("../php/SAP.php"); //Se usa para poder usar los valores obtenidos del SAP
    $respuestaServicios = servicios($sesion);
    require('header.php');
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>SERVICIOS</h3>
        </div>
        <div class="overflow-x-scroll" id="tArticulos">
            <table class="table table-bordered table-striped table-hover" id="tablaServicios">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Cuenta Mayor</th>
                        <th>UEN</th>
                        <th>Linea</th>
                        <th>Sublinea</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1; //numero de articulo en la tabla
                    foreach ($respuestaServicios->value as $item): ?>
                        <!-- se toma el respuestaServicios del SAP.php y se usa para llenar la tabla con los servicios obtenidos -->
                        <tr>
                            <td>
                                <?php echo $i ?>
                            </td>
                            <td>
                                <?php
                                echo "$item->Code" . PHP_EOL; //se llama el valor del codigo del servicio con el nombre que lo relaciona en el SAP 
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
                                echo "$item->U_Linea" . PHP_EOL; //se llama el valor de la linea del servicio con el nombre que lo relaciona en el SAP 
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
                </tbody>
            </table>
        </div>
    </div>
    <?php
    require('footer.php')
        ?>
</body>
<script>
    $('#tablaServicios').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        }
    });
</script>

</html>