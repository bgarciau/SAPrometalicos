<?php

if (!isset($_SESSION['sesion'])) {
    //Conexion a Service Layer
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"CompanyDB": "BPROMETALICOS", "Password": "HYC909", "UserName": "manager"}',
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        )
    );
    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo 'Error en la solicitud cURL: ' . curl_error($curl);
    }
    $json = json_decode($response, true);
    $sesion = $json['SessionId'];
    $_SESSION['sesion'] = $sesion;
    curl_close($curl);
} else {
    $sesion = $_SESSION['sesion'];
}

// LLAMADO PROVEEDORES  

$curlProv = curl_init();

curl_setopt_array($curlProv, array(
    CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/BusinessPartners?$select=CardCode,CardName,CardType&$filter=startswith(CardCode,%20\'P\')%20&$orderby=CardName',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Prefer:odata.maxpagesize=2790',
        'Content-Type: application/json',
        'Cookie: B1SESSION=' . $sesion . ''
    ),
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
)
);

$responseProv = curl_exec($curlProv);

if (curl_errno($curlProv)) {
    echo 'Error en la solicitud cURL prov: ' . curl_error($curl);
}

curl_close($curlProv);

$respuestaProveedor = json_decode($responseProv);
//print_r($respuestaProveedor);

// LLAMADO DE SERVICIOS
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
            'Prefer:odata.maxpagesize=500',
            'Content-Type: application/json',
            'Cookie: B1SESSION=' . $sesion . ''
        ),
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,

    )
);
$responseServ = curl_exec($curlServicios);

if (curl_errno($curlServicios)) {
    echo 'Error en la solicitud cURL Serv: ' . curl_error($curl);
}
curl_close($curlServicios);
$respuestaServicios = json_decode($responseServ);
// print_r($respuestaServicios);

//LLAMADO DE PROYECTOS
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
            'Prefer:odata.maxpagesize=40',
            'Content-Type: application/json',
            'Cookie: B1SESSION=' . $sesion . ''
        ),
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    )
);

$responseProyecto = curl_exec($curlProyecto);

$respuestaProyecto = json_decode($responseProyecto);

curl_close($curlProyecto);

//LLAMADO ARTICULOS

$curlArticulos = curl_init();

curl_setopt_array($curlArticulos, array(
  CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Items?$select=ItemCode,ItemName,PurchaseItem,AssetItem&$filter=PurchaseItem%20eq%20\'tYES\'%20and%20AssetItem%20eq%20\'tNO\'%20&$orderby=ItemName',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Prefer:odata.maxpagesize=7400',
    'Content-Type: application/json',
    'Cookie: B1SESSION=' . $sesion . ''
),
CURLOPT_SSL_VERIFYHOST => false,
CURLOPT_SSL_VERIFYPEER => false,
));

$responseArticulos = curl_exec($curlArticulos);

$respuestaArticulos = json_decode($responseArticulos);

curl_close($curlArticulos);

//LLAMADO INDICADOR IMPUESTOS

$curlIndImp = curl_init();

curl_setopt_array($curlIndImp, array(
  CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/SalesTaxCodes?$select=Code,Name,Rate&$filter=Inactive%20eq%20\'tNO\'%20and%20ValidForAP%20eq%20\'tYES\'&$orderby=Code',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Cookie: B1SESSION=' . $sesion . ''
),
CURLOPT_SSL_VERIFYHOST => false,
CURLOPT_SSL_VERIFYPEER => false,
));

$responseIndImp = curl_exec($curlIndImp);

$respuestaIndImp = json_decode($responseIndImp);

curl_close($curlIndImp);

//LLAMADO UEN

$curlUen = curl_init();

curl_setopt_array($curlUen, array(
  CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/DistributionRules?$select=FactorCode,FactorDescription&$filter=InWhichDimension%20eq%201',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Prefer:odata.maxpagesize=80',
    'Content-Type: application/json',
    'Cookie: B1SESSION=' . $sesion . ''
),
CURLOPT_SSL_VERIFYHOST => false,
CURLOPT_SSL_VERIFYPEER => false,
));

$responseUen = curl_exec($curlUen);

$respuestaUen = json_decode($responseUen);

curl_close($curlUen);

//LLAMADO LINEA 

$curlLinea = curl_init();

curl_setopt_array($curlLinea, array(
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
));

$responseLinea = curl_exec($curlLinea);

$respuestaLinea = json_decode($responseLinea);

curl_close($curlLinea);


//LLAMADO SUBLINEA 

$curlSubLinea = curl_init();

curl_setopt_array($curlSubLinea, array(
  CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/DistributionRules?$select=FactorCode,FactorDescription&$filter=InWhichDimension%20eq%203',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Prefer:odata.maxpagesize=650',
    'Content-Type: application/json',
    'Cookie: B1SESSION=' . $sesion . ''
),
CURLOPT_SSL_VERIFYHOST => false,
CURLOPT_SSL_VERIFYPEER => false,
));

$responseSubLinea = curl_exec($curlSubLinea);

$respuestaSubLinea = json_decode($responseSubLinea);

curl_close($curlSubLinea);