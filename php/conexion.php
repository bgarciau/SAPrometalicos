<?php
        try {
            
            $base=new PDO("mysql:host=localhost;dbname=saprometalicos","root",""); //representa una conexion a la base de datos
            $base->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION); //Establece un atributo en el manejador de la base de datos con el reporte de errores y excepciones
            $base->exec("SET CHARACTER SET UTF8");  //codifica los caracteres
            
        } catch (Exception $e) {
            die("Error: " . $e->getMessage()); //mensaje poor si se encuentra un error
        }
            
    ?>
