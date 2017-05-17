<?php
        require("../includes/config.php");

        /** This file is used for the login controller */

        /* Checks if the file was requested by filling the form atop the page.
        if so, starts a new session given everything is correct */

        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // query database for user            
            try{
              $conn = connect_db();
              $query = "SELECT * FROM public.\"PACO_users\" WHERE email = '" . $_POST["id"]."'";
              $users = pg_query($conn, $query);
            }
            catch(Exception $e){
              render("apology.php", ['errormessage' => htmlspecialchars("test")]);
            }

            // if we found user, check password
            if ($users)
            {

                $users = pg_fetch_array($users,0,PGSQL_BOTH);


                // compare hash of user's input against hash that's in database
                if (password_verify($_POST["password"], $users["userhash"]))
                {
                    // remember that user's now logged in by storing user's ID in session
                    $_SESSION["id"] = $users["id"];
                    $_SESSION["username"] = $users['username'];

                    // redirect to landing page
                    redirect("index.php", ['P_MODE' => true]);
                }
                else
                {

                    render("apology.php", ['errormessage' => htmlspecialchars("Usuario ou senha errados ")]);
                }
            }
            else
            {
                render("apology.php", ['errormessage' => htmlspecialchars("Usuario ou senha errados")]);
            }

        }
?>
