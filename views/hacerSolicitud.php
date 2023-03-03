<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hacer solicitud</title>
    <link rel="stylesheet" href="../css/style.css">
    </head>
<body>
    <?php
    
    session_start();

    if (!isset($_SESSION['usuario'])) {

        header("location:../index.php");

    }

    //session_destroy();

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

    if(isset($_POST["guardarS"])){
        $tipo="servicio";
        $cantidad=0;
        $j=0;
        while($j<20){
            $cod_arse=$_POST["cod_arse$j"];
            if($cod_arse==-1){

            }
            else{ 
                $codSol=$_POST["numSol"];
                $codArse[$j]=$_POST["cod_arse$j"];
                $code[$j]=  $respuestaServicios->value[$codArse[$j]]->Code;
                $fechaNec[$j]=$_POST["fecha_Nec$j"];
                $proveedor[$j]=$_POST["proveedor$j"];
                $precioInfo[$j]=$_POST["precio_inf$j"];
                $cuentaMayor[$j]=$_POST["cuentaMayor$j"];
                $uen[$j]=$_POST["uen$j"];
                $linea[$j]=$_POST["lineas$j"];
                $sublinea[$j]=$_POST["sublineas$j"];
                $proyecto[$j]=$_POST["proyecto$j"];
                $porDesc[$j]=$_POST["por_dec$j"];
                $indImp[$j]=$_POST["ind_imp$j"];
                $total[$j]=$_POST["total_ml$j"];
                $cantidad++;

                if($proyecto[$j]=="" || $indImp[$j]==""){
                    echo '<script>alert("Error al enviar su solicitud, verifique los campos obligatorios\nLos unicos que no son obligatorios con el proveedor y el precio info");</script>';
                    $cantidad=-50;
                    while($j<20){
                        if($cod_arse==-1){

                        }else{
                        $codArse[$j]=$_POST["cod_arse$j"];
                        $fechaNec[$j]=$_POST["fecha_Nec$j"];
                        $proveedor[$j]=$_POST["proveedor$j"];
                        $precioInfo[$j]=$_POST["precio_inf$j"];
                        $cuentaMayor[$j]=$_POST["cuentaMayor$j"];
                        $uen[$j]=$_POST["uen$j"];
                        $linea[$j]=$_POST["lineas$j"];
                        $sublinea[$j]=$_POST["sublineas$j"];
                        $proyecto[$j]=$_POST["proyecto$j"];
                        $porDesc[$j]=$_POST["por_dec$j"];
                        $indImp[$j]=$_POST["ind_imp$j"];
                        $total[$j]=$_POST["total_ml$j"];
                        }
                        $j++;
                    }
                }else{
                        $sql="INSERT INTO list_arse (fk_num_sol,fk_cod_arse,fecha_nec,fk_prov,precio_info,cuenta_mayor,uen,linea,sublinea,proyecto,por_desc,ind_imp,total_ml) 
                        VALUES(:_numSol,:_codArse,:_fechaNec,:_proveedor,:_precioInfo,:_cuentaMayor,:_uen,:_linea,:_sublinea,:_proyecto,:_porDesc,:_indImp,:_total)";

                        $serv=$base->prepare($sql);
            
                        $serv->execute(array(":_numSol"=>$codSol,":_codArse"=>$code[$j],":_fechaNec"=>$fechaNec[$j],":_proveedor"=>$proveedor[$j],":_precioInfo"=>$precioInfo[$j],":_cuentaMayor"=>$cuentaMayor[$j],":_uen"=>$uen[$j],":_linea"=>$linea[$j],":_sublinea"=>$sublinea[$j],":_proyecto"=>$proyecto[$j],":_porDesc"=>$porDesc[$j],":_indImp"=>$indImp[$j],":_total"=>$total[$j]));        
                }
            }
            $j++;
        }
            if($cantidad>0 ){
                $codSol=$_POST["numSol"];
                $estado=$_POST["estado"];
                $nomSol=$_POST["nomSol"];
                $correoElectronico=$_POST["correoElectronico"];
                $propietario=$_POST["propietario"];
                $comentarios=$_POST["comentarios"];
                $codUsr=$_POST["codUsr"];
                $departamento=$_POST["departamento"];
                $sucursal=$_POST["sucursal"];
                
                $sql="INSERT INTO solicitud_compra (pk_num_sol,estado_sol,nom_solicitante,sucursal,correo_sol,propietario,comentarios,fk_cod_usr,depart_sol,tipo,cantidad) 
                VALUES(:_codSol,:_estado,:_nomSol,:_sucursal,:_correoElectronico,:_propietario,:_comentarios,:_codUsr,:_departamento,:_tipo,:_cantidad)";

                $solicitud=$base->prepare($sql);
                
                $solicitud->execute(array(":_codSol"=>$codSol,":_estado"=>$estado,":_nomSol"=>$nomSol,":_sucursal"=>$sucursal,":_correoElectronico"=>$correoElectronico,":_propietario"=>$propietario,":_comentarios"=>$comentarios,":_codUsr"=>$codUsr,":_departamento"=>$departamento,":_tipo"=>$tipo,":_cantidad"=>$cantidad));
                header("location:misSolicitudes.php");
            }
            
            
    }

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <table border="1px" id="tabla__general">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <tr>
                    <td colspan="6">
                        <div id="div__solicitante">
                            <?php
                            include("../php/conexion.php");

                            $usuario = $_SESSION['usuario'];

                            $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($user as $duser):
                                $tuser = $base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$duser->fk_tipo_usr'")->fetchAll(PDO::FETCH_OBJ); foreach ($tuser as $tipo): ?>
                                    <input type="hidden" name="codUsr"
                                        value="<?php echo $duser->pk_cod_usr?>">
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="solicitante" id="sel__solicitante">
                                        <option value="<?php echo $tipo->des_usr ?>"><?php echo $tipo->des_usr ?></option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="rolSol"
                                        value="<?php echo $duser->rol_usr ?>" required><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" name="nomSol"
                                        value="<?php echo $duser->nom_usr ?>" required><br>
                                    <label for="Sucursal">Sucursal:</label>
                                    <select name="sucursal">
                                        <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?></option>
                                        <option value="Principal">Principal</option>
                                        <option value="DefinirNuervo">Definir nuevo</option>
                                    </select><br>
                                    <label for="Departamento">Departamento:</label>
                                    <select name="departamento" id="sel__departamento">
                                        <?php
                                        $dep = $base->query("SELECT * FROM departamento WHERE pk_dep= '<?php $duser->fk_depart ?>'")->fetchAll(PDO::FETCH_OBJ); foreach ($dep as $depa): ?>
                                            <option value="<?php echo $duser->fk_depart ?>"><?php echo $depa->nom_dep ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                        <?php
                                        $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ); foreach ($departamento as $departamentos): ?>
                                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select><br>
                                    <?php
                                endforeach;
                            endforeach;
                            ?>
                            <input type="checkbox" value="enviarCorreo" name="enviarCorreo">
                            <label id="enviarCorreo" for="EnviarCorreo">Enviar Correo Electronico si se agrego
                                pedido</label><br>
                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" name="correoElectronico"
                                placeholder="correo@correo.com"><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas">
                            <?php
                            $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                            $num = 1; 
                            foreach ($ultimo as $ultimoo):
                                $num++;
                            endforeach; ?>
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text"name="numSol" value="<?php echo $num ?>" readonly><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" name="estado" value="ABIERTO" readonly><br>
                            <!-- <label for="FechaContabilizacion">Fecha contabilizacion:</label>
                            <input type="text" name="fechaContabilizacion"
                                placeholder="Fecha Contabilizasion"><br>
                            <label for="ValidoHasta">Valido hasta:</label>
                            <input type="text" name="validoHasta" placeholder="Valido hasta"><br> -->
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" name="fechaDocumento"
                                value="<?php echo date("d-m-y")?>"><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" name="fechaNecesaria"
                                placeholder="Fecha necesaria"><br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div__tablaServicios">
                                <a href=""><input class="btn_sel" type="button" value="servicios"></a>
                                <a href="hacerSolicitudArt.php"><input class="btn_sel" type="button" value="articulos"></a>
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                        <!-- tabla servicios  -->
                                        <table id="tabla__servicios">
                                            <thead>
                                                <th>#</th>
                                                <th>Descripcion servicio</th>
                                                <th>Fecha Necesaria</th>
                                                <th>Proveedor</th>
                                                <th>Precio Info</th>
                                                <th>Cuenta de Mayor</th>
                                                <th>UEN</th>
                                                <th>lineas</th>
                                                <th>sublineas</th>
                                                <th>proyecto</th>
                                                <th>% Descuento</th>
                                                <th>indicador de impuestos</th>
                                                <th>total ml</th>
                                            </thead>
                                            <?php

                                            $i = 0;
                                            while ($i < 20) {
                                                ?>
                                                <tbody>
                                                    <td>
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td>  <select name="cod_arse<?php echo $i ?>" id="codigoServicio<?php echo $i ?>">
                                                                    <option value="<?php if(isset($codArse[$i])){ echo $codArse[$i];}else{echo (-1);}?>" selected><?php if(isset($codArse[$i]) and $codArse[$i]!=-1){print_r($respuestaServicios->value[$codArse[$i]]->Name);}else{echo "Seleccione un servicio";}?></option>
                                                                <?php
                                                                    $s=0;
                                                                    foreach ($respuestaServicios->value as $item):
                                                                        
                                                                ?>
                                                                    <option value="<?php echo $s ?>"><?php echo "$item->Name".PHP_EOL;?></option>
                                                                <?php
                                                                    $s++;
                                                                    endforeach;
                                                                ?>
                                                            </select>
                            
                                                    </td>
                                                    <td><input class="inputTabla" type="date" value="<?php if(isset($fechaNec[$i])){ echo $fechaNec[$i];}else{echo date("Y-m-d");}?>" name="fecha_Nec<?php echo $i ?>"
                                                            ></td>
                                                    <td><input id="proveedor<?php echo $i ?>" class="inputTabla"  type="search" name="proveedor<?php echo $i ?>"
                                                            placeholder=""></td>
                                                    <td><input class="inputTabla" type="number" value=0 id="precio_inf<?php echo $i ?>" name="precio_inf<?php echo $i ?>"></td>
                                                    <td><input class="inputTabla" type="search"
                                                            name="cuentaMayor<?php echo $i ?>" value="<?php if(isset($cuentaMayor[$i])){ echo $cuentaMayor[$i];}else{echo "";}?>" id="cuentaMayor<?php echo $i ?>" readonly></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($uen[$i])){ echo $uen[$i];}else{echo "";}?>" id="uen<?php echo $i ?>" name="uen<?php echo $i ?>"
                                                readonly></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($linea[$i])){ echo $linea[$i];}else{echo "";}?>" id="lineas<?php echo $i ?>" name="lineas<?php echo $i ?>"
                                                            readonly></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($sublinea[$i])){ echo $sublinea[$i];}else{echo "";}?>" id="sublineas<?php echo $i ?>" name="sublineas<?php echo $i ?>"
                                                            readonly></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($proyecto[$i])){ echo $proyecto[$i];}else{echo "";}?>" id="proyecto<?php echo $i ?>" name="proyecto<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="number" value=0 id="por_dec<?php echo $i ?>" name="por_dec<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($indImp[$i])){ echo $indImp[$i];}else{echo "";}?>" id="ind_imp<?php echo $i ?>" name="ind_imp<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search"  id="total_ml<?php echo $i ?>" name="total_ml<?php echo $i ?>" onclick="ftotal()"
                                                            ></td>
                                                </tbody>
                            
                                                <?php
                                                $i = $i + 1;
                                            }

                                            ?>
                                              <script 
                                                    src="https://code.jquery.com/jquery-3.6.0.min.js"
                                                    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"
                                                ></script>
                                                <script>
                                                    const datos = <?php echo json_encode($respuestaServicios);?>
                                                    // Justo aquí estamos pasando la variable ----^
                                                    // Y ya la tenemos desde JavaScript. Podemos hacer cualquier cosa con ella
                                                    console.log("Los datos son: ", datos);

                                                    const value = datos.value;

                                                    console.log("Los valores son: ", value);

                                                    console.log(value[0]['Name'])
                                                    const codigoser = document.querySelector("#codigoServicio1");

                                                    console.log('hola'+codigoser)
                                                    function ftotal(){
                                                        i=0;
                                                        while (i<20){
                                                        const desc = document.getElementById('por_dec'+i).value;
                                                        const precio = document.getElementById('precio_inf'+i).value;
                                                        document.getElementById('total_ml'+i).value=precio-(desc*precio/100);
                                                        i++;
                                                        } 
                                                    }
                                                        
                                                        $(document).ready(function () {
                                                            
                                                            for (i=0;i<20;i++){
                                                            $('#codigoServicio'+i).change(function (e) {
                                                            
                                                            for (i=0;i<20;i++){
                                                            if ($(this).val() == document.getElementById('codigoServicio'+i).value) {
                                                                $('#proyecto'+i).prop("required", true);
                                                                $('#ind_imp'+i).prop("required", true);
                                                                $('#uen'+i).val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                                $('#cuentaMayor'+i).val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                                $('#lineas'+i).val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                                $('#sublineas'+i).val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);
                                                        

                                                            }
                                                        } 
                                                            })
                                                        }
                                                            // $('#codigoServicio2').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto2').prop("required", true);
                                                            //     $('#ind_imp2').prop("required", true);
                                                            //     $('#uen2').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor2').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas2').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas2').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio3').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto3').prop("required", true);
                                                            //     $('#ind_imp3').prop("required", true);
                                                            //     $('#uen3').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor3').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas3').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas3').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio4').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto4').prop("required", true);
                                                            //     $('#ind_imp4').prop("required", true);
                                                            //     $('#uen4').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor4').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas4').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas4').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio5').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto5').prop("required", true);
                                                            //     $('#ind_imp5').prop("required", true);
                                                            //     $('#uen5').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor5').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas5').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas5').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio6').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto6').prop("required", true);
                                                            //     $('#ind_imp6').prop("required", true);
                                                            //     $('#uen6').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor6').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas6').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas6').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio7').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto7').prop("required", true);
                                                            //     $('#ind_imp7').prop("required", true);
                                                            //     $('#uen7').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor7').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas7').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas7').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio8').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto8').prop("required", true);
                                                            //     $('#ind_imp8').prop("required", true);
                                                            //     $('#uen8').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor8').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas8').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas8').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio9').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto9').prop("required", true);
                                                            //     $('#ind_imp9').prop("required", true);
                                                            //     $('#uen9').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor9').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas9').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas9').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio10').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto10').prop("required", true);
                                                            //     $('#ind_imp10').prop("required", true);
                                                            //     $('#uen10').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor10').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas10').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas10').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio11').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto11').prop("required", true);
                                                            //     $('#ind_imp11').prop("required", true);
                                                            //     $('#uen11').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor11').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas11').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas11').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio12').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto12').prop("required", true);
                                                            //     $('#ind_imp12').prop("required", true);
                                                            //     $('#uen12').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor12').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas12').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas12').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio13').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto13').prop("required", true);
                                                            //     $('#ind_imp13').prop("required", true);
                                                            //     $('#uen13').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor13').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas13').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas13').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio14').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto14').prop("required", true);
                                                            //     $('#ind_imp14').prop("required", true);
                                                            //     $('#uen14').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor14').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas14').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas14').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio15').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto15').prop("required", true);
                                                            //     $('#ind_imp15').prop("required", true);
                                                            //     $('#uen15').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor15').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas15').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas15').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio16').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto16').prop("required", true);
                                                            //     $('#ind_imp16').prop("required", true);
                                                            //     $('#uen16').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor16').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas16').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas16').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio17').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto17').prop("required", true);
                                                            //     $('#ind_imp17').prop("required", true);
                                                            //     $('#uen17').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor17').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas17').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas17').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio18').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto18').prop("required", true);
                                                            //     $('#ind_imp18').prop("required", true);
                                                            //     $('#uen18').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor18').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas18').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas18').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio19').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto19').prop("required", true);
                                                            //     $('#ind_imp19').prop("required", true);
                                                            //     $('#uen19').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor19').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas19').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas19').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                            // $('#codigoServicio0').change(function (e) {
                                                            // if ($(this).val() !== -1) {
                                                            //     $('#proyecto0').prop("required", true);
                                                            //     $('#ind_imp0').prop("required", true);
                                                            //     $('#uen0').val(value[$(this).val()]["U_UEN"]).prop("readonly", true);
                                                            //     $('#cuentaMayor0').val(value[$(this).val()]["U_CuentaCosto"]).prop("readonly", true);
                                                            //     $('#lineas0').val(value[$(this).val()]["U_Linea"]).prop("readonly", true);
                                                            //     $('#sublineas0').val(value[$(this).val()]["U_Sublinea"]).prop("readonly", true);

                                                            // } 
                                                            // })
                                                          
                                                        });
                                                </script>
                                        </table>
                                
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div id="div__comentarios">
                            <label for="Propietario">Propietario:</label>
                            <input type="text" name="propietario" placeholder="Propietario"><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea name="comentarios" rows="4" cols="50" placeholder="comentarios"></textarea>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__enviar">
                            <!-- <label for="TotalAntesDescuento">Total antes del descuento:</label>
                            <input type="text" name="TotalAntesDescuento"
                                placeholder="Total"><br>
                            <label for="GastosAdicionales">Gastos adicionales:</label>
                            <input type="text" name="GastosAdicionales"
                                placeholder="Gastos Adicionales"><br>
                            <label for="Impuesto">Impuesto:</label>
                            <input type="text" name="Impuesto" placeholder="Impuesto"><br>
                            <label for="TotalPagoVencido">Total pago vencido:</label>
                            <input type="text" name="TotalPagoVencido"
                                placeholder="Total pago vencido"><br> -->
                                <a><input class="btn_env" type="submit" value="GUARDAR SOLICITUD" name="guardarS" onclick="ftotal()"></a>
                            <!-- <button class="btn_env">ENVIAR SOLICITUD</button> -->

                        </div>
                    </td>

                </tr>
                </form>
            </table>
        </div>
    </div>
    <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
</body>

</html>