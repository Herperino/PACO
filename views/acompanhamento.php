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
     *
     *  P_ID is passed from the server as the user name.
     */


?>

<?php if($P_MODE == true):?>
<div class ="container">
    <br><br>
    <div class= "row" style = "overflow-x:auto">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> <?php echo $P_ID ?> - <?php echo $patientID ?>  </h4>
            </div>

            <div id ="prescription_list" class="panel-body">

                <table class = 'table'>
                <?php displayPrescription($prescriptions); ?>
                </table>
            </div>     <!--end panel body-->
        </div>     <!--end panel-->

    <script>
        //Will add a button that allows to insert a new prescription to the DB
        var patientID =""+ <?php echo $P_ID ?>;
        window.onload = function(){
            newPrescriptionButton(patientID);
        }
    </script>

    </div>     <!--end row-->
</div>     <!--end container-->

<?php else: ?>
<div class = 'container'>
    <div class = 'page-header' style="overflow-x:auto"><h2>Acompanhamento de prescrições</h2></div>
    <h4>Pacientes em acompanhamento por <?php echo $_SESSION['username'] ?></h4>
    <div id="patient_list" class = "panel panel-default">
        <script>
            $.when(showPatients("acompanhamento.php")).then(function(){
                console.log("This should've worked");
                

                return $('#patients').stacktable();
            });



        </script>
    </div>
</div> <!-- end container-->
<?php endif?>
