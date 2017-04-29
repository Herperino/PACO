<?php
    
    /**
     * This is the controller file for reviewing patients lab results
     * (labref)
     */
     
    require("../includes/config.php");
    
    $labresults = []; 
    $page_mode = false; //Defines whether the view will be displayed in Select or View mode
    
    
     //Get patients for the user
    /** Upon a GET request, the server will will then render the page in select mode
     *  then query the server for all the active patients under care by the user. 
     *  the query will be done via AJAX via patients.php. Once selection is done,
     *  a POST request will be done to this same controller.
     */
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        
        render("labref.php", ['P_MODE' => $page_mode]);
    }
     
    /**A server request will trigger a POST request to the server, meaning that
     * a patient was selected. For such selection, we will grab the patient id
     * and make a request to the server for the patients lab results in the 
     * database.
     * 
     * Along with that, we will also display the patient's name, age and sex.
     */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        //Gets the patient name for displaying.
        $patientID = $_POST['patientID'];
        $patientname = cs50::query("SELECT patientname FROM patients WHERE patientID = ?", $patientID);
        $name = ($patientname[0]['patientname']);
        $name = utf8_encode($name);
        
        //Gets patientID and userID
        $userID = $_SESSION['id'];
        
        //Query database for the patient's lab results given a userID and patient name
        $labresults =  cs50::query("SELECT * FROM labref WHERE patientID = ? ORDER BY Date ASC", $patientID);
        
        //TODO: Editar prescrições
        if($_POST['operation'] == 'PRESCRIPTION_ADD'){
            
            addPrescription($patientID);
            
        }
        else if ($_POST['operation'] == 'PRESCRIPTION_EDIT'){
            
            editPrescription($patientID);
            
        }
        else {
            
            //TODO make a JSON return of the prescriptions array.
            //(Will be used for rendering the forms adequately)
        }
        
        //Sets the page mode to display lab results rather than patients
        $page_mode = true;
        render("labref.php", ['P_MODE' => $page_mode, 'labresults' => $labresults, 'patientID' => $name]);
    }
     
?>