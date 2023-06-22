<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('head.php');
    include("../crud/procesados.php");
        ?>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion
    
        header("location:../index.php");
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
    if (isset($_GET["estado"])) {
        $estado = $_GET["estado"];
    } 

    require('header.php');
    include("../php/conexion.php");
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>SOLICITUDES</h3>
        </div>
        <div class="overflow-x-scroll" id="tSolicitudes">
            <table class="table table-bordered table-striped table-hover" id="tablaSolicitudes">
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
                        <th># ser/art</th>
                        <th>propietario</th>
                        <th>Comentarios</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar sus solicitudes
                    $solicitud = $base->query("SELECT * FROM solicitud_compra WHERE estado_sol='$estado'")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
                    foreach ($solicitud as $solicitudes): // se recorren todos las solicitudes de servicio del usuario
                        ?>
                        <tr>
                            <!-- con $solicitudes cargamos los datos que necesitando usando el mismo nombre que tienen en la base de datos -->
                            <td>
                                <?php echo $solicitudes->pk_num_sol ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->numSAP ?>
                            </td>
                            <?php
                            if ($solicitudes->estado_sol == "ABIERTO") {
                                ?>
                                <td style="background-color:#69dbea;">
                                    <?php echo $solicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($solicitudes->estado_sol == "ENVIADO") {
                                ?>
                                <td style="background-color:#84da84;">
                                    <?php echo $solicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($solicitudes->estado_sol == "RECHAZADO") {
                                ?>
                                <td style="background-color:#ff2100b5;">
                                    <?php echo $solicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            if ($solicitudes->estado_sol == "PROCESADO") {
                                ?>
                                <td style="background-color:#EAEDED;">
                                    <?php echo $solicitudes->estado_sol ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td>
                                <?php echo $solicitudes->fecha_necesaria ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->fecha_documento ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->nom_solicitante ?>
                            </td>
                            <?php
                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$solicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($depSol as $depSols):
                                ?>
                                <td>
                                    <?php echo $depSols->nom_dep ?>
                                </td>
                                <?php
                            endforeach;
                            ?>
                            <td>
                                <?php echo $solicitudes->correo_sol ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->cantidad ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->propietario ?>
                            </td>
                            <td>
                                <?php echo $solicitudes->comentarios ?>
                            </td>
                            <td class="opcionesTabla">
                                <a href="infoS.php?numSol=<?php echo $solicitudes->pk_num_sol ?>"
                                    class="btn btn-info btn-sm">INFO</a>
                                <?php
                                //verificamos si el usuario es administrador y le agrega 2 botones para realizar acciones con las solicitudes
                                $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($usutipo as $usutipoo):
                                    if ($usutipoo->tipo_usuario == 3) {
                                        if ($solicitudes->estado_sol == "ABIERTO") { 
                                            if($solicitudes->tipo == "servicio"){?>
                                            <a onclick="enviarServicio(<?php echo $solicitudes->pk_num_sol ?>)"
                                                class="btn btn-success btn-sm">ENVIAR</a>
                                            <a href="../crud/rechazar.php?numSol=<?php echo $solicitudes->pk_num_sol ?>&lugar=solicitudes&estado=<?php echo $estado ?>"
                                                class="btn btn-danger btn-sm">RECHAZAR</a>
                                            <?php
                                            }
                                            else{?>
                                             <a onclick="enviarArticulo(<?php echo $solicitudes->pk_num_sol ?>)"
                                                class="btn btn-success btn-sm">ENVIAR</a>
                                            <a href="../crud/rechazar.php?numSol=<?php echo $solicitudes->pk_num_sol ?>&lugar=solicitudes&estado=<?php echo $estado ?>"
                                                class="btn btn-danger btn-sm">RECHAZAR</a>
                                             <?php
                                            }
                                        }
                                        if ($solicitudes->estado_sol == "RECHAZADO") { ?>
                                            <a href="../crud/reabrir.php?numSol=<?php echo $solicitudes->pk_num_sol ?>&lugar=solicitudes&estado=<?php echo $estado ?>"
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
        <a href="javascript:history.back()" class="btn btn-danger" onclick="pantallaCarga()"><i class="bi bi-arrow-bar-left">VOLVER
                            </i></a>
    </div>
    <?php
    require('footer.php')
        ?>
</body>
<script>
    if ($.fn.DataTable.isDataTable('#tablaSolicitudes')) {
            $('#tablaSolicitudes').DataTable().destroy();
        }
        $('#tablaSolicitudes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
            }
        });
    //MANDA EL NUMERO DE SOLICITUD PARA QUE ESTA SEA PROCESADA
    function enviarServicio(numSolicitud) {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar solicitud al SAP?',
            showCancelButton: true,
            html: '<span class="letra-blanco">Al enviar la solicitud el estado de esta cambiara a ENVIADO</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../crud/enviarServicio.php?numSol=' + numSolicitud + '&lugar=solicitudes&estado=<?php echo $estado ?>';
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
                window.location = '../crud/enviarArticulo.php?numSol=' + numSolicitud + '&lugar=solicitudes&estado=<?php echo $estado ?>';
            }
        })
    }
    // ALERTA LUEGO DE ENVIAR LA SOLICITUD Y RECIBIR LA RESPUESTA DE SAP
    alerta = <?php echo $alerta ?>;
    if (alerta != "NO") {
        Swal.fire({
            title: 'ERROR AL ENVIAR LA SOLICITUD',
            icon: "error",
            html: "<p style='color:red'>ERROR: " + alerta + "<p><p style='color:white'>Vuelva a intentarlo y si el problema continua comuniquise con soporte<p>"
        })
    }
    numSAP = '<?php echo $numSAP ?>';
    console.log(numSAP)
    if (numSAP != "NO") {
        Swal.fire({
            title: 'SOLICITUD ENVIADA CON EXITO',
            icon: "success",
            html: "<p style='color:white'>Numero de la solicitud en SAP:" + numSAP + "<P>"
        })
    }
</script>

</html>