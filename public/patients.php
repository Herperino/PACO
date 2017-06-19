<?php

    /**
     * Controlador de informações de paciente
     */

    require("../includes/config.php");

    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        //Busca o banco de dados pelo ID do usuário ordenando pela última modificação
        $query = "SELECT * FROM public.\"patients\" WHERE userid = '".$_SESSION['id']."' ORDER BY p_status DESC,lastactive DESC ";
        $data = pg_query($conn, $query);

        //Reúne pacientes em um array
        $patients = pg_fetch_all($data);

        //Retorna os pacientes como um objeton em notação Javascript (JSON)
        header("Content-type: application/json; charset=UTF-8");
        print(html_entity_decode(json_encode($patients, JSON_PRETTY_PRINT)));
        exit();
    }

    else if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Recebe a operação e o ID do paciente
        $operation = $_POST['operation'];
        $patientID = ltrim($_POST['patientID'],"0"); //Removes trailing zeroes
        $page = basename($_SERVER['HTTP_REFERER']);

        //Realiza funções no banco de dados conforme a 
        switch($operation){
            case 'REMOVE':
                pg_query($conn, "DELETE FROM public.\"patients\" WHERE patientid ='".$patientID."'");
                break;

            case 'STATUS':
                $result = changeStatus($patientID,$conn);
                break;             

            case 'EDIT':
                $result = editPatient($patientID,$conn);
                break;

            case 'ADD':
                $result = addPatient($conn);
                break;    
            case 'RETRIEVE'
                $query = "SELECT patientname FROM public.\"patients\" WHERE userid = '".$_SESSION['id']."' ORDER BY p_status DESC,lastactive DESC ";
                $data = pg_query($conn, $query);

                //Reúne pacientes em um array
                $patients = pg_fetch_all($data);

                header("Content-type: application/json; charset=UTF-8");
                print(json_encode($patients,JSON_PRETTY_PRINT));
                exit();
                break;
        }        
        

        //Caso o resultado da execução das funções ocorra sem problemas, redireciona para a página original 
        if ($result != false)
            redirect($page);
        else
            render("apology.php",['errormessage' => "O ID de paciente já está em uso"]);
    }
?>
