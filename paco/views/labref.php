<?php

    /**
     * This is the view file for reviewing patients lab results
     * (labref)
     */
     
?>

<?php if($P_MODE == true):?>

<div class ="container">
    <br><br>
    <div class= "row">
        <div class="panel panel-default">
        <div class="panel-heading"><h4><?php echo $patientID ?></h4></div>

<div id ="labresults_list" class="panel-body">
    <table class = 'table'>
    <?php
    print("<th>Paciente</th>");
    print("<th>Data</th>");
    print("<th colspan= '10'> Exames</th>");
    
    
    foreach($labresults as $result){
        
      $items = sizeof($result);
      $labref = $sub = array_slice($result, 1, null, true); //Removes index and userID from the array
      
      print("<tr><center>");
      
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
            "' type = 'button' onClick = 'prescriptionHandler(this, 'acompanhamento.php')' 
            class= 'coolbuttons' value='Editar resultados'/>");
      print("</td>");
      
      print("</center></tr>");
    }
    ?>
    </table>
    
</div>     <!--end panel-->
</div>     <!--end row-->    
 </div>   
    <input  data-patient = <?php echo $labresults[0]['patientID'] ?> 
            data-operation = 'LAB_ADD'
            type = 'button' onClick = "prescriptionHandler(this, 'acompanhamento.php')" 
            class= 'coolbuttons' value="Adicionar resultados"/>
</div>

</div> <!-- end container-->

<!--THE VIEW mode allows the users to review a patient prescriptions and also edit the current prescription or add a new prescription -->
<?php else: ?>
<div class = 'container-fluid'>
<div class = 'page-header'><h2>Acompanhamento laboratorial</h2></div>
<h4>Pacientes em acompanhamento por <?php echo $_SESSION['username'] ?></h4>
<div id="patient_list">      
    <script>showPatients("labref.php");
    </script>
    <script type="text/javascript">
    $('.selectpicker').selectpicker();
</script>
</div>
</div>
<?php endif?>