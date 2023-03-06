<?php
        try {
            
            $base=new PDO("mysql:host=localhost;dbname=saprometalicos","root","");
            $base->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
            $base->exec("SET CHARACTER SET UTF8");
            
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        if (!isset($_SESSION['sesion'])) {
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
                'Content-Type: application/json',
                'Cookie: B1SESSION=' . $sesion . ''
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            ));
    
            $responseProv = curl_exec($curlProv);
            
            if (curl_errno($curlProv)) {
                echo 'Error en la solicitud cURL prov: ' . curl_error($curl);
            }
    
        curl_close($curlProv);
    
        $respuestaProveedor = json_decode($responseProv);
        //print_r($respuestaProveedor);
        
        // LLAMADO DE SERVICIOS
        $curlServicios = curl_init();
    
        curl_setopt_array($curlServicios, array(
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

    ?>
