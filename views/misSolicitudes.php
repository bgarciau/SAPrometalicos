<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mis solicitudes</title>
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion
    
        header("location:../index.php");
    }
    if(isset($_GET["alerta"])){
        $alerta = $_GET["alerta"];
    }
    else{
        $alerta = "'NO'";
    }
    $numSAP="NO";
    if(isset($_GET["numSAP"])){
        $numSAP = $_GET["numSAP"];
    }
    include("../php/conexion.php");

    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php'); //carga el header
            ?>
        </header>
        <div class="contenedor" id="carga" hidden>
            <img id="centrar-carga" src="../images/carga10.gif">
        </div>
        <div class="contenedor" id="principal"> <!-- contenido entre el header y el footer -->
            <h2>MIS SOLICITUDES</h2>
            <div id="div_tablas"> <!-- div para la tabla de solicitudes -->
                <div id="div_boton_volver"> <!-- div para el boton volver, este queda a la derecha -->
                    <a href="javascript:history.back()"><input class="btn_volver" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <?php
                    $xtabla = "tservicios";
                    if (isset($_GET["xtabla"])) {
                        $xtabla = $_GET["xtabla"];
                    }
                    if ($xtabla == "tservicios") { ?>
                        <form> <!-- Con este form se actulizan los datos segun el boton -->
                            <button class="btn_opciones_selected" name="xtabla" id="btn_servicios"
                                value="tservicios">servicios</button><!-- Boton para cargar los servicios -->
                            <button class="btn_opciones" name="xtabla" id="btn_articulos"
                                value="tarticulos">articulos</button><!-- Boton para cargar los articulos -->
                        </form>
                        <!-- tabla servicios -->
                        <div class="table_wrapperS">
                            <table id="tabla__solicitudes">
                                <thead>
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
                                </thead>
                                <?php
                                $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar sus solicitudes
                                $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'servicio' AND fk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
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
                                        if($misolicitudes->estado_sol=="ABIERTO"){
                                            ?>
                                                <td style="background-color:#69dbea;">
                                                    <?php echo $misolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($misolicitudes->estado_sol=="ENVIADO"){
                                            ?>
                                                <td style="background-color:#84da84;">
                                                    <?php echo $misolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($misolicitudes->estado_sol=="RECHAZADO"){
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
                                        $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); foreach ($depSol as $depSols):
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
                                            <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"><input
                                                    class="btn_info" type="button" value="info"></a>
                                            <?php
                                            //verificamos si el usuario es administrador y le agrega 2 botones para realizar acciones con las solicitudes
                                            $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); foreach ($usutipo as $usutipoo):
                                                if ($usutipoo->tipo_usuario == 3) { 
                                                    if($misolicitudes->estado_sol=="ABIERTO"){?>
                                                    <a onclick="enviarServicio(<?php echo $misolicitudes->pk_num_sol ?>)"><input class="btn_enviar" type="button" value="enviar"></a>
                                                    <a href="../crud/rechazar.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=misSolicitudes"><input class="btn_delete" type="button" value="rechazar"></a>
                                                    <?php
                                                    }
                                                    if ($misolicitudes->estado_sol == "RECHAZADO") { ?>
                                                        <a href="../crud/reabrir.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=misSolicitudes"><input class="btn_delete" type="button" value="reabrir"></a>
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
                            </table>
                        </div>
                        <?php
                    } else { ?>
                        <!-- tabla articulos -->
                        <form> <!-- Con este form se actulizan los datos segun el boton -->
                            <button class="btn_opciones" name="xtabla" id="btn_servicios"
                                value="tservicios">servicios</button><!-- Boton para cargar los servicios -->
                            <button class="btn_opciones_selected" name="xtabla" id="btn_articulos"
                                value="tarticulos">articulos</button><!-- Boton para cargar los articulos -->
                        </form>
                        <div class="table_wrapperS">
                            <table id="tabla__solicitudes">
                                <thead>
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
                                </thead>
                                <?php
                                $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar sus solicitudes
                                $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'articulo' AND fk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de articulos hechas por el ususario de la sesion en un PDOStatement
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
                                        if($misolicitudes->estado_sol=="ABIERTO"){
                                            ?>
                                                <td style="background-color:#69dbea;">
                                                    <?php echo $misolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($misolicitudes->estado_sol=="ENVIADO"){
                                            ?>
                                                <td style="background-color:#84da84;">
                                                    <?php echo $misolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($misolicitudes->estado_sol=="RECHAZADO"){
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
                                        $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); foreach ($depSol as $depSols):
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
                                            <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"><input
                                                    class="btn_info" type="button" value="info"></a>
                                            <?php
                                            //verificamos si el usuario es administrador y le agrega 2 botones para realizar acciones con las solicitudes
                                            $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); foreach ($usutipo as $usutipoo):
                                                if ($usutipoo->tipo_usuario == 3) { 
                                                    if($misolicitudes->estado_sol=="ABIERTO"){?>
                                                    <a onclick="enviarArticulo(<?php echo $misolicitudes->pk_num_sol ?>)"><input class="btn_enviar" type="button" value="enviar"></a>
                                                    <a href="../crud/rechazar.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=misSolicitudes&xtabla=tarticulos"><input class="btn_delete" type="button" value="rechazar"></a>
                                                    <?php
                                                    }
                                                    if ($misolicitudes->estado_sol == "RECHAZADO") { ?>
                                                        <a href="../crud/reabrir.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>&lugar=misSolicitudes&xtabla=tarticulos"><input class="btn_delete" type="button" value="reabrir"></a>
                                                        <?php
                                                    }
                                                }
                                            endforeach;
                                endforeach;
                    }
                    ?>
                                </td>
                            </tr>
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
<script>
    function pantallaCarga(){
        $('#principal').fadeOut();
        $('#carga').prop("hidden",false);
    }
    function enviarServicio(numSolicitud) {
        Swal.fire({
            icon: 'question',
            title: '¿Enviar solicitud al SAP?',
            showCancelButton: true,
            html: '<span class="letra-blanco">Al enviar la solicitud el estado de esta cambiara a ENVIADO</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = '../crud/enviarServicio.php?numSol='+numSolicitud+'&lugar=misSolicitudes'; 
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
                window.location = '../crud/enviarArticulo.php?numSol='+numSolicitud+'&lugar=misSolicitudes'; 
            }
        })
    }
    alerta = <?php echo $alerta ?>;
    if(alerta != "NO"){
        Swal.fire({
            title: 'ERROR AL ENVIAR LA SOLICITUD',
            icon: "error",
            html: "<p style='color:red'>ERROR: "+alerta+"<p><p style='color:white'>Vuelva a intentarlo y si el problema continua comuniquise con soporte<p>"
        })
    }
    numSAP = <?php echo $numSAP ?>;
    if(numSAP != "NO"){
        Swal.fire({
            title: 'SOLICITUD ENVIADA CON EXITO',
            icon: "success",
            html: "<p style='color:white'>Numero de la solicitud en SAP:"+numSAP+"<P>"
        })
    }
</script>

</html>