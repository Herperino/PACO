<?php

    /** DCB insert is an utility used to insert drug names into a SQL database of drug names.
     *  It iterates over each line of our DCB.txt and places it into our database. */
     
    //Requires CS50 php library and initializes it
    require("../vendor/library50-php-5/CS50/CS50.php");
    CS50::init(__DIR__ . "/../config.json");
    
    
    if ($argc <2)
        exit("Usage: DCBinserter.php PATH\n");
    
    $path = $argv[1];
    
    DCBfiller($path);
    
    function DCBfiller($medlist)
    {
    
        /** Fills the drug database with drug names in DCB (Denominação Comum Brasileira) denomination */
        //Opens the DCB.txt for reading.
        $DCB = fopen($medlist, "r");
        
        //Iterates over DCB.txt and adds to the database
        while($drugname = fgets($DCB))
        {
            cs50::query("INSERT INTO medications(id, name) VALUES(NULL, ?)", $drugname);
        }
    
    }
?>