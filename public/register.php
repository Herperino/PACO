<?php

    require("../includes/config.php");

    //If the user reached the controller via a GET request, will render a form
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
          render("register.php");
    }
    //If the form was filled and sent back as POST request, will add user to DB
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //Query the database to insert a new user. Check if the user is valid.
        $query ="SELECT * FROM public.\"PACO_users\" WHERE username =" .$_POST['regisid'];         
        $registered = pg_query($conn, $query);

        if (!empty($registered)) //If user isn't valid
        {
         render("apology", ['errormesage' => "This username is already in use. Try again"]);
        }
        else
        {
            $pwd = password_hash($_POST['regispwd'], PASSWORD_DEFAULT);

            $query = "INSERT INTO public.\"PACO_users\"(username, userhash) VALUES (".$_POST['regisid'].",".$pwd.")";
            $register = pg_query($conn, $query);

            render("register.php");
        }
    }
?>
