<?php

    /**
     *  This is the controller file for reviewing patients prescriptions
     * (acompanhamento). The SQL database allows for up to 20 medications
     *  The fields are:userID, patient ID, patient active?, patient name,
     *  patient age and medication (name, dose and frequency) 1-20
     */

      /**A server request will trigger a POST request to the server, meaning that
     * a patient was selected. For such selection, we will grab the patient id
     * and make a request to the server for the patients prescriptions in the
     * database.
     *
     * Along with that, we will also display the patient's name, age and sex.
     */

    require("../includes/config.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Gets the patient name for displaying.
        $patientID = $_POST['patientID'];
        $query = pg_query($conn, "SELECT patientname FROM public.\"patients\" WHERE patientid = '".$patientID."'");
        $patients = pg_fetch_all($query);

        $name = ($patients[0]['patientname']);
        $name = utf8_encode($name);

        //token for displaying prescriptions correctly
        $token = false;

        //Gets patientID and userID
        $userID = $_SESSION['id'];

        if($_POST['operation'] == 'PRESCRIPTION_ADD'){
            addPrescription($patientID);
            $token = true;
        }
        else if ($_POST['operation'] == 'PRESCRIPTION_EDIT'){

            editPrescription($patientID);
            $token = true;
        }
        else if($_POST['operation'] == "GET_PRESCRIPTION"){

            //makes a JSON return of the prescriptions array.
            //$last_prescription = cs50::query("SELECT * FROM prescriptions WHERE Date =?", $_POST['date']);

            header("Content-type: application/json; charset=UTF-8");
            print(json_encode($last_prescription, JSON_PRETTY_PRINT));
            $token = true;
            exit();
        }

        //Query database for the patient's prescriptions given a userID and patient name
        //$prescriptions =  cs50::query("SELECT * FROM prescriptions WHERE patientID = ? AND userID = ? ORDER BY Date ASC", $patientID, $userID);

        $page_mode = true;
        render("acompanhamento.php", ['P_MODE' => $page_mode, 'prescriptions' => $prescriptions, 'patientID' => $name, 'P_ID' =>$patientID, 'token' => $token]);
    }

    /** Upon a GET request, the server will will then render the page in select mode
     *  then query the server for all the active patients under care by the user.
     *  the query will be done via AJAX via patients.php. Once selection is done,
     *  a POST request will be done to this same controller.
     */

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
       //Renders acompanhamento in view mode
        $page_mode = false; //Defines whether the view will be displayed in Select or View mode
        render("acompanhamento.php", ['P_MODE' => $page_mode]);
    }

    function addPrescription($patientID){

     $date = "CURRENT_TIMESTAMP";

     //Concatenates the prescription data into a single k/v array
     for ($i = 1; $i<=10; $i++){

        $currentM = "med" . $i;
        $currentD = "dos" . $i;
        $currentV = "via" . $i;
        $currentP = "pos" . $i;

        $prescriptions[$currentM] = $_POST[$currentM] ."". $_POST[$currentD] ."". $_POST[$currentV] ;
        $prescriptions[$currentP] = $_POST[$currentP];

     }

    // cs50::query("INSERT INTO prescriptions(userID,patientID, med1,pos1,med2,pos2,med3,pos3,med4,pos4,med5,pos5,
    //                                       med6,pos6,med7,pos7,med8,pos8,med9,pos9,med10,pos10)
    //                             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
    //                             $_SESSION['id'], $patientID, $prescriptions['med1'],$prescriptions['pos1'],
    //                             $prescriptions['med2'],$prescriptions['pos2'],
    //                             $prescriptions['med3'],$prescriptions['pos3'],
    //                             $prescriptions['med4'],$prescriptions['pos4'],
    //                             $prescriptions['med5'],$prescriptions['pos5'],
    //                             $prescriptions['med6'],$prescriptions['pos6'],
    //                             $prescriptions['med7'],$prescriptions['pos7'],
    //                             $prescriptions['med8'],$prescriptions['pos8'],
    //                             $prescriptions['med9'],$prescriptions['pos9'],
    //                             $prescriptions['med10'],$prescriptions['pos10']);
    }

    function editPrescription($patientID){

     //Concatenates the prescription data into a single k/v array
     for ($i = 1; $i<=10; $i++){

        $currentM = "med" . $i;
        $currentD = "dos" . $i;
        $currentV = "via" . $i;
        $currentP = "pos" . $i;

        $prescriptions[$currentM] = $_POST[$currentM] ."". $_POST[$currentD] ."". $_POST[$currentV] ;
        $prescriptions[$currentP] = $_POST[$currentP];
        }

      // cs50::query("UPDATE prescriptions SET Date = Date,med1=?,pos1=?,med2=?,pos2=?,med3=?,pos3=?,med4=?,pos4=?,med5=?,pos5=?,
      //                                     med6=?,pos6=?,med7=?,pos7=?,med8=?,pos8=?,med9=?,pos9=?,med10=?,pos10=? WHERE
      //                           Date = ? AND userID = ? AND patientID =? ",
      //                           $prescriptions['med1'],$prescriptions['pos1'],
      //                           $prescriptions['med2'],$prescriptions['pos2'],
      //                           $prescriptions['med3'],$prescriptions['pos3'],
      //                           $prescriptions['med4'],$prescriptions['pos4'],
      //                           $prescriptions['med5'],$prescriptions['pos5'],
      //                           $prescriptions['med6'],$prescriptions['pos6'],
      //                           $prescriptions['med7'],$prescriptions['pos7'],
      //                           $prescriptions['med8'],$prescriptions['pos8'],
      //                           $prescriptions['med9'],$prescriptions['pos9'],
      //                           $prescriptions['med10'],$prescriptions['pos10'],
      //                           $_POST['date'], $_SESSION['id'], $patientID);
    }
?>
