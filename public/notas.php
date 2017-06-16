<?php

  //Requer arquivos de configuração
  require("../includes/config.php");

  //Funções de escrita no banco de dados serão feitas via POST
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Capturar dados referentes à prescrição via POST
   
    //Informações relevantes de servidor
    $fonte = $_SERVER['HTTP_REFERER'];
    $operation = $_POST['operation'];


    //Verifica as operações e as corrige de acordo
    switch($operation){

      case 'RETRIEVE':
        //Busca comentários no servidor referente à um paciente
        $dados = fetchData($assunto['patientid'], $assunto['userid']);

        header("Content-type: application/json; charset=UTF-8");
        print(json_encode($dados,JSON_PRETTY_PRINT));
        break;

      case 'COMMENT_THIS':
        if (strcmp($fonte,"labref.php") == 0){
          $assunto['tipo'] = "LAB" ;                   
        }
        else if (strcmp($fonte,"acompanhamento.php") == 0){
          $assunto['tipo'] = "MED";
        }
        $comment = new Comment($tipo, $assunto);
    
        break;

      case 'EDIT_COMMENT':
        # code...
        break;      
    }
  }  
    
  
  //Chegou-se a página via GET. Visualização ocorrerá via GET
  else{

    $comment = new Comment("Mamilos", "Mamilos são muito polêmicos");

    //Renderiza a página conforme os parâmetros 
    render("notas.php", ['comment' =>$comment]);
    exit();
  }
  
  //Volta para a página inicial se vier via POST
  redirect(basename($_SERVER['HTTP_REFERER']))
  

?>
