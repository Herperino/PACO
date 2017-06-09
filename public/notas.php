<?php
  require("../includes/config.php");

  //FALSE significa que a página é renderizada em modo de seleção de comentários
  //TRUE significa que a página é renderizada em modo de visualização de comentários
  $render_mode = FALSE; 

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

  	$render_mode = TRUE;

  }
  
  else{



  }
  

  //Renderiza a página conforme os parâmetros 
  render("notas.php", ['mode' => $render_mode]);

?>
