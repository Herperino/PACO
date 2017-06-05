<?php

    /**
     * This is the controller file for reviewing patients lab results
     * (labref)
     */

    require("../includes/config.php");

    //Defines whether the view will be displayed in Select or View mode (default = false --> view)
    $page_mode = false;

    /**A server request will trigger a POST request to the server, meaning that
     * a patient was selected. For such selection, we will grab the patient id
     * and make a request to the server for the patients lab results in the
     * database.
     *
     * Along with that, we will also display the patient's name, age and sex.
     */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Gets the patient name and ID for displaying.
        $name = getName($conn);
        $patientID = $_POST['patientID'];
        echo $_POST['operation'];

        if(strcmp($_POST['operation'],'LAB_ADD') == 0){

            addResults($patientID,$conn);
        }
        else if (strcmp($_POST['operation'],'LAB_EDIT') == 0){

            editResults($patientID,$conn);
        }
        else if(strcmp($_POST['operation'],'DELETE_LAB') == 0){
          //Adds a row ignoring errors or warnings if no row is matched
          $query = pg_query($conn, "DELETE FROM public.\"labref\" WHERE date ='".$_POST['timestamp']."'");
        }
        else if(strcmp($_POST['operation'],'GET_LAB')){
          //Gets the last result as an array and return as a json object
          $query = pg_query($conn, "SELECT * FROM public.\"labref\" WHERE date ='".$_POST['timestamp']."'");

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

        //Query database for the patient's all lab results given a patientID
        $query =  pg_query($conn, "SELECT * FROM public.\"labref\" WHERE patientid = '".$patientID."' ORDER BY date ASC");
        $results = pg_fetch_all($query);

        //Sets the page mode to display lab results rather than patients
        $page_mode = true;
        render("labref.php", ['P_MODE' => $page_mode, 'labresults' => $results, 'patientID' => $name, 'P_ID' => $patientID]);
    }

    /** Upon a GET request, the server will will then render the page in select mode
     *  then query the server for all the active patients under care by the user.
     *  the query will be done via AJAX via patients.php. Once selection is done,
     *  a POST request will be done to this same controller.
     */
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {

        render("labref.php", ['P_MODE' => $page_mode]);
    }
?>
