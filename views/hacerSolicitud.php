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

    if(isset($_POST["guardarS"])){
        $tipo="servicio";
        $cantidad=0;
        $j=1;
        while($j<=20){
            $cod_arse=$_POST["cod_arse$j"];
            if($cod_arse==0){

            }
            else{ 
                $codSol=$_POST["numSol"];
                $codArse[$j]=$_POST["cod_arse$j"];
                $fechaNec[$j]=$_POST["fecha_Nec$j"];
                $proveedor[$j]=$_POST["proveedor$j"];
                $precioInfo[$j]=$_POST["precio_inf$j"];
                $cuentaMayor[$j]=$_POST["cuentaMayor$j"];
                $uen[$j]=$_POST["uen$j"];
                $linea[$j]=$_POST["lineas$j"];
                $sublinea[$j]=$_POST["sublineas$j"];
                $proyecto[$j]=$_POST["proyecto$j"];
                $porDesc[$j]=$_POST["por_dec$j"];
                $indImp[$j]=$_POST["ind_imp$j"];
                $total[$j]=$_POST["total_ml$j"];
                $cantidad++;

                if($cuentaMayor[$j]=="" || $uen[$j]=="" || $linea[$j]=="" || $sublinea[$j]=="" || $proyecto[$j]=="" || $indImp[$j]==""){
                    echo '<script>alert("Error al enviar su solicitud, verifique los campos obligatorios\nLos unicos que no son obligatorios con el proveedor y el precio info");</script>';
                    $cantidad=-50;
                    while($j<=20){
                        if($cod_arse==0){

                        }else{
                        $codArse[$j]=$_POST["cod_arse$j"];
                        $fechaNec[$j]=$_POST["fecha_Nec$j"];
                        $proveedor[$j]=$_POST["proveedor$j"];
                        $precioInfo[$j]=$_POST["precio_inf$j"];
                        $cuentaMayor[$j]=$_POST["cuentaMayor$j"];
                        $uen[$j]=$_POST["uen$j"];
                        $linea[$j]=$_POST["lineas$j"];
                        $sublinea[$j]=$_POST["sublineas$j"];
                        $proyecto[$j]=$_POST["proyecto$j"];
                        $porDesc[$j]=$_POST["por_dec$j"];
                        $indImp[$j]=$_POST["ind_imp$j"];
                        $total[$j]=$_POST["total_ml$j"];
                        }
                        $j++;
                    }
                }else{
                        $sql="INSERT INTO list_arse (fk_num_sol,fk_cod_arse,fecha_nec,fk_prov,precio_info,cuenta_mayor,uen,linea,sublinea,proyecto,por_desc,ind_imp,total_ml) 
                        VALUES(:_numSol,:_codArse,:_fechaNec,:_proveedor,:_precioInfo,:_cuentaMayor,:_uen,:_linea,:_sublinea,:_proyecto,:_porDesc,:_indImp,:_total)";

                        $serv=$base->prepare($sql);
            
                        $serv->execute(array(":_numSol"=>$codSol,":_codArse"=>$codArse[$j],":_fechaNec"=>$fechaNec[$j],":_proveedor"=>$proveedor[$j],":_precioInfo"=>$precioInfo[$j],":_cuentaMayor"=>$cuentaMayor[$j],":_uen"=>$uen[$j],":_linea"=>$linea[$j],":_sublinea"=>$sublinea[$j],":_proyecto"=>$proyecto[$j],":_porDesc"=>$porDesc[$j],":_indImp"=>$indImp[$j],":_total"=>$total[$j]));        
                }
            }
            $j++;
        }
            if($cantidad>0 ){
                $codSol=$_POST["numSol"];
                $estado=$_POST["estado"];
                $nomSol=$_POST["nomSol"];
                $correoElectronico=$_POST["correoElectronico"];
                $propietario=$_POST["propietario"];
                $comentarios=$_POST["comentarios"];
                $codUsr=$_POST["codUsr"];
                $departamento=$_POST["departamento"];
                $sucursal=$_POST["sucursal"];
                
                $sql="INSERT INTO solicitud_compra (pk_num_sol,estado_sol,nom_solicitante,sucursal,correo_sol,propietario,comentarios,fk_cod_usr,depart_sol,tipo,cantidad) 
                VALUES(:_codSol,:_estado,:_nomSol,:_sucursal,:_correoElectronico,:_propietario,:_comentarios,:_codUsr,:_departamento,:_tipo,:_cantidad)";

                $solicitud=$base->prepare($sql);
                
                $solicitud->execute(array(":_codSol"=>$codSol,":_estado"=>$estado,":_nomSol"=>$nomSol,":_sucursal"=>$sucursal,":_correoElectronico"=>$correoElectronico,":_propietario"=>$propietario,":_comentarios"=>$comentarios,":_codUsr"=>$codUsr,":_departamento"=>$departamento,":_tipo"=>$tipo,":_cantidad"=>$cantidad));
                header("location:misSolicitudes.php");
            }
            
            
    }

    ?>
    <header>
        <?php
        require_once('../php/header.php');
        ?>
    </header>
    <div class="base">
        <div class="contenedor">
            <table border="1px" id="tabla__general">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <tr>
                    <td colspan="6">
                        <div id="div__solicitante">
                            <?php
                            include("../php/conexion.php");

                            $usuario = $_SESSION['usuario'];

                            $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ);
                            foreach ($user as $duser):
                                $tuser = $base->query("SELECT * FROM tipo_usr WHERE pk_t_usr= '$duser->fk_tipo_usr'")->fetchAll(PDO::FETCH_OBJ); foreach ($tuser as $tipo): ?>
                                    <input type="hidden" name="codUsr"
                                        value="<?php echo $duser->pk_cod_usr?>">
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="solicitante" id="sel__solicitante">
                                        <option value="<?php echo $tipo->des_usr ?>"><?php echo $tipo->des_usr ?></option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="rolSol"
                                        value="<?php echo $duser->rol_usr ?>" required><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" name="nomSol"
                                        value="<?php echo $duser->nom_usr ?>" required><br>
                                    <label for="Sucursal">Sucursal:</label>
                                    <select name="sucursal">
                                        <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?></option>
                                        <option value="Principal">Principal</option>
                                        <option value="DefinirNuervo">Definir nuevo</option>
                                    </select><br>
                                    <label for="Departamento">Departamento:</label>
                                    <select name="departamento" id="sel__departamento">
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
                            <input type="checkbox" value="enviarCorreo" name="enviarCorreo">
                            <label id="enviarCorreo" for="EnviarCorreo">Enviar Correo Electronico si se agrego
                                pedido</label><br>
                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" name="correoElectronico"
                                placeholder="correo@correo.com"><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__fechas">
                            <?php
                            $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                            $num = 1; 
                            foreach ($ultimo as $ultimoo):
                                $num++;
                            endforeach; ?>
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text"name="numSol" value="<?php echo $num ?>" readonly><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" name="estado" value="ABIERTO" readonly><br>
                            <!-- <label for="FechaContabilizacion">Fecha contabilizacion:</label>
                            <input type="text" name="fechaContabilizacion"
                                placeholder="Fecha Contabilizasion"><br>
                            <label for="ValidoHasta">Valido hasta:</label>
                            <input type="text" name="validoHasta" placeholder="Valido hasta"><br> -->
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" name="fechaDocumento"
                                value="<?php echo date("d-m-y")?>"><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" name="fechaNecesaria"
                                placeholder="Fecha necesaria"><br>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
                        <div id="div__tablaServicios">
                                <a href=""><input class="btn_sel" type="button" value="servicios"></a>
                                <a href="hacerSolicitudArt.php"><input class="btn_sel" type="button" value="articulos"></a>
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
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
                                                    <td>
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td><select name="cod_arse<?php echo $i ?>" id="cod_arse" >
                                                            <option value="<?php if(isset($codArse[$i])){ echo $codArse[$i];}else{echo 0;}?>"><?php if(isset($codArse[$i])){ if ($codArse[$i]!=0){echo $codArse[$i]." | ";}}?></option>
                                                            <option value="" disabled>cod | descripcion servicio</option>
                                                            <?php
                                                            $servicios = $base->query("SELECT * FROM arse WHERE tipo_arse='servicio'")->fetchAll(PDO::FETCH_OBJ);
                                                            foreach ($servicios as $servicioss): ?>
                                                                <option value="<?php echo $servicioss->pk_cod_arse ?>" ><?php echo $servicioss->pk_cod_arse . " | " . $servicioss->des_arse ?></option>
                                                                
                                                                <?php
                                                            endforeach;
                                                            ?>
                                                            
                                                        </select>
                            
                                                    </td>
                                                    <td><input class="inputTabla" type="date" value="<?php if(isset($fechaNec[$i])){ echo $fechaNec[$i];}else{echo date("Y-m-d");}?>" name="fecha_Nec<?php echo $i ?>"
                                                            ></td>
                                                    <td><input id="seleccion" class="inputTabla"  type="search" name="proveedor<?php echo $i ?>"
                                                            placeholder=""></td>
                                                    <td><input class="inputTabla" type="number" value=0
                                                            name="precio_inf<?php echo $i ?>"></td>
                                                    <td><input class="inputTabla" type="search"
                                                            name="cuentaMayor<?php echo $i ?>" value="<?php if(isset($cuentaMayor[$i])){ echo $cuentaMayor[$i];}else{echo "";}?>"></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($uen[$i])){ echo $uen[$i];}else{echo "";}?>" name="uen<?php echo $i ?>"
                                                ></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($linea[$i])){ echo $linea[$i];}else{echo "";}?>" name="lineas<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($sublinea[$i])){ echo $sublinea[$i];}else{echo "";}?>" name="sublineas<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($proyecto[$i])){ echo $proyecto[$i];}else{echo "";}?>" name="proyecto<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="number" value=0 name="por_dec<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search" value="<?php if(isset($indImp[$i])){ echo $indImp[$i];}else{echo "";}?>" name="ind_imp<?php echo $i ?>"
                                                            ></td>
                                                    <td><input class="inputTabla" type="search" value="" name="total_ml<?php echo $i ?>"
                                                            ></td>
                                                </tr>
                                                <?php
                                                $i = $i + 1;
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
                            <input type="text" name="propietario" placeholder="Propietario"><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea name="comentarios" rows="4" cols="50" placeholder="comentarios"></textarea>
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
                                <a><input class="btn_env" type="submit" value="GUARDAR SOLICITUD" name="guardarS"></a>
                            <!-- <button class="btn_env">ENVIAR SOLICITUD</button> -->

                        </div>
                    </td>

                </tr>
                </form>
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