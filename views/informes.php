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
    
    $filtro = "NO";
    if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") {
        $usuarioo = $_GET["usuarioo"];
        $filtro = "SI";
    }
    if (isset($_GET["estado"]) && $_GET["estado"] != "") {
        $estado = $_GET["estado"];
        $filtro = "SI";
    }
    if (isset($_GET["tipo"]) && $_GET["tipo"] != "") {
        $tipo = $_GET["tipo"];
        $filtro = "SI";
    }
    $desde = 0;
    $hasta = date("Y-m-d");
    if (isset($_GET["desde"])) {
        $desde = $_GET["desde"];
    }
    if (isset($_GET["hasta"])) {
        $hasta = $_GET["hasta"];
    }
    if ($filtro == 'NO') {
        $filtros = "SELECT * FROM solicitud_compra";
    } else {
        $filtros = "SELECT * FROM solicitud_compra WHERE ";
        $i = 0;
        if (isset($_GET["usuarioo"]) && $_GET["usuarioo"] != "") {
            if ($i == 0) {
                $filtros = $filtros . "fk_cod_usr='$usuarioo'";
                $i++;
            } else {
                $filtros = $filtros . "and fk_cod_usr='$usuarioo'";
                $i++;
            }
        }
        if (isset($_GET["estado"]) && $_GET["estado"] != "") {
            if ($i == 0) {
                $filtros = $filtros . "estado_sol='$estado'";
                $i++;
            } else {
                $filtros = $filtros . "and estado_sol='$estado'";
                $i++;
            }
        }
        if (isset($_GET["tipo"]) && $_GET["tipo"] != "") {
            if ($i == 0) {
                $filtros = $filtros . "tipo='$tipo'";
                $i++;
            } else {
                $filtros = $filtros . "and tipo='$tipo'";
                $i++;
            }
        }

    }
    require '../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $informe = "no";
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
        $solicitud = $base->query($filtros)->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
        foreach ($solicitud as $solicitudes) {
            if ($solicitudes->fecha_documento >= $desde && $solicitudes->fecha_documento <= $hasta) {

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
                if ($solicitudes->tipo == "servicio") {
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
                    $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solicitudes->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los articulos de la solicitud en la variable 
                    $j=1;
                    foreach ($lista as $listaa) {
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
                } else {
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
                    $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solicitudes->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los articulos de la solicitud en la variable                
                    $j=1;
                    foreach ($lista as $listaa) {
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

        $writer = new Xlsx($spreadsheet);
        $linkInforme='../informes/informe-'.date("Y-m-d H-i-s").'.xlsx';
        $writer->save($linkInforme);
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
                    <h4>Solicitante:</h4>
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
                    <Select id="tipo" style="width: 18rem">
                        <?php if (isset($_GET["tipo"]) && $_GET["tipo"] != "") { ?>
                            <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                        <?php } ?>
                        <option value="">todos</option>
                        <option value="servicio">servicio</option>
                        <option value="articulo">articulo</option>
                    </Select>
                    <h4>Fecha Documento:(Desde - Hasta)</h4>
                    <input type="date" id="desde" value="<?php if (isset($_GET["desde"]) && $_GET["desde"] != "") {
                        echo $desde;
                    } ?>" max="<?= date("Y-m-d") ?>">~
                    <input type="date" id="hasta" value="<?php if (isset($_GET["hasta"]) && $_GET["hasta"] != "") {
                        echo $hasta;
                    } else {
                        echo date("Y-m-d");
                    } ?>" max="<?= date("Y-m-d") ?>">
                    <br>
                    <button class="btn_informes" type="button" onclick="aplicarFiltro() ">APLICAR</button>
                    <a href="informes.php"><button class="btn_informes" type="button">QUITAR</button></a>
                </div>
                <div id="informes-observaciones">
                    <h4>Observaciones:</h4>
                    <textarea name="observaciones" id="observaciones" cols="45" rows="7"><?php if (isset($_GET["observaciones"])) {
                        echo $_GET["observaciones"];
                    } ?></textarea>
                </div>
                <div id="informes-boton">
                    <button class="btn_informes" type="button" onclick="generarInforme()">Generar Excel del
                        informe</button>
                    <a href="<?php echo $linkInforme ?>" download><button class="btn_descargar" type="button"
                            id="btn_descargar" hidden>DESCARGAR</button></a>
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
                                        <th>Solicitante</th>
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
    function generarInforme() {
        console.log("USUARIO: " + $('#usuario').val());
        console.log("ESTADO: " + $('#estado').val());
        console.log("TIPO: " + $('#tipo').val());
        console.log("DESDE: " + $('#desde').val());
        console.log("HASTA: " + $('#hasta').val());
        console.log("OBSERVACIONES: " + $('#observaciones').val());
        window.location = "informes.php?generarInforme=si&usuarioo=" + $('#usuario').val() + "&estado=" + $('#estado').val() + "&tipo=" + $('#tipo').val() + "&desde=" + $('#desde').val() + "&hasta=" + $('#hasta').val() + "&observaciones=" + $('#observaciones').val() + "";
    }
    if ('<?php echo $informe ?>' == 'si') {
        $('#btn_descargar').prop("hidden", false)
    }
</script>

</html>