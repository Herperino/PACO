<?php

    /**
     * This is the view file for review of prescriptions (acompanhamento).
     * It has two main modes: 
     * 
     * - A GET mode that allows the users to access their current patient list
     * - A POST mode that allows the users to review a patient prescriptions
     *  and also edit the current prescription or add a new prescription
     * 
     *  The controller will, when asking for the page, output a page mode,
     *  referenced by the variable named P_MODE. If P_MODE is true, it means
     *  the view will be rendered in a view prescription mode. 
     */
     
?>

<?php if($P_MODE == true):?>
<div class ="container">
    <br><br>
    <div class= "row">
        <div class="panel panel-default">
        <div class="panel-heading"><h4><?php echo $patientID ?></h4></div>

<div id ="prescription_list" class="panel-body">
    <table class = 'table'>
    <?php
    
        
    function displayPrescription($prescriptions){
        
        print("<th>Paciente</th>");
        print("<th>Data</th>");
        print("<th colspan= '20'>Medicamentos</th>");
            if (!empty($prescriptions)){
            foreach($prescriptions as $prescription){
                 $prescription = $sub = array_slice($prescription, 2, null, true); //Remove ID and userID from array
                    
                print("<tr>"); 
                print("<td>" . $prescription["patientID"]. "</td>");
                print("<td>" . $prescription["Date"]. "</td>");
                for($i = 1; $i <= 10; $i++){
                    print("<td>". $prescription["med".$i] ." ". $prescription["pos".$i]."</td>");
                }
             print("<td>"); 
             print("<input  data-patient =" . (string)$prescription['patientID'] ."
                    data-operation = \"PRESCRIPTION_EDIT\"
                     data-timestamp ='" . (string)$prescription['Date'] . 
                    "' type = 'button' onClick = 'prescriptionHandler(this)' 
                    class= 'btn btn-success' value='Editar Prescrição'/>"); print("</td></tr>");}}
    }
    
    displayPrescription($prescriptions);
    
    ?>
    </table>
    
</div>     <!--end panel-->
</div>     <!--end row-->
<script>
        var patientID =<?php echo json_encode($P_ID); ?>;
        window.onload = function(){
            newPrescriptionButton();
        }
    </script>
</div>     <!--end container-->
    
    
    
        
</div>
<?php else: ?>
<div class = 'container-fluid'>
<div class = 'page-header'><h2>Acompanhamento de prescrições</h2></div>
<h4>Pacientes em acompanhamento por <?php echo $_SESSION['username'] ?></h4>
<div id="patient_list">      
    <script>showPatients("acompanhamento.php");
    </script>
    <script type="text/javascript">
    $('.selectpicker').selectpicker();
</script>
</div>
</div>
<?php endif?>