<?php
  require("../includes/config.php");
  render("notas.php");


  /** ------------------- Prototyping ---------------------*/

  /**
  * /---------------------------------------------
  * Retrieves data from the server regarding a given patient
  * Source as a data structure should be consisted of:
  * Timestamp, patientID, userID
  * From these sources, fetches the associated prescription and
  * all of its comments returned as JSON.
  * ---------------------------------------------*/
  function fetchData($source){
    //TODO
  }

  /**
  * /--------------------------------------------
  * Inserts a comment into the database
  * As a data structure, $comment should follow the schema of:
  * AUTHOR, CONTENT, timestamp
  * --------------------------------------------*/
  function addComment($source, $comment){
    //TODO
    //Create a template for comments
  }

  //edits or deletes a comment
  function editComment($commentID, $operation){
    //TODO

  }

?>
