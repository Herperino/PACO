<?php

/** Final project helper file. Contains an assortment of tools that wil be needed to run the site */

function connect_db(){

    //nome do banco de dados
    $database = "da9ca7l565c2pg";

    //Abre uma conexão persistente com o banco de dados
    try{
      $conn = pg_pconnect("host=ec2-23-21-227-73.compute-1.amazonaws.com port=5432
      dbname=".$database."
      user=hypmpmdpmsubvi
      password=d4338194bb3376272ff09a413786ed3852229812b977259d5d4b5e7958c37c85
      sslmode=require");
    }
    catch(Exception $e){
      error_log($e);
      return;
    }
    return $conn;
}

/** Renders a view from POST requests to the server.
*  Used mostly for login and main transitions.
*  Taken from pset7 helpers.php, used for the same purpose
*/
function render($view, $values = [])
{

  // Se existe o view, exibe-o
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
    * Takes data from the server to display the result list
    * for a registered patient.
    *
    * It must be called within a table div.
    */
    function displayResults($labresults){
      print("<th>Paciente</th>" . "<th>Data</th>" . "<th colspan= '13'> Exames</th>");

      if(!empty($labresults)){
        foreach($labresults as $result){

          $labref = $sub = array_slice($result, 1, 14, true); //Removes index and userID from the array

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
                       data-uniqid ='" . (string)$result['uniqid'] ."'
                       type = 'button' onClick = 'console.log(this)'
                       class= 'btn btn-default' value='Editar Prescrição'>".
                        "<span class='glyphicon glyphicon-comment'></span>
              </button>


            </td>");

          print("<td>

              <button  data-patient =" . (string)$result['patientid'] ."
                       data-operation = \"LAB_EDIT\"
                       data-uniqid ='" . (string)$result['uniqid'] ."'
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
    function editResults($conn){

      //Checks for empty strings. ISe encontradas, são puladas.
      foreach ($_POST as $key => $value){

          if ($value != '' && $key != 'operation' && $key != 'patientID' )
            $query[$key] = $key ." = '". $value . "'";
      }

      //echo implode(' , ',$query)
      if(isset($query)){

      pg_query($conn,"UPDATE public.\"labref\" SET "

                    .implode(' , ',$query). //Implode keys do POST na query

                    " WHERE
                    \"uniqid\" = '".$_POST['uniqid']."'
                    ");
      }
      return true;
    }
?>
