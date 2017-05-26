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

        //Gets the patient name and ID for displaying.
        $name = getName($conn);
        $patientID = "\n".$_POST['patientID'];

        //token for displaying prescriptions correctly
        $token = false;

        //Gets patientID and userID
        $userID = $_SESSION['id'];

        if(strcmp($_POST['operation'],'PRESCRIPTION_ADD')==0){
            addPrescription($patientID,$conn);
            $token = true;
        }
        else if (strcmp($_POST['operation'],'PRESCRIPTION_EDIT')==0){

            editPrescription($patientID, $conn);
            $token = true;
        }
        else if(strcmp($_POST['operation'],'GET_PRESCRIPTION')==0){

            $query = pg_query($conn, "SELECT * FROM public.\"prescriptions\" WHERE date ='".$_POST['date']."'");

            //If the query returns something
            if ($query != false){
              $last_prescription = pg_fetch_all($query);
              //makes a JSON return of the prescriptions array.
              header("Content-type: application/json; charset=UTF-8");
              print(json_encode($last_prescription, JSON_PRETTY_PRINT));
              $token = true;
              exit();
            }
        }
        else if(strcmp($_POST['operation'],'DELETE_PRESCRIPTION')==0){
          //Deletes selected prescription. Ignores warnings if not on delete function
          @$query = pg_query($conn, "DELETE FROM public.\"prescriptions\" WHERE date ='".$_POST['timestamp']."'");
        }

        //Query database for the patient's prescriptions given a userID and patient name
        $query = pg_query($conn, "SELECT * FROM public.\"prescriptions\"
                                  WHERE \"patientID\" = '".$patientID."' AND \"userID\" = '".$userID."'
                                  ORDER BY \"date\" ASC;");
        $prescriptions = pg_fetch_all($query);

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
?>
