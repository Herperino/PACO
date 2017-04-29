 /** 
  *  /paco/public/scripts.js
  * 
  *  This is the PACO JS script file.
  *  It contains functions that will work client-side to help the functioning
  *  of the whole PACO platform. The following functions are:
  * 
  *  - renderForm(Renders the login/navigation pill on the topbar)
  */

function showPatients(page){
    
    $.getJSON("patients.php").done(function(data){
    
        var form = $('#patient_list');
        var patients = data.length;
        
        var pageid = "\""+ page + "\"";
        
        var content = "<table id='patients' class='table'>";
        
        content += "<tr><th>ID</th><th>Nome</th><th class='p_age'>Idade</th><th>Status</th><th>Ação</th></tr>";
        
        for (var count = 0; count < patients; count++)
            {
                
                var rowindex = "row_" + count;
                
                if (data[count].p_status != "inactive"){
                    content += "<tr class = 'tc'  id= '" + rowindex + "'>" ;
                }
                else{
                    content += "<tr class = 'tc'  id= '" + rowindex + "' style = 'color:gray'>" ;
                }
                
                content += "<td class = 'p_id' value = '" + count + "'>" + data[count].patientID + "</td>";
                content += "<td class = 'p_name' value = '" + count + "'>" + data[count].patientname + "</td>";
                content += "<td class = 'p_age' value ='" + count + "'>" + data[count].patientage + "</td>";
                content += "<td class = 'p_status' value = '" + count + "'>" + data[count].p_status+ "</td>";
                content += "<td> <select class = 'coolbuttons' data-style='btn-success' id = 'ptt_" + data[count].patientID + 
                "' onchange='if (this.selectedIndex) patientHandler(this," + pageid + ");'>  <option  value='nada'>Selecione</option> <option value='edit'  data-toggle='modal' data-target='myform'>Editar</option><option value='changestatus'>Mudar status</option><option value='acomp'>Acompanhar</option></select></td>";
                content += "</tr>"
                
            }
        
        content += "<tr></td> <td colspan = '4'></td>";
        content += "<td><input onClick= 'handler()' id = 'addBtn' type ='button' value= 'Adicionar Paciente'";
        content += "class = 'btn btn-success'/></tr>";
        content += "</table>";
        form.html(content);    
    });
}

function patientHandler(event, page){
    
    //Based on selectedIndex from the menu option
    var options = ["EDIT", "STATUS", "ACOMP", "ADD"];
    var index = event.selectedIndex; //Index of the option in select
    var choice = options[index-1]; //offsetting the non 0 indexed select
    
    var patientid = event.id.substring(4); //Patient ids are passed as "ptt_(patientid)"
    
    //If option chosen is ACOMP, send patient chosen to acompanhamento.php(Request method is POST)
    if (choice == "ACOMP"){
        
        //TODO: SEND INFO TO the correct page
        var package_to_send = {
            operation : choice,
            patientID : patientid
        }
        post(page, package_to_send);
    }
    
    //If option chosen is EDIT or STATUS, send info to patients.php (Request method is POST)
    else if(choice == "STATUS")
    {
        //TODO: SEND INFO TO PATIENTS.PHP (Will query the database for the fix)
        var package_to_send = {
            operation : choice,
            patientID : patientid
        }
        $.post("patients.php", package_to_send);
        //Render new list of patients at the end
        showPatients();

    }
    else if (choice == "EDIT"){
        
        var info = {
            operation:choice,
            patientID : patientid
        }
        
        renderPatientForm("patients.php", info);
    }
    else{
        
        var info = {
            operation:choice,
            patientID: null
        }
        
        renderPatientForm("patients.php", info);
    }
    
}

/** Event -> Controller response
 *  Request a response for the server given a page event. 
 * 
 *  Event can be:
 *  - ADD a prescription
 *  - EDIT a prescription
 *  - GET last prescription 
 */

function prescriptionHandler(event){
    

    // important info to send
    var patientID = event.dataset.patient;
    var operation = event.dataset.operation;
    var timestamp = event.dataset.timestamp;
    
    var pkg_to_send = {event: event,
                       operation: operation,
                       patientID: patientID,
                       timestamp: timestamp};
    
    getLastPrescription(pkg_to_send);
}

/* --------------------------------------------------------------------------------------------------------------
 *  HELPERS
 ---------------------------------------------------------------------------------------------------------------*/
function renderLognav(){
   
    $.getJSON("login.php").done(function(data)
    {
        var form = $("#loginform");
        var html = '';
        
        for (var count = 0; count < data.length; count++)
        {
            html += data[count];
            
        }
        form.html(html);
    });
}

/** Path, parameters -> Form (on page)
 *  Takes the parameters given to create a form containing them all
 *  that can be submitted to the path given. This form is used to
 *  edit patient information and will be sent to the patient controller
 */
function renderPatientForm(path, parameters){
    
    document.getElementById("pagemid").style.opacity ='0.4';
    
    if(parameters['operation'] == "ADD") {var info = 'Adicionar novo paciente';}
    else {var info = 'Alterar dados do paciente';}
    
    var choice = parameters['operation'];
    var pat_id = parameters['patientID'];
    
    var content = "<div class='row newform'><div id='myform' class= 'form-group col-12'>";
    
    //the form itself
    content += "<form accept-charset='UTF-8' action ='" + path + "' method='post' >";
    content+= "<label>ID</label><br><input name= 'new_id' type = 'text' value="+ pat_id +"></input><br><small>Código de identificação do paciente</small><br>";
    content+= "<label>Nome</label><br><input name= 'patient_name' type = 'text' required></input><br><small>Edite o nome do paciente</small><br>";
    content+= "<label>Idade</label><br><input name= 'patient_age' type = 'text'required></input><br><small>Edite a idade do paciente</small><br><div>";
    content+= "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>";
    content+= "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>";
    content+= "<br><input class= 'btn btn-success' type = 'submit' value= '" + info +"'> &nbsp;";
    content+= "<input class= 'btn btn-success' type = 'button' value= 'Cancelar' onclick = 'clearForm()'>";
    content+= "</form></div>";
    
    document.body.innerHTML += content;
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
    
    var content = "<div style='margin-left:5%;width:auto'>";
    
    //The form
    content+= "<center>";
    content += "<form style='margin:auto' action = \"" + controller + "\" method='POST'>";
    
    for(var i = 1; i <= 10; i++){
        try{
        content+= i + ". Medicamento<input name= 'med"+ i + "' type = 'text' value =' "+ parameters.last_p['med'+i] +"' placeholder = 'Inserir'></input>";    
        }
        catch(err){
        content+= i + ". Medicamento<input name= 'med"+ i + "' type = 'text' placeholder = 'Inserir'></input>";
        }
        content+= "Dose<input name= 'dos"+ i + "' type = 'text' placeholder = 'Inserir'></input>";
        content+= "Via<input name= 'via"+ i + "' type = 'text'  placeholder = 'Inserir'></input>";
        content+= "Posologia <select class = 'coolbuttons' name= 'pos"+ i +"'>";
        content+= "<option value = 'null'> Selecione </option>";
        content+= "<option value = '1x/d'> 1x/dia </option>";
        content+= "<option value = '2x/d'> 2x/dia (12/12h) </option>";
        content+= "<option value = '3x/d'> 3x/dia (8/8h) </option>";
        content+= "<option value = '4x/d> 4x/dia (6/6h) </option>";
        content+= "<option value = '6x/d'> 6x/dia (4/4h) </option>";
        content+= "<option value = 'SOS'> Caso necessário </option>";
        content+= "</select><br>";
    }
    content+= "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>";
    content+= "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>";
    
    console.log("prescription date: "+date);
    content+= "<input name= 'date' type = 'hidden' value = '"+ date +"'></input>"
    content+= "<br><input class= 'coolbuttons' style= 'width:90%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>";
    content+= "</form></div></center>";
    
    
    document.getElementById("prescription_list").innerHTML = content;
}

function clearForm(){
        
    document.getElementById("pagemid").style.opacity ='1';
    var clear = document.getElementsByClassName("newform");
    
    for(var i = 0; i< clear.length; i++) {clear[i].style.visibility = "hidden";}
}

/** Event -> Array
  * Queries the database for the last prescription. 
  * Returns an empty array if no empty prescription was found
  */
function getLastPrescription(source){
    
    var last_prescription = [];
    
    //Declaring the prescription's date and patient
    if (source.operation == "PRESCRIPTION_EDIT"){
        var tr = source.event.parentElement.parentElement; 
        var timestamp = source.timestamp;
        var patientID = source.patientID;
    }
    else{
        var target = document.getElementById("prescription_list");
        var timestamp = source.timestamp;
        var patientID = source.patientID;        
    }
    
    //Parameters to be sent to the request
    var info = {operation:"GET_PRESCRIPTION",
                patientID: patientID,
                date:timestamp};
                
    //Query acompanhamento controller via POST ajax            
    $.post("acompanhamento.php", info).done(function(data){
         var to_form = {last_p: data[0],
                        operation:source.operation,
                        patientID: patientID,
                        date : timestamp};
                        
        renderPrescriptionForm(to_form);
    });
}

function validate(){
    
    var form = document.getElementById("register");
    var regispwd = form.regispwd.value;
    var confirmation = form.confirmation.value;
    var betakey = form.testebeta.value;
    
    console.log(form);
    
    if ((regispwd != confirmation) || (betakey != "catioro"))
    {
        var midscreen = document.getElementById("pagemid");
        midscreen.innerHTML ="<div class='container'><h2 class='alert alert-danger'> Não foi possível realizar o cadastro</h2></div>";
        return false;
    }
}


//By Ryan Delluchi on http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
function post(path, parameters) {
    var form = $('<form></form>');

    form.attr("method", "post");
    form.attr("action", path);

    $.each(parameters, function(key, value) {
        var field = $('<input></input>');

        field.attr("type", "hidden");
        field.attr("name", key);
        field.attr("value", value);

        form.append(field);
    });

    // The form needs to be a part of the document in
    // order for us to be able to submit it.
    $(document.body).append(form);
    form.submit();
}

function handler(){
        /*Since adding a new patient doesn't really send the same event
         * we create our own event and send it to the function */
        var event = { selectedIndex: 4, id: "supergambiarra"}
        patientHandler(event, "acompanhamento.php");
    }
    
function newPrescriptionButton(){
    
    //Sorry for this super specific part. It gets the DOM object containg the last prescription timestamp
    var target = document.getElementById("prescription_list");
    try{
        var source = target.firstElementChild.firstElementChild.lastElementChild.firstElementChild;
        var date = source.nextSibling.innerHTML;
        var patient = source.innerHTML;
        console.log("The date is "+ date + " and the patient is: "+patient);
    }
    catch(err){
        console.log(err);
        patient = patientID;
        date = 'null';
    }
    content ="<input id ='dateinfo' data-patient ='"+ patient +"'";
    content +="data-operation = 'PRESCRIPTION_ADD'";
    content += "data-timestamp ='" + date + "'";
    content += "type = 'button' onClick = 'prescriptionHandler(this)'";
    content += "class= 'btn btn-success' value='Adicionar Prescrição'/>";
    
    target.innerHTML += content;
}