<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('head.php')
        ?>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) {

        header("location:../index.php");
    }

    include("../php/conexion.php");
    require('header.php');

    $numSol = $_GET["numSol"]; //se guarda la id que se manda en una variable
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>INFORMACION DE LA SOLICITUD</h3>
        </div>
        <?php
        $soli = $base->query("SELECT * FROM solicitud_compra WHERE pk_num_sol='$numSol'")->fetchAll(PDO::FETCH_OBJ); // se guardan los datos de la solicitud de compra en un PDOStatement
        foreach ($soli as $solis):
            $user = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$solis->fk_cod_usr'")->fetchAll(PDO::FETCH_OBJ); // con un dato de la solicitud se guardan los datos del usuario en un PDOStatement
            foreach ($user as $duser):
                ?>
                <div class="row">
                    <div class="col bloques py-2">
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="solicitante">SOLICITANTE:</label>
                                    <input type="hidden" id="codUsr" value="<?php echo $duser->pk_cod_usr ?>">
                                    <select class="form-select" aria-label="Default select example" id="sel__solicitante">
                                        <?php
                                        if ($duser->tipo_usuario == 3) {
                                            ?>
                                            <option value="Administrador">Administrador</option>
                                            <?php
                                        } else if ($duser->tipo_usuario == 2) {
                                            ?>
                                                <option value="Empleado">Empleado</option>
                                            <?php
                                        } else {
                                            ?>
                                                <option value="Usuario">Usuario</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col form-group">
                                    <label for="tipo">ROL SOLICITANTE:</label>
                                    <input type="text" class="form-control" id="Solicitante"
                                        value="<?php echo $duser->rol_usr ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="nombres">NOMBRE SOLICITANTE:</label>
                                    <input type="text" class="form-control" id="nomSol" value="<?php echo $duser->nom_usr ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="sucursal">SUCURSAL:</label>
                                    <input type="text" class="form-control" id="sucursal" value="PRINCIPAL">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="departamento">DEPARTAMENTO:</label>
                                    <select class="form-select" aria-label="Default select example" id="departamento">
                                        <?php
                                        $dep = $base->query("SELECT * FROM departamento WHERE pk_dep= '<?php $duser->fk_depart ?>'")->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($dep as $depa): ?>
                                            <option value="<?php echo $duser->fk_depart ?>"><?php echo $depa->nom_dep ?>
                                            </option>
                                            <?php
                                        endforeach;
                                        ?>
                                        <?php
                                        $departamento = $base->query("SELECT * FROM departamento")->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($departamento as $departamentos): ?>
                                            <option value="<?php echo $departamentos->pk_dep ?>"><?php echo $departamentos->nom_dep ?></option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="correo">DIRECCION CORREO ELECTRONICO:</label>
                                    <input type="email" class="form-control" id="correoElectronico"
                                        value="<?php echo $solis->correo_sol ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col bloques py-2">
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <?php
                                    $ultimo = $base->query('SELECT * FROM solicitud_compra')->fetchAll(PDO::FETCH_OBJ);
                                    $num = 1;
                                    foreach ($ultimo as $ultimoo):
                                        $num++;
                                    endforeach; ?>
                                    <label for="sucursal">N° SOLICITUD DE COMPRA:</label>
                                    <input type="text" class="form-control" value="<?php echo $numSol ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="sucursal">ESTADO:</label>
                                    <input type="text" class="form-control" value="<?php echo $solis->estado_sol ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="sucursal">FECHA DOCUMENTO:</label>
                                    <input type="date" class="form-control" id="fechaDocumento"
                                        value="<?php echo $solis->fecha_documento ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col form-group">
                                    <label for="sucursal">FECHA NECESARIA:</label>
                                    <input type="date" class="form-control" id="fechaNecesaria"
                                        value="<?php echo $solis->fecha_necesaria ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col bloques py-2">
                        <?php
                        if ($solis->tipo == "servicio") { //La condicion es para saber si la solicitud tiene articulos o servicios
                            ?>
                            <div class="py-2">
                                <button type="button" class="btn btn-danger">SERVICIOS</button>
                            </div>
                            <div class="overflow-x-scroll">
                                <table class="table table-bordered table-striped table-hover" id="tablaServicios">
                                    <thead class="table-dark">
                                        <tr>
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
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                <td>
                                                    <?php echo $listaa->nom_arse ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->fecha_nec ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->proveedor ?>

                                                </td>
                                                <td>
                                                    <?php echo $listaa->precio_info ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->cuenta_mayor ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->uen ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->linea ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->sublinea ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->proyecto ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->por_desc ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->ind_imp ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->total_ml ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="py-2">
                                <a class="btn btn-danger">ARTICULOS</a>
                            </div>
                            <div class="overflow-x-scroll">
                                <table class="table table-bordered table-striped table-hover" id="tablaArticulos">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>codigo Articulo</th>
                                            <th>Descripcion Articulo</th>
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
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                <td>
                                                    <?php echo $listaa->codigo_articulo ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->nom_arse ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->proveedor ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->fecha_nec ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->cant_nec ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->precio_info ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->por_desc ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->ind_imp ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->total_ml ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->uen ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->linea ?>
                                                </td>
                                                <td>
                                                    <?php echo $listaa->sublinea ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col bloques py-2">
                        <div class="mb-3">
                            <label class="form-label">PROPIETARIO</label>
                            <input type="email" class="form-control" id="propietario" value="<?php echo $solis->propietario ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">COMENTARIOS</label>
                            <textarea class="form-control" id="comentarios"
                                rows="3"><?php echo $solis->comentarios ?></textarea>
                        </div>
                    </div>
                    <div class="col text-center bloques py-2">
                        <br>
                        <a target="_blank" href="pdf.php?numSol=<?php echo $numSol ?>" class="btn btn-danger"><i
                                class="bi bi-file-earmark-pdf">GENERAR PDF </i></a>
                        <br><br>
                        <a href="javascript:history.back()" class="btn btn-danger"><i class="bi bi-arrow-bar-left">VOLVER
                            </i></a>
                    </div>
                </div>
                <?php
            endforeach;
        endforeach;
        ?>
    </div>
    <?php
    require('footer.php')
        ?>
</body>

</html>