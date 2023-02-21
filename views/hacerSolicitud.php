<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hacer solicitud</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/hfstyle.css">
</head>
<body>
    <?php
        session_start();

        if (!isset($_SESSION["usuario"])) {

             header("location:../index.php");
        }

        include("../php/conexion.php");
        $departamento=$base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
        
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
                            <label for="Solicitante">Solicitante:</label>
                            <select name="Solicitante" id="sel__solicitante">
                                <option value="Usuario">Usuario</option>
                                <option value="Empleado">Empleado</option>
                            </select>
                            <input type="text" id="Solicitante" name="RolSolicitante" placeholder="Rol Solicitante"><br>
                            <label for="NombreSolicitante">Nombre Solicitante:</label>
                            <input type="text" id="NombreSolicitante" name="NombreSolicitante" placeholder="Nombre Solicitante"><br>
                            <label for="Sucursal">Sucursal:</label>
                            <select name="Sucursal" id="sel__sucursal">
                                <option value="Principal">Principal</option>
                                <option value="DefinirNuervo">Definir nuevo</option>
                            </select><br>
                            <label for="Departamento">Departamento:</label>
                            <select name="Departamento" id="sel__departamento">
                            <?php
                            foreach($departamento as $departamentos):?>  
                                <option value="<?php echo $departamentos->NOMBRE_DEPARTAMENTO?>"><?php echo $departamentos->NOMBRE_DEPARTAMENTO?></option>
                            <?php
                            endforeach;
                            ?>   
                            </select><br>
                            <input type="checkbox" id="EnviarCorreo" value="EnviarCorreo" placeholder="Enviar correo electronico si se agrego pedido">
                            <label id="enviarCorreo" for="EnviarCorreo">Enviar Correo Electronico si se agrego pedido</label><br>
                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" id="CorreoElectronico" name="CorreoElectronico" placeholder="correo@correo.com"><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas">
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text" id="Nsoliciud" name="Nsolicitud" placeholder="Nsolicitud"><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" id="Estado" name="Estado" placeholder="Estado"><br>
                            <label for="FechaContabilizacion">Fecha conabilizacion:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion" placeholder="Fecha Contabilizasion"><br>
                            <label for="ValidoHasta">Valido hasta:</label>
                            <input type="text" id="ValidoHasta" name="ValidoHasta" placeholder="Valido hasta"><br>
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion" placeholder="Fecha Contabilizasion"><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" id="FechaContabilizacion" name="FechaContabilizacion" placeholder="Fecha Contabilizasion"><br>
                            <button class="btn_doc">Documento de referencia</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div__tablaServicios">
                            <form id="menu">
                                <button class="btn_sel" name="xtabla"  value="tservicios">servicios</button>
                                <button class="btn_sel" name="xtabla"  value="tarticulos">articulos</button>
                            </form>
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                    <?php
                                        $xtabla="tservicios";
                                        if(isset($_GET["xtabla"])){
                                            $xtabla=$_GET["xtabla"]; 
                                        }
                                            if($xtabla=="tservicios"){ ?>
                                                <!-- tabla servicios  -->
                                                <table id="tabla__servicios">
                                                    <thead>
                                                        <th>Descripcion servicio</th>
                                                        <th>Fecha Necesaria</td>
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

                                                        $i=1;
                                                        while ($i<=20){
                                                        ?>
                                                        <tr>
                                                            <td><input class="inputTabla" type="search" name="" value=""><img class="lupa" src="../images/lupa.png" alt="lupa""></td>
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
                                                        </tr>
                                                        <?php 
                                                        $i=$i+1;
                                                            }

                                                        ?>
                                                </table>
                                                <?php
                                            }
                                            else{ ?> 
                                            <!-- tabla articulos -->
                                                <table id="tabla__articulos">
                                                    <thead>
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

                                                        $i=1;
                                                        while ($i<=20){
                                                        ?>
                                                        <tr>
                                                            <td><input class="inputTabla" type="search" name="" value=""><img class="lupa" src="../images/lupa.png" alt="lupa""></td>
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
                                                        $i=$i+1;
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
                            <input type="text" id="TotalAntesDescuento" name="TotalAntesDescuento" placeholder="Total"><br>
                            <label for="GastosAdicionales">Gastos adicionales:</label>
                            <input type="text" id="GastosAdicionales" name="GastosAdicionales" placeholder="Gastos Adicionales"><br>
                            <label for="Impuesto">Impuesto:</label>
                            <input type="text" id="Impuesto" name="Impuesto" placeholder="Impuesto"><br>
                            <label for="TotalPagoVencido">Total pago vencido:</label>
                            <input type="text" id="TotalPagoVencido" name="TotalPagoVencido" placeholder="Total pago vencido"><br>
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

