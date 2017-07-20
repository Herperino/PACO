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

        //Busca o nome do paciente no banco de dados
        $name = getName($conn);

        //Define patientID e userID a partir do POST
        $userID = $_SESSION['id'];
        $patientID = $_POST['patientID'] ?: '0';

        if ($_POST['operation'] != 'ACOMP')
            $uniqueID = $_POST['uniqid'];

        switch($_POST['operation']){

            case 'PRESCRIPTION_ADD':

            $prescription = new Prescription($patientID);
            $prescription->addPrescription($patientID,$conn);

            break;

            case 'PRESCRIPTION_EDIT':

            $prescription = Prescription::restorePrescription($_POST['uniqid']);
            $prescription->editPrescription($conn);

            break;

            case 'GET_PRESCRIPTION':

            $query = pg_query($conn, "SELECT * FROM public.\"prescriptions\" WHERE uniqid ='".$_POST['uniqid']."'");

            //If the query returns something
            if ($query != false){
              $last_prescription = pg_fetch_all($query);
              //makes a JSON return of the prescriptions array.
              header("Content-type: application/json; charset=UTF-8");
              print(json_encode($last_prescription, JSON_PRETTY_PRINT));
              exit();
            }
            break;
            case 'ALL_PRESCRIPTION':

            $query = pg_query($conn, "SELECT * FROM public.\"prescriptions\"
                                      WHERE \"patientID\" = '".$patientID."' AND \"userID\" = '".$userID."'
                                      ORDER BY \"date\" ASC;");

            if ($query != false){
              $prescriptions  = pg_fetch_all($query);
              //Faz um JSON da query
              header("Content-type: application/json; charset=UTF-8");
              print(json_encode($last_prescription, JSON_PRETTY_PRINT));
              exit();
            }

            break;

            case 'DELETE_PRESCRIPTION':

            //Remove uma linha do banco de dados que equivale ao uniqid
            @$query = pg_query($conn, "DELETE FROM public.\"prescriptions\" WHERE uniqid ='".$_POST['uniqid']."'");

            break;

        }//Fim do switch


        //Busca o banco de dados para um determinado paciente
        $query = pg_query($conn, "SELECT * FROM public.\"prescriptions\"
                                  WHERE \"patientID\" = '".$patientID."' AND \"userID\" = '".$userID."'
                                  ORDER BY \"date\" ASC;");
        $prescriptions = pg_fetch_all($query);


        //Exibe a página em modo de visualização de prescrições
        $page_mode = true;


        //Renderiza a página com os parâmetros passados
        render("acompanhamento.php", ['P_MODE' => $page_mode, 'prescriptions' => $prescriptions, 'patientID' => $name, 'P_ID' =>$patientID]);
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
