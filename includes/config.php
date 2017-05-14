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

    // requirements
    require("helpers.php");

    // CS50 Library
    require("../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");

    // enable sessions
    session_start();

    //init postgres connection
    $database = "da9ca7l565c2pg";
    try{
    $conn = pg_connect("host=ec2-23-21-227-73.compute-1.amazonaws.com port=5432 dbname=".$database." user=hypmpmdpmsubvi password=d4338194bb3376272ff09a413786ed3852229812b977259d5d4b5e7958c37c85 sslmode=require");

    //require authentication for all pages except /login.php, /logout.php, and /register.php
    if (!in_array($_SERVER["PHP_SELF"], ["/login.php", "/logout.php", "/register.php"]))
    {
        if (empty($_SESSION["id"]))
        {
            redirect("index.php");
        }
    }

?>
