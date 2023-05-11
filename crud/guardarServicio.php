<?php


include("../php/conexion.php"); //incluye la conexion a la bd
$tipo = "servicio";//se define la variable tipo para establecer este en la base de datos

//se hace para sumar la cantidad de solicitudes que hay en el momento y dejarle a esta nueva solicitud el siguiente numero

$ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
 $numSolicitud = 1;
 foreach ($ultimo as $ultimoo):
    $numSolicitud++;
endforeach;

echo $numSolicitud; //para mostrar en pantalla el numero de la solicitud creada

$estado = "ABIERTO"; //como apenas se crea la solicitud su estado es ABIERTO
//se hace un get de los datos enviados mediante ajax
$nomSol = $_GET["nomSol"];
$correoElectronico = $_GET["correoElectronico"];
$propietario = $_GET["propietario"];
$comentarios = $_GET["comentarios"];
$codUsr = $_GET["codUsr"];
$departamento = $_GET["departamento"];
$sucursal = $_GET["sucursal"];
$cantidad =$_GET['cantidad'];
$fechaNecesaria=$_GET['fechaNecesaria'];
$fechaDocumento=$_GET['fechaDocumento'];

//se define la sentencia para insertar los valores en la base de datos y crear la solicitud de compra
$sql = "INSERT INTO solicitud_compra (pk_num_sol,estado_sol,nom_solicitante,sucursal,correo_sol,propietario,comentarios,fk_cod_usr,depart_sol,tipo,cantidad,fecha_documento,fecha_necesaria) 
        VALUES(:_codSol,:_estado,:_nomSol,:_sucursal,:_correoElectronico,:_propietario,:_comentarios,:_codUsr,:_departamento,:_tipo,:_cantidad,:_fechaDocumento,:_fechaNecesaria)";

$solicitud = $base->prepare($sql);//se prepara la sentencia

//se ejecuta la sentencia con los datos requeridos
$solicitud->execute(array(":_codSol" => $numSolicitud, ":_estado" => $estado, ":_nomSol" => $nomSol, ":_sucursal" => $sucursal, ":_correoElectronico" => $correoElectronico, ":_propietario" => $propietario, ":_comentarios" => $comentarios, ":_codUsr" => $codUsr,":_departamento" => $departamento, ":_tipo" => $tipo, ":_cantidad" => $cantidad,":_fechaDocumento" => $fechaDocumento,":_fechaNecesaria" => $fechaNecesaria));

//se llaman los datos enviados por ajax y se les hace un explode para convertir el string en un array
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


for ($j = 0; $j < $cantidad; $j++) { //for para recorrer cada item de los array 
     //se define la sentencia para insertar los valores en la base de datos y crear cada articulo solicitado
    $sql = "INSERT INTO list_arse (fk_num_sol,nom_arse,fecha_nec,proveedor,precio_info,cuenta_mayor,uen,linea,sublinea,proyecto,por_desc,ind_imp,total_ml) 
                        VALUES(:_numSol,:_nomArse,:_fechaNec,:_proveedor,:_precioInfo,:_cuentaMayor,:_uen,:_linea,:_sublinea,:_proyecto,:_porDesc,:_indImp,:_total)";

    $serv = $base->prepare($sql);//se prepara la sentencia

    //se ejecuta la sentencia con los datos requeridos
    $serv->execute(array(":_numSol" => $numSolicitud, ":_nomArse" => $codigoArse[$j], ":_fechaNec" => $fechaNec[$j], ":_proveedor" => $proveedor[$j], ":_precioInfo" => $precioInfo[$j], ":_cuentaMayor" => $cuentaMayor[$j], ":_uen" => $uen[$j], ":_linea" => $linea[$j], ":_sublinea" => $sublinea[$j], ":_proyecto" => $proyecto[$j], ":_porDesc" => $porDesc[$j], ":_indImp" => $indImp[$j], ":_total" => $total[$j]));
 }

?>