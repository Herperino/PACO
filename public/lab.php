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

        //Recebe nome e ID do paciente
        $patientID = isset($_POST['patientID'])?: '0';
        $paciente = Patient::restorePatient($patientID);

        $name = Patient::getName($conn);

        if($_POST['operation'] != 'ACOMP')
            $uniqid = $_POST['uniqid']; //Se a operação precisar de uniqid, o define;


        switch($_POST['operation']){


            case 'LAB_ADD':

            addResults($patientID,$conn);

            break;

            case 'LAB_EDIT' :

            editResults($conn);

            break;

            case 'DELETE_LAB':

            //Omite erros em caso de queries que os retornariam
            @$query = pg_query($conn, "DELETE FROM public.\"labref\" WHERE uniqid ='".$uniqid."'");

            break;

            case 'GET_LAB':

            $query = pg_query($conn, "SELECT * FROM public.\"labref\" WHERE uniqid ='".$uniqid."'");

            if ($query != false){
                $last_prescription = pg_fetch_all($query);
                //makes a JSON return of the prescriptions array.
                header("Content-type: application/json; charset=UTF-8");
                print(json_encode($last_prescription, JSON_PRETTY_PRINT));
                $token = true;
                exit();
            }

            break;

        }//Fim do switch

        //Busca o banco de dados para todos os resultados para aquele paciente
        $query =  pg_query($conn, "SELECT * FROM public.\"labref\" WHERE patientid = '".$patientID."' ORDER BY date ASC");
        $results = pg_fetch_all($query);

        //Renderiza a página
        $page_mode = true;
        render("lab.php", ['P_MODE' => $page_mode, 'labresults' => $results, 'patientID' => $name, 'P_ID' => $patientID]);

    }

    /** Upon a GET request, the server will will then render the page in select mode
     *  then query the server for all the active patients under care by the user.
     *  the query will be done via AJAX via patients.php. Once selection is done,
     *  a POST request will be done to this same controller.
     */
    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        render("lab.php", ['P_MODE' => $page_mode]);
    }
?>
