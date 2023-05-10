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
    date_default_timezone_set('America/Bogota');
    include("../php/conexion.php"); //incluye la conexion a la bd
    if(isset($_SESSION["tipo"])){
        $tipo=$_SESSION["tipo"];
    }
    // se define el NO para qeu cuando se cargue la pagina por primera vez no se apiquen filtros
    $filtro = "NO";
    if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") { //si se mando un valor del filtro o guarda en la viariable correspondiente 
        $usuarioo = $_GET["usuarioo"];
        $filtro = "SI";
    }
    if (isset($_GET["estado"]) && $_GET["estado"] != "") {//si se mando un valor del filtro o guarda en la viariable correspondiente 
        $estado = $_GET["estado"];
        $filtro = "SI";
    }
    if (isset($_GET["tipo"]) && $_GET["tipo"] != "") {//si se mando un valor del filtro o guarda en la viariable correspondiente 
        $tipo = $_GET["tipo"];
        $filtro = "SI";
    }
    $desde = 0; //se define en 0 para que se carguen todas las solicitudes que se han cread
    $hasta = date("Y-m-d");//se toma la fecha actual para cargar las solicitudes hasta el dia de hoy 
    if (isset($_GET["desde"])) {//si se mando un valor del filtro o guarda en la viariable correspondiente 
        $desde = $_GET["desde"];
    }
    if (isset($_GET["hasta"])) {//si se mando un valor del filtro o guarda en la viariable correspondiente 
        $hasta = $_GET["hasta"];
    }
    if ($filtro == 'NO') { //si no hay valores en el filtro se cargan todas las soolicitudes que se han hecho
        $filtros = "SELECT * FROM solicitud_compra";//sentencia sin filtros
    } else { //si hay valores en el filtro se crea una nueva sentencia
        $filtros = "SELECT * FROM solicitud_compra WHERE ";//sentencia con filtros
        $i = 0;
        if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") {
            if ($i == 0) { //si es el primer filtro 
                $filtros = $filtros . "fk_cod_usr='$usuarioo'";//como es el primero no se agrega el AND 
                $i++;
            } else { //si ya se agrego un filtro
                $filtros = $filtros . "and fk_cod_usr='$usuarioo'";//como ya esta el primero se tiene que agregar un AND
                $i++;
            }
        }
        if (isset($_GET["estado"]) && $_GET["estado"] != "") {
            if ($i == 0) {//si es el primer filtro 
                $filtros = $filtros . "estado_sol='$estado'";//como es el primero no se agrega el AND 
                $i++;
            } else {//si ya se agrego un filtro
                $filtros = $filtros . "and estado_sol='$estado'";//como ya esta el primero se tiene que agregar un AND
                $i++;
            }
        }
        if (isset($_GET["tipo"]) && $_GET["tipo"] != "") {
            if ($i == 0) {//si es el primer filtro 
                $filtros = $filtros . "tipo='$tipo'";//como es el primero no se agrega el AND 
                $i++;
            } else {//si ya se agrego un filtro
                $filtros = $filtros . "and tipo='$tipo'";//como ya esta el primero se tiene que agregar un AND
                $i++;
            }
        }

    }
    // LIBRERIA PARA CREAR ARCHIVOS EXCEL DESDE PHP
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    // -------------------------------------------------
    $informe = "no";//se define en NO para que sea el usuario el que genere el informe
    $linkInforme = "";
    if (isset($_GET["generarInforme"]) && $_GET["generarInforme"] == 'si') { //genera el informe
        $informe = "si";
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        // PRIMERA PARTE DEL EXCEL CON LOS DATOS QUE SE VERAN EN EL INFORME Y LAS OBSERVACIONES
        $activeWorksheet->setCellValue('B1', 'FECHA INFORME:');
        $activeWorksheet->setCellValue('C1', date("Y-m-d"));
        $activeWorksheet->setCellValue('B2', 'OBSERVACIONES:');
        $spreadsheet->getActiveSheet()->mergeCells('C2:G3');
        if (isset($_GET["observaciones"])) {
            $activeWorksheet->setCellValue('C2', $_GET["observaciones"]);
        } else {
            $activeWorksheet->setCellValue('C2', "");
        }
        $spreadsheet->getActiveSheet()->mergeCells('H4:K4');
        $activeWorksheet->setCellValue('H4', 'FECHA DOCUMENTO');
        $activeWorksheet->setCellValue('B5', 'SOLICITANTE:');
        if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") {
            $activeWorksheet->setCellValue('C5', $_GET["usuarioo"]);
        } else {
            $activeWorksheet->setCellValue('C5', "todos");
        }
        $activeWorksheet->setCellValue('D5', 'ESTADO SOLICITUD:');
        if (isset($_GET["estado"]) && $_GET["estado"] != "") {
            $activeWorksheet->setCellValue('E5', $_GET["estado"]);
        } else {
            $activeWorksheet->setCellValue('E5', "todos");
        }
        $activeWorksheet->setCellValue('F5', 'TIPO SOLICITUD:');
        if (isset($_GET["tipo"]) && $_GET["tipo"] != "") {
            $activeWorksheet->setCellValue('G5', $_GET["tipo"]);
        } else {
            $activeWorksheet->setCellValue('G5', "todos");
        }
        $activeWorksheet->setCellValue('H5', 'DESDE:');
        if (isset($_GET["desde"])) {
            $activeWorksheet->setCellValue('I5', $_GET["desde"]);
        } else {
            $activeWorksheet->setCellValue('I5', "");
        }
        $activeWorksheet->setCellValue('J5', 'HASTA:');
        if (isset($_GET["hasta"])) {
            $activeWorksheet->setCellValue('K5', $_GET["hasta"]);
        } else {
            $activeWorksheet->setCellValue('K5', "");
        }

        //TABLA SOLICITUDES Y SUS ARTICULOS O SERVICIOS
        $i = 7;
        $solicitud = $base->query($filtros)->fetchAll(PDO::FETCH_OBJ); //solicitudes aplicando el filtro
        foreach ($solicitud as $solicitudes) {
            if ($solicitudes->fecha_documento >= $desde && $solicitudes->fecha_documento <= $hasta) {
                //para las solicitudes filtradas
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':L' . $i . '')
                    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':L' . $i . '')
                    ->getFill()->getStartColor()->setARGB('FCAAAA');
                $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':L' . $i . '')
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $activeWorksheet->setCellValue('A' . $i, 'N° Sol');
                $activeWorksheet->setCellValue('B' . $i, 'Num SAP');
                $activeWorksheet->setCellValue('C' . $i, 'Tipo');
                $activeWorksheet->setCellValue('D' . $i, 'Estado');
                $activeWorksheet->setCellValue('E' . $i, 'Fecha nec');
                $activeWorksheet->setCellValue('F' . $i, 'Fehca doc');
                $activeWorksheet->setCellValue('G' . $i, 'Solicitante');
                $activeWorksheet->setCellValue('H' . $i, 'Depart');
                $activeWorksheet->setCellValue('I' . $i, 'Correo');
                $activeWorksheet->setCellValue('J' . $i, '# sev /art');
                $activeWorksheet->setCellValue('K' . $i, 'propietario');
                $activeWorksheet->setCellValue('L' . $i, 'Coment');
                $i++;
                $activeWorksheet->setCellValue('A' . $i, $solicitudes->pk_num_sol);
                $activeWorksheet->setCellValue('B' . $i, $solicitudes->numSAP);
                $activeWorksheet->setCellValue('C' . $i, $solicitudes->tipo);
                $activeWorksheet->setCellValue('D' . $i, $solicitudes->estado_sol);
                $activeWorksheet->setCellValue('E' . $i, $solicitudes->fecha_necesaria);
                $activeWorksheet->setCellValue('F' . $i, $solicitudes->fecha_documento);
                $activeWorksheet->setCellValue('G' . $i, $solicitudes->nom_solicitante);
                $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$solicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                foreach ($depSol as $depSols) {
                    $activeWorksheet->setCellValue('H' . $i, $depSols->nom_dep);
                }
                $activeWorksheet->setCellValue('I' . $i, $solicitudes->correo_sol);
                $activeWorksheet->setCellValue('J' . $i, $solicitudes->cantidad);
                $activeWorksheet->setCellValue('K' . $i, $solicitudes->propietario);
                $activeWorksheet->setCellValue('L' . $i, $solicitudes->comentarios);
                $i++;
                if ($solicitudes->tipo == "servicio") { //si la solicitud es de servicios se le agrega un color amarillo al header de la tabla
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i - 1)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i - 1)
                        ->getFill()->getStartColor()->setARGB('FFE699');
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getFill()->getStartColor()->setARGB('FFE699');
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $activeWorksheet->setCellValue('A' . $i, '#');
                    $activeWorksheet->setCellValue('B' . $i, 'Des serv');
                    $activeWorksheet->setCellValue('C' . $i, 'Fecha Nec');
                    $activeWorksheet->setCellValue('D' . $i, 'Proveedor');
                    $activeWorksheet->setCellValue('E' . $i, 'Precio Info');
                    $activeWorksheet->setCellValue('F' . $i, 'C. Mayor');
                    $activeWorksheet->setCellValue('G' . $i, 'UEN');
                    $activeWorksheet->setCellValue('H' . $i, 'lineas');
                    $activeWorksheet->setCellValue('I' . $i, 'sublineas');
                    $activeWorksheet->setCellValue('J' . $i, 'proyecto');
                    $activeWorksheet->setCellValue('K' . $i, '% Descuento');
                    $activeWorksheet->setCellValue('L' . $i, 'ind imp');
                    $activeWorksheet->setCellValue('M' . $i, 'total');
                    $i++;
                    $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solicitudes->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los servicios de la solicitud
                    $j=1;
                    foreach ($lista as $listaa) {
                        //se llama cada uno de los valores de la base de datos
                        $activeWorksheet->setCellValue('A' . $i, $j);
                        $activeWorksheet->setCellValue('B' . $i, $listaa->nom_arse);
                        $activeWorksheet->setCellValue('C' . $i, $listaa->fecha_nec);
                        $activeWorksheet->setCellValue('D' . $i, $listaa->proveedor);
                        $activeWorksheet->setCellValue('E' . $i, $listaa->precio_info);
                        $activeWorksheet->setCellValue('F' . $i, $listaa->cuenta_mayor);
                        $activeWorksheet->setCellValue('G' . $i, $listaa->uen);
                        $activeWorksheet->setCellValue('H' . $i, $listaa->linea);
                        $activeWorksheet->setCellValue('I' . $i, $listaa->sublinea);
                        $activeWorksheet->setCellValue('J' . $i, $listaa->proyecto);
                        $activeWorksheet->setCellValue('K' . $i, $listaa->por_desc);
                        $activeWorksheet->setCellValue('L' . $i, $listaa->ind_imp);
                        $activeWorksheet->setCellValue('M' . $i, $listaa->total_ml);
                        $i++;
                        $j++;
                    }
                    $i++;
                } else {//si la soolicitud es de articulos
                    //le agrega un color verde al header de la tabla
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i - 1)
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $spreadsheet->getActiveSheet()->getStyle('C' . $i - 1)
                        ->getFill()->getStartColor()->setARGB('C6E0B4');
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getFill()->getStartColor()->setARGB('C6E0B4');
                    $spreadsheet->getActiveSheet()->getStyle('A' . $i . ':M' . $i . '')
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    $activeWorksheet->setCellValue('A' . $i, '#');
                    $activeWorksheet->setCellValue('B' . $i, 'Codigo');
                    $activeWorksheet->setCellValue('C' . $i, 'Descripcion');
                    $activeWorksheet->setCellValue('D' . $i, 'Proveedor');
                    $activeWorksheet->setCellValue('E' . $i, 'Fecha Nec');
                    $activeWorksheet->setCellValue('F' . $i, 'Cant Nec');
                    $activeWorksheet->setCellValue('G' . $i, 'Precio info');
                    $activeWorksheet->setCellValue('H' . $i, '% descuento');
                    $activeWorksheet->setCellValue('I' . $i, 'ind imp');
                    $activeWorksheet->setCellValue('J' . $i, 'total');
                    $activeWorksheet->setCellValue('K' . $i, 'uen');
                    $activeWorksheet->setCellValue('L' . $i, 'lineas');
                    $activeWorksheet->setCellValue('M' . $i, 'sublineas');
                    $i++;
                    $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solicitudes->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los articulos de la solicitud              
                    $j=1;
                    foreach ($lista as $listaa) {
                    //se llama cada uno de los valores de la base de datos
                    $activeWorksheet->setCellValue('A' . $i, $j );
                    $activeWorksheet->setCellValue('B' . $i, $listaa->codigo_articulo );
                    $activeWorksheet->setCellValue('C' . $i, $listaa->nom_arse );
                    $activeWorksheet->setCellValue('D' . $i, $listaa->proveedor );
                    $activeWorksheet->setCellValue('E' . $i, $listaa->fecha_nec );
                    $activeWorksheet->setCellValue('F' . $i, $listaa->cant_nec);
                    $activeWorksheet->setCellValue('G' . $i, $listaa->precio_info);
                    $activeWorksheet->setCellValue('H' . $i, $listaa->por_desc);
                    $activeWorksheet->setCellValue('I' . $i, $listaa->ind_imp);
                    $activeWorksheet->setCellValue('J' . $i, $listaa->total_ml);
                    $activeWorksheet->setCellValue('K' . $i, $listaa->uen);
                    $activeWorksheet->setCellValue('L' . $i, $listaa->linea);
                    $activeWorksheet->setCellValue('M' . $i, $listaa->sublinea);
                    $i++;
                    $j++;
                    }
                    $i++;
                }
            }
        }

        $writer = new Xlsx($spreadsheet); //se crear el archivo excel coon los datoos creados anteriormente
        $linkInforme='../informes/informe-'.date("Y-m-d H-i-s").'.xlsx'; //se define el lugar donde se va a guardar con el nombre
        $writer->save($linkInforme);//se guarda el archivo en el link
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
                    <!-- OPCIONES PARA EL FILTRO DEL INFORME -->
                    <h3>Seleccione los datos a mostrar en el informe</h3>
                    <h4>Solicitante:</h4>
                    <!-- SELECT PARA EL USUARIO -->
                    <Select id="usuario" style="width: 18rem">
                        <?php if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") { ?>
                            <option value="<?php echo $usuarioo; ?>"><?php echo $usuarioo; ?></option>
                        <?php } ?>
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
                    <!-- SELECT PARA EL ESTADO DE LA SOLICITUD -->
                    <Select id="estado" style="width: 18rem">
                        <?php if (isset($_GET["estado"]) && $_GET["estado"] != "") { ?>
                            <option value="<?php echo $estado; ?>"><?php echo $estado; ?></option>
                        <?php } ?>
                        <option value="">todos</option>
                        <option value="ENVIADO">ENVIADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                        <option value="ABIERTO">ABIERTO</option>
                    </Select>
                    <h4>Tipo solicitud:</h4>
                    <!-- SELECT PARA EL TIPO DE SOLICITUD -->
                    <Select id="tipo" style="width: 18rem">
                        <?php if (isset($_GET["tipo"]) && $_GET["tipo"] != "") { ?>
                            <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                        <?php } ?>
                        <option value="">todos</option>
                        <option value="servicio">servicio</option>
                        <option value="articulo">articulo</option>
                    </Select>
                    <h4>Fecha Documento:(Desde - Hasta)</h4>
                    <!-- INPUT PARA SELECCIONAR LA FECHA DESDE DONDE SE QUIERE HACER EL INFORME -->
                    <input type="date" id="desde" value="<?php if (isset($_GET["desde"]) && $_GET["desde"] != "") {
                        echo $desde;
                    } ?>" max="<?= date("Y-m-d") ?>">~
                    <!-- INPUT PARA SELECCIONAR LA FECHA HASTA DONDE SE QUIERE HACER EL INFORME -->
                    <input type="date" id="hasta" value="<?php if (isset($_GET["hasta"]) && $_GET["hasta"] != "") {
                        echo $hasta;
                    } else {
                        echo date("Y-m-d");
                    } ?>" max="<?= date("Y-m-d") ?>">
                    <br>
                    <!-- BOTONES PARA APLICAR FILTROS O QUITAR -->
                    <button class="btn_informes" type="button" onclick="aplicarFiltro() ">APLICAR</button>
                    <a href="informes.php"><button class="btn_informes" type="button">QUITAR</button></a>
                </div>
                <div id="informes-observaciones">
                    <h4>Observaciones:</h4>
                    <!-- TEXT AREA PARA LAS OBSERVACIONES -->
                    <textarea style="resize: none;" name="observaciones" id="observaciones" cols="45" rows="7"><?php if (isset($_GET["observaciones"])) {
                        echo $_GET["observaciones"];
                    } ?></textarea>
                </div>
                <div id="informes-boton">
                    <!-- BOTON PARA GENERAR EXCEL -->
                    <button class="btn_informes" type="button" onclick="generarInforme()">Generar Excel del
                        informe</button>
                        <!-- CUANDO SE GENERA EL EXCEL SE ACTIVA EL BOTON PARA DESCARGAR -->
                    <a href="<?php echo $linkInforme ?>" download><button class="btn_descargar" type="button"
                            id="btn_descargar" hidden>DESCARGAR</button></a>
                </div>
                <!-- TABLA PARA VISUALIZAR LAS SOLICITUDES -->
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
                                        <th>Solicitante</th>
                                        <th>Departamento</th>
                                        <th>Correo</th>
                                        <th># sevicios /articulos</th>
                                        <th>propietario</th>
                                        <th>Comentarios</th>

                                    </thead>
                                    <tbody>
                                        <?php
                                        // SE CARGAN LAS SOLICITUDES SEGUN LOS FILTROS
                                        $solicitud = $base->query($filtros)->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
                                        foreach ($solicitud as $solicitudes) {
                                            if ($solicitudes->fecha_documento >= $desde && $solicitudes->fecha_documento <= $hasta) {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="infoS.php?numSol=<?php echo $solicitudes->pk_num_sol ?>"><?php echo $solicitudes->pk_num_sol ?></a>
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
    // LE DA ESTIO A LOS SELECT Y LES AGREGA EL BUSCADOR
    $('#usuario').select2();
    $('#estado').select2();
    $('#tipo').select2();
                            
    //FUNCION PARA APLICAR LOS FILTROS SELECCIONADOS POR EL USUARIO
    function aplicarFiltro() {
        console.log("USUARIO: " + $('#usuario').val());
        console.log("ESTADO: " + $('#estado').val());
        console.log("TIPO: " + $('#tipo').val());
        console.log("DESDE: " + $('#desde').val());
        console.log("HASTA: " + $('#hasta').val());
        // SE CARGA LA MISMA PAGINA CON LOS FILTROS POR URL
        window.location = "informes.php?usuarioo=" + $('#usuario').val() + "&estado=" + $('#estado').val() + "&tipo=" + $('#tipo').val() + "&desde=" + $('#desde').val() + "&hasta=" + $('#hasta').val() + "";
    }
    // FUNCION PARA GENERAR INFORMES
    function generarInforme() {
        console.log("USUARIO: " + $('#usuario').val());
        console.log("ESTADO: " + $('#estado').val());
        console.log("TIPO: " + $('#tipo').val());
        console.log("DESDE: " + $('#desde').val());
        console.log("HASTA: " + $('#hasta').val());
        console.log("OBSERVACIONES: " + $('#observaciones').val());
        // SE CARGA LA PAGINA CON LOS DATOS Y LA OPCION DE GENERAR INFORME
        window.location = "informes.php?generarInforme=si&usuarioo=" + $('#usuario').val() + "&estado=" + $('#estado').val() + "&tipo=" + $('#tipo').val() + "&desde=" + $('#desde').val() + "&hasta=" + $('#hasta').val() + "&observaciones=" + $('#observaciones').val() + "";
    }
    //SI SE ACTIVA LA OPCION DEL INFORME SE ACTIVA EL BOTON DE DESCARGAR
    if ('<?php echo $informe ?>' == 'si') {
        $('#btn_descargar').prop("hidden", false)
    }
    if ('<?php echo $tipo ?>' != '3') {
        console.log("<?php echo $tipo ?>")
        $('#usuario').prop("disabled", true)
    }
</script>

</html>