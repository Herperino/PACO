<?php

    /**
     * This is the controller file for reviewing patients lab results
     * (labref)
     */

    require("../includes/config.php");

    $page_mode = false; //Defines whether the view will be displayed in Select or View mode (default = false --> view)

    /**A server request will trigger a POST request to the server, meaning that
     * a patient was selected. For such selection, we will grab the patient id
     * and make a request to the server for the patients lab results in the
     * database.
     *
     * Along with that, we will also display the patient's name, age and sex.
     */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Gets the patient name for displaying.
        $name = getName();

        //Query database for the patient's all lab results given a patientID
        $query =  pg_query("SELECT * FROM labref WHERE patientID = ? ORDER BY Date ASC", $patientID);
        $results = pg_fetch_all($query);

        if(strcmp($_POST['operation'],'LAB_ADD') == 0){

            addResult($patientID);

        }
        else if (strcmp($_POST['operation'],'LAB_EDIT') == 0){

            editResult($patientID);

        }
        
        //Sets the page mode to display lab results rather than patients
        $page_mode = true;
        render("labref.php", ['P_MODE' => $page_mode, 'labresults' => $labresults, 'patientID' => $name]);
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
