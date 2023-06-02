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

    if (isset($_GET["alerta"])) {
        $alerta = $_GET["alerta"];
    } else {
        $alerta = "'NO'";
    }
    $numSAP = "NO";
    if (isset($_GET["numSAP"])) {
        $numSAP = $_GET["numSAP"];
    }

    $xtabla = "tservicios";
    if (isset($_GET["xtabla"])) {
        $xtabla = $_GET["xtabla"];
    }
    require('header.php');
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>SOLICITUDES USUARIOS</h3>
        </div>
        <div class="py-2">
            <form>
                <button class="btn btn-danger" name="xtabla" value="tservicios">SERVICIOS</button>
                <button class="btn btn-danger" name="xtabla" value="tarticulos">ARTICULOS</button>
            </form>
        </div>
        <div class="overflow-x-scroll" id="tServicios" hidden>
            <table class="table table-bordered table-striped table-hover" id="tablaServicios">
                <thead class="table-dark">
                    <tr>
                        <th>N° Sol</th>
                        <th>Num SAP</th>
                        <th>Estado</th>
                        <th>Fecha necesaria</th>
                        <th>Fehca documento</th>
                        <th>Nombre solicitante</th>
                        <th>Departamento</th>
                        <th>Correo</th>
                        <th># sevicios</th>
                        <th>propietario</th>
                        <th>Comentarios</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar sus solicitudes
                    $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'servicio' AND fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
                    foreach ($misolicitud as $misolicitudes): // se recorren todos las solicitudes de servicio del usuario
                        ?>
                        <tr>
                            <!-- con $misolicitudes cargamos los datos que necesitando usando el mismo nombre que tienen en la base de datos -->
                            <td>
                                <?php echo $misolicitudes->pk_num_sol ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->numSAP ?>
                            </td>
                            <?php
                            if ($misolicitudes->estado_sol == "ABIERTO") {
                                ?>
                                <td style="background-color:#69dbea;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($misolicitudes->estado_sol == "ENVIADO") {
                                ?>
                                <td style="background-color:#84da84;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($misolicitudes->estado_sol == "RECHAZADO") {
                                ?>
                                <td style="background-color:#ff2100b5;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td>
                                <?php echo $misolicitudes->fecha_necesaria ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->fecha_documento ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->nom_solicitante ?>
                            </td>
                            <?php
                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($depSol as $depSols):
                                ?>
                                <td>
                                    <?php echo $depSols->nom_dep ?>
                                </td>
                                <?php
                            endforeach;
                            ?>
                            <td>
                                <?php echo $misolicitudes->correo_sol ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->cantidad ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->propietario ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->comentarios ?>
                            </td>
                            <td class="opcionesTabla">
                                <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"
                                    class="btn btn-info btn-sm">INFO</a>
                                <?php
                                //verificamos si el usuario es administrador y le agrega 2 botones para realizar acciones con las solicitudes
                                $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($usutipo as $usutipoo):
                                    if ($usutipoo->tipo_usuario == 3) {
                                        if ($misolicitudes->estado_sol == "ABIERTO") { ?>
                                            <a onclick="enviarServicio(<?php echo $misolicitudes->pk_num_sol ?>)"
                                                class="btn btn-success btn-sm">ENVIAR</a>
                                            <a href="../crud/rechazar.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario"
                                                class="btn btn-danger btn-sm">RECHAZAR</a>
                                            <?php
                                        }
                                        if ($misolicitudes->estado_sol == "RECHAZADO") { ?>
                                            <a href="../crud/reabrir.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario"
                                                class="btn btn-secondary btn-sm">REABRIR</a>
                                            <?php
                                        }
                                    }
                                endforeach;
                                ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <div class="overflow-x-scroll" id="tArticulos" hidden>
            <table class="table table-bordered table-striped table-hover" id="tablaArticulos">
                <thead class="table-dark">
                    <tr>
                        <th>N° Sol</th>
                        <th>Num SAP</th>
                        <th>Estado</th>
                        <th>Fecha necesaria</th>
                        <th>Fehca documento</th>
                        <th>Nombre solicitante</th>
                        <th>Departamento</th>
                        <th>Correo</th>
                        <th># articulos</th>
                        <th>propietario</th>
                        <th>Comentarios</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar sus solicitudes
                    $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'articulo' AND fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de articulos hechas por el ususario de la sesion en un PDOStatement
                    foreach ($misolicitud as $misolicitudes):
                        ?>
                        <tr>
                            <!-- con $misolicitudes cargamos los datos que necesitando usando el mismo nombre que tienen en la base de datos -->
                            <td>
                                <?php echo $misolicitudes->pk_num_sol ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->numSAP ?>
                            </td>
                            <?php
                            if ($misolicitudes->estado_sol == "ABIERTO") {
                                ?>
                                <td style="background-color:#69dbea;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($misolicitudes->estado_sol == "ENVIADO") {
                                ?>
                                <td style="background-color:#84da84;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($misolicitudes->estado_sol == "RECHAZADO") {
                                ?>
                                <td style="background-color:#ff2100b5;">
                                    <?php echo $misolicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td>
                                <?php echo $misolicitudes->fecha_necesaria ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->fecha_documento ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->nom_solicitante ?>
                            </td>
                            <?php
                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($depSol as $depSols):
                                ?>
                                <td>
                                    <?php echo $depSols->nom_dep ?>
                                </td>
                                <?php
                            endforeach;
                            ?>
                            <td>
                                <?php echo $misolicitudes->correo_sol ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->cantidad ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->propietario ?>
                            </td>
                            <td>
                                <?php echo $misolicitudes->comentarios ?>
                            </td>
                            <td class="opcionesTabla">
                                <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"
                                    class="btn btn-info btn-sm">INFO</a>
                                <?php
                                //verificamos si el usuario es administrador y le agrega 2 botones para realizar acciones con las solicitudes
                                $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($usutipo as $usutipoo):
                                    if ($usutipoo->tipo_usuario == 3) {
                                        if ($misolicitudes->estado_sol == "ABIERTO") { ?>
                                            <a onclick="enviarArticulo(<?php echo $misolicitudes->pk_num_sol ?>)"
                                                class="btn btn-success btn-sm">ENVIAR</a>
                                            <a href="../crud/rechazar.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario&xtabla=tarticulos"
                                                class="btn btn-danger btn-sm">RECHAZAR</a>
                                            <?php
                                        }
                                        if ($misolicitudes->estado_sol == "RECHAZADO") { ?>
                                            <a href="../crud/reabrir.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario&xtabla=tarticulos"
                                                class="btn btn-secondary btn-sm">REABRIR</a>
                                            <?php
                                        }
                                    }
                                endforeach;
                    endforeach;

                    ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    require('footer.php')
        ?>
</body>
<script>
    <?php
    if ($xtabla == "tservicios") {
        ?>
         $('#tServicios').prop("hidden",false);
        if ($.fn.DataTable.isDataTable('#tablaServicios')) {
            $('#tablaServicios').DataTable().destroy();
        }
        $('#tablaServicios').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
        });
    <?php
    }
    if ($xtabla == "tarticulos") { ?>
        $('#tArticulos').prop("hidden",false);
        if ($.fn.DataTable.isDataTable('#tablaArticulos')) {
            $('#tablaArticulos').DataTable().destroy();
        }
        $('#tablaArticulos').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
        });
    <?php
    }
    ?>
    function enviarServicio(numSolicitud) {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar solicitud al SAP?',
            showCancelButton: true,
            html: '<span class="letra-blanco">Al enviar la solicitud el estado de esta cambiara a ENVIADO</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../crud/enviarServicio.php?numSol=' + numSolicitud + '&lugar=solicitudesUsuario';
            }
        })
    }

    function enviarArticulo(numSolicitud) {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar solicitud al SAP?',
            showCancelButton: true,
            html: '<span class="letra-blanco">Al enviar la solicitud el estado de esta cambiara a ENVIADO</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../crud/enviarArticulo.php?numSol=' + numSolicitud + '&lugar=solicitudesUsuario';
            }
        })
    }
    alerta = <?php echo $alerta ?>;
    if (alerta != "NO") {
        Swal.fire({
            title: 'ERROR AL ENVIAR LA SOLICITUD',
            icon: "error",
            html: "<p style='color:red'>ERROR: " + alerta + "<p><p style='color:white'>Vuelva a intentarlo y si el problema continua comuniquise con soporte<p>"
        })
    }
    numSAP = '<?php echo $numSAP ?>';
    if (numSAP != "NO") {
        Swal.fire({
            title: 'SOLICITUD ENVIADA CON EXITO',
            icon: "success",
            html: "<p style='color:white'>Numero de la solicitud en SAP:" + numSAP + "<P>"
        })
    }
</script>

</html>