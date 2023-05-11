<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>solicitud</title>
    <link rel="icon" type="image/png" href="../images/fav.png" /> <!-- imagen del fav -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.js"
        integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");

    $numSol = $_GET["numSol"]; //se guarda la id que se manda en una variable
    ?>
    <div class="base">
        <header>
            <?php
            require_once('../php/header.php');
            ?>
        </header>
        <div class="contenedor" id="carga" hidden>
            <img id="centrar-carga" src="../images/carga10.gif">
        </div>
        <div class="contenedor" id="principal"> <!-- contenido entre el header y el footer -->
            <table border="1px" id="tabla__general">
                <!-- tabla general es la tabla que contiene los datos del solicitante, las fechas necesarias, los articulos o servicios, los comentarios y la opcion de volver  -->
                <tr>
                    <td colspan="6">
                        <!-- Esto se hace para que una fila de la tabla tome 6 columnas, en este caso esa es la mitad de la tabla -->
                        <div id="div__solicitante">
                            <!--  Este div contiene todos los datos de la persona que va a realizar la solicitud -->
                            <?php
                            $soli = $base->query("SELECT * FROM solicitud_compra WHERE pk_num_sol='$numSol'")->fetchAll(PDO::FETCH_OBJ); // se guardan los datos de la solicitud de compra en un PDOStatement
                            foreach ($soli as $solis):
                                $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$solis->fk_cod_usr'")->fetchAll(PDO::FETCH_OBJ); // con un dato de la solicitud se guardan los datos del usuario en un PDOStatement
                                foreach ($user as $duser):
                                    ?>
                                    <!-- Se muestran los datos del usuario que hizo la solicitud pero no se pueden modificar -->
                                    <label for="Solicitante">Solicitante:</label>
                                    <select name="solicitante" id="sel__solicitante" disabled>
                                        <option value="">
                                            <?php echo $duser->tipo_usuario ?>
                                        </option>
                                        <option value="Usuario">Usuario</option>
                                        <option value="Empleado">Empleado</option>
                                    </select>
                                    <input type="text" id="Solicitante" name="rolSol" value="<?php echo $duser->rol_usr ?>"
                                        disabled><br>
                                    <label for="NombreSolicitante">Nombre Solicitante:</label>
                                    <input type="text" name="nomSol" value="<?php echo $solis->nom_solicitante ?>" disabled><br>
                                    <label for="Sucursal">Sucursal:</label>
                                    <select name="sucursal" class="select_formulario" disabled>
                                        <option value="<?php echo $duser->sucursal ?>"><?php echo $duser->sucursal ?></option>
                                        <option value="Principal">Principal</option>
                                        <option value="DefinirNuervo">Definir nuevo</option>
                                    </select><br>
                                    <label for="Departamento">Departamento:</label>
                                    <select name="departamento" class="select_formulario" disabled>
                                        <?php
                                        $depSol = $base->query("SELECT * FROM departamento WHERE pk_dep= '$solis->depart_sol'")->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($depSol as $depSols):
                                            ?>
                                            <option value="<?php echo $solis->nom_solicitante ?>"><?php echo $depSols->nom_dep ?>
                                            </option>
                                            <?php
                                        endforeach
                                        ?>
                                    </select><br>
                                    <?php
                                endforeach;
                            endforeach;
                            ?>

                            <label for="CorreoElectronico">Direccion Correo Electronico:</label>
                            <input type="text" name="correoElectronico" placeholder="<?php echo $solis->correo_sol ?>"
                                disabled><br>
                        </div>
                    </td>
                    <td colspan="6">
                        <!-- Este toma la otra mitad de la fila para las fechas y el estado de la solicitud -->
                        <div id="div__fechas">
                            <!-- Se muestran las fechas de la solicitud y su estado  -->
                            <label for="Nsolicitud">N° solicitud de compra:</label>
                            <input type="text" name="numSol" value="<?php echo $numSol ?>" disabled><br>
                            <label for="Estado">Estado:</label>
                            <input type="text" name="estado" value="<?php echo $solis->estado_sol ?>" disabled><br>
                            <label for="FechaContabilizacion">Fecha documento:</label>
                            <input type="text" name="fechaDocumento" value="<?php echo $solis->fecha_documento ?>"
                                disabled><br>
                            <label for="FechaContabilizacion">Fecha necesaria:</label>
                            <input type="text" name="fechaNecesaria" value="<?php echo $solis->fecha_necesaria ?>"
                                disabled><br>
                        </div>
                    </td>
                </tr>
                <tr> <!-- Abre otra fila nueva  -->
                    <td colspan="12"> <!-- Toma todas las columnas de la tabla  -->
                        <div id="div_tabla_AS">
                            <div class="outer_wrapper">
                                <div class="table_wrapper">
                                    <?php
                                    if ($solis->tipo == "servicio") { //La condicion es para saber si la solicitud tiene articulos o servicios
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
                                            $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solis->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los servicios de la solicitud en la variable 
                                            $i = 1;
                                            foreach ($lista as $listaa):
                                                ?>
                                                <tr>
                                                    <!-- Se llama cada uno de los campos con el nombre que tienen en la base de datos -->
                                                    <td>
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td><input class="inputTablaServicios"
                                                            value="<?php echo $listaa->nom_arse ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->fecha_nec ?>"
                                                            disabled></td>
                                                    <td><input class="inputTablaServicios"
                                                            value="<?php echo $listaa->proveedor ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->precio_info ?>"
                                                            disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->cuenta_mayor ?>"
                                                            disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->uen ?>" disabled>
                                                    </td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->linea ?>" disabled>
                                                    </td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->sublinea ?>"
                                                            disabled></td>
                                                    <td><input class="inputTablaServicios"
                                                            value="<?php echo $listaa->proyecto ?>" disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->por_desc ?>"
                                                            disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->ind_imp ?>"
                                                            disabled></td>
                                                    <td><input class="inputTabla" value="<?php echo $listaa->total_ml ?>"
                                                            disabled></td>
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
                                                    <th>Cantidad Necesaria</th>
                                                    <th>Precio Info</th>
                                                    <th>% Descuento</th>
                                                    <th>indicador de impuestos</th>
                                                    <th>total ml</th>
                                                    <th>UEN</th>
                                                    <th>lineas</th>
                                                    <th>sublineas</th>
                                                </thead>
                                                <?php
                                                $lista = $base->query("SELECT * FROM list_arse WHERE fk_num_sol= '$solis->pk_num_sol'")->fetchAll(PDO::FETCH_OBJ); //se guardan los articulos de la solicitud en la variable 
                                                $i = 1;
                                                foreach ($lista as $listaa):
                                                    ?>
                                                    <tr>
                                                        <!-- Se llama cada uno de los campos con el nombre que tienen en la base de datos -->
                                                        <td>
                                                            <?php echo $i ?>
                                                        </td>
                                                        <td><input class="inputTabla"
                                                                value="<?php echo $listaa->codigo_articulo ?>" disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->nom_arse ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->proveedor ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->fecha_nec ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->cant_nec ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->precio_info ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->por_desc ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->ind_imp ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->total_ml ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->uen ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->linea ?>"
                                                                disabled></td>
                                                        <td><input class="inputTabla" value="<?php echo $listaa->sublinea ?>"
                                                                disabled></td>
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
                            <input type="text" name="propietario" value="<?php echo $solis->propietario ?>"
                                disabled><br>
                            <label for="Comentarios">Comentarios:</label>
                            <textarea name="comentarios" rows="4" cols="50"
                                disabled><?php echo $solis->comentarios ?></textarea>
                        </div>
                    </td>
                    <td colspan="6">
                        <div id="div__enviar">
                            <a target="_blank" href="pdf.php?numSol=<?php echo $numSol ?>"><input class="btn_guardar"
                                    type="button" value="GENERAR PDF"></a>
                            <br>
                            <a href="javascript:history.back()"><input class="btn_volver" type="button"
                                    value="VOLVER"></a>
                        </div>
                    </td>

                </tr>
            </table>
        </div>
        <footer>
            <?php
            require_once('../php/footer.php');
            ?>
        </footer>
    </div>
</body>
<script>
    function pantallaCarga() {
        $('#principal').fadeOut();
        $('#carga').prop("hidden", false);
    }
</script>

</html>