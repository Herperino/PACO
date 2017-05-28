<?php

    /**
     * This is the view file for reviewing patients lab results
     * (labref)
     */

?>

<?php if($P_MODE == true):?>

<div class ="container">
    <br><br>
    <div class= "row" style="overflow-x:auto">
        <div class="panel panel-default">
        <div class="panel-heading"><h4><?php echo $patientID ?></h4></div>

        <div id ="labresults_list" class="panel-body">
            <table class = 'table'>
            <?php displayResults($labresults);?>
            </table>

        </div>     <!--end panel body-->
    </div>     <!--end panel-->
    </div> <!--end row-->
    <script>
        window.onload = function(){
            //Will add a button that allows to insert a new result set to the DB
            var patientID =<?php echo json_encode($P_ID); ?>;
                newLabButton(patientID);
            }
    </script>

</div> <!-- end container-->

<!--THE VIEW mode allows the users to review a patient prescriptions and also edit the current prescription or add a new prescription -->
<?php else: ?>
<div class = 'container'>
    <div class = 'page-header'><h2>Acompanhamento laboratorial</h2></div>
    <h4>Pacientes em acompanhamento por <?php echo $_SESSION['username'] ?></h4>
    <div id="patient_list">
        <script>showPatients("labref.php"); </script>
    </div>
</div> <!-- end container -->
<?php endif?>
