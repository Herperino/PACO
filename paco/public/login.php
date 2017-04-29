<?php
        require("../includes/config.php");
        
        /** This file is used for the login controller */    
        
        /* Checks if the file was requested by filling the form atop the page.
        if so, starts a new session given everything is correct */
        
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
          
            // query database for user
            $users = CS50::query("SELECT * FROM PACO_users WHERE username = ?", $_POST["id"]);
            
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

