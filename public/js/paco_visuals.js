/** Este arquivo contém funcionalidades javascript para a execução
 *  de código que customiza a aparência do site, tal como a adição
 *  de formulários, listas e tabelas. Os arquivos principais ficam
 *  no main.js
 */


 /** Displays a list of patients and their current state
  *  based on data sent by the server
  */
 function showPatients(page){

    $.getJSON("patients.php").done(function(data){

        var form = $('#patient_list');
        var patients = data.length;

        var pageid = "\""+ page + "\"";

        var content = "<div class = 'panel-body' style='overflow-x:scroll'>" +
                        "<table id='patients' class='table'>"+
                        "<tr><th>ID</th><th>Nome</th><th class='p_age'>Idade</th><th>Status</th><th>Ação</th></tr>";

        for (var count = 0; count < patients; count++)
            {

                var rowindex = "row_" + count;
                var status = ["inativo", "ativo"];

                if (data[count].p_status != "0"){
                    content += "<tr class = 'tc'  id= '" + rowindex + "'>" ;
                }
                else{
                    content += "<tr class = 'tc'  id= '" + rowindex + "' style = 'color:gray'>" ;
                }

                content += "<td class = 'p_id' value = '" + count + "'>" + data[count].patientid + "</td>";
                content += "<td class = 'p_name' value = '" + count + "'>" + data[count].patientname + "</td>";
                content += "<td class = 'p_age' value ='" + count + "'>" + data[count].patientage + "</td>";
                content += "<td class = 'p_status' value = '" + count + "'>" + status[data[count].p_status] + "</td>";
                content += "<td> <select class = 'form-control coolbuttons' data-style='btn-success' id = 'ptt_" + data[count].patientid +
                "' onchange='if (this.selectedIndex) patientHandler(this," + pageid + ");'>  <option  value='nada'>Selecione</option> <option value='edit'  data-toggle='modal' data-target='myform'>Editar</option><option value='changestatus'>Mudar status</option><option value='acomp'>Acompanhar</option></select></td>";
                content += "</tr>"

            }

        content += "<tr></td>";
        content += "</table>";
        content += "<td><input onClick= 'handler()' id = 'addBtn' type ='button' value= 'Adicionar Paciente'";
        content += "class = 'btn btn-success'/></tr>" + "</div>" //panel body;

        form.html(content);
    });
}

/** Path, parameters -> Form (on page)
 *  Takes the parameters given to create a form containing them all
 *  that can be submitted to the path given. This form is used to
 *  edit patient information and will be sent to the patient controller
 */
function renderPatientForm(path, parameters){

    //Declare variables to be used in the form
    if(parameters['operation'] == "ADD") {var info = 'Adicionar novo paciente';}
    else {var info = 'Alterar dados do paciente';}

    var choice = parameters['operation'];
    var pat_id = parameters['patientID'];

    if (pat_id == null){
        pat_id = "";
    }

    var action = "submitModal();"

    //the form itself

    var content = ""+
    "<div class='modal fade' id='myForm' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
        "<div class='modal-dialog' role='document'>"+
            "<div class='modal-content'>"+
                "<div class='modal-header'>"+
                    "<h3 class='modal-title' id='exampleModalLabel'>Insira um novo paciente</h3>"+
                    "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>"+
                    "<span aria-hidden='true'>&times;</span>"+
                    "</button>"+
                "</div>"+
            "<div class='modal-body'>"+
            "<form id= 'formC' class= 'form-group col-12' accept-charset='UTF-8' action ='" + path + "' method='post' >" +
            "<label>ID</label><br><input name= 'new_id' type = 'text' placeholder='' value="+ pat_id +"></input><br><small>Código de identificação do paciente</small><br>" +
            "<label>Nome</label><br><input name= 'patient_name' type = 'text' required></input><br><small>Edite o nome do paciente (sem acentos)</small><br>" +
            "<label>Idade</label><br><input name= 'patient_age' type = 'text'required></input><br><small>Edite a idade do paciente</small><br><div>" +
            "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>" +
            "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>" +
            "</form>" +
            "</div>" +

            "</div>"+
            "<div class='modal-footer'>"+
                "<input class= 'btn btn-default' type = 'button' value= 'Cancelar' data-dismiss='modal'>" +
                "<input class= 'btn btn-success' type = 'button' onclick ='" + action + "' value= '" + info +"'> &nbsp;" +

            "</div>"+
            "</div>"+
        "</div>"+
    "</div>";

    document.body.innerHTML += content;

    $('#myForm').modal('show');
}


/** Parameters -> Form (on page)
 *  Takes the parameters given to create a form containing them all
 *  that can be submitted to the path given. This form is used to edit
 *  lab results and will be sent to the labref controller
 */

function renderLabrefForm(parameters){

    //Do function here

}

/** Parameters -> Form (on page)
 *  Takes the parameters given to create a form containing them all
 *  that can be submitted to the path given. This form is used to edit
 *  precriptions and will be sent to the prescription controller
 */
function renderPrescriptionForm(parameters){

    //For prescription handling, the controller can only be acompanhamento.php
    var controller = "acompanhamento.php";

    if(parameters['operation'] == "PRESCRIPTION_ADD") {
        var info = 'Adicionar nova prescrição';
        var date = document.getElementById("dateinfo").dataset.timestamp;
    }
    else {
        var info = 'Alterar prescrição';
        var date = parameters.date;

    }

    var choice = parameters['operation'];
    var pat_id = parameters['patientID'];

    var content = "<div class = 'content' style='margin-left:5%;width:auto'>";

    //The form
    content += "<form style='margin:auto' action = \"" + controller + "\" method='POST'>";

    for(var i = 1; i <= 10; i++){
        try{
          content+="<div class='panel'>" + i + ". Medicamento<input name= 'med"+ i + "' type = 'text' value =' "+ parameters.last_p['med'+i] +"' placeholder = 'Inserir'></input>";
        }
        catch(err){
          content+= i + ". Medicamento<input name= 'med"+ i + "' type = 'text' placeholder = 'Inserir'></input>";
        }
        content+= "Dose<input name= 'dos"+ i + "' type = 'text' placeholder = 'Inserir'></input>";
        content+= "Via<input name= 'via"+ i + "' type = 'text'  placeholder = 'Inserir'></input>";
        content+= "Posologia <select class = 'form-control' name= 'pos"+ i +"'>";
        content+= "<option value = 'null'> Selecione </option>";
        content+= "<option value = '1x/d'> 1x/dia </option>";
        content+= "<option value = '2x/d'> 2x/dia (12/12h) </option>";
        content+= "<option value = '3x/d'> 3x/dia (8/8h) </option>";
        content+= "<option value = '4x/d'> 4x/dia (6/6h) </option>";
        content+= "<option value = '6x/d'> 6x/dia (4/4h) </option>";
        content+= "<option value = 'SOS'> Caso necessário </option>";
        content+= "</select></div>";
    }
    content+= "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>";
    content+= "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>";

    content+= "<input name= 'date' type = 'hidden' value = '"+ date +"'></input>"
    content+= "<br><input class= 'btn btn-default' style= 'width:90%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>";
    content+= "</form></div></center>";


    document.getElementById("prescription_list").innerHTML = content;
}

function newPrescriptionButton(id){

    //Sorry for this super specific part. It gets the DOM object containg the last prescription timestamp
    var target = document.getElementById("prescription_list");
    try{
        var source = target.firstElementChild.firstElementChild.lastElementChild.firstElementChild;
        var date = source.nextSibling.innerHTML;
        var patient = source.innerHTML;

        if(patient == "Paciente" || date == "data"){
            throw ("got a th");
        }

        console.log("The date is "+ date + " and the patient is: "+patient);
    }
    catch(err){
        console.log(err);
        var patient = id;
        var date = 'null';
        console.log("The date is "+ date + " and the patient is: "+patient);
    }
    var content ="<input id ='dateinfo' data-patient ='"+ patient +"'";
    content +="data-operation = 'PRESCRIPTION_ADD'";
    content += "data-timestamp ='" + date + "'";
    content += "type = 'button' onClick = 'prescriptionHandler(this)'";
    content += "class= 'btn btn-success' value='Adicionar Prescrição'/>";

    target.innerHTML += content;
}

function newLabButton(id){

    //Sorry for this super specific part. It gets the DOM object containg the last prescription timestamp
    var target = document.getElementById("labresults_list");
    try{
        var source = target.firstElementChild.firstElementChild.lastElementChild.firstElementChild;
        var date = source.nextSibling.innerHTML;
        var patient = source.innerHTML;

        if(patient == "Paciente" || date == "data"){
            throw ("got a th");
        }
        console.log("The date is "+ date + " and the patient is: "+patient);
    }
    catch(err){
        console.log(err);
        var patient = id;
        var date = 'null';
        console.log("The date is "+ date + " and the patient is: "+patient);
    }
   var  content ="<input id ='dateinfo' data-patient ='"+ patient +"'";
    content +="data-operation = 'LAB_ADD'";
    content += "data-timestamp ='" + date + "'";
    content += "type = 'button' onClick = 'labHandler(this)'";
    content += "class= 'btn btn-success' value='Adicionar Resultados'/>";

    target.innerHTML += content;
}

/*
 * Clears a modal form
 */

function submitModal(){

    var form = document.getElementById('formC').submit();
}
