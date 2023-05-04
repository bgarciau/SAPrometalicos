<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>INFORMES</title>
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- se usan librerias para usar el select2 que permite agregar un buscador en los select -->
    <link href="../css/select2/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="../css/select2/select2.min.js"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion
    
        header("location:../index.php");
    }
    include("../php/conexion.php"); //incluye la conexion a la bd
    
    $filtro="NO";
    if (isset($_GET["usuarioo"])) {
        $usuarioo = $_GET["usuarioo"];
        $filtro="SI";
    }
    if (isset($_GET["estado"])) {
        $estado = $_GET["estado"];
        $filtro="SI";
    }
    if (isset($_GET["tipo"])) {
        $tipo = $_GET["tipo"];
        $filtro="SI";
    }
    if (isset($_GET["desde"])) {
        $desde = $_GET["desde"];
        $filtro="SI";
    }
    if (isset($_GET["hasta"])) {
        $hasta = $_GET["hasta"];
        $filtro="SI";
    }
    if($filtro=='NO'){
        $filtros="SELECT * FROM solicitud_compra";
    }else{
        if (isset($_GET["usuarioo"]) && isset($_GET["estado"]) && isset($_GET["tipo"])) {
            $filtros="SELECT * FROM solicitud_compra";
        }
    }
    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php'); //carga el header
            $i = 1;
            ?>
        </header>
        <div class="contenedor-informes"> <!-- contenido entre el header y el footer -->
            <h2>INFORMES</h2>
            <div id="div_informes">
                <div id="informes-filtro">
                    <h3>Seleccione los datos a mostrar en el informe</h3>
                    <h4>Usuario:</h4>
                    <Select id="usuario" style="width: 18rem">
                        <?php if (isset($_GET["usuarioo"]) && $_GET["usuarioo"]!="" ) {?> <option value=" <?php echo $usuarioo; ?>"><?php echo $usuarioo; ?></option><?php } ?>
                        <option value="">todos</option>
                        <?php
                        $user = $base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);
                        foreach ($user as $duser) {
                            ?>
                            <option value="<?php echo $duser->pk_cod_usr ?>"><?php echo $duser->pk_cod_usr ?></option>
                            <?php
                        }
                        ?>
                    </Select>
                    <h4>Estado solicitud:</h4>
                    <Select id="estado" style="width: 18rem">
                        <?php if (isset($_GET["estado"]) && $_GET["estado"]!="") {?> <option value=" <?php echo $estado; ?>"><?php echo $estado; ?></option><?php } ?>
                        <option value="">todos</option>
                        <option value="ENVIADO">ENVIADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                        <option value="ABIERTO">ABIERTO</option>
                    </Select>
                    <h4>Tipo solicitud:</h4>
                    <Select id="tipo" style="width: 18rem">
                        <?php if (isset($_GET["tipo"]) && $_GET["tipo"]!="") {?> <option value=" <?php echo $tipo; ?>"><?php echo $tipo; ?></option><?php } ?>
                        <option value="">todos</option>
                        <option value="servicio">servicio</option>
                        <option value="articulo">srticulo</option>
                    </Select>
                    <h4>Desde - Hasta</h4>
                    <input type="date" id="desde" value="<?php if (isset($_GET["desde"]) && $_GET["desde"]!="") { echo $desde; }?>" max="<?= date("Y-m-d") ?>">~
                    <input type="date" id="hasta" value="<?php if (isset($_GET["hasta"]) && $_GET["hasta"]!="") { echo $hasta; }else{ echo date("Y-m-d");}?>" max="<?= date("Y-m-d") ?>">
                    <br>
                    <button class="btn_informes" type="button" onclick="aplicarFiltro() ">APLICAR</button>
                </div>
                <div id="informes-observaciones">
                    <h4>Observaciones:</h4>
                    <textarea name="" id="" cols="45" rows="7"></textarea>
                </div>
                <div id="informes-boton">
                    <button class="btn_informes" type="button">Guardar informe</button>
                </div>
                <div id="informes-tabla">
                    <div id="div_tablas_informes">
                        <div class="outer_wrapperS">
                            <div class="table_wrapperS">
                                <table border="4px" id="tabla__informes">
                                    <thead>
                                        <th>N° Sol</th>
                                        <th>Num SAP</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Fecha necesaria</th>
                                        <th>Fehca documento</th>
                                        <th>Nombre solicitante</th>
                                        <th>Departamento</th>
                                        <th>Correo</th>
                                        <th># sevicios /articulos</th>
                                        <th>propietario</th>
                                        <th>Comentarios</th>

                                    </thead>
                                    <tbody>
                                        <?php
                                        $solicitud = $base->query($filtros)->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
                                        foreach ($solicitud as $solicitudes) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $solicitudes->pk_num_sol ?>
                                                </td>
                                                <td>
                                                    <?php echo $solicitudes->numSAP ?>
                                                </td>
                                                <td>
                                                    <?php echo $solicitudes->tipo ?>
                                                </td>
                                                <td>
                                                    <?php echo $solicitudes->estado_sol ?>
                                                </td>
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
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php'); //carga el footer
            ?>
        </footer>
    </div>
</body>
<script>
    $('#usuario').select2();
    $('#estado').select2();
    $('#tipo').select2();

    function aplicarFiltro() {
        console.log("USUARIO: " + $('#usuario').val());
        console.log("ESTADO: " + $('#estado').val());
        console.log("TIPO: " + $('#tipo').val());
        console.log("DESDE: " + $('#desde').val());
        console.log("HASTA: " + $('#hasta').val());
        window.location = "informes.php?usuarioo=" + $('#usuario').val() + "&estado=" + $('#estado').val() + "&tipo=" + $('#tipo').val() + "&desde=" + $('#desde').val() + "&hasta=" + $('#hasta').val() + "";
    }

</script>

</html>