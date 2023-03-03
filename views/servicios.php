<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    if (!isset($_SESSION['sesion']))
    {
      //Conexion a Service Layer
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/Login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"CompanyDB": "BPROMETALICOS", "Password": "HYC909", "UserName": "manager"}',
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
      ));
      $response = curl_exec($curl);
  
      if(curl_errno($curl)) {
        echo 'Error en la solicitud cURL: ' . curl_error($curl);
    }
      //curl_close($curl);
      // echo $response;
      $json = json_decode($response, true);
      //echo  $json['SessionId'];;
      //exit;
      $sesion = $json['SessionId'];
      $_SESSION['sesion'] = $sesion;
      //echo "inicio "; echo  $sesion;
      curl_close($curl);
    }
    else{
      $sesion = $_SESSION['sesion'];
      //echo "else "; echo  $sesion;
    }
  
    $curl1 = curl_init();
  
    curl_setopt_array($curl1, array(
      CURLOPT_URL => 'https://192.168.1.229:50000/b1s/v1/U_BP_CODSERVICIOS',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: B1SESSION='.$sesion.''
      ),
      CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $response1 = curl_exec($curl1);
  //   echo $response1;
    //echo $ruta;
    if(curl_errno($curl1)) {
      echo 'Error en la solicitud cURL: ' . curl_error($curl);
  }
    //exit();
    curl_close($curl1);
    $respuestaServicios = json_decode($response1);
      // print_r($respuestaServicios);
  
    //echo "<br>";
    
    include("../php/conexion.php");

    $usuario = $_SESSION['usuario'];

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->fk_tipo_usr;
    }
    if ($userx != 3) {
        header("location:hacerSolicitud.php");
    }

    $serv = $base->query("SELECT * FROM arse WHERE tipo_arse= 'servicio'")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>SERVICIOS</h2>
            <div id="div__tablaSolicitudes">
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <div class="table_wrapperS">
                        <input class="inputBuscar" type="search" name="" value="BUSCAR">
                        <table border="4px" id="tabla__usuarios">
                            <thead>
                                <th>#</th>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Cuenta Mayor</th>
                                <th>UEN</th>
                                <th>Linea</th>
                                <th>Sublinea</th>
                            </thead>
                            <?php
                            $i = 1;
                            foreach ($respuestaServicios->value as $item): ?>
                                <tr>
                                    <td>
                                        <?php echo $i ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->Code" . PHP_EOL; 
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->Name" . PHP_EOL; 
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->U_CuentaCosto" . PHP_EOL; 
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->U_UEN" . PHP_EOL; 
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->U_Linea" . PHP_EOL; 
                                    ?>
                                    </td>
                                    <td>
                                    <?php
                                        echo "$item->U_SubLinea" . PHP_EOL; 
                                    ?>
                                    </td>

                    
                                </tr>
                                <?php
                                $i = $i + 1;
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>

</html>