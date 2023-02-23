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

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");
    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <table border="1px" id="tabla__general">
                <tr>
                    <td colspan="6">
                        <div id="div__solicitante">
                            <?php
                            include("../php/conexion.php");

                            $usuario = $_SESSION['usuario'];

                            $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($user as $duser):
                                $tuser = $base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$duser->fk_tipo_usr'")->fetchAll(PDO::FETCH_OBJ); foreach ($tuser as $tipo): ?>
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="Solicitante" id="sel__solicitante">
                                        <option value="<?php echo $tipo->des_usr ?>"><?php echo $tipo->des_usr ?></option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="RolSolicitante"
                                        placeholder="rol: <?php echo $duser->rol_usr ?>"><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" id="NombreSolicitante" name="NombreSolicitante"
                                        placeholder="<?php echo $duser->nom_usr ?>"><br>
                                    <label for="Sucursal">Sucursal:</label>
                                    <select name="Sucursal" id="sel__sucursal">
                                        <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?></option>
                                        <option value="Principal">Principal</option>
                                        <option value="DefinirNuervo">Definir nuevo</option>
                                    </select><br>
                                    <label for="Departamento">Departamento:</label>
                                    <select name="Departamento" id="sel__departamento">
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
                            <input type="checkbox" id="EnviarCorreo" value="EnviarCorreo"
                                placeholder="Enviar correo electronico si se agrego pedido">
                            <label id="enviarCorreo" for="EnviarCorreo">Enviar Correo Electronico si se agrego
                                pedido</label><br>
                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" id="CorreoElectronico" name="CorreoElectronico"
                                placeholder="correo@correo.com"><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas">
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text" id="Nsoliciud" name="Nsolicitud" placeholder="Nsolicitud" disabled><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" id="Estado" name="Estado" placeholder="ABIERTO" disabled><br>
                            <label for="FechaContabilizacion">Fecha contabilizacion:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion"
                                placeholder="Fecha Contabilizasion"><br>
                            <label for="ValidoHasta">Valido hasta:</label>
                            <input type="text" id="ValidoHasta" name="ValidoHasta" placeholder="Valido hasta"><br>
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion"
                                placeholder="Fecha Contabilizasion"><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion"
                                placeholder="Fecha Contabilizasion"><br>
                            <button class="btn_doc">Documento de referencia</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div__tablaServicios">
                            <form id="menu">
                                <button class="btn_sel" name="xtabla" value="tservicios">servicios</button>
                                <button class="btn_sel" name="xtabla" value="tarticulos">articulos</button>
                            </form>
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                    <?php
                                    $xtabla = "tservicios";
                                    if (isset($_GET["xtabla"])) {
                                        $xtabla = $_GET["xtabla"];
                                    }
                                    if ($xtabla == "tservicios") { ?>
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
                                                <th>Nombre Cuenta mayor</th>
                                                <th>proyecto</th>
                                                <th>% Descuento</th>
                                                <th>indicador de impuestos</th>
                                                <th>total ml</th>
                                            </thead>
                                            <?php

                                            $i = 1;
                                            while ($i <= 20) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><select name="cod_arse<?php echo $i ?>" id="arse">
                                                        <option value=""></option>
                                                        <option value="" disabled>cod |  descripcion servicio</option>
                                                        <?php
                                                            $servicios = $base->query("SELECT * FROM arse WHERE tipo_arse='servicio'")->fetchAll(PDO::FETCH_OBJ); foreach ($servicios as $servicioss): ?>
                                                            <option value="<?php echo $servicioss->pk_cod_arse?>"><?php echo $servicioss->pk_cod_arse." | ".$servicioss->des_arse?></option>
                                                            <?php
                                                            endforeach;
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td><input class="inputTabla" type="date" name="fecha_nec<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="proveedor<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="precio_inf<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="cuentaMayor<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="uen<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="lineas<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="sublineas<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="nom_cnt_may<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="proyecto<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="por_dec<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="ind_imp<?php echo $i ?>" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="total_ml<?php echo $i ?>" value=""></td>
                                                </tr>
                                                <?php
                                                $i = $i + 1;
                                            }

                                            ?>
                                        </table>
                                        <?php
                                    } else { ?>
                                        <!-- tabla articulos -->
                                        <table id="tabla__articulos">
                                            <thead>
                                                <th>#</th>
                                                <th>Numero de articulo</th>
                                                <th>Descripcion articulo</th>
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
                                                <th>Grupo Products</th>
                                                <th>Unidad de medida</th>
                                                <th>Pais</th>
                                                <th>Concepto entrada</th>
                                                <th>Concepto salida</th>
                                                <th>Crea diferido</th>
                                                <th>digito verificacion mandante</th>
                                                <th>tipo documento mandante</th>
                                                <th>tipo ingreso mandato</th>
                                                <th>dato maestro dife</th>
                                                <th>doc entry activo fijo</th>
                                                <th>identificador contrato de mandato</th>
                                                <th>fecha contrato</th>
                                                <th>nit mandante 1</th>
                                                <th>nit mandante 2</th>
                                                <th>matricula mercantil mandante 1</th>
                                                <th>matricula mercantil mandante 2</th>
                                                <th>primer nombre representante legal</th>
                                                <th>segundo nombre representante legal</th>
                                                <th>apellidos representante legal</th>
                                                <th>razon descuento</th>
                                                <th>imprimir</th>
                                                <th>descripcion</th>
                                                <th>valor</th>
                                                <th>tipo precio referencia</th>
                                                <th>base instalada</th>
                                                <th>vigencia desde</th>
                                                <th>vigencia hasta</th>
                                                <th>numero cuentas diferido</th>
                                                <th>codigo articulo</th>
                                                <th>cantidad</th>
                                                <th>unidad medida</th>
                                                <th>empleado</th>
                                                <th>fecha modificacion</th>
                                                <th>unidades producidas</th>
                                                <th>fecha entrega</th>
                                                <th>socio negocio</th>
                                            </thead>
                                            <?php

                                            $i = 1;
                                            while ($i <= 20) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><input class="inputTabla" type="search" name="cod_arse" value=""><img id="lupa"
                                                            src="../images/lupa.png" alt="lupa">
                                                    </td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="date" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                    <td><input class="inputTabla" type="search" name="" value=""></td>
                                                </tr>
                                                <?php
                                                $i = $i + 1;
                                            }

                                            ?>
                                        </table>
                                        <?php
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div id="div__comentarios">
                            <label for="Propietario">Propietario:</label>
                            <input type="text" id="Propietario" name="Propietario" placeholder="Propietario"><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea id="Comentarios" name="Comentarios" rows="4" cols="50">comentarios</textarea>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__enviar">
                            <label for="TotalAntesDescuento">Total antes del descuento:</label>
                            <input type="text" id="TotalAntesDescuento" name="TotalAntesDescuento"
                                placeholder="Total"><br>
                            <label for="GastosAdicionales">Gastos adicionales:</label>
                            <input type="text" id="GastosAdicionales" name="GastosAdicionales"
                                placeholder="Gastos Adicionales"><br>
                            <label for="Impuesto">Impuesto:</label>
                            <input type="text" id="Impuesto" name="Impuesto" placeholder="Impuesto"><br>
                            <label for="TotalPagoVencido">Total pago vencido:</label>
                            <input type="text" id="TotalPagoVencido" name="TotalPagoVencido"
                                placeholder="Total pago vencido"><br>
                            <button class="btn_env">GUARDAR SOLICITUD</button>
                            <button class="btn_env">ENVIAR SOLICITUD</button>

                        </div>
                    </td>

                </tr>
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