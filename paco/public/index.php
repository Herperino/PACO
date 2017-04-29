<?php

    // Loads some php helpers for the site
    require("../includes/helpers.php"); 
    
    session_start();
    
    // render landing page
    render("landing.php");
?>
