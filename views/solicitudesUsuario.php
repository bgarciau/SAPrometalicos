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

    if (!isset($_SESSION["usuario"])) { // comprueba si esta logeado y si no lo esta lo manda a inciar sesion

        header("location:../index.php");  
    }

    include("../php/conexion.php"); //se incluye la conexion a la base de datos

    ?>
    <div class="base"> <!-- Vista de  la pagina -->
    <header>
        <?php
        require_once('../php/header.php'); //llama el header
        ?>
    </header>
        <div class="contenedor"> <!-- Contenedido entre el header y el footer -->
            <h2>SOLICITUDES USUARIO</h2>
            <div id="div__tablaSolicitudes"> <!-- Contiene la tabla de solicitudes -->
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a> <!-- boton para volver a hacer solicitud -->
                </div>
                <div class="outer_wrapperS">
                    <form id="menu"> <!-- Con este form se actulizan los datos segun el boton -->
                        <button class="btn_sel" name="xtabla" value="tservicios">servicios</button> <!-- Boton para cargar los servicios -->
                        <button class="btn_sel" name="xtabla" value="tarticulos">articulos</button> <!-- Boton para cargar los articulos -->
                    </form>
                    <?php
                    $xtabla = "tservicios"; //Con esta variable se cargan los articulos o los servicios segun su valor
                    if (isset($_GET["xtabla"])) { //Aca toma el valor de la variable si se ha preisonado un boton 
                        $xtabla = $_GET["xtabla"];
                    }
                    if ($xtabla == "tservicios") { ?> <!-- Pregunta si es la tabla de servicios para cargarla -->
                        <!-- tabla servicios -->
                        <div class="table_wrapperS">
                            <h4>Servicios</h4>
                            <table id="tabla__solicitudes">
                                <thead>
                                    <th>N° Sol</th>
                                    <th>Estado</th>
                                    <th>Nombre solicitante</th>
                                    <th>Departamento</th>
                                    <th>Correo electronico</th>
                                    <th>Cantidad de sevicios</th>
                                    <th>propietario</th>
                                    <th>Comentarios</th>
                                    <th>OPCIONES</th>
                                </thead>
                                <?php
                                $usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para cargar las solicitudes que no son suyas
                                $usolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'servicio' and fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se llama de la tabla de solicitudes filtrando las de servicios y que no pertenecen al usuaio de la sesion para guardala en un PDOStatement
                                foreach ($usolicitud as $usolicitudes) : //se usa para recorrer el PDOStatement 
                                ?>
                                    <tr>

                                        <td><?php echo $usolicitudes->pk_num_sol ?></td> <!-- se llama el numero de solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td><?php echo $usolicitudes->estado_sol ?></td> <!-- se llama el estado de solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td><?php echo $usolicitudes->nom_solicitante ?></td> <!-- se llama el nombre del solicitante con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <?php
                                        $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($depSol as $depSols) :
                                        ?>
                                            <td><?php echo $depSols->nom_dep ?></td> <!-- se llama el departamento -->
                                        <?php
                                        endforeach;
                                        ?>
                                        <td><?php echo $usolicitudes->correo_sol ?></td> <!-- se llama el correo de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td><?php echo $usolicitudes->cantidad ?></td> <!-- se llama la cantidad de servicios que tiene la solicitud -->
                                        <td><?php echo $usolicitudes->propietario ?></td> <!-- se llama el propieteario de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td><?php echo $usolicitudes->comentarios ?></td> <!-- se llaman los comentarios de la solicitud con el nombre asociado a su valor, el cual es el mismo de la base de datos -->
                                        <td class="opcionesTabla">
                                            <a href="infoS.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>"><input class="btn_info" type="button" value="info"></a> <!-- se manda el id de la solicitud para cargar todos los datos de esta -->
                                            <a><input class="btn_aceptar" type="button" value="enviar"></a>
                                            <a><input class="btn_delete" type="button" value="rechazar"></a>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                                ?>
                            </table>
                        <?php
                    } else { ?>
                            <!-- tabla articulos -->
                            <div class="table_wrapperS">
                                <h4>Articulos</h4>
                                <table id="tabla__solicitudes">
                                    <thead>
                                        <th>N° Sol</th>
                                        <th>Estado</th>
                                        <th>Nombre solicitante</th>
                                        <th>Departamento</th>
                                        <th>Correo electronico</th>
                                        <th>Cantidad de articulos</th>
                                        <th>propietario</th>
                                        <th>Comentarios</th>
                                        <th>OPCIONES</th>
                                    </thead>
                                    <?php
                                    $usuario = $_SESSION['usuario'];
                                    $usolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'articulo' and fk_cod_usr!= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se llama de la tabla de solicitudes filtrando las de articulos y que no pertenecen al usuaio de la sesion para guardala en un PDOStatement
                                    foreach ($usolicitud as $usolicitudes) :
                                    ?>
                                        <tr>

                                            <td><?php echo $usolicitudes->pk_num_sol ?></td>
                                            <td><?php echo $usolicitudes->estado_sol ?></td>
                                            <td><?php echo $usolicitudes->nom_solicitante ?></td>
                                            <?php
                                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$usolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($depSol as $depSols) :
                                            ?>
                                                <td><?php echo $depSols->nom_dep ?></td>
                                            <?php
                                            endforeach;
                                            ?>
                                            <td><?php echo $usolicitudes->correo_sol ?></td>
                                            <td><?php echo $usolicitudes->cantidad ?></td>
                                            <td><?php echo $usolicitudes->propietario ?></td>
                                            <td><?php echo $usolicitudes->comentarios ?></td>
                                            <td class="opcionesTabla">
                                                <a href="infoS.php?numSol=<?php echo $usolicitudes->pk_num_sol ?>"><input class="btn_info" type="button" value="info"></a>
                                                <a><input class="btn_aceptar" type="button" value="enviar"></a>
                                                <a><input class="btn_delete" type="button" value="rechazar"></a>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </table>
                            <?php
                        } ?>
                            </div>
                        </div>
                </div>
            </div>
            <footer>
            <?php
            require_once('../php/footer.php');
            ?>
        </footer>
        </div>
</body>

</html>