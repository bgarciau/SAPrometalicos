<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- llama el estilo para el contenido entre el header y el footer -->
</head>

<body>
    <?php
    session_start(); //inicia la sesion para comprobar si el usuario ya inicio sesion en el login

    if (!isset($_SESSION["usuario"])) { // comprueba si esta logeado y si no lo esta lo manda a inciar sesion

        header("location:../"); 
    }

    include("../php/conexion.php"); //se incluye la conexion a la base de datos

    $usuario = $_SESSION['usuario']; //se toma el nombre del usuario para saber que tipo de usuario es y cuales son sus permisos

    $registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //toma el tipo de usuario de la base de datos
    foreach ($registros as $Tusuario) {
        $userx = $Tusuario->fk_tipo_usr;
    }
    if ($userx != 3) {   //Con esta condicion se comprueba si el usuario es administrdor y si no lo es lo manda a hacer solicitud
        header("location:hacerSolicitud.php");
    }

    $usuar = $base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);    //se llaman todos los usuarios de la base de datos

    ?>
    <div class="base">   <!-- Vista de  la pagina -->
        <header>
            <?php
            require_once('../php/header.php'); // llama el header
            ?>
        </header>
        <div class="contenedor"> <!-- Contenedido entre el header y el footer -->
            <h2>USUARIOS</h2>
            <div id="div__tablaSolicitudes"> <!-- Este div contiene la tabla de usuarios que se maneja igual a la de solicitudes -->
                <div class="outer_wrapperS"> <!-- el outer y el table wrappers es para mantener la tabla en el div sin importar su tamaño, si la tabla es mas grande que el div se usa un scroll -->
                    <div class="table_wrapperS">
                        <div id="div__agregar">
                            <a class="agregarUsuario" href="agregarUsuario.php"><input class="btn_add" type="button"  
                                    value="+AGREGAR"></a> <!-- Boton para agregar usuario -->
                        </div>
                        <div id="div__volver">
                            <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>  <!-- boton para volver a hacer solicitud -->
                        </div>
                        <table border="4px" id="tabla__usuarios"> <!-- tabla de usuarios -->
                            <thead>
                                <th>#</th>
                                <th>Codigo de usuario</th>
                                <th>Nombre usuario</th>
                                <th>Rol usuario</th>
                                <th>Departamento</th>
                                <th>Sucursal</th>
                                <th>OPCIONES</th>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1; //numero de usuario
                            foreach ($usuar as $usuario): ?> <!-- se usan los datos del usuario para llenar la tabla -->
                                <tr>
                                    <td>
                                        <?php echo $i ?> <!-- numero de usuario en la tabla -->
                                    </td>
                                    <td>
                                        <?php echo $usuario->pk_cod_usr ?> <!-- Codigo del usuario -->
                                    </td>
                                    <td>
                                        <?php echo $usuario->nom_usr ?> <!-- Nombre del usuario -->
                                    </td>
                                    <td>
                                        <?php echo $usuario->rol_usr ?> <!-- rol del usuario -->
                                    </td>
                                    <td>
                                        <?php
                                        $depart = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usuario->fk_depart'")->fetchAll(PDO::FETCH_OBJ); foreach ($depart as $departt) {
                                            echo $departt->nom_dep;
                                        }
                                        ?> <!--  Departamento del usuario -->
                                    </td>
                                    <td>
                                        <?php echo $usuario->sucursal ?> <!-- Sucursal del usuario -->
                                    </td>
                                    <td class="opcionesTabla"> <!-- Clase para centrar los botones -->
                                        <a href="actualizarUsuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><input
                                                class="btn_update" type="button" value="update"></a> <!-- Boton para actualizar los datos del usuario, mandando su id -->
                                        <a
                                            href="../crud/eliminar_usuario.php?codigoUsuario=<?php echo $usuario->pk_cod_usr ?>"><input
                                                class="btn_delete" type="button" value="delete"></a> <!-- Boton para eliminar usuario segun la id seleccionada -->
                                    </td>
                                </tr>
                                <?php
                                $i = $i + 1; //se suma uno popr cada usuario
                            endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php'); //se lama el footer
            ?>
        </footer>
    </div>
</body>

</html>