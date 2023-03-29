<?php

include("../php/conexion.php");
include("../php/SAP.php");
$tipo = "articulo";
$cantidad = 0;
$j = 0;

$ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
$numSolicitud = 1;
foreach ($ultimo as $ultimoo):
    $numSolicitud++;
endforeach;

    $codArse = explode("_", $_GET['codArse']);
    //agregar descripcion y cambiar valores para enviar a la base de datos
    $fechaNec = explode("_", $_GET['fechaNec']);
    $proveedor =explode("_", $_GET['proveedor']);
    $cantNec =explode("_", $_GET['cantNec']);
    $precioInfo =explode("_", $_GET['precioInfo']);
    $porDesc =explode("_", $_GET['porDesc']);
    $indImp =explode("_", $_GET['indImp']);
    $total =explode("_", $_GET['total']);
    $uen =explode("_", $_GET['uen']);
    $linea =explode("_", $_GET['linea']);
    $sublinea =explode("_", $_GET['sublinea']);
    $cantidad = $_GET['cantidad'];


for ($j = 0; $j < $cantidad; $j++) {    

    $sql = "INSERT INTO list_arse (fk_num_sol,fk_cod_arse,nom_arse,fecha_nec,fk_prov,cant_nec,precio_info,por_desc,ind_imp,total_ml,uen,linea,sublinea) 
                    VALUES(:_numSol,:_codArse,:_descripcion,:_fechaNec,:_proveedor,:_cant_nec,:_precioInfo,:_por_desc,:_ind_imp,:_total_ml,:_uen,:_linea,:_sublinea)";

                    $serv = $base->prepare($sql);

                    $serv->execute(array(":_numSol" => $numSolicitud, ":_codArse" => $code[$j], ":_descripcion" => $desc[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_cant_nec" => $cant_nec[$j], ":_precioInfo" => $precioInfo[$j], ":_por_desc" => $porDesc[$j], ":_ind_imp" => $indImp[$j], ":_total_ml" => $total[$j], ":_uen" => $uen[$j], ":_linea" => $xlinea[$j], ":_sublinea" => $sublinea[$j]));



}

?>