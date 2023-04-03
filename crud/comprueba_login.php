<?php
        try {

            include("../php/conexion.php");

            //toma los datos del formulario que se envia desde el index
            $usuario=htmlentities(addslashes($_POST["usuario"]));
            $password=htmlentities(addslashes($_POST["password"]));

            $contador=0; //Este contador se usa para saber si el usuario existe, cuando se encuentra el ususario se le suma uno

            $sql="SELECT * FROM usuario WHERE pk_cod_usr= :usuario"; //selecciona los datos del usuario donde su codigo de usuario es igual al enviado en el formulario
            $resultado=$base->prepare($sql); //preparia una sentencia para su ejecucion y devuelve un objeto de sentencia

            $resultado->execute(array(":usuario"=>$usuario));//ejecuta la secuencia preparada 

                while($registro=$resultado->fetch(PDO::FETCH_ASSOC)){ //recorre cada fila de los usuarios encontrados

                    if (password_verify($password, $registro['pass_usr'])) {//se comprueba si la contraseña es igual a la que se ingreso en el formulario, lo que hace el password_verify es desifrar la contraseña
                        $contador++;//si la clave es correcta se suma uno al contador
                    }
                }

            if($contador>0){//si el contador es mayor que cero es porque el usuario y la contraseña son correctos
                session_start();   //como es correcto el inicio se inicia la sesion para que el usuario pueda entrar a los modulos

                $_SESSION["usuario"]=$_POST["usuario"];     //se hace un post a la sesion del usuario para verificar que permisos tiene el usuario en cada modulo

                header("location:../views/hacerSolicitud.php"); //manda al usuario a hacer solicitud que es la pagina principal de la aplicacion
            }
            else{
                header("location:../index.php");//Si no hay datos correctos manda al usuario al inicio para que lo intente nuevamente
             }

            $resultado->closeCursor();//libera la conexion al servidor, es un metodo opcional que permite la maxima eficiencia

        }catch (Exception $e) { //Si ocurre algun error en este proceso se muestra un mensaje 
             die("Error: " . $e->getMessage());
        }
        
    ?>
