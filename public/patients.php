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
        $query = "SELECT * FROM public.\"patients\" WHERE userid = '".$_SESSION['id']."' ORDER BY p_status ASC,lastactive DESC ";
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
        $patientID = $_POST['patientID'];
        $page = basename($_SERVER['HTTP_REFERER']);

        //Query database according to operation selected
        if($operation == 'STATUS'){

            changeStatus($patientID,$conn);

        }
        else if($operation == 'EDIT'){

            editPatient($patientID,$conn);

        }
        else if($operation == 'ADD'){

            addPatient($conn);
        }

        //Returns to the original page
        redirect($page);
    }


    /** PatientID, database connection -> NULL
     *  Gets a given patient ID and changes its status in the
     *  database row corresponding to the patient under care
     *  A connection to the database must be passed
     */
    function changeStatus($patientID, $conn){

        //Get patient and it's status from the database
        $query = "SELECT * FROM public.\"patients\" WHERE patientid = '" . $patientID ."'";
        $res = pg_query($conn, $query);

        //Fetches the single row for the user found
        $patient = pg_fetch_row($res);
        $status = $patient[6]; //6 = p_status

        //Change status given current status state
        if($status == 1) { pg_query($conn,"UPDATE public.\"patients\" SET p_status = 0 WHERE patientid = '".$patientID."'");}
        else { pg_query($conn, "UPDATE public.\"patients\" SET p_status = 1 WHERE patientid ='".$patientID."'");}
    }

    /** PatientID, database connection -> NULL
     *  This part of the function will take patients new ID, name and age.
     *  Query patients, lab and prescriptions to make changes to the user ID
     *  or else it will fuck all the databases(prescriptions and labref)
     *  A connection to the database must be passed
     */
    function editPatient($patientID, $conn){

        //Edit the patient to contain html supported chars.
        $pname = htmlspecialchars($_POST['patient_name']);

        //If the patient ID remains the same
        if ($_POST['new_id'] == $_POST['patientID']){
            pg_query($conn,"UPDATE public.\"patients\" SET
                            patientname ='". $pname ."',
                            patientage = ". $_POST['patient_age'] ."
                            WHERE patientid = '". $_POST['patientID'] ."'");
        }
        //If the patientID changes. Adds a dot to the end of the string to ensure no ID is equal.
        else{
            $new_id = $_POST['new_id'] . ".";


            //Here be queries updating the new ID into patients, labref and prescriptions
            pg_query($conn,"UPDATE public.\"patients\" SET
                            patientid = '". $new_id ."',
                            patientname = '". $pname ."',
                            patientage = ". $_POST['patient_age'] ."
                            WHERE patientid = '".$_POST['patientID'] ."'");

            pg_query($conn,"UPDATE public.\"prescriptions\" SET
                            patientid = '". $new_id."'
                            WHERE patientid ='". $_POST['patientID']."'");

            pg_query($conn,"UPDATE public.\"labref\" SET
                            patientid = '". $new_id."'
                            WHERE patientid ='". $_POST['patientID']."'");
           }
    }

    /** PatientID, page -> NULL
     *  Adds a new patient to the database of patients
     */
    function addPatient($conn){

        $patientname = $_POST['patient_name'];
        $patientage = $_POST['patient_age'];
        $patientID = $_POST['new_id'];
        $userID = $_SESSION['id'];

        //Insert a new patient into the patients database
        pg_query($conn,"INSERT INTO public.\"patients\"(patientid, patientname, patientage,userid)
                         values ('". $patientID."','".$patientname."','".$patientage."','".$userID."')");
    }
?>
