<?php

  //Requer arquivos de configuração
  require("../includes/config.php");

  //Funções de escrita no banco de dados serão feitas via POST
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Capturar dados referentes à prescrição via POST
    $assunto = ['timestamp' => $_POST['timestamp'],
                'patientid' => $_POST['patientid'],
                'userid' => $_SESSION['id']];

    $fonte = $_SERVER['HTTP_REFERER'];

    //Condensa estes dados em um array para passar para função
    if (strcmp($operation,'RETRIEVE') == 0){

      //Busca comentários no servidor referente à um paciente
      $dados = fetchData($assunto['patientid'], $assunto['userid']);

      header("Content-type: application/json; charset=UTF-8");
      print(json_encode($dados,JSON_PRETTY_PRINT));
    }
    else if (strcmp($operation,'COMMENT_THIS') == 0){

        if (strcmp($fonte,"labref.php") == 0){
          $assunto['tipo'] = "LAB" ;                   
        }
        else if (strcmp($fonte,"acompanhamento.php") == 0){
          $assunto['tipo'] = "MED";
        }

        $comment = new Comment($tipo, $assunto);
    }
    else if (strcmp($operation,'EDIT_COMMENT') == 0){      
  	
    }
  }
  
  //Chegou-se a página via GET. Visualização ocorrerá via GET
  else{

    //Renderiza a página conforme os parâmetros 
    render("notas.php");
    exit();
  }
  
  //Volta para a página inicial se vier via POST
  redirect(basename($_SERVER['HTTP_REFERER']))
  

?>
