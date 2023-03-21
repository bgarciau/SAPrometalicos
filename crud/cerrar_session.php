    <?php
    // cierra la session y lo manda para el inicio 
        session_start();
        session_destroy();
        header("location:../");
    ?>
