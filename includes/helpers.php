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

/**
* Redirects user to location, which can be a URL or
* a relative path on the local host.
*
* http://stackoverflow.com/a/25643550/5156190
*
* Because this function outputs an HTTP header, it
* must be called before caller outputs any HTML.
*/
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
                       data-operation = \"PRESCRIPTION_EDIT\"
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

          print("<td>");
          print("<input  data-patient =" . (string)$result['patientid'] ."
          data-operation = \"PRESCRIPTION_EDIT\"
          data-timestamp ='" . (string)$result['date'] .
          "' type = 'button' onClick = 'labHandler(this)'
          class= 'btn btn-success' value='Editar Prescrição'/>");
          print("</td></tr></div>");
        }
      }
    }

    /** -------------------------------------------------------------------------------------------*/
    /**           FROM HERE ON ARE FUNCTIONS THAT WORK ON THE DATABASE                             */
    /** -------------------------------------------------------------------------------------------*/

    /** Adds prescription values into the database. Requires a connection for pg_query */
    function addPrescription($patientID,$conn){

      //Concatenates the prescription data into a single k/v array
      for ($i = 1; $i<=10; $i++){

        $currentM = "med" . $i;
        $currentD = "dos" . $i;
        $currentV = "via" . $i;
        $currentP = "pos" . $i;

        $prescriptions[$currentM] = $_POST[$currentM] ." ". $_POST[$currentD] ." ". $_POST[$currentV] ;
        $prescriptions[$currentP] = $_POST[$currentP];

      }

      pg_query($conn,"INSERT INTO public.\"prescriptions\"(\"patientID\",\"userID\", \"med1\",\"pos1\",\"med2\",\"pos2\",\"med3\"
        ,\"pos3\",\"med4\",\"pos4\",\"med5\",\"pos5\",
        \"med6\",\"pos6\",\"med7\",\"pos7\",\"med8\",
        \"pos8\",\"med9\",\"pos9\",\"med10\",\"pos10\")
        VALUES ('".$patientID."','".$_SESSION['id']."',
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

      function addResults($patientID,$conn){

        //Concatenates the prescription data into a single k/v array

        pg_query($conn,"INSERT INTO public.\"labref\"(\"patientid\",\"userid\",
                                                      \"hgb\",\"hemacias\", \"hct\",
                                                      \"ureia\",\"cr\",\"k\")
          VALUES ('".$patientID."','".$_SESSION['id']."', '".$_POST['hgb']."',
                  '".$_POST['hemacias']."','".$_POST['hct']."','".$_POST['ureia']."',
                  '".$_POST['cr']."','".$_POST['k']."')");

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

        pg_query("UPDATE public.\"prescriptions\" SET date = date,
          \"med1\" = '".$prescriptions['med1']."',\"pos1\" = '".$prescriptions['pos1']."',
          \"med2\" = '".$prescriptions['med2']."',\"pos2\" = '".$prescriptions['pos2']."',
          \"med3\" = '".$prescriptions['med3']."',\"pos3\" = '".$prescriptions['pos3']."',
          \"med4\" = '".$prescriptions['med4']."',\"pos4\" = '".$prescriptions['pos4']."',
          \"med5\" = '".$prescriptions['med5']."',\"pos5\" = '".$prescriptions['pos5']."',
          \"med6\" = '".$prescriptions['med6']."',\"pos6\" = '".$prescriptions['pos6']."',
          \"med7\" = '".$prescriptions['med7']."',\"pos7\" = '".$prescriptions['pos7']."',
          \"med8\" = '".$prescriptions['med8']."',\"pos8\" = '".$prescriptions['pos8']."',
          \"med9\" = '".$prescriptions['med9']."',\"pos9\" = '".$prescriptions['pos9']."',
          \"med10\" = '".$prescriptions['med10']."',\"pos10\" = '".$prescriptions['pos10']."'
          WHERE \"date\" = '".$_POST['date']."' AND
          \"userID\" = '".$_SESSION['id']."'
          AND \"patientID\" ='".$patientID."'");
        }

        /** Queries the database in order to get the current patient name for a given user **/
        function getName($conn){

          //Gets the patient ID
          $patientID = $_POST['patientID'];

          //The query
          $query = pg_query($conn, "SELECT patientname FROM public.\"patients\" WHERE
            patientid = '".$patientID."' AND userid = '".$_SESSION['id']."'");
            $patients = pg_fetch_all($query);

            $name = ($patients[0]['patientname']);

            return $name;
          }

          /** PatientID, database connection -> NULL
          *  Gets a given patient ID and changes its status in the
          *  database row corresponding to the patient under care
          *  A connection to the database must be passed
          */
          function changeStatus($patientID, $conn){

            //Get patient and it's status from the database
            $query = "SELECT * FROM public.\"patients\" WHERE patientid = '" . $patientID ."'";
            $res = pg_query($conn, $query);

            //Fetches the single row for the user found
            $patient = pg_fetch_row($res);
            $status = $patient[6]; //6 = p_status

            //Change status given current status state
            if($status == 1) { pg_query($conn,"UPDATE public.\"patients\" SET p_status = 0 WHERE patientid = '".$patientID."'");}
            else { pg_query($conn, "UPDATE public.\"patients\" SET p_status = 1 WHERE patientid ='".$patientID."'");}
          }

          /** PatientID, database connection -> NULL
          *  This part of the function will take patients new ID, name and age.
          *  Query patients, lab and prescriptions to make changes to the user ID
          *  or else it will fuck all the databases(prescriptions and labref)
          *  A connection to the database must be passed
          */
          function editPatient($patientID, $conn){

          //Edit the patient to contain html supported chars.
          $pname = htmlspecialchars($_POST['patient_name']);

          //If the patient ID remains the same
          if ($_POST['new_id'] == $_POST['patientID']){
            pg_query($conn,"UPDATE public.\"patients\" SET
              patientname ='". $pname ."',
              patientage = ". $_POST['patient_age'] ."
              WHERE patientid = '". $_POST['patientID'] ."'");
            }
            //If the patientID changes.
            else{
              $new_id = $_POST['new_id'];

              //Here be queries updating the new ID into patients, labref and prescriptions
              pg_query($conn,"UPDATE public.\"patients\" SET
                patientid = '". $new_id ."',
                patientname = '". $pname ."',
                patientage = ". $_POST['patient_age'] ."
                WHERE patientid = '".$_POST['patientID'] ."'");

                pg_query($conn,"UPDATE public.\"prescriptions\" SET
                  patientid = '". $new_id."'
                  WHERE patientid ='". $_POST['patientID']."'");

                  pg_query($conn,"UPDATE public.\"labref\" SET
                    patientid = '". $new_id."'
                    WHERE patientid ='". $_POST['patientID']."'");
                  }
                }

                /** PatientID, page -> NULL
                *  Adds a new patient to the database of patients
                */
    function addPatient($conn){
        //TODO: Work on validation. No two ids should be the same for the same user.

        $patientname = $_POST['patient_name'];
        $patientage = $_POST['patient_age'];
        $patientID = ltrim($_POST['new_id'],"0");
        $userID = $_SESSION['id'];

        //Insert a new patient into the patients database
        pg_query($conn,"INSERT INTO public.\"patients\"(id,patientid, patientname, patientage,userid, p_status)
        values (DEFAULT,'". $patientID."','".$patientname."','".$patientage."','".$userID."', '1')");
        }

?>
