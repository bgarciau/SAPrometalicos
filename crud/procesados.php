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
        CURLOPT_SSL_VERIFYPEER => false,
        //Se usa para no verificar el certificado del curl
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
// Procesados---------------------------------------------------------------------------------------------------
$solicitud = $base->query("SELECT * FROM solicitud_compra")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
foreach ($solicitud as $solicitudes) {
    if ($solicitudes->docEntry != 0) {
        // echo "entra";
        $docEntry = $solicitudes->docEntry;
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/PurchaseRequests(' . $docEntry . ')?$select=DocumentStatus',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Prefer:odata.maxpagesize=648',
                    'Content-Type: application/json',
                    'Cookie: B1SESSION=' . $sesion . ''
                ),
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            )
        );

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        // echo "sale";
        $respuesta = json_decode($response);
        $estadoSAP = $respuesta->DocumentStatus;
        // echo $estadoSAP;
        if ($estadoSAP == "bost_Close") {
            // echo "SI";
            $numSol=$solicitudes->pk_num_sol;
            $sql = "UPDATE solicitud_compra SET estado_sol=? WHERE pk_num_sol='$numSol'"; //ACTUALIZA el estado de la solicitud y el numero SAP
            $solicitud = $base->prepare($sql); //se prepara la sentencia
            $estado_sol = "PROCESADO";
            $solicitud->execute(array($estado_sol));
        }
        else{
            // echo "NO";
        }
    }
}
// -------------------------------------------------------------------------------------------------------------