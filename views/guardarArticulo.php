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

echo "numero Solicitud:".$numSolicitud;
$estado = "ABIERTO";
$nomSol = $_GET["nomSol"];
$correoElectronico = $_GET["correoElectronico"];
$propietario = $_GET["propietario"];
$comentarios = $_GET["comentarios"];
$codUsr = $_GET["codUsr"];
$departamento = $_GET["departamento"];
$sucursal = $_GET["sucursal"];
$cantidad =$_GET['cantidad'];

$sql = "INSERT INTO solicitud_compra (pk_num_sol,estado_sol,nom_solicitante,sucursal,correo_sol,propietario,comentarios,fk_cod_usr,depart_sol,tipo,cantidad) 
        VALUES(:_codSol,:_estado,:_nomSol,:_sucursal,:_correoElectronico,:_propietario,:_comentarios,:_codUsr,:_departamento,:_tipo,:_cantidad)";

$solicitud = $base->prepare($sql);
$solicitud->execute(array(":_codSol" => $numSolicitud, ":_estado" => $estado, ":_nomSol" => $nomSol, ":_sucursal" => $sucursal, ":_correoElectronico" => $correoElectronico, ":_propietario" => $propietario, ":_comentarios" => $comentarios, ":_codUsr" => $codUsr, ":_departamento" => $departamento, ":_tipo" => $tipo, ":_cantidad" => $cantidad));


    $codArt = explode("_", $_GET['codigoArse']);
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
     //agregar descripcion y cambiar valores para enviar a la base de datos
    $code[$j] = $respuestaArticulos->value[$codArt[$j]]->ItemCode;
    $desc[$j] = $respuestaArticulos->value[$codArt[$j]]->ItemName;

    $sql = "INSERT INTO list_arse (fk_num_sol,fk_cod_arse,nom_arse,fecha_nec,fk_prov,cant_nec,precio_info,por_desc,ind_imp,total_ml,uen,linea,sublinea) 
                    VALUES(:_numSol,:_codArse,:_descripcion,:_fechaNec,:_proveedor,:_cant_nec,:_precioInfo,:_por_desc,:_ind_imp,:_total_ml,:_uen,:_linea,:_sublinea)";

                    $serv = $base->prepare($sql);

                    $serv->execute(array(":_numSol" => $numSolicitud, ":_codArse" => $code[$j], ":_descripcion" => $desc[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_cant_nec" => $cantNec[$j], ":_precioInfo" => $precioInfo[$j], ":_por_desc" => $porDesc[$j], ":_ind_imp" => $indImp[$j], ":_total_ml" => $total[$j], ":_uen" => $uen[$j], ":_linea" => $linea[$j], ":_sublinea" => $sublinea[$j]));



}

?>