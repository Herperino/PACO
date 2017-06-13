/**
  *  /paco/public/scripts.js
  *
  *  This is the PACO JS script file.
  *  It contains functions that will work client-side to help the functioning
  *  of the whole PACO platform.
  */


/**Handles operations for the patients page
*
*  Operations may be one of:
*  -EDIT: Edits a a given patient's info.
*  -STATUS: Change current's patient status (active, inactive)
*  -ADD: Inserts a new patient into the database
*  -ACOMP: Operation to see a patient's prescription/results/notes
*
*  Requires a PatientID and the Operation.
*  Timestamp required for EDIT_LAB and GET_LAB.
**/
function patientHandler(event){

    //Based on selectedIndex from the menu option
    var options = ["EDIT","STATUS", "REMOVE","ACOMP", "ADD"];
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
        post(window.location.href, package_to_send);
    }

    //If option chosen is EDIT or STATUS, send info to patients.php (Request method is POST)
    else if(choice == "REMOVE")
    {
        //TODO: SEND INFO TO PATIENTS.PHP (Will query the database for the fix)
        var package_to_send = {
            operation : choice,
            patientID : patientid
        }
        $.post("patients.php", package_to_send).done(showPatients());

    }
    else if(choice == "STATUS")
    {
        //TODO: SEND INFO TO PATIENTS.PHP (Will query the database for the fix)
        var package_to_send = {
            operation : choice,
            patientID : patientid
        }
        $.post("patients.php", package_to_send).done(showPatients());
        
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

/** 
*  Handles operations for the prescriptions page.
*
*  The operations may be one of:
*  - ADD a prescription
*  - EDIT a prescription
*  - GET last prescription
**/
function prescriptionHandler(event){


    // important info to send
    var uniqueID = event.dataset.id;
    var operation = event.dataset.operation;
    var patientID = event.dataset.patient;
    
    var pkg_to_send = {event: event,
                       operation: operation,
                       patientID : patientID,
                       uniqid : uniqueID};

    getLastPrescription(pkg_to_send);
}

/**
*  Handles operations for the lab results page
*
*  Operations may be one of:
*  -GET_LAB: Recover the last lab results from the server
*  -ADD_LAB: Inserts a set of lab results into the database
*  -EDIT_LAB: Edits a set of lab results on the server
*
*  Requires a PatientID and the Operation.
*  Timestamp required for EDIT_LAB and GET_LAB.
**/
function labHandler(event){


    // important info to send
    var patientID = event.dataset.patient;
    var operation = event.dataset.operation;
    var uniqid = event.dataset.uniqid;


    if (operation == "GET_LAB"){
      $.post("labref.php", {operation: operation, patientID: patientID,uniqid: uniqid}).done(function(data){

          //handle data here
          console.log(data);
          //eventually render a form
          return alert("things went okay");

      });
    }
    else{
      var pkg_to_send = {operation: operation,
                         patientID: patientID,
                         uniqid: uniqid};
      console.log(pkg_to_send);
      renderLabForm(pkg_to_send);
    }
}

 /** Event -> Array
  *  Queries the database for the last prescription.
  *  Returns an empty array if no empty prescription was found
  */
function getLastPrescription(source){

    var last_prescription = [];


    //Declarando o ID de objeto e de paciente
    var id_unico = source.uniqid;
    var patientID = source.patientID;

    //Store parameters to be sent to the request
    var info = {operation:"GET_PRESCRIPTION",
                patientID : patientID,
                uniqid: id_unico};

    //If it is the first prescription. Render an empty form
     if(id_unico == "null")
        renderPrescriptionForm(info);

    //Query acompanhamento controller via POST ajax
    $.post("acompanhamento.php", info).done(function(data){
        
        var to_form = {last_p: data[0],
                       uniqid:id_unico,
                       operation: "PRESCRIPTION_EDIT",
                       patientID: patientID,
                       date : data[0]['date']};
        renderPrescriptionForm(to_form);
    });
}

/* Removes an entry from the database */
function deleteEntry(event){

  // important info to send
  var target = document.location.href;
  var patientID = event.dataset.patient;
  var operation = event.dataset.operation;
  var uniqid = event.dataset.uniqid;

  //creates a package that will be sent as a request to the server
  var pkg_to_send = {operation: operation,
                     patientID: patientID,
                     timestamp: timestamp};

  $.post(target, {operation: operation, patientID: patientID,timestamp: timestamp})

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
        midscreen.innerHTML ="<div class='container'><div class ='alert alert-warning'>"+
        "<h3> Não foi possível realizar o cadastro.</h3><br> <p>Entre em contato com Leon para receber sua senha beta</p></div></div>";
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
        var event = { selectedIndex: 5, id: "supergambiarra"}
        patientHandler(event);
    }

//Calls stacktable
$('#patients').stacktable();    