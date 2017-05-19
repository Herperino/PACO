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

    /**
     * Takes data from the server to display the prescription list
     * for a registered patient.
     *
     * It must be called within a table div.
     */
    function displayPrescription($prescriptions){

        print("<th>Paciente</th>");
        print("<th>Data</th>");
        print("<th colspan='13'>Medicamentos </th>");
            if (!empty($prescriptions)){
            foreach($prescriptions as $prescription){
                 $prescription = $sub = array_slice($prescription, 2, null, true); //Remove ID and userID from array

                print("<tr>");
                print("<td>" . $prescription["patientID"]. "</td>");
                print("<td>" . $prescription["date"]. "</td>");
                for($i = 1; $i <= 10; $i++){
                    if ($prescription["med".$i] == "null" || $prescription["pos".$i] = "null")
                      print("<td>"."</td>");
                    else
                      print("<td>". $prescription["med".$i] ." ". $prescription["pos".$i]."</td>");
                }
             print("<td>");
             print("<input  data-patient =" . (string)$prescription['patientID'] ."
                    data-operation = \"PRESCRIPTION_EDIT\"
                     data-timestamp ='" . (string)$prescription['date'] .
                    "' type = 'button' onClick = 'prescriptionHandler(this)'
                    class= 'btn btn-success' value='Editar Prescrição'/>"); print("</td></tr>");}}
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

                 if(strcmp($currentkey,"Date") == 0 || strcmp($currentkey,"patientID") == 0){
                      print("<td>". $item . "</td>");
                  }
                  else{

                    if($item !=  null)
                        print("<td>". $key. "<br> " .$item. "</td>");
                     else
                        print("<td>". $key. "<br> --</td>");
                  }
              }

              print("<td>");
              print("<input  data-patient = ". (string)$result['patientID'] .
                    "data-operation = 'LAB_EDIT'
                     data-timestamp ='" . (string)$result['Date'] .
                    "' type = 'button' onClick = 'labHandler(this, 'labref.php')'
                    class= 'btn btn-success' value='Editar resultados'/>");
              print("</td>"."</tr>");
            }
        }
    }
?>
