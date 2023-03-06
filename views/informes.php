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
    ));
    $response = curl_exec($curl);
    //echo $response;

    //curl_close($curl);
    $json = json_decode($response, true);
    //echo  $json['SessionId'];;
    //exit;
    $sesion = $json['SessionId'];
    $_SESSION['sesion'] = $sesion;
    //echo "inicio "; echo  $sesion;
  }
  else{
    $sesion = $_SESSION['sesion'];
    //echo "else "; echo  $sesion;
  }
    include("../php/conexion.php");
    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            informes
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>

</html>