<?php

    // Exibição de erros
    error_reporting(-1);
    ini_set("display_errors", "1");
    ini_set("log_errors", 1);
    ini_set("error_log", "/bin/php-error.log");

    //Requerimentos - Infra-estrutura
    require_once("helpers.php"); //Funções
    require("objects/objconf.php"); //Classes

    //Define o Locale e timezone
    date_default_timezone_set("America/Sao_Paulo");

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
