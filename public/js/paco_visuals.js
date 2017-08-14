/** Este arquivo contém funcionalidades javascript para a execução
 *  de código que customiza a aparência do site, tal como a adição
 *  de formulários, listas e tabelas. Os arquivos principais ficam
 *  no main.js
 */


///////////////////////////////////////////////////////////////////////////////////////////////
////                                   EXIBIÇÃO DE DADOS                                   ////
///////////////////////////////////////////////////////////////////////////////////////////////


 /**
  * Mostra uma lista de pacientes e oferece opções
  */
 function showPatients(page){
   
    $.getJSON("patients.php").done(function(data){

        alert("AAAAAAAAAAAAAAAAAAAAAAAA")

        var form = $('#patient_list');
        var patients = data.length;

        //Se não houverem pacientes
        if (patients === undefined)
          patients = 0;

        var content = "<div class='panel-heading'>Pacientes ("+ patients+")</div>"+
                        "<div class ='panel-body'>" +
                          "<div class = 'wrapper'>";

        //Caso exista pelo menos um paciente em acompanhamento
        if (patients > 0){

          content += "<table id='patients' class='table'>"+
                            "<tr>"+
                              "<th>ID</th>"+
                              "<th>Nome</th>"+
                              "<th class='p_age'>Idade</th>"+
                              "<th>Status</th>"+
                              "<th>Ação</th>"+
                            "</tr>";

          //Para cada paciente, elabora uma linha na tabela
          for (var count = 0; count < patients; count++)
              {
                  var rowindex = "row_" + count;
                  var status = ["Inativo", "Ativo"];

                  if (data[count].p_status != "0"){
                      content += "<tr class = 'tc'  id= '" + rowindex + "'>" ;
                  }
                  else{
                      content += "<tr class = 'tc'  id= '" + rowindex + "' style = 'color:gray'>" ;
                  }

                  content += "<td value = '" + count + "'>" + data[count].patientid + "</td>";
                  content += "<td value = '" + count + "'>" + data[count].patientname + "</td>";
                  content += "<td value ='" + count + "'>" + data[count].patientage + "</td>";
                  content += "<td value = '" + count + "'>" + status[data[count].p_status] + "</td>";
                  content += "<td> <select class = 'fake-select' data-style='btn-success'"+
                  "uniqid = '" + data[count].uniqid +
                  "'onchange='if (this.selectedIndex) patientHandler(this);'>"+
                  "<option value='nada'>Selecione</option>"+
                  "<option value='edit' data-toggle='modal' data-target='myform'>Editar</option>"+
                  "<option value='changestatus'>Status</option>"+
                  "<option value='remover'>Remover</option>"+
                  "<option value='acomp'>Acompanhar</option></select></td>";
                  content += "</tr>"

              }
          }

          //Inclui o botão de inserção de novos pacientes
          content += "</table></div>";
          content += "<input onClick= 'handler()'"+
          "id = 'addBtn' style='width:100%' "+
          "type ='button' value= 'Adicionar Paciente'"+
          "class = 'btn btn-success'/>" +
            "</div>" //panel body;

          //Insere o conteúdo no DOM
          form.html(content);

          //Extensão stacktable para view mobile
          $('#patients').stacktable();
    });
}

function makeCommentList(){

    //Incialmente busca informações do paciente no banco de dados
    $.ajax({
        type:'POST',
        url:"patients.php",
        data:{operation: 'RETRIEVE', patientID:'ignore'},
        success: function(data){

            var pacientes = []; // Array de objetos de pacientes
            var content = ""; //Conteúdo a ser inserido no template

            for(var i = 0; i<data.length;i++){

                pacientes[i] = { name:data[i].patientname,
                                 status:data[i].p_status,
                                 id: data[i].patientid,
                                 updated:data[i].LastActive
                                }


                  //Se for um paciente com status ativo, exibir na lista
                  if (pacientes[i].status == 1)
                    content += "<tr id='l"+ i +"'><td>"+  pacientes[i].id +"</td> <td>"+  pacientes[i].name +"</td>";
            }

            document.getElementById("lista").innerHTML += content;

            for (var i = 0; i<data.length;i++)
              getCommentNumber("l"+i, pacientes[i].id);
      },
      error: function(){alert("Erro ao resgatar o conteúdo")}
    });
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////
////                                          FORMULÁRIOS                                              ////
//////////////////////////////////////////////////////////////////////////////////////////////////////////


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
    //pat_id is intended to be inserted as a tag attrivute
    var pat_id = "value = " + parameters['patientID'];

     if (parameters['operation'] == "ADD"){
         pat_id = "";
     }

    var action = "submitModal('formC');"

    //the form itself

    var content = ""+
    "<div class='modal fade' id='myForm' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
        "<div class='modal-dialog' role='document'>"+
            "<div class='modal-content'>"+
                "<div class='modal-header'>"+
                    "<h3 class='modal-title' id='exampleModalLabel'>Insira um novo paciente</h3>"+
                "</div>"+
            "<div class='modal-body'>"+
            "<form id= 'formC' class= 'form-group col-12' accept-charset='UTF-8' action ='" + path + "' method='post' >" +
            "<label>ID</label><br><input name= 'new_id' type = 'text' placeholder=''" + pat_id +"></input><br><small>Código de identificação do paciente</small><br>" +
            "<label>Nome</label><br><input name= 'patient_name' type = 'text' required></input><br><small>Edite o nome do paciente (sem acentos)</small><br>" +
            "<label>Idade</label><br><input name= 'patient_age' type = 'text'required></input><br><small>Edite a idade do paciente</small><br><div>" +
            "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>" +
            "<input name= 'patientID' type = 'hidden'"+ pat_id +"  ></input>" +
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

function renderLabForm(parameters){

  //For lab result handling, the controller has to be labref.php
  var controller = "labref.php";

  //A few variables
  var info = 'Inserir resultado'; //A ser inserido como conteúdo do botão
  var uniqid = parameters.uniqid; // Parameters.date comes from patient selector
  var choice = parameters['operation']; // Also comes from patient selector
  var pat_id = parameters['patientID']; // Also comes from patient selector
  var form = "<div class ='row'>" //Form as an empty string that will be displayed

  form += "<h3 class = 'col-sm-12 col-md-12 col-xs-12'> Insira os valores númericos dos resultados </h3>"

  //Hemácias
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Hemácias</label><br>"
  form += "<input class='form-norm' name='hemacias' type='text' placeholder='4.85 milhões/dL'></input></div>";
  //Hematócrito
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Hematócrito</label><br>"
  form += "<input class='form-norm' name='hct' type='text' placeholder='35%'></input></div>";
  //Hemoglobina
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Hemoglobina</label><br>"
  form += "<input class='form-norm' name='hgb' type='text' placeholder='12.5 mg/dL'></input></div>";
  //Ureia
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Uréia</label><br>"
  form += "<input class='form-norm' name='ureia' type='text' placeholder='150 mg/dL'></input></div>";
  //Creatinina
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Creatinina</label><br>"
  form += "<input class='form-norm' name='cr' type='text' placeholder='1.0 mg/dL'></input></div>";
  //Potássio
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Potássio</label><br>"
  form += "<input class='form-norm' name='k' type='text' placeholder='4.5 mmol/L'></input></div>";
  //Sódio
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Sódio</label><br>"
  form += "<input class='form-norm' name='na' type='text' placeholder='130.0 mmol/L'></input></div>";
  //Leucócitos
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>Leucócitos</label><br>"
  form += "<input class='form-norm' name='leuco' type='text' placeholder='12000 celulas/dL'></input></div>";
  //INR
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>INR</label><br>"
  form += "<input class='form-norm' name='inr' type='text' placeholder='1.0 mmol/L'></input></div>";
  //PCR
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>PCR</label><br>"
  form += "<input class='form-norm' name='pcr' type='text' placeholder='0.3 '></input></div>";
  //TGO e TGP
  form += "<div class ='col-sm-4 col-md-4 col-xs-12'><label>TGO/TGP</label><br>"
  form += "<input class='form-norm' name='tgo&tgp' type='text' placeholder='3'></input></div>";
  //Outros
  form += "<div class ='col-sm-12 col-md-12 col-xs-12'><label>Outros Exames</label><br>"
  form += "<input class='form-ext' name='outros' type='text' placeholder='Insira resultados de outros exames aqui'></input></div>";

  form += "</div>"; //End row div

  var content = "<div class='row'>"+
          "<form style='margin:auto' class = 'form-group col-md-12 col-sm-12 col-lg-12' action = \"" + controller + "\" method='POST'>" +
            form +
          "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>"+
          "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>"+
          "<input name= 'uniqid' type = 'hidden' value = '"+ uniqid +"'></input>"
      "<br><input class= 'btn btn-success' style= 'width:45%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>"
      if (choice == "LAB_EDIT"){ //Allows for a delete button only in EDITION MODE
        content +="<br><input class= 'btn btn-success' style= 'width:45%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>" +
        "<button class= 'btn btn-warning' style= 'width:45%;margin:1em 0 0 1em' data-uniqid ='"+ uniqid +"' data-operation='DELETE_LAB'" +
      "onclick = 'deleteEntry(this)'>Remover resultado</button> "
      }
      else
        content +="<br><input class= 'btn btn-success' style= 'width:90%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>" +
     "</form>"+
  "</div>";


  document.getElementById("labresults_list").innerHTML = content;


}

/** Parameters -> Form (on page)
 *  Takes the parameters given to create a form containing them all
 *  that can be submitted to the path given. This form is used to edit
 *  precriptions and will be sent to the prescription controller
 */
function renderPrescriptionForm(parameters){

    //For prescription handling, the controller can only be acompanhamento.php
    var controller = "acompanhamento.php";
    //console.log(typeof(parameters.last_p))

    var info = 'Inserir prescrição';
    var uniqid = parameters.uniqid;
    var choice = parameters.operation;
    var pat_id = parameters.patientID;
    var form = '';

    //The form content
    for(var i = 1; i <= 10; i++){

        var current;

        try{
          current = "value = '" + parameters.last_p['med'+i] + "'" ;
            if (parameters.last_p['med'+i] == null) throw "error";

        }
        catch(err){
          current = ""
        }

        form += "<span class='glyphicon glyphicon-minus' aria-hidden='true'></span>"
        form+= "<input name= 'med"+ i + "'  type = 'text' class='form_input'" + current +  "placeholder = 'Medicamento'></input>";
        form+= "<input name= 'dos"+ i + "' type = 'text' class='form_input' placeholder = 'Dose'></input>";
        form+= "<input name= 'via"+ i + "' type = 'text'  class='form_input' placeholder = 'Via'></input>";
        form+= "<select class = 'custom-select' name= 'pos"+ i +"'>";
        form+= "<option value = 'null'> Posologia </option>";
        form+= "<option value = '1x/d' selected> 1x/dia </option>";
        form+= "<option value = '2x/d'> 2x/dia (12/12h) </option>";
        form+= "<option value = '3x/d'> 3x/dia (8/8h) </option>";
        form+= "<option value = '4x/d'> 4x/dia (6/6h) </option>";
        form+= "<option value = '6x/d'> 6x/dia (4/4h) </option>";
        form+= "<option value = 'SOS'> Caso necessário </option>";
        form+= "</select><br>";
    }

    var content = "<div class='row'>"+
            "<form style='margin:auto' class = 'form-group col-sm-12 col-md-12 col-lg-12' action = \"" + controller + "\" method='POST'>" +
              form +
            "<input name= 'operation' type = 'hidden' value = "+ choice +"></input>"+
            "<input name= 'patientID' type = 'hidden' value = "+ pat_id +"></input>"+
            "<input name= 'uniqid' type = 'hidden' value = '"+ uniqid +"'></input>"
        "<br><input class= 'btn btn-success' style= 'width:45%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>"

        if (choice == "PRESCRIPTION_EDIT"){ //Allows for a delete button only in EDITION MODE

          content +="<br><input class= 'btn btn-success' style= 'width:45%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>" +
          "<button class= 'btn btn-warning' style= 'width:45%;margin:1em 0 0 1em' data-uniqid ='"+ uniqid +"' data-operation='DELETE_PRESCRIPTION'" +
          "onclick = 'deleteEntry(this)'>Remover Prescrição</button> "

        }
        else
          content +="<br><input class= 'btn btn-success' style= 'width:90%;margin:1em 0 0 1em' type = 'submit' value= '" + info +"'>" +
       "</form>"+
    "</div>";


    document.getElementById("prescription_list").innerHTML = content;
}

/* ---------------------------------------------------------------
 *
 * Exibe um formulário para adição de comentários no banco de dados
 *
 *----------------------------------------------------------------*/
function showCommentForm(parameters){


    var paciente = document.getElementById("nome").innerText;

    var id_tipo = parameters.dataset.id.substring(0,3); //Todo comentário começa com um cód de 3 letras(med, com, lab)

    parameters.texto = (function(codigo){

        //Define qual tipo de texto será exibido
        switch(codigo){
          case 'med':
              var text = "prescrição";
            break;
          case 'lab':
              var text = "resultado";
            break;
          case 'adm':
              var text = "entrada";
            break;
        }
        return text;
    })(id_tipo); //id_tipo é passado para ser avaliado

    console.log(parameters.texto);

    if(parameters.dataset.operation == "COMMENT_THIS")
      var info = 'Adicionar novo comentário';
    else
      var info = 'Alterar comentário';

    function_call = "submitModal(\'comment\')";

    var content = ""+
    "<div class='modal fade' id='comment_form' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>"+
      "<div class='modal-dialog' role='document'>"+
        "<div class='modal-content'>"+
            "<div class='modal-header'>"+
              "<h3 class='modal-title' id='titulo'>Comentando "+ parameters.texto +" de "+ paciente +"</h3>"+
            "</div>"+
            "<div class='modal-body'>"+
                "<form id= 'comment' class= 'form-group col-12' accept-charset='UTF-8' action ='notas.php' method='POST'>" +
                  "<input name= 'conteudo' class = 'form-ext' type = 'text' placeholder='Comente aqui'></input>" +
                  "<input name= 'operation' type = 'hidden' value = '"+ parameters.dataset.operation +"'/>" +
                  "<input name= 'patientid' type = 'hidden' value = '"+ parameters.dataset.pat_id +"'/>" +
                  "<input name= 'assunto' type = 'hidden' value = '"+ parameters.dataset.id +"'/>" +
                "</form>" +
            "</div>" +
            "<div class='modal-footer'>"+
              "<input class= 'btn btn-default' type = 'button' value= 'Cancelar' data-dismiss='modal'>" +
              "<input class= 'btn btn-success' type = 'button' onclick ="+function_call+" value= '" + info +"'> &nbsp;" +
            "</div>"+
        "</div>"+
      "</div>"+
    "</div>";

    //Console log
    console.log(function_call);

    document.body.innerHTML += content;

    $('#comment_form').modal('show');

}

////////////////////////////////////////////////////////////////////////////////////////////////////
////                                      UTILIDADES                                            ////
////////////////////////////////////////////////////////////////////////////////////////////////////

function newPrescriptionButton(id){

    //Gets the DOM object containg the last prescription timestamp
    var target = document.getElementById("prescription_list");
    var source = target.firstElementChild.firstElementChild.lastElementChild.firstElementChild;
    var date = source.nextSibling.innerHTML;
    var patient = id;

    if(patient == "Paciente" || date == "Data"){//If it is the first prescription
          date = 'null';
    }


    var content ="<input id ='dateinfo' data-patient ='"+ patient +"'";
    content +="data-operation = 'PRESCRIPTION_ADD'";
    content += "data-date ='" + date + "'";
    content += "type = 'button' onClick = 'prescriptionHandler(this)'";
    content += "class= 'btn btn-success' value='Adicionar Prescrição'/>";

    target.innerHTML += content;
}

function newLabButton(id){

  var target = document.getElementById("labresults_list");
  var source = target.firstElementChild.firstElementChild.lastElementChild.firstElementChild;
  var date = source.nextSibling.innerHTML;
  var patient = id;

  if(patient == "Paciente" || date == "Data"){//If it is the first prescription
        date = 'null';
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

function submitModal(form){

    var form = document.getElementById(form).submit();
}

//Retorna o número de comentários como um badge
function getCommentNumber(target,paciente){

  var comments;
  var target = document.getElementById(target);

  $.post("notas.php",{operation:"RETRIEVE", patientid:paciente}).done(function(data){

    if (data == false)
      comments = "0";
    else
      comments = data.length;

    //Define a chamada do onclick
    var call = "onclick=\"displayComments('" + target.id + "'," + paciente +")\"";

    target.innerHTML += "<td>"+
                          "<button type='button' style='width:60%;margin-left:15%' "+call+" class='btn btn-default'> Ver Comentários"+
                          "<span class='glyphicon glyphicon-chevron-right' aria-hidden='true'> <span class='badge'>"+comments+"</span></span>"+
                          "</button>"+
                        "</td>";
  });
}

//Cria uma lista de comentários para um paciente específico
/*
* Linha é uma linha da tabela passada como nó HTML
*/
function displayComments(line, paciente){

  //Se já existir um comentário sendo exibido, limpa os comentários

  //Obtem o elemento da pagina com o id da linha da tabela
  var table = document.getElementById(line).parentNode;
  console.log("A função foi chamada");

  $.post("notas.php", {operation:"RETRIEVE", patientid: paciente}).done(function(data){

      for(var i = 0; i < data.length; i++){

        var comment = data[i];
        var commentinfo = "";
        var content = "";

        //Condensa toda a informação de comentarios em as insere na tabela para exibição
        for(var j = 1; j < Object.keys(comment).length; j++){
          commentinfo += "<td>" + comment[Object.keys(comment)[j]] + "</td>";
          console.log(comment[Object.keys(comment)[j]]);

          if (j == (Object.keys(comment).length) - 1)
            content += "<tr>" + commentinfo + "</tr>";
        }
        table.innerHTML += content;
      }
   });
 }

 function displayPrescription(id){

   var params = function(){
     if (id){
       var params1 = {
          operation: 'GET_PRESCRIPTION',
          uniqid : id
       }

       return params;
     }
     else {

       var params2 = {
          operation: 'ALL_PRESCRIPTIONS',
         //Pensar num jeito de obter user e patientID via JS.
       }
     }
   }

   var html = "<th>Paciente</th>"+
              "<th>Data</th>"+
              "<th colspan='13'>Medicamentos </th>";

   $.post('acompanhamento.php', params).done(function(prescriptions){
     if (prescriptions){
       for(var i = 0; i < prescriptions.length; i++){

         prescriptions[i] = prescriptions[i].slice(2); //Remove ID and userID from array

         //Header da tabela que será exibida
         html += "<tr>"+
         "<td>" +
         prescriptions[i]["patientID"]+
         "</td>"+
         "<td>" +
         prescriptions[i]["date"]+
         "</td>";

         //Imprime os medicamentos listados de 1 à 10 na página
         for(var i = 1; i <= 10; i++){
           if (prescriptions[i]["med"+ i] == "1x/d")
           html += "<td>"+"</td>";
           else
           html += "<td>"+ prescriptions[i]["med"+i] +"&nbsp"+ prescriptions[i]["pos"+i]+"</td>";
         }

         //Botão de comentário
         html += "<td>"+
                 "<button data-id ='" + prescriptions[i].uniqid + "'"+
                          "data-operation = \"COMMENT_THIS\"" +
                          "data-pat_id = '"+ prescriptions[i].patientID +"'"+
                          "type = 'button' onClick = 'showCommentForm(this)'"+
                          "class= 'btn btn-default' value='Editar Prescrição'>"+
                           "<span class='glyphicon glyphicon-comment'></span></button>"+
               "</td>";

         //Botão de edição
         html +="<td>"+
                 "<button  data-id ='" + prescriptions[i].uniqid +"'"+
                          "data-operation = \"PRESCRIPTION_EDIT\""+
                          "data-patient =" + prescriptions[i].patientID +"'"+
                          "type = 'button' onClick = 'prescriptionHandler(this)'"+
                          "class= 'btn btn-default' value='Editar Prescrição'>"+
                           "<span class='glyphicon glyphicon-pencil'></span></button>";
         html += "</td></tr></div>";

         }}

         return html;



   });
}
//Readies the Fake-select module
  jQuery(document).ready(function($) {
    $('.fake-select').fakeSelect();
  });

  function test(){
      $.post("acompanhamento.php", {operation:"GET_PRESCRIPTION", uniqid:'med5947329ec5e5d'}).done(function(data){

          console.log(data);
      });

  }
