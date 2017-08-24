<?php

    // Carrega algumas funções helpers
    require("../includes/helpers.php");

    //Habilita sessões para login
    session_start();

    // render landing page
    render("landing.php");
?>
