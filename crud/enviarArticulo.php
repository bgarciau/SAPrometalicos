<?php
include("../php/conexion.php"); //incluye la conexion a la bd0
//Conexion a Service Layer
//curl se usa para que se usa para la transferenia de datos
$curl = curl_init(); //inicia sesion curl
curl_setopt_array(
    // Configura múltiples opciones para una transferencia cURL
    $curl,
    array(
        CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Login',
        //Dirección URL a capturar.
        CURLOPT_RETURNTRANSFER => true,
        //true para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
        CURLOPT_ENCODING => '',
        //se enviarán todos los tipos de condificación soportados.
        CURLOPT_MAXREDIRS => 10,
        //Número máximo de redirecciones HTTP a seguir
        CURLOPT_TIMEOUT => 0,
        //Número máximo de segundos permitido para ejectuar funciones cURL.
        CURLOPT_FOLLOWLOCATION => true,
        //true para seguir cualquier encabezado "Location: " que el servidor envíe como parte del encabezado HTTP (observe la recursividad, PHP seguirá tantos header "Location: " como se envíen, a no ser que la opción CURLOPT_MAXREDIRS esté establecida).
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //fuerza HTTP/1.1
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"CompanyDB": "TEST_BPROMETALICOS", "Password": "HYC909", "UserName": "manager"}',
        //datos que se envian a la direccion para iniciar sesion
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
        //Un array de campos a configurar para el header HTTP
        CURLOPT_SSL_VERIFYHOST => false,
        //Se usa para no verificar el certificado del curl
        CURLOPT_SSL_VERIFYPEER => false, //Se usa para no verificar el certificado del curl
    )
);
$response = curl_exec($curl); //Ejecuta la sesión cURL que se le pasa como parámetro. 

if (curl_errno($curl)) {
    echo 'Error en la solicitud cURL: ' . curl_error($curl);
}
$json = json_decode($response, true); //Convierte un string codificado en JSON a una variable de PHP.
$sesion = $json['SessionId']; //se guarda la sessionId que viene del sap para poder llamar otros valores mas adelante
$_SESSION['sesion'] = $sesion; //se guarda como una variavle de la sesion en la pagina
curl_close($curl); //Esta función cierra una sesión CURL y libera todos sus recursos

// ----------------------------------------------------------------------------------------------------------------------------
//cargar datos al json
$numSol = $_GET["numSol"];
$soli = $base->query("SELECT * FROM solicitud_compra WHERE pk_num_sol='$numSol'")->fetchAll(PDO::FETCH_OBJ); // se guardan los datos de la solicitud de compra en un PDOStatement
foreach ($soli as $solis) {
    $solicitud = new stdClass();
    $solicitud->DocType = "dDocument_Items";
    $solicitud->DocDate = "2023-04-03";
    $solicitud->RequriedDate = $solis->fecha_necesaria;
    $solicitud->Comments = $solis->comentarios;
    $solicitud->Requester = "manager";
    $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solis->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los servicios de la solicitud en la variable 
    $i = 0;
    foreach ($lista as $listaa) {

    $articulo = new stdClass();
    $articulo->LineNum = $i;
    $articulo->ItemCode=$listaa->codigo_articulo;
    $articulo->Quantity=$listaa->cant_nec;
    $articulo->RequiredDate=$listaa->fecha_nec;
    $articulo->LineVendor=$listaa->proveedor;
    $articulo->CostingCode=$listaa->uen;
    $articulo->CostingCode2=$listaa->linea;
    $articulo->CostingCode3=$listaa->sublinea;
    $articulo->TaxCode=$listaa->ind_imp;
    $articulos[$i] = $articulo;
    $i++;
    }
    $solicitud->DocumentLines = $articulos;

    $JSONsolicitud = json_encode($solicitud);
}
echo $JSONsolicitud;
//------------------------------------------------------------------------------------------------------------------------------

echo "\n envia la solicitud \n";
//enviar solicitud
$curlEnviar = curl_init(); //inicia sesion curlEnviar
curl_setopt_array( // Configura múltiples opciones para una transferencia cURL
    $curlEnviar,
    array(
        CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/PurchaseRequests', //Dirección URL a capturar.
        CURLOPT_RETURNTRANSFER => true, //true para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
        CURLOPT_ENCODING => '', //se enviarán todos los tipos de condificación soportados.
        CURLOPT_MAXREDIRS => 10, //Número máximo de redirecciones HTTP a seguir
        CURLOPT_TIMEOUT => 0, //Número máximo de segundos permitido para ejectuar funciones cURL.
        CURLOPT_FOLLOWLOCATION => true, //true para seguir cualquier encabezado "Location: " que el servidor envíe como parte del encabezado HTTP (observe la recursividad, PHP seguirá tantos header "Location: " como se envíen, a no ser que la opción CURLOPT_MAXREDIRS esté establecida).
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, //fuerza HTTP/1.1
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $JSONsolicitud, //datos que se envian para crear la solicitud
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded','Cookie: B1SESSION=' . $sesion . ''), //Un array de campos a configurar para el header HTTP
        CURLOPT_SSL_VERIFYHOST => false, //Se usa para no verificar el certificado del curlEnviar
        CURLOPT_SSL_VERIFYPEER => false,//Se usa para no verificar el certificado del curlEnviar
    )
);
$response = curl_exec($curlEnviar);  //Ejecuta la sesión cURL que se le pasa como parámetro. 

if (curl_errno($curlEnviar)) {
    echo 'Error en la solicitud cURL: ' . curl_error($curlEnviar);
}

echo $response;
curl_close($curlEnviar); //Esta función cierra una sesión CURL y libera todos sus recursos

// -----------------------------------------------------------------------------------------------------------------------

?>