<?php
  require("../includes/config.php");

  //FALSE significa que a página é renderizada em modo de seleção de comentários
  //TRUE significa que a página é renderizada em modo de visualização de comentários
  $render_mode = FALSE; 

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Capturar dados referentes à prescrição via POST
    $assunto = ['timestamp' => $_POST['timestamp'],
                'patientid' => $_POST['patientid'],
                'userid' => $_SESSION['id']]

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
          $assunto['tipo'] = "LAB"                    
        }
        else if (strcmp($fonte,"acompanhamento.php") == 0){
          $assunto['tipo'] = "MED"
        }

        $comment = new Comment($tipo, $assunto,$) 
    }
    else if (strcmp($operation,'EDIT_COMMENT') == 0){      
  	
    }

    $render_mode = TRUE;
  }
  
  else{



  }
  

  //Renderiza a página conforme os parâmetros 
  render("notas.php", ['mode' => $render_mode]);

?>
