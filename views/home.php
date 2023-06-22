<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    require('head.php');
    include("../crud/procesados.php");
        ?>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["usuario"])) { //confirma si el usuario ya inicio sesion
    
        header("location:../index.php");
    }

    require('header.php');
    include("../php/conexion.php");
    $abierto=0;
    $enviado=0;
    $rechazado=0;
    $procesado=0;
    $solicitud = $base->query("SELECT * FROM solicitud_compra")->fetchAll(PDO::FETCH_OBJ); //guarda las solicitudes de servicios hechas por el ususario de la sesion en un PDOStatement
    foreach ($solicitud as $solicitudes){ // se recorren todos las solicitudes de servicio del usuario
       if($solicitudes->estado_sol == "ABIERTO"){
            $abierto++;
       }
       if($solicitudes->estado_sol == "ENVIADO"){
            $enviado++;
       }
       if($solicitudes->estado_sol == "RECHAZADO"){
            $rechazado++;
       }
       if($solicitudes->estado_sol == "PROCESADO"){
            $procesado++;
       }
    }
    ?>
    <div class="contenedor-carga" id="carga" hidden>
        <img id="centrar-carga" src="../images/carga.gif">
    </div>
    <div class="container py-2" style="min-height: 80vh;" id="principal">
        <div class="text-center">
            <h3>SAPROMETALICOS</h3>
        </div>
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4>
                SOLICITUDES:
                </h4>
            </div>
            <div class="row py-2 px-2">
                <div class="col py-2 px-2">
                    <div class="card font-weight-bold text-center border-info">
                        <div class="card-header bg-info text-white">
                            ABIERTOS:
                        </div>
                        <div class="card-body text-info">
                        <h1><?php echo $abierto ?></h1>
                        <div class="d-md-block">
                        <a href="solicitudes.php?estado=ABIERTO" class="btn btn-info" onclick="pantallaCarga()">VER</a>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col py-2 px-2">
                    <div class="card font-weight-bold text-center border-success">
                        <div class="card-header bg-success text-white">
                            ENVIADOS:
                        </div>
                        <div class="card-body text-success">
                        <h1><?php echo $enviado ?></h1>
                        <div class="d-md-block">
                        <a href="solicitudes.php?estado=ENVIADO" class="btn btn-success" onclick="pantallaCarga()">VER</a>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col py-2 px-2">
                    <div class="card font-weight-bold text-center border-danger">
                        <div class="card-header bg-danger text-white">
                            RECHAZADOS:
                        </div>
                        <div class="card-body text-danger">
                        <h1><?php echo $rechazado ?></h1>
                        <div class="d-md-block">
                        <a href="solicitudes.php?estado=RECHAZADO" class="btn btn-danger" onclick="pantallaCarga()">VER</a>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col py-2 px-2">
                    <div class="card font-weight-bold text-center border-secondary">
                        <div class="card-header bg-secondary text-white">
                            PROCESADOS:
                        </div>
                        <div class="card-body text-secondary">
                        <h1><?php echo $procesado ?></h1>
                        <div class="d-md-block">
                        <a href="solicitudes.php?estado=PROCESADO" class="btn btn-secondary" onclick="pantallaCarga()">VER</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require('footer.php')
        ?>
</body>

</html>