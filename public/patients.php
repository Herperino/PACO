<?php

    /**
     * This is the controller file for selecting patients from the file
     * The select view allows for a JSON request from acompanhamento or
     * labref to render the correct patient list.
     */

    require("../includes/config.php");

    $patients = []; //Array containing all the patients currently under care

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        /*
         *    Query the server for the patients given a user and return a JSON.
         *    Patients are sorted by status(active first) and last modification.
         */

        //Queries the data from the postgresql db
        $query = "SELECT * FROM public.\"patients\" WHERE userid = '".$_SESSION['id']."' ORDER BY p_status DESC,lastactive DESC ";
        $data = pg_query($conn, $query);

        //Store patient data in an array
        $patients = pg_fetch_all($data);

        // output patients as JSON (pretty-printed for debugging convenience)
        header("Content-type: application/json; charset=UTF-8");
        print(html_entity_decode(json_encode($patients, JSON_PRETTY_PRINT)));
        exit();
    }

    else if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Get operation (Remove, Add, Change Status)
        $operation = $_POST['operation'];
        $patientID = ltrim($_POST['patientID'],"0"); //Removes trailing zeroes
        $page = basename($_SERVER['HTTP_REFERER']);

        //Query database according to operation selected
        if($operation == "REMOVE"){

          pg_query($conn, "DELETE FROM public.\"patients\" WHERE patientid ='".$patientID."'");

        }
        else if($operation == "STATUS"){

            $result = changeStatus($patientID,$conn);
        }
        else if($operation == "EDIT"){

            $result = editPatient($patientID,$conn);

        }
        else if(strcmp($operation,"ADD") == 0){

            $result = addPatient($conn);
        }

        //Returns to the original page
        if ($result)
            redirect($page);
        else
            render("apology.php",['errormessage' => "O ID de usuário já está em uso"])
    }
?>
