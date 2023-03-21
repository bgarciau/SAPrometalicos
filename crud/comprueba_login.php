<?php
        try {

            include("../php/conexion.php");

            $usuario=htmlentities(addslashes($_POST["usuario"]));
            $password=htmlentities(addslashes($_POST["password"]));

            $contador=0;

            $sql="SELECT * FROM usuario WHERE pk_cod_usr= :usuario";
            $resultado=$base->prepare($sql);

            $resultado->execute(array(":usuario"=>$usuario));

                while($registro=$resultado->fetch(PDO::FETCH_ASSOC)){

                    if (password_verify($password, $registro['pass_usr'])) {
                        $contador++;
                    }
                    echo $contador;
                }

            if($contador>0){
                session_start();

                $_SESSION["usuario"]=$_POST["usuario"];

                header("location:../views/hacerSolicitud.php");
            }
            else{
                header("location:../index.php");
             }

            $resultado->closeCursor();

        }catch (Exception $e) {
             die("Error: " . $e->getMessage());
        }
        
    ?>
