    <?php
    // cierra la session y lo manda para el inicio 
        session_start();
        session_destroy(); //destruye la sesion
        header("location:../"); //se situa al usuario en el inicio de sesion
    ?>
