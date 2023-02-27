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

    include("../php/conexion.php");
    // $registros = $base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <h2>MIS SOLICITUDES</h2>
            <div id="div__tablaSolicitudes">
                <div id="div__volver">
                    <a href="hacerSolicitud.php"><input class="btn_vol" type="button" value="< VOLVER"></a>
                </div>
                <div class="outer_wrapperS">
                    <form id="menu">
                        <button class="btn_sel" name="xtabla" value="tservicios">servicios</button>
                        <button class="btn_sel" name="xtabla" value="tarticulos">articulos</button>
                    </form>
                    <?php
                    $xtabla = "tservicios";
                    if (isset($_GET["xtabla"])) {
                        $xtabla = $_GET["xtabla"];
                    }
                    if ($xtabla == "tservicios") { ?>
                        <!-- tabla servicios -->
                        <div class="table_wrapperS">
                            <input class="inputBuscar" type="search" name="" value="BUSCAR">
                            <table id="tabla__solicitudes">
                                <thead>
                                    <th>N° Sol</th>
                                    <th>Estado</th>
                                    <th>Nombre solicitante</th>
                                    <!-- <th>Sucursal</th> -->
                                    <th>Departamento</th>
                                    <th>Corre electronico</th>
                                    <th>Cantidad de sevicios</th>
                                    <th>propietario</th>
                                    <th>Comentarios</th>
                                    <th>OPCIONES</th>
                                </thead>
                                <?php
                                    $usuario = $_SESSION['usuario'];
                                    $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'servicio' AND fk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($misolicitud as $misolicitudes):
                                ?>
                                    <tr>
                                        
                                        <td><?php echo $misolicitudes->pk_num_sol?></td>
                                        <td><?php echo $misolicitudes->estado_sol?></td>
                                        <td><?php echo $misolicitudes->nom_solicitante?></td>
                                        <?php
                                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); 
                                            foreach ($depSol as $depSols): 
                                        ?>
                                        <td><?php echo $depSols->nom_dep?></td>
                                        <?php
                                        endforeach;
                                        ?>
                                        <td><?php echo $misolicitudes->correo_sol?></td>
                                        <td><?php echo $misolicitudes->cantidad?></td>
                                        <td><?php echo $misolicitudes->propietario?></td>
                                        <td><?php echo $misolicitudes->comentarios?></td>
                                        <td class="opcionesTabla">
                                            <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"><input class="btn_info" type="button" value="info"></a>
                                            <?php
                                            $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); 
                                            foreach ($usutipo as $usutipoo): 
                                                if($usutipoo->fk_tipo_usr==3){?>
                                                <a><input class="btn_aceptar" type="button" value="enviar"></a>
                                                <a><input class="btn_delete" type="button" value="rechazar"></a>
                                       
                                        <?php
                                         
                                            }
                                        endforeach;
                                        ?>
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
                                <input class="inputBuscar" type="search" name="" value="BUSCAR">
                                <table id="tabla__solicitudes">
                                    <thead>
                                        <th>N° Sol</th>
                                        <th>Estado</th>
                                        <th>Nombre solicitante</th>
                                        <!-- <th>Sucursal</th> -->
                                        <th>Departamento</th>
                                        <th>Corre electronico</th>
                                        <th>Cantidad de articulos</th>
                                        <th>propietario</th>
                                        <th>Comentarios</th>
                                        <th>OPCIONES</th>
                                    </thead>
                                    <?php
                                    $usuario = $_SESSION['usuario'];
                                    $misolicitud = $base->query("SELECT * FROM solicitud_compra WHERE tipo= 'articulo' AND fk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($misolicitud as $misolicitudes):
                                ?>
                                    <tr>
                                        
                                        <td><?php echo $misolicitudes->pk_num_sol?></td>
                                        <td><?php echo $misolicitudes->estado_sol?></td>
                                        <td><?php echo $misolicitudes->nom_solicitante?></td>
                                        <?php
                                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$misolicitudes->depart_sol'")->fetchAll(PDO::FETCH_OBJ); 
                                            foreach ($depSol as $depSols): 
                                        ?>
                                        <td><?php echo $depSols->nom_dep?></td>
                                        <?php
                                        endforeach;
                                        ?>
                                        <td><?php echo $misolicitudes->correo_sol?></td>
                                        <td><?php echo $misolicitudes->cantidad?></td>
                                        <td><?php echo $misolicitudes->propietario?></td>
                                        <td><?php echo $misolicitudes->comentarios?></td>
                                        <td class="opcionesTabla">
                                        <a href="infoS.php?numSol=<?php echo $misolicitudes->pk_num_sol ?>"><input class="btn_info" type="button" value="info"></a>
                                        <?php
                                            $usutipo = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); 
                                            foreach ($usutipo as $usutipoo): 
                                                if($usutipoo->fk_tipo_usr==3){?>
                                                <a><input class="btn_aceptar" type="button" value="enviar"></a>
                                                <a><input class="btn_delete" type="button" value="rechazar"></a>
                                       
                                        <?php
                                         
                                            }
                                        endforeach;
                                    endforeach;
                                }
                                        ?>
                                             </td>
                                    </tr>
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