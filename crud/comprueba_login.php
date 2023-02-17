   <?php
        try {
            include("../php/conexion.php");
            $sql="SELECT * FROM usuario WHERE PK_CODIGO_USUARIO= :usuario AND PASSWORD= :password";
            $resultado=$base->prepare($sql);
            $usuario=htmlentities(addslashes($_POST["usuario"]));
            $password=htmlentities(addslashes($_POST["password"]));
            $resultado->bindValue(":usuario",$usuario);
            $resultado->bindValue(":password",$password);
            $resultado->execute();
            $numero_registro=$resultado->rowCount();

            if ($numero_registro!=0) {
                
                session_start();

                $_SESSION["usuario"]=$_POST["usuario"];

                header("location:../views/hacerSolicitud.php");

            }else {
                header("location:../index.php");
            }

        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    ?>
