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
        $registros=$base->query("SELECT * FROM usuario")->fetchAll(PDO::FETCH_OBJ);
        
    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <table border="4px" id="tabla__general">
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
                                <option value="Administracion">Administracion</option>
                                <option value="Comercial">Comercial</option>
                                <option value="DefinirNuervo">Definir nuevo</option>
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
                            <button class="btn_sel">servicios</button>
                            <button class="btn_sel  ">articulos</button>
                            <table border="3px" id="tabla__servicios">
                                <tr>
                                    <td>Descripcion</td>
                                    <td>Fecha Necesaria</td>
                                    <td>Proveedor</td>
                                    <td>Precio Info</td>
                                    <td>Cuenta de Mayor</td>
                                    <td>UEN</td>
                                    <td>lineas</td>
                                    <td>sublineas</td>
                                    <td>Nombre Cuenta mayor</td>
                                    <td>proyecto</td>
                                    <td>% Descuento</td>
                                    <td>indicador de impuestos</td>
                                </tr>
                                <?php

                                    $i=1;
                                    while ($i<=9){
                                    ?>
                                    <tr>
                                        <td><img class="lupa" src="../images/lupa.png" alt="lupa""></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php 
                                    $i=$i+1;
                                        }

                                    ?>
                            </table>
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

