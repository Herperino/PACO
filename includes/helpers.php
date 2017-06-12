<?php

/** Final project helper file. Contains an assortment of tools that wil be needed to run the site */

function connect_db(){

  //database name
  $database = "da9ca7l565c2pg";

  $conn = 0;

  //try to open a persistent connection with DB
  try{
    $conn = pg_pconnect("host=ec2-23-21-227-73.compute-1.amazonaws.com port=5432
    dbname=".$database."
    user=hypmpmdpmsubvi
    password=d4338194bb3376272ff09a413786ed3852229812b977259d5d4b5e7958c37c85
    sslmode=require");
  }
  catch(Exception $e){
    error_log($e);
  }
  return $conn;
}

/** Renders a view from POST requests to the server.
*  Used mostly for login and main transitions.
*  Taken from pset7 helpers.php, used for the same purpose
*/
function render($view, $values = [])
{


  // if view exists, render it
  if (file_exists("../views/{$view}"))
  {
    // extract variables into local scope
    extract($values);

    // render view (between header and footer)
    require("../views/pagetop.php");
    require("../views/{$view}");
    require("../views/pagebottom.php");
    exit;
  }

  // else err
  else
  {
    trigger_error("Invalid view: {$view}", E_USER_ERROR);
  }
}

/** Stupid ass UTF8 won't accept my brazilian letters */

function utf8_string_array_encode(&$array){
  $func = function(&$value,&$key){
    if(is_string($value)){
      $value = utf8_encode($value);
    }
    if(is_string($key)){
      $key = utf8_encode($key);
    }
    if(is_array($value)){
      utf8_string_array_encode($value);
    }
  };
  array_walk($array,$func);
  return $array;
}

/**--------------------------------------
* Redirects user to location, which can be a URL or
* a relative path on the local host.
*
* http://stackoverflow.com/a/25643550/5156190
*
* Because this function outputs an HTTP header, it
* must be called before caller outputs any HTML.
* --------------------------------------*/

function redirect($location)
{
  if (headers_sent($file, $line))
  {
    trigger_error("HTTP headers already sent at {$file}:{$line}", E_USER_ERROR);
  }
  header("Location: {$location}");
  exit;
}

/* ----------------------------------------------------------------------------------------------*/
/**             FROM HERE ON THERE ARE FUNCTIONS THAT HELP DATA TO BE DISPLAYED                  */
/* --------------------------------------------------------------------------------------------- */
/**
* Takes data from the server to display the prescription list
* for a registered patient.
*
* It must be called within a table div.
*/
function displayPrescription($prescriptions){
  print ( "<th>Paciente</th>".
  "<th>Data</th>".
  "<th colspan='13'>Medicamentos </th>");
  if (!empty($prescriptions)){
    foreach($prescriptions as $prescription){
      $prescription = $sub = array_slice($prescription, 2, null, true); //Remove ID and userID from array

      print("<tr>".
      "<td>" .
      $prescription["patientID"].
      "</td>".
      "<td>" .
      $prescription["date"].
      "</td>");
      for($i = 1; $i <= 10; $i++){
        if (strcmp($prescription["med".$i]," 1x/d") < 0)
        print("<td>"."</td>");
        else
        print("<td>". $prescription["med".$i] ."&nbsp". $prescription["pos".$i]."</td>");
      }
      print("<td>

              <button  data-patient =" . (string)$prescription['patientID'] ."
                       data-operation = \"COMMENT_THIS\"
                       data-timestamp ='" . (string)$prescription['date'] ."' 
                       type = 'button' onClick = 'console.log(this)'
                       class= 'btn btn-default' value='Editar Prescrição'>".
                        "<span class='glyphicon glyphicon-comment'></span>
              </button>   


            </td>");
      print("<td>
              
              <button  data-patient =" . (string)$prescription['patientID'] ."
                       data-operation = \"PRESCRIPTION_EDIT\"
                       data-timestamp ='" . (string)$prescription['date'] ."' 
                       type = 'button' onClick = 'prescriptionHandler(this)'
                       class= 'btn btn-default' value='Editar Prescrição'>".
                        "<span class='glyphicon glyphicon-pencil'></span></button>"); 
      print("</td></tr></div>");}}
    }

    /**
    * Takes data from the server to display the result list
    * for a registered patient.
    *
    * It must be called within a table div.
    */
    function displayResults($labresults){
      print("<th>Paciente</th>" . "<th>Data</th>" . "<th colspan= '13'> Exames</th>");

      if(!empty($labresults)){
        foreach($labresults as $result){

          $labref = $sub = array_slice($result, 1, null, true); //Removes index and userID from the array

          print("<tr>");

          foreach ($labref as $key=>$item){

            $currentkey = $key;

            if(strcmp($currentkey,"date") == 0 || strcmp($currentkey,"patientid") == 0){
              print("<td>". $item . "</td>");
            }
            else if (strcmp($currentkey,"userid") == 0){
              continue;
            }
            else{

              if($item !=  null)
              print("<td>". $key. "<br> " .$item. "</td>");
              else
              print("<td>". $key. "<br> -- </td>");
            }
          }

          print("<td>

              <button  data-patient =" . (string)$result['patientid'] ."
                       data-operation = \"COMMENT_THIS\"
                       data-timestamp ='" . (string)$result['date'] ."' 
                       type = 'button' onClick = 'console.log(this)'
                       class= 'btn btn-default' value='Editar Prescrição'>".
                        "<span class='glyphicon glyphicon-comment'></span>
              </button>   


            </td>");

          print("<td>
              
              <button  data-patient =" . (string)$result['patientid'] ."
                       data-operation = \"LAB_EDIT\"
                       data-timestamp ='" . (string)$result['date'] ."' 
                       type = 'button' onClick = 'labHandler(this)'
                       class= 'btn btn-default' value='Editar Prescrição'>".
                        "<span class='glyphicon glyphicon-pencil'></span></button>"); 

          
          print("</td></tr></div>");
        }
      }
    }

    /** -------------------------------------------------------------------------------------------*/
    /**           FROM HERE ON ARE FUNCTIONS THAT WORK ON THE DATABASE                             */
    /** -------------------------------------------------------------------------------------------*/

  /** --------------------------------------------------------
  *   Inclui prescrições no banco de dados 
  *   
  *   $patientID é recebido do lado do cliente
  *   $conn é a conexão padrão definida.
  *
  *   $conn é necessária para o funcionamento do query
  *-----------------------------------------------------------*/
  function addPrescription($patientID,$conn){

    //Concatena cada entrada em uma única entrada medicamento + posologia
    for ($i = 1; $i<=10; $i++){

      $currentM = "med" . $i;
      $currentD = "dos" . $i;
      $currentV = "via" . $i;
      $currentP = "pos" . $i;

      $prescriptions[$currentM] = $_POST[$currentM] ." ". $_POST[$currentD] ." ". $_POST[$currentV] ;
      $prescriptions[$currentP] = $_POST[$currentP];

    }

    //Cria uma ID unica para cada prescrição inserida
    $uniqueID = uniqid("med");

    //Query que inclui a informação no banco de dados
    pg_query($conn,"INSERT INTO public.\"prescriptions\"(\"uniqid\",\"patientID\",\"userID\", 
      \"med1\",\"pos1\",\"med2\",\"pos2\",\"med3\",
      \"pos3\",\"med4\",\"pos4\",\"med5\",\"pos5\",
      \"med6\",\"pos6\",\"med7\",\"pos7\",\"med8\",
      \"pos8\",\"med9\",\"pos9\",\"med10\",\"pos10\")
      VALUES ('".$uniqueID."','".$patientID."','".$_SESSION['id']."',
      '".$prescriptions['med1']."','".$prescriptions['pos1']."',
      '".$prescriptions['med2']."','".$prescriptions['pos2']."',
      '".$prescriptions['med3']."','".$prescriptions['pos3']."',
      '".$prescriptions['med4']."','".$prescriptions['pos4']."',
      '".$prescriptions['med5']."','".$prescriptions['pos5']."',
      '".$prescriptions['med6']."','".$prescriptions['pos6']."',
      '".$prescriptions['med7']."','".$prescriptions['pos7']."',
      '".$prescriptions['med8']."','".$prescriptions['pos8']."',
      '".$prescriptions['med9']."','".$prescriptions['pos9']."',
      '".$prescriptions['med10']."','".$prescriptions['pos10']."')  ");

    }


    /** Adds lab result values into the database. 
    *   
    *   $patientID is a valid ID received from the client-side
    *   $conn is defined in config. Passed on due variable scope.
    *
    *   Requires a connection($conn) for pg_query */

      function addResults($patientID,$conn){

        //Avalia se existem entradas vazias. Se exisitirem, são ignoradas.
        foreach ($_POST as $key => $value){

            if ($key == '')
              $key = 0;
        }

        //Cria um ID único para cada resultado laboratorial
        $uniqueID = uniqid("lab");        

        pg_query($conn,"INSERT INTO public.\"labref\"(\"uniqid\",\"patientid\",\"userid\",
                                                      \"hgb\",\"hemacias\", \"hct\",
                                                      \"ureia\",\"cr\",\"k\",\"na\",
                                                      \"leuco\",\"inr\",\"pcr\",\"tgo&tgp\", 
                                                      \"outros\")
          VALUES ('".$uniqueID."','".$patientID."','".$_SESSION['id']."', '".$_POST['hgb']."',
                  '".$_POST['hemacias']."','".$_POST['hct']."','".$_POST['ureia']."',
                  '".$_POST['cr']."','".$_POST['k']."','".$_POST['na']."','".$_POST['leuco']."',
                  '".$_POST['inr']."','".$_POST['pcr']."','".$_POST['tgo&tgp']."', '".$_POST['outros']."')");

        }

      //Edits labs results
      function editResults($patientID,$conn){

        //Checks for empty strings. ISe encontradas, são puladas.
        foreach ($_POST as $key => $value){

            if ($value != '' && $key != 'operation' && $key != 'patientID' )
              $query[$key] = $key ." = '". $value . "'";
        }

        //echo implode(' , ',$query)

        pg_query($conn,"UPDATE public.\"labref\" SET "
                      
                      .implode(' , ',$query). //Implode keys do POST na query

                      " WHERE 
                      \"date\" = '".$_POST['date']."' AND
                      \"userid\" = '".$_SESSION['id']."'AND 
                      \"patientid\" ='".$patientID."'
                      ");

        }        
      /** Edits prescription data in the database. Requires a connection to be passed for pg_query */

      function editPrescription($patientID,$conn){

        //Concatenates the prescription data into a single k/v array
        for ($i = 1; $i<=10; $i++){

          $currentM = "med" . $i;
          $currentD = "dos" . $i;
          $currentV = "via" . $i;
          $currentP = "pos" . $i;

          $prescriptions[$currentM] = $_POST[$currentM] ." ". $_POST[$currentD] ." ". $_POST[$currentV] ;
          $prescriptions[$currentP] = $_POST[$currentP];
        }

        foreach ($prescriptions as $key => $value){

            $query[$key] = $key . " = '" . $value ."'";

        }

        pg_query("UPDATE public.\"prescriptions\" SET date = date, "
            
            . implode(' , ', $query) .

          " WHERE \"date\" = '".$_POST['date']."' AND
                 \"userID\" = '".$_SESSION['id']."' AND 
                 \"patientID\" ='".$patientID."'");
        }

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /**  --------------------------Funções de paciente ------------------------------*/

  /**----------------------------------------------- 
  *  Inclui um novo paciente no banco de dados.
  *  Informações do paciente, como $patientage e $patientID são oriundas
  *  do formulário enviado por POST.
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  function addPatient($conn){
      
      //Variáveis obtidas via POST
      $patientname = $_POST['patient_name'];
      $patientage = $_POST['patient_age'];
      $patientID = ltrim($_POST['new_id'],"0"); //Remove 'trailing zeroes'
      $userID = $_SESSION['id'];

      $collision = checkCollision($patientID, "patients");

      if ($collision == TRUE)
        return false; //Retorna falso em caso de colisão

      else{

        //Insere um novo paciente no banco de dados
        pg_query($conn,"INSERT INTO public.\"patients\"(id,patientid, patientname, patientage,userid, p_status)
        values (DEFAULT,'". $patientID."','".$patientname."','".$patientage."','".$userID."', '1')");
      }

      return true; //Retorna TRUE se a inserção ocorrer com sucesso
  }

  /**----------------------------------------------- 
  *  Inclui um altera os dados de paciente no banco de dados.
  *  Informações do paciente, como $patientage e $patientID são oriundas
  *  do formulário enviado por POST.
  *
  *  Também são feitas alterações no banco de dados onde o ID de paciente
  *  é utilizado (prescriptions e labref)
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  function editPatient($patientID, $conn){

    //Se o ID de paciente é mantido, atualiza seus dados
    if ($_POST['new_id'] == $_POST['patientID']){
        pg_query($conn,"UPDATE public.\"patients\" SET
          patientname ='". $pname ."',
          patientage = ". $_POST['patient_age'] ."
          WHERE patientid = '". $_POST['patientID'] ."'
          AND userid = '".$_SESSION['id']."'");
    }

    //Se o ID de paciente é diferente, atualiza as tabelas
    else{
      $new_id = $_POST['new_id'];

      //Verifica se há colisão entre o novo ID com os ids no banco de dados
      $collision = checkCollision($new_id, "patients");

      if ($collision == TRUE) //Se houve colisão de IDs
        return false; //Retorna FALSE se não tiver sucesso em alterar o conteúdo

      else{ //Se não houver colisão de IDs

        //As queries para atualização de patients, prescriptions e labref
        pg_query($conn,"UPDATE public.\"patients\" SET
          patientid = '". $new_id ."',
          patientname = '". $pname ."',
          patientage = ". $_POST['patient_age'] ."
          WHERE patientid = '".$_POST['patientID'] ."' 
          AND userid = '".$_SESSION['id']."'");

        pg_query($conn,"UPDATE public.\"prescriptions\" SET
          patientid = '". $new_id."'
          WHERE patientid ='". $_POST['patientID']."'
          AND \"userID\" = '".$_SESSION['id']."'");

        pg_query($conn,"UPDATE public.\"labref\" SET
          patientid = '". $new_id."'
          WHERE patientid ='". $_POST['patientID']."'
          AND userid = '".$_SESSION['id']."'");
      }
      
    }

    return true; //Retorna TRUE se tiver sucesso em alterar o conteúdo
  }

  /**----------------------------------------------- 
  *  Altera o status de acompanhamento de paciente no banco de dados.
  *  Informações do paciente são passadas por $patientID.
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  function changeStatus($patientID, $conn){

    //Busca o paciente no banco de dados
    $query = "SELECT * FROM public.\"patients\" 
              WHERE patientid = '" . $patientID ."'
              AND userid = '".$_SESSION['id']."'";

    $res = pg_query($conn, $query);

    //Seleciona a linha contendo o paciente
    $patient = pg_fetch_row($res);
    $status = $patient[6]; //6 = p_status

    //Altera o status conforme o status atual
    if($status == 1) { 

      pg_query($conn,"UPDATE public.\"patients\" 
                      SET p_status = 0 
                      WHERE patientid = '".$patientID."' 
                      AND userid = '".$_SESSION['id']."'");
    }
    else { 

      pg_query($conn,"UPDATE public.\"patients\" 
                      SET p_status = 1
                      WHERE patientid = '".$patientID."' 
                      AND userid = '".$_SESSION['id']."'");
    }

  }


  /** --------------------------------------------------------------- 
  *  Avalia o banco de dados afim de obter o nome de um paciente
  * 
  *  Requer uma conexão ativa ($conn) com o banco de dados.
  *-----------------------------------------------------------------*/
  function getName($conn){

    //Recebe o ID de paciente via POST
    $patientID = $_POST['patientID'];

    //A query
    $query = pg_query($conn, "SELECT patientname FROM public.\"patients\" WHERE
              patientid = '".$patientID."' AND userid = '".$_SESSION['id']."'");

    $patients = pg_fetch_all($query); //Obtem a linha que contém o paciente

    $name = ($patients[0]['patientname']); //Obtem a coluna com o nome do paciente

    return $name;
  }  

  /**------------------------------------
  *  Verifica se ocorre uma colisão entre um id que pretende
  *  ser incluído ou alterado e um id já existente no banco de
  *  dados
  *  
  * $id é o ID a ser verificado e $table é uma string com o nome da tabela  
  *----------------------------------------*/
  function checkCollision($id, $table){

    //Verifica se há colisão de IDs
    $check = pg_query("SELECT * FROM public.\"".$table."\"
                       WHERE patientid = '".$id."' 
                       AND userid = '".$_SESSION['id']."'");

    $collision = pg_fetch_all($check); //TRUE se o tamanho do array retornado é maior que 1;

    return $collision != false;
  }

  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /** ------------------- Funções de Comentário------------------*/

  /**
  * /---------------------------------------------
  * Recebe dados de paciente do servidor como $source
  * Deve ser receber:
  * patientID, userID
  * Desta fonte, a função deve retornar um array com os dados relevantes.
  * ---------------------------------------------*/
  function fetchData($patientID, $userID){
    //TODO


    
  }

  /**
  * /--------------------------------------------
  * Insere um comentário no banco de dados
  *
  * $subject é um objeto criado pela operação comment_this  
  * Como estrutura de dados, $comment deve seguir o esquema de:
  * AUTHOR, CONTENT, timestamp
  * --------------------------------------------*/
  function addComment($subject, $comment){
    //TODO
    //Create a template for comments
  }

  //Edita ou deleta um comentário
  function editComment($commentID, $operation){
    //TODO

  }    

?>
