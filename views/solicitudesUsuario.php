<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Soliciudes usuarios</title>
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { // comprueba si esta logeado y si no lo esta lo manda a inciar sesion
    
        header("location:../index.php");
    }

    include("../php/conexion.php"); //se incluye la conexion a la base de datos
    
    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->tipo_usuario; //Sacamos el tipo de usuario de la sesion para saber si es administrador y si no lo es lo mandamos a hacer solicitud
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
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
    ?>
    <div class="base"> <!-- Vista de  la pagina -->
        <header>
            <?php
            require_once('../php/header.php'); //llama el header
            ?>
        </header>
        <div class="contenedor" id="carga" hidden>
            <img id="centrar-carga" src="../images/carga10.gif">
        </div>
        <div class="contenedor" id="principal"> <!-- contenido entre el header y el footer -->
            <h2>SOLICITUDES USUARIO</h2>
            <div id="div_tablas"> <!-- Contiene la tabla de solicitudes -->
                <div id="div_boton_volver">
                    <a href="javascript:history.back()"><input class="btn_volver" type="button" value="< VOLVER"></a>
                    <!-- boton para volver a hacer solicitud -->
                </div>
                <div class="outer_wrapperS">

                    <?php
                    $xtabla = "tservicios"; //Con esta variable se cargan los articulos o los servicios segun su valor
                    if (isset($_GET["xtabla"])) { //Aca toma el valor de la variable si se ha preisonado un boton 
                        $xtabla = $_GET["xtabla"];
                    }
                    if ($xtabla == "tservicios") { ?> <!-- Pregunta si es la tabla de servicios para cargarla -->
                        <!-- tabla servicios -->
                        <form> <!-- Con este form se actulizan los datos segun el boton -->
                            <button class="btn_opciones_selected" name="xtabla" value="tservicios">servicios</button>
                            <!-- Boton para cargar los servicios -->
                            <button class="btn_opciones" name="xtabla" value="tarticulos">articulos</button>
                            <!-- Boton para cargar los articulos -->
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
                                    <th># sevicios</th>
                                    <th>propietario</th>
                                    <th>Comentarios</th>
                                    <th>OPCIONES</th>
                                </thead>
                                <?php
                                $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar las solicitudes que no son suyas
                                $usolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'servicio' and fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se llama de la tabla de solicitudes filtrando las de servicios y que no pertenecen al usuaio de la sesion para guardala en un PDOStatement
                                foreach ($usolicitud as $usolicitudes): //se usa para recorrer el PDOStatement 
                                    ?>
                                    <tr>
                                        <!-- con $usolicitudes cargamos los datos que necesitando usando el mismo nombre que tienen en la base de datos -->
                                        <td>
                                            <?php echo $usolicitudes->pk_num_sol ?>
                                        </td>
                                        <td>
                                            <?php echo $usolicitudes->numSAP ?>
                                        </td>
                                        <!-- se llama el numero de solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <?php 
                                        if($usolicitudes->estado_sol=="ABIERTO"){
                                            ?>
                                                <td style="background-color:#69dbea;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($usolicitudes->estado_sol=="ENVIADO"){
                                            ?>
                                                <td style="background-color:#84da84;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($usolicitudes->estado_sol=="RECHAZADO"){
                                            ?>
                                                <td style="background-color:#ff2100b5;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        ?>
                                        <td>
                                            <?php echo $usolicitudes->fecha_necesaria ?>
                                        </td>
                                        <td>
                                            <?php echo $usolicitudes->fecha_documento ?>
                                        </td>
                                        <!-- se llama el estado de solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td>
                                            <?php echo $usolicitudes->nom_solicitante ?>
                                        </td>
                                        <!-- se llama el nombre del solicitante con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <?php
                                        $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); foreach ($depSol as $depSols):
                                            ?>
                                            <td>
                                                <?php echo $depSols->nom_dep ?>
                                            </td> <!-- se llama el departamento -->
                                            <?php
                                        endforeach;
                                        ?>
                                        <td>
                                            <?php echo $usolicitudes->correo_sol ?>
                                        </td>
                                        <!-- se llama el correo de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td>
                                            <?php echo $usolicitudes->cantidad ?>
                                        </td> <!-- se llama la cantidad de servicios que tiene la solicitud -->
                                        <td>
                                            <?php echo $usolicitudes->propietario ?>
                                        </td>
                                        <!-- se llama el propieteario de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td>
                                            <?php echo $usolicitudes->comentarios ?>
                                        </td>
                                        <!-- se llaman los comentarios de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td class="opcionesTabla">
                                            <a href="infoS.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>"><input
                                                    class="btn_info" type="button" value="info"></a>
                                            <!-- se manda el id de la solicitud para cargar todos los datos de esta -->
                                            <?php if ($usolicitudes->estado_sol == "ABIERTO") { ?>
                                                <a onclick="enviarServicio(<?php echo $usolicitudes->pk_num_sol ?>)"><input
                                                        class="btn_enviar" type="button" value="enviar"></a>
                                                <a href="../crud/rechazar.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario"><input class="btn_delete" type="button" value="rechazar"></a>
                                                <?php
                                            }
                                            if ($usolicitudes->estado_sol == "RECHAZADO") { ?>
                                                <a href="../crud/reabrir.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario"><input class="btn_delete" type="button" value="reabrir"></a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </table>
                            <?php
                    } else { ?> <!-- Como no es la tabla de servicios entonces carga la de articuls -->
                            <!-- tabla articulos -->
                            <form id="menu"> <!-- Con este form se actulizan los datos segun el boton -->
                                <button class="btn_opciones" name="xtabla" value="tservicios">servicios</button>
                                <!-- Boton para cargar los servicios -->
                                <button class="btn_opciones_selected" name="xtabla" value="tarticulos">articulos</button>
                                <!-- Boton para cargar los articulos -->
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
                                    $usuario = $_SESSION['usuario'];
                                    $usolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'articulo' and fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se llama de la tabla de solicitudes filtrando las de articulos y que no pertenecen al usuaio de la sesion para guardala en un PDOStatement
                                    foreach ($usolicitud as $usolicitudes):
                                        ?>
                                        <tr>
                                            <!-- con $usolicitudes cargamos los datos que necesitando usando el mismo nombre que tienen en la base de datos -->
                                            <td>
                                                <?php echo $usolicitudes->pk_num_sol ?>
                                            </td>
                                            <td>
                                                <?php echo $usolicitudes->numSAP ?>
                                            </td>
                                            <?php 
                                        if($usolicitudes->estado_sol=="ABIERTO"){
                                            ?>
                                                <td style="background-color:#69dbea;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($usolicitudes->estado_sol=="ENVIADO"){
                                            ?>
                                                <td style="background-color:#84da84;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        if($usolicitudes->estado_sol=="RECHAZADO"){
                                            ?>
                                                <td style="background-color:#ff2100b5;">
                                                    <?php echo $usolicitudes->estado_sol ?>
                                                </td>
                                            <?php
                                        } 
                                        ?>
                                            <td>
                                            <?php echo $usolicitudes->fecha_necesaria ?>
                                        </td>
                                        <td>
                                            <?php echo $usolicitudes->fecha_documento ?>
                                        </td>
                                            <td>
                                                <?php echo $usolicitudes->nom_solicitante ?>
                                            </td>
                                            <?php
                                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); foreach ($depSol as $depSols):
                                                ?>
                                                <td>
                                                    <?php echo $depSols->nom_dep ?>
                                                </td>
                                                <?php
                                            endforeach;
                                            ?>
                                            <td>
                                                <?php echo $usolicitudes->correo_sol ?>
                                            </td>
                                            <td>
                                                <?php echo $usolicitudes->cantidad ?>
                                            </td>
                                            <td>
                                                <?php echo $usolicitudes->propietario ?>
                                            </td>
                                            <td>
                                                <?php echo $usolicitudes->comentarios ?>
                                            </td>
                                            <td class="opcionesTabla">
                                                <a href="infoS.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>"><input
                                                        class="btn_info" type="button" value="info"></a>
                                                <?php if ($usolicitudes->estado_sol == "ABIERTO") { ?>
                                                    <a onclick="enviarArticulo(<?php echo $usolicitudes->pk_num_sol ?>)"><input
                                                            class="btn_enviar" type="button" value="enviar"></a>
                                                    <a href="../crud/rechazar.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario&xtabla=tarticulos"><input class="btn_delete" type="button" value="rechazar"></a>
                                                    <?php
                                                }
                                                if ($usolicitudes->estado_sol == "RECHAZADO") { ?>
                                                    <a href="../crud/reabrir.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>&lugar=solicitudesUsuario&xtabla=tarticulos"><input class="btn_delete" type="button" value="reabrir"></a>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    ?>
                                </table>
                                <?php
                    } ?>
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
<Script>
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
                window.location = '../crud/enviarServicio.php?numSol=' + numSolicitud+'&lugar=solicitudesUsuario';
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
                window.location = '../crud/enviarArticulo.php?numSol=' + numSolicitud+'&lugar=solicitudesUsuario';
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
</Script>

</html>