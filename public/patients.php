<?php

    /**
     * Controlador de informações de paciente
     */

    require("../includes/config.php");

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        $patients = Patient::showAllPatients($_SESSION['id']);
        header("Content-type: application/json; charset=UTF-8");
        print(html_entity_decode(json_encode($patients, JSON_PRETTY_PRINT)));
        exit();
    }

    else if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Recebe a operação e o ID do paciente
        $operation = $_POST['operation'] ?: 'nada';
        $uniqid = $_POST['uniqid'] ?: 'nenhum'; //Se for um novo paciente, uniqid é 'nenhum'
        $page = basename($_SERVER['HTTP_REFERER']);

        $paciente = Patient::restorePatient($uniqid);

        //Realiza funções no banco de dados conforme a
        switch($operation){
            case 'REMOVE':
                $paciente->remover();
                break;

            case 'STATUS':
                $paciente->changeStatus($patientID,$conn);
                break;

            case 'EDIT':
                $paciente->editPatient($patientID,$conn);
                break;

            case 'ADD':
                Patient::addPatient($conn);
                break;
            case 'RETRIEVE':

                $patients = Patient::showAllPatients($_SESSION['id']);
                header("Content-type: application/json; charset=UTF-8");
                print(html_entity_decode(json_encode($patients, JSON_PRETTY_PRINT)));
                break;
        }


        //Caso o resultado da execução das funções ocorra sem problemas, redireciona para a página original
        if ($result != false)
            redirect($page);
        else
            render("apology.php",['errormessage' => "O ID de paciente já está em uso"]);
    }
?>
