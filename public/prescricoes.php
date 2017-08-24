<?php

    /** Controlador prescições **/

    require("../includes/config.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Define patientID e userID a partir do POST
        $userID = $_SESSION['id'];

        //Uniqid pode ser do paciente ou da prescrição conforme a operação
        $uniqid = $_POST['uniqid'] ?: '0';

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

              $prescriptions = Prescription::fetchAllPrescriptions($uniqid,$userID);

              //Faz um JSON da query
              header("Content-type: application/json; charset=UTF-8");
              print(json_encode($prescriptions, JSON_PRETTY_PRINT));
              exit();

            break;

            case 'DELETE_PRESCRIPTION':

              //Remove uma linha do banco de dados que equivale ao uniqid
              @$query = pg_query($conn, "DELETE FROM public.\"prescriptions\" WHERE uniqid ='".$_POST['uniqid']."'");

            break;

            default:  

              //Busca o banco de dados para um determinado paciente
              $prescriptions = Prescription::fetchAllPrescriptions($uniqid,$userID);

              //Exibe a página em modo de visualização de prescrições
              $ver_prescrições = true;

              //Renderiza a página com os parâmetros passados
              render("prescricoes.php", ['P_MODE' => $ver_prescriçoes, 'prescriptions' => Prescription::displayPrescription($prescriptions), 'P_ID' =>$patientID]);

            break;

        }//Fim do switch        

        
    }

    /** Upon a GET request, the server will will then render the page in select mode
     *  then query the server for all the active patients under care by the user.
     *  the query will be done via AJAX via patients.php. Once selection is done,
     *  a POST request will be done to this same controller.
     */

    if($_SERVER['REQUEST_METHOD'] == 'GET')
    {
       //Renders acompanhamento in view mode
        $ver_prescrições = false; //Defines whether the view will be displayed in Select or View mode
        render("prescricoes.php", ['P_MODE' => $ver_prescrições]);
    }
?>
