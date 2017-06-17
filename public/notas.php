<?php

  //Requer arquivos de configuração
  require("../includes/config.php");

  //Funções de escrita no banco de dados serão feitas via POST
  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Capturar dados pertinentes via POST    
    $operation = $_POST['operation'];
    $paciente = $_POST['patientid'];

    //Verifica as operações e as corrige de acordo
    switch($operation){

      case 'RETRIEVE':
        //Busca comentários no servidor referente à um paciente
        
        $dados = fetchData($paciente,$_SESSION['id']);

        header("Content-type: application/json; charset=UTF-8");
        print(json_encode($dados,JSON_PRETTY_PRINT));
        break;

      case 'COMMENT_THIS':
         
        //O assunto e conteúdo passados pela form são segurados nestas variáveis
        $assunto = $_POST['assunto'];
        $conteudo = $_POST['conteudo'];

        //Cria-se um objeto contendo o assunto e o conteúdo
        $comment = new Comment($assunto, $conteudo,$paciente);

        //Inclui-se o comentário no banco de dados
        $comment->databaseIt();
    
        break;

      case 'EDIT_COMMENT':
        $assunto = $_POST['assunto'];
        $conteudo = $_POST['conteudo'];


        break;      
    }
  }  
    
  
  //Chegou-se a página via GET. Visualização ocorrerá via GET
  else{

    //Renderiza a página 
    render("notas.php");
    exit();
  }
  
  //Volta para a página inicial se vier via POST
  redirect(basename($_SERVER['HTTP_REFERER']))
  

?>
