<?php

    /**
     *  config.php
     *  Taken from PSET7, allows for storing of sessions.
     *  Also, some helpers
     */

    // display errors, warnings, and notices
    error_reporting(-1); // reports all errors
    ini_set("display_errors", "1"); // shows all errors
    ini_set("log_errors", 1);
    ini_set("error_log", "/bin/php-error.log");

    //Requerimentos
    require("helpers.php"); //Funções
    require("objects.php"); //Classes

    //Define a o Locale
    date_default_timezone_set("America/Sao_Paulo");

    // CS50 Library
    require("../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");

    global $conn;

    $conn = connect_db();
    // enable sessions
    session_start();

    //require authentication for all pages except /login.php, /logout.php, and /register.php
    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/logout.php", "/register.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("index.php");
        }
    }

?>
