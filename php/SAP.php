<?php

//Conexion a Service Layer
//curl se usa para que se usa para la transferenia de datos
$curl = curl_init(); //inicia sesion curl
curl_setopt_array( // Configura múltiples opciones para una transferencia cURL
    $curl,
    array(
        CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Login', //Dirección URL a capturar.
        CURLOPT_RETURNTRANSFER => true, //true para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
        CURLOPT_ENCODING => '', //se enviarán todos los tipos de condificación soportados.
        CURLOPT_MAXREDIRS => 10, //Número máximo de redirecciones HTTP a seguir
        CURLOPT_TIMEOUT => 0, //Número máximo de segundos permitido para ejectuar funciones cURL.
        CURLOPT_FOLLOWLOCATION => true, //true para seguir cualquier encabezado "Location: " que el servidor envíe como parte del encabezado HTTP (observe la recursividad, PHP seguirá tantos header "Location: " como se envíen, a no ser que la opción CURLOPT_MAXREDIRS esté establecida).
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, //fuerza HTTP/1.1
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"CompanyDB": "BPROMETALICOS", "Password": "HYC909", "UserName": "manager"}', //datos que se envian a la direccion para iniciar sesion
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'), //Un array de campos a configurar para el header HTTP
        CURLOPT_SSL_VERIFYHOST => false, //Se usa para no verificar el certificado del curl
        CURLOPT_SSL_VERIFYPEER => false,//Se usa para no verificar el certificado del curl
    )
);
$response = curl_exec($curl);  //Ejecuta la sesión cURL que se le pasa como parámetro. 

if (curl_errno($curl)) {
    echo 'Error en la solicitud cURL: ' . curl_error($curl);
}
$json = json_decode($response, true); //Convierte un string codificado en JSON a una variable de PHP.
$sesion = $json['SessionId'];//se guarda la sessionId que viene del sap para poder llamar otros valores mas adelante
$_SESSION['sesion'] = $sesion;//se guarda como una variavle de la sesion en la pagina
curl_close($curl); //Esta función cierra una sesión CURL y libera todos sus recursos

// ----------------------------------------------------------------------------------------------------------------------------

// LLAMADO PROVEEDORES  
function proveedores($sesion) //funcion para llamar a los proveedores
{
    $curlProv = curl_init();

    curl_setopt_array(
        $curlProv,
        array(
            //url que llama los datos que se necesitan de los proveedores para esta aplicacion
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/BusinessPartners?$select=CardCode,CardName,CardType&$filter=startswith(CardCode,%20\'P\')%20&$orderby=CardName',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=2890',   //maximo de datos a recibir, el total de  proveedores en este momento es 2890
                'Content-Type: application/json',   //como se quieren recibir los datos
                'Cookie: B1SESSION=' . $sesion . '' //esta sesion nos permite recibir los datos, sin iniciar sesion no podemos recibir nada
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseProv = curl_exec($curlProv);

    curl_close($curlProv);

    $respuestaProveedor = json_decode($responseProv);

    return $respuestaProveedor; //devuelve los proveedores en la pagina que se llamo la funcion
}
// -----------------------------------------------------------------------------------------------------------------------------------------

  // LLAMADO DE SERVICIOS
function servicios($sesion)
{
    $curlServicios = curl_init();

    curl_setopt_array(
        $curlServicios,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/U_BP_CODSERVICIOS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=468',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

        )
    );
    $responseServ = curl_exec($curlServicios);

    curl_close($curlServicios);
    $respuestaServicios = json_decode($responseServ);

    return $respuestaServicios;
}
// ---------------------------------------------------------------------------------------------------------------------------------

//LLAMADO DE PROYECTOS
function proyectos($sesion)
{
    $curlProyecto = curl_init();

    curl_setopt_array(
        $curlProyecto,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Projects?$select=Code,Name&$filter=Active%20eq%20\'tYES\'%20and%20startswith(Code,%20\'PR\')&$orderby=Name',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=33',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseProyecto = curl_exec($curlProyecto);

    curl_close($curlProyecto);
    $respuestaProyecto = json_decode($responseProyecto);

    return $respuestaProyecto;
}
// -----------------------------------------------------------------------------------------------------------------------------------

//LLAMADO ARTICULOS
function articulos($sesion)
{
    $curlArticulos = curl_init();

    curl_setopt_array(
        $curlArticulos,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Items?$select=ItemCode,ItemName,PurchaseItem,AssetItem&$filter=PurchaseItem%20eq%20\'tYES\'%20and%20AssetItem%20eq%20\'tNO\'%20&$orderby=ItemName',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=7357',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseArticulos = curl_exec($curlArticulos);

    $respuestaArticulos = json_decode($responseArticulos);

    curl_close($curlArticulos);

    return $respuestaArticulos;

}
// --------------------------------------------------------------------------------------------------------------------------------------

//LLAMADO INDICADOR IMPUESTOS
function indImpuestos($sesion)
{

    $curlIndImp = curl_init();

    curl_setopt_array(
        $curlIndImp,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/SalesTaxCodes?$select=Code,Name,Rate&$filter=Inactive%20eq%20\'tNO\'%20and%20ValidForAP%20eq%20\'tYES\'&$orderby=Code',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=10',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseIndImp = curl_exec($curlIndImp);

    $respuestaIndImp = json_decode($responseIndImp);

    curl_close($curlIndImp);

    return $respuestaIndImp;

}
// ----------------------------------------------------------------------------------------------------------------------------------------------------

//LLAMADO UEN
function uen($sesion)
{
    $curlUen = curl_init();

    curl_setopt_array(
        $curlUen,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/DistributionRules?$select=FactorCode,FactorDescription&$filter=InWhichDimension%20eq%201',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=81',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseUen = curl_exec($curlUen);

    $respuestaUen = json_decode($responseUen);

    curl_close($curlUen);

    return $respuestaUen;
}
//--------------------------------------------------------------------------------------------------------------------------------- 

//LLAMADO LINEA 
function linea($sesion)
{
    $curlLinea = curl_init();

    curl_setopt_array(
        $curlLinea,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/DistributionRules?$select=FactorCode,FactorDescription&$filter=InWhichDimension%20eq%202',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Prefer:odata.maxpagesize=360',
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );

    $responseLinea = curl_exec($curlLinea);

    $respuestaLinea = json_decode($responseLinea);

    curl_close($curlLinea);

    return $respuestaLinea;
}
// ----------------------------------------------------------------------------------------------------------------------------------

//LLAMADO SUBLINEA 
function sublinea($sesion)
{
    $curlSubLinea = curl_init();

    curl_setopt_array(
        $curlSubLinea,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/DistributionRules?$select=FactorCode,FactorDescription&$filter=InWhichDimension%20eq%203',
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

    $responseSubLinea = curl_exec($curlSubLinea);

    $respuestaSubLinea = json_decode($responseSubLinea);

    curl_close($curlSubLinea);
    return $respuestaSubLinea;
}
// -------------------------------------------------------------------------------------------------------------------------------------------
