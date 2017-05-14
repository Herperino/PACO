<?php
        require("../includes/config.php");

        /** This file is used for the login controller */

        /* Checks if the file was requested by filling the form atop the page.
        if so, starts a new session given everything is correct */

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            //init postgres connection
            $database = "da9ca7l565c2pg";
            try{
            $conn = pg_connect("host=ec2-23-21-227-73.compute-1.amazonaws.com port=5432 dbname=".$database." user=hypmpmdpmsubvi password=d4338194bb3376272ff09a413786ed3852229812b977259d5d4b5e7958c37c85 sslmode=require");// enable sessions

              // query database for user
              try{

                $query = "SELECT * FROM \"PACO_users\" WHERE username = ''" . $_POST["id"]. "'";
                $users = pg_query($conn, $query);
              }
              catch(Exception $e){
                render("apology.php", ['errormessage' => htmlspecialchars("test")]);
              }
            }
            catch(Exception $e){
              render("apology.php", ['errormessage' => htmlspecialchars("test")]);
            }


            // if we found user, check password
            if (count($users) == 1)
            {
                // first (and only) row
                $user = $users[0];

                // compare hash of user's input against hash that's in database
                if (password_verify($_POST["password"], $user["userhash"]))
                {
                    // remember that user's now logged in by storing user's ID in session
                    $_SESSION["id"] = $user["id"];
                    $_SESSION["username"] = $user['username'];

                    // redirect to landing page
                    redirect("index.php", ['P_MODE' => true]);
                }
                else
                {
                    render("apology.php", ['errormessage' => htmlspecialchars("Usuario ou senha errados")]);
                }
            }
            else
            {
                render("apology.php", ['errormessage' => htmlspecialchars("Usuario ou senha errados")]);
            }

        }
?>
