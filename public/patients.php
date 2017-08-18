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
        $uniqid = isset($_POST['uniqid']) ? $_POST['uniqid'] : 'nenhum'; //Se for um novo paciente, uniqid é 'nenhum'
        $page = basename($_SERVER['HTTP_REFERER']);

        $paciente = Patient::restorePatient($uniqid);

        //Realiza funções no banco de dados conforme a
        switch($operation){
            case 'REMOVE':
                $paciente->remover();
                break;

            case 'STATUS':
                $paciente->changeStatus();
                break;

            case 'EDIT':
                $params['nome'] = $_POST['patient_name'];
                $params['idade'] = $_POST['patient_age'];
                $params['id'] = $_POST['new_id'];

                $paciente->editPatient($params);
                break;

            case 'ADD':
                $paciente = Patient::addPatient($conn);
                $paciente->databaseIt();
                break;
        }


        //Caso o resultado da execução das funções ocorra sem problemas, redireciona para a página original
        if ($paciente != false)
            redirect($page);
        else
            render("apology.php",['errormessage' => "O ID de paciente já está em uso"]);
    }
?>
