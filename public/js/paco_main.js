/**
  *  /paco/public/scripts.js
  *
  *  This is the PACO JS script file.
  *  It contains functions that will work client-side to help the functioning
  *  of the whole PACO platform.
  */

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
        post(window.location.href, package_to_send);
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

        showPatients();
    }
    else if (choice == "EDIT"){

        var info = {
            operation:choice,
            patientID : patientid
        }

        console.log(info)
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

    if(operation == "PRESCRIPTION_DELETE"){
      console.log(pkg_to_send);
      $.post("acompanhamento.php", pkg_to_send)

    }
    else
      getLastPrescription(pkg_to_send);
}

function labHandler(event){


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

 /** Event -> Array
  *  Queries the database for the last prescription.
  *  Returns an empty array if no empty prescription was found
  */
function getLastPrescription(source){

    var last_prescription = [];


    //Declaring the prescription's date and patient
    var timestamp = source.timestamp;
    var patientID = source.patientID;

    //Store parameters to be sent to the request
    var info = {operation:"GET_PRESCRIPTION",
                patientID: patientID,
                date:timestamp};

    //If it is the first prescription. Render an empty form
     if(timestamp == "null")
        renderPrescriptionForm(info);

    //Query acompanhamento controller via POST ajax
    $.post("acompanhamento.php", info).done(function(data){
        console.log("hello from handler");
        var to_form = {last_p: data[0],
                        operation:source.operation,
                        patientID: patientID,
                        date : timestamp};
        console.log("GLP is being called AJAX")
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
        midscreen.innerHTML ="<div class='container'><div class ='alert alert-warning'><h3> Não foi possível realizar o cadastro.</h3><br> <p>Entre em contato com Leon para receber sua senha beta</p></div></div>";
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
