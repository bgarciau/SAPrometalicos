<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>solicitud servicio</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");

    $numSol = $_GET["numSol"];
    ?>
    <div class="base">
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
        <div class="contenedor">
            <table border="1px" id="tabla__general">
                <tr>
                    <td colspan="6">
                        <div id="div__solicitante">
                            <?php
                            $soli = $base->query("SELECT * FROM solicitud_compra WHERE pk_num_sol='$numSol'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($soli as $solis) :
                                $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$solis->fk_cod_usr'")->fetchAll(PDO::FETCH_OBJ);
                                foreach ($user as $duser) :
                                    $tuser = $base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$duser->fk_tipo_usr'")->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($tuser as $tipo) :
                            ?>
                                        <input type="hidden" name="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                        <label for="Solicitante">Solicitante:</label>
                                        <select name="solicitante" id="sel__solicitante" disabled>
                                            <option value=""><?php echo $tipo->des_usr ?></option>
                                            <option value="Usuario">Usuario</option>
                                            <option value="Empleado">Empleado</option>
                                        </select>
                                        <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>" disabled><br>
                                        <label for="NombreSolicitante">Nombre Solicitante:</label>
                                        <input type="text" name="nomSol" value="<?php echo $solis->nom_solicitante ?>" disabled><br>
                                        <label for="Sucursal">Sucursal:</label>
                                        <select name="sucursal" id="datosFormu" disabled>
                                            <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?></option>
                                            <option value="Principal">Principal</option>
                                            <option value="DefinirNuervo">Definir nuevo</option>
                                        </select><br>
                                        <label for="Departamento">Departamento:</label>
                                        <select name="departamento" id="datosFormu" disabled>
                                            <?php
                                            $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$solis->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($depSol as $depSols) :
                                            ?>
                                                <option value="<?php echo $solis->nom_solicitante ?>"><?php echo $depSols->nom_dep ?></option>
                                            <?php
                                            endforeach
                                            ?>
                                        </select><br>
                                <?php
                                    endforeach;
                                endforeach;
                                ?>

                                <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                                <input type="text" name="correoElectronico" placeholder="<?php echo $solis->correo_sol ?>" disabled><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas">

                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text" name="numSol" value="<?php echo $numSol ?>" disabled><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" name="estado" value="<?php echo $solis->estado_sol ?>" disabled><br>

                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" name="fechaDocumento" placeholder="Fecha documento" disabled><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" name="fechaNecesaria" placeholder="Fecha necesaria" disabled><br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div__tablaServicios">
                            <!-- <a href=""><input class="btn_sel" type="button" value="servicios"></a>
                                <a href="hacerSolicitudArt.php"><input class="btn_sel" type="button" value="articulos"></a> -->
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                    <?php
                                    if ($solis->tipo == "servicio") {
                                    ?>
                                        <!-- tabla servicios  -->
                                        <h4>SERVICIOS</h4>
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
                                            $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solis->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ);
                                            $i = 1;
                                            foreach ($lista as $listaa) :
                                            ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><input class="inputTablaSer" value="<?php echo $listaa->fk_cod_arse ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->fecha_nec ?>" disabled></td>
                                                    <td><input class="inputTablaSer" value="<?php echo $listaa->fk_prov ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->precio_info ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->cuenta_mayor ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->uen ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->linea ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->sublinea ?>" disabled></td>
                                                    <td><input class="inputTablaSer" value="<?php echo $listaa->proyecto ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->por_desc ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->ind_imp ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->total_ml ?>" disabled></td>
                                                </tr>
                                            <?php
                                                $i++;
                                            endforeach;
                                        } else {
                                            ?>
                                            <h4>ARTICULOS</h4>
                                            <!-- tabla articulos  -->
                                            <table id="tabla__servicios">
                                                <thead>
                                                    <th>#</th>
                                                    <th>codigo</th>
                                                    <th>Descripcion</th>
                                                    <th>Proveedor</th>
                                                    <th>Fecha Necesaria</th>
                                                    <th>Canidad Necesaria</th>
                                                    <th>Precio Info</th>
                                                    <th>% Descuento</th>
                                                    <th>indicador de impuestos</th>
                                                    <th>total ml</th>
                                                    <th>UEN</th>
                                                    <th>lineas</th>
                                                    <th>sublineas</th>
                                                </thead>
                                                <?php
                                                $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solis->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ);
                                                $i = 1;
                                                foreach ($lista as $listaa) :
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i ?></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->fk_cod_arse ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->nom_arse ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->fk_prov ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->fecha_nec ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->cant_nec ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->precio_info ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->por_desc ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->ind_imp ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->total_ml ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->uen ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->linea ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->sublinea ?>" disabled></td>
                                                    </tr>
                                            <?php
                                                    $i++;
                                                endforeach;
                                            }
                                            ?>
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
                            <input type="text" name="propietario" placeholder="Propietario" disabled><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea name="comentarios" rows="4" cols="50" placeholder="comentarios" disabled></textarea>
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
                            <a href="misSolicitudes.php"><input class="btn_vol" type="button" value="VOLVER"></a>
                            <!-- <button class="btn_env">ENVIAR SOLICITUD</button> -->

                        </div>
                    </td>

                </tr>
            </table>
        <?php
                            endforeach;
        ?>
        </div>
        <footer>
        <?php
        require_once('../php/footer.php');
        ?>
    </footer>
    </div>
</body>

</html>