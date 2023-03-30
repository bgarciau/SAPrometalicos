<?php

include("../php/conexion.php");
include("../php/SAP.php");
$tipo = "servicio";
$cantidad = 0;
$j = 0;

// $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
// $numSolicitud = 1;
// foreach ($ultimo as $ultimoo):
//     $numSolicitud++;
// endforeach;

$numSolicitud = $_GET['numSolicitud'];
$codigoArse = explode("_", $_GET['codigoArse']);
$fechaNec =explode("_", $_GET['fechaNec']);
$proveedor =explode("_", $_GET['proveedor']);
$precioInfo =explode("_", $_GET['precioInfo']);
$cuentaMayor =explode("_", $_GET['cuentaMayor']);
$uen =explode("_", $_GET['uen']);
$linea =explode("_", $_GET['linea']);
$sublinea =explode("_", $_GET['sublinea']);
$proyecto =explode("_", $_GET['proyecto']);
$porDesc =explode("_", $_GET['porDesc']);
$indImp =explode("_", $_GET['indImp']);
$total =explode("_", $_GET['total']);
$cantidad =$_GET['cantidad'];


for ($j = 0; $j < $cantidad; $j++) {
    $sql = "INSERT INTO list_arse (fk_num_sol,nom_arse,fecha_nec,fk_prov,precio_info,cuenta_mayor,uen,linea,sublinea,proyecto,por_desc,ind_imp,total_ml) 
                        VALUES(:_numSol,:_nomArse,:_fechaNec,:_proveedor,:_precioInfo,:_cuentaMayor,:_uen,:_linea,:_sublinea,:_proyecto,:_porDesc,:_indImp,:_total)";

    $serv = $base->prepare($sql);

    $serv->execute(array(":_numSol" => $numSolicitud, ":_nomArse" => $codigoArse[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_precioInfo" => $precioInfo[$j], ":_cuentaMayor" => $cuentaMayor[$j], ":_uen" => $uen[$j], ":_linea" => $linea[$j], ":_sublinea" => $sublinea[$j], ":_proyecto" => $proyecto[$j], ":_porDesc" => $porDesc[$j], ":_indImp" => $indImp[$j], ":_total" => $total[$j]));
 }





?>