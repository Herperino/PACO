<?php

    /**
     * This is the controller file for selecting patients from the file
     * The select view allows for a JSON request from acompanhamento or
     * labref to render the correct patient list.
     */

    include("../includes/config.php");

    $patients = []; //Array containing all the patients currently under care

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        /*
         *    Query the server for the patients given a user and return a JSON.
         *    Patients are sorted by status(active first) and last modification.
         */

        //Queries the data from the postgresql db
        $query = "SELECT * FROM public.\"patients\"
                  WHERE userid = '".$_SESSION['id']."'
                  ORDER BY p_status ASC,lastactive DESC ";
        $data = pg_query($conn, $query);

        //Store patient data in an array
        $patients = pg_fetch_all($data);

        // output patients as JSON (pretty-printed for debugging convenience)
        header("Content-type: application/json; charset=UTF-8");
        print(html_entity_decode(json_encode($patients, JSON_PRETTY_PRINT)));
    }

    else if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Get operation (Remove, Add, Change Status)
        $operation = $_POST['operation'];
        $patientID = $_POST['patientID'];
        $page = basename($_SERVER['HTTP_REFERER']);

        if($operation == 'STATUS'){

            changeStatus($patientID);
            //trigger_error("CARALHO FILHO DA PUTA");
        }
        else if($operation == 'EDIT'){

            editPatient($patientID);

        }
        else if($operation == 'ADD'){

            addPatient();
        }

        //Returns to the original page
        redirect($page);
    }



    /** PatientID -> NULL
     *  Gets a given patient ID and changes its status in the
     *  database row corresponding to the patient under care
     */
    function changeStatus($patientID){

        //Get patient and it's status from the database

        $query = "SELECT * FROM public.\"patients\"
                  WHERE patientid ='" . $patientID ."'" ;
        $res = pg_query($conn, $query);

        $patient = pg_fetch_all($data);
        $status = $patient[6]; //6 = p_status

        //Change status given current status state
        if($status == 1) { pg_query($conn,"UPDATE public.\"patients\" SET p_status = 0 WHERE patientid = '".$patientID."'");}
        else { pg_query($conn, "UPDATE public.\"patients\" SET p_status = 1 WHERE patientid ='".$patientID."'");}
    }

    /** PatientID, page -> NULL
     *  This part of the function will take patients new ID, name and age.
     *  Query patients, lab and prescriptions to make changes to the user ID
     *  or else it will fuck all the databases(prescriptions and labref)
     */
    function editPatient($patientID){

        //Edit the patient to contain html supported chars.
        $pname = htmlspecialchars($_POST['patient_name']);

        //If the patient ID remains the same
        if ($_POST['new_id'] == $patientID){
            //$query = "UPDATE public.\"patients\" SET patientname = '".$pname."', patientage = ".$_POST['patient_age']." WHERE patientid = '".$patientID;
            //$res = pg_query($conn, $query);
        }
        //If the patientID changes. Adds a dot to the end of the string to ensure no ID is equal.
        else{
            $new_id = $_POST['new_id'] . ".";

            cs50::query("UPDATE patients SET patientID = ?, patientname = ?, patientage = ? WHERE patientID = ?", $new_id, $pname, $_POST['patient_age'],$_POST['patientID']);
            cs50::query("UPDATE prescriptions SET patientID = ? WHERE patientID = ?", $new_id, $_POST['patientID']);
            cs50::query("UPDATE labref SET patientID = ? WHERE patientID = ?", $new_id, $_POST['patientID']);
           }
    }

    /** PatientID, page -> NULL
     *  Adds a new patient to the database of patients
     */
    function addPatient(){

        $patientname = $_POST['patient_name'];
        $patientage = $_POST['patient_age'];
        $patientID = $_POST['new_id'];
        $userID = $_SESSION['id'];

        cs50::query("INSERT INTO patients(patientID, patientname, patientage,userID)
                                 values (?,?,?,?)",
                                 $patientID, $patientname,$patientage,$userID);
    }
?>
