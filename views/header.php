<?php
include("../php/conexion.php");
if(isset($_SESSION['usuario'])){
$usuario = $_SESSION['usuario']; //se toma el usuario de la sesion para los permisos
}
else{
  header("location:../index.php");
}
$registros = $base->query("SELECT * FROM usuario WHERE pk_cod_usr= '$usuario'")->fetchAll(PDO::FETCH_OBJ); //se toma su tiipo de usuario

foreach ($registros as $Tusuario) {
  $userx = $Tusuario->tipo_usuario;
}
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-bottom-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <img src="../images/logo.png" alt="PROMETALICOS" height="80px">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
      aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
          <a class="nav-link" href="home.php" onclick="pantallaCarga()"><i class="bi bi-house-door"></i>HOME</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            HACER SOLICITUD
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="hacerSolicitud.php" onclick="pantallaCarga()">SERVICIOS</a></li>
            <li><a class="dropdown-item" href="hacerSolicitudArt.php" onclick="pantallaCarga()">ARTICULOS</a></li>
          </ul>
        </li>
        <?php
        if ($userx == 3) { //este es el tipo de usuario administrador
          ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              SOLICITUDES
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="misSolicitudes.php" onclick="pantallaCarga()">MIS SOLICITUDES</a></li>
              <li><a class="dropdown-item" href="solicitudesUsuario.php" onclick="pantallaCarga()">SOLICITUDES USUARIO</a></li>
            </ul>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="usuarios.php" onclick="pantallaCarga()">USUARIOS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="servicios.php" onclick="pantallaCarga()">SERVICIOS</a>
        </li>
          <?php
        } else {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="misSolicitudes.php" onclick="pantallaCarga()">MIS SOLICITUDES</a>
          </li>
          <?php
        }
        ?>
        <li class="nav-item">
          <a class="nav-link" href="informes.php" onclick="pantallaCarga()">INFORMES</a>
        </li>
      </ul>
      <div class="d-flex">
        <button type="button" class="btn btn-danger me-2" onclick="cerrar_sesion()"><i class="bi bi-door-open"> SALIR</i></button>
      </div>
    </div>
  </div>
</nav>
<script>
  function cerrar_sesion() {
    Swal.fire({
      title: '¿Esta seguro que quiere cerrar sesion?',
      color: '#ffffff',
      icon: 'question',
      iconColor: 'red',
      showCancelButton: true,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        window.location = "../crud/cerrar_session.php";
      }
    })
  }
  function pantallaCarga() {
    $('#principal').fadeOut();
    $('#carga').prop("hidden", false);
  }
</script>