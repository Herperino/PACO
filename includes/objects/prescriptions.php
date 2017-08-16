<?php

class Prescription{

  private $uniqid;
  private $date;
  private $patient;
  public $medications;


  public function __construct($patient){

      //Initializes a prescription with a patient, id and date;
      $this->uniqid = uniqid("med");
      $this->patient = $patient;
      $this->date = date('Y-m-d H:i:s');
  }

  /* -------------------------------
    Restaura um objeto prescrição a partir do uniqid
     ---------------------------------*/
  public static function restorePrescription($uniqid){

    $prescription =  new Prescription("");

    $original = pg_query("SELECT * FROM public.\"prescriptions\" WHERE uniqid = $uniqid");

    $params = pg_fetch_all($original);

    $prescription->setDate($params[0]['date']);
    $prescription->setUniqid($params[0]['uniqid']);
    $prescription->setPatient($params[0]['PatientID']);

    //TODO: Loop que coloca todos os medicamentos como parte do objeto

    return $prescription;
  }

  /* --------------------------------------------
    Setters e Getters
  -----------------------------------------------*/
  private function setDate($date){
    $this->date = $date;
  }

  private function setPatient($patient){
    $this->patient = $patient;
  }

  private function setUniqid($uniqid){
    $this->uniqid = $uniqid;
  }

  /** --------------------------------------------------------
  *   Inclui prescrições no banco de dados
  *
  *   $patientID é recebido do lado do cliente
  *   $conn é a conexão padrão definida.
  *
  *   $conn é necessária para o funcionamento do query
  *-----------------------------------------------------------*/
  public function addPrescription($patientID,$conn){

    //Concatena cada entrada em uma única entrada medicamento + posologia
    for ($i = 1; $i<=10; $i++){

      $currentM = "med" . $i;
      $currentD = "dos" . $i;
      $currentV = "via" . $i;
      $currentP = "pos" . $i;

      $prescriptions[$currentM] = $_POST[$currentM] ." ". $_POST[$currentD] ." ". $_POST[$currentV] ;
      $prescriptions[$currentP] = $_POST[$currentP];

    }

    //Query que inclui a informação no banco de dados
    pg_query($conn,"INSERT INTO public.\"prescriptions\"(\"uniqid\",\"patientID\",\"userID\",
      \"med1\",\"pos1\",\"med2\",\"pos2\",\"med3\",
      \"pos3\",\"med4\",\"pos4\",\"med5\",\"pos5\",
      \"med6\",\"pos6\",\"med7\",\"pos7\",\"med8\",
      \"pos8\",\"med9\",\"pos9\",\"med10\",\"pos10\")
      VALUES ('".$this->uniqid."','".$this->patient."','".$_SESSION['id']."',
      '".$prescriptions['med1']."','".$prescriptions['pos1']."',
      '".$prescriptions['med2']."','".$prescriptions['pos2']."',
      '".$prescriptions['med3']."','".$prescriptions['pos3']."',
      '".$prescriptions['med4']."','".$prescriptions['pos4']."',
      '".$prescriptions['med5']."','".$prescriptions['pos5']."',
      '".$prescriptions['med6']."','".$prescriptions['pos6']."',
      '".$prescriptions['med7']."','".$prescriptions['pos7']."',
      '".$prescriptions['med8']."','".$prescriptions['pos8']."',
      '".$prescriptions['med9']."','".$prescriptions['pos9']."',
      '".$prescriptions['med10']."','".$prescriptions['pos10']."')  ");
    }

    /* -------------------------------
        Edita a prescrição e a insere no banco de dados
       ---------------------------------*/
    public function editPrescription($conn){

      //Concatenates the prescription data into a single k/v array
      for ($i = 1; $i<=10; $i++){

        $currentM = "med" . $i;
        $currentD = "dos" . $i;
        $currentV = "via" . $i;
        $currentP = "pos" . $i;

        $prescriptions[$currentM] = $_POST[$currentM] ." ". $_POST[$currentD] ." ". $_POST[$currentV] ;
        $prescriptions[$currentP] = $_POST[$currentP];
      }

      //Prepara o array para ser inserido na query
      foreach ($prescriptions as $key => $value){

          $query[$key] = $key . " = '" . $value ."'";

      }

      //Impode o array de k/v no query e a insere no banco de dados
      pg_query("UPDATE public.\"prescriptions\" SET "

          . implode(' , ', $query) .

        " WHERE \"uniqid\" = '".$this->uniqid."'");
      }

      /**
      * Takes data from the server to display the prescription list
      * for a registered patient.
      *
      * It must be called within a table div.
      */
      public static function displayPrescription($prescriptions){
        $html = "<th>Paciente</th>".
        "<th>Data</th>".
        "<th colspan='13'>Medicamentos </th>";

        if (!empty($prescriptions)){
          foreach($prescriptions as $prescription){
            $prescription = $sub = array_slice($prescription, 2, null, true); //Remove ID and userID from array

            //Header da tabela que será exibida
            $html .= "<tr>".
            "<td>" .
            $prescription["patientID"].
            "</td>".
            "<td>" .
            $prescription["date"].
            "</td>";

            //Imprime os medicamentos listados de 1 à 10 na página
            for($i = 1; $i <= 10; $i++){
              if (strcmp($prescription["med".$i]," 1x/d") < 0)
              $html .= "<td>"."</td>";
              else
              $html .= "<td>". $prescription["med".$i] ."&nbsp". $prescription["pos".$i]."</td>";
            }

            //Botão de comentário
            $html .= "<td>

                    <button  data-id = '".$prescription["uniqid"]."'
                             data-operation = \"COMMENT_THIS\"
                             data-pat_id = '".$prescription["patientID"]."'
                             type = 'button' onClick = 'showCommentForm(this)'
                             class= 'btn btn-default' value='Editar Prescrição'>".
                              "<span class='glyphicon glyphicon-comment'></span>
                    </button>


                  </td>";

            //Botão de edição
            $html .="<td>

                    <button  data-id =" . (string)$prescription['uniqid'] ."
                             data-operation = \"PRESCRIPTION_EDIT\"
                             data-patient =" . (string)$prescription['patientID'] ."
                             type = 'button' onClick = 'prescriptionHandler(this)'
                             class= 'btn btn-default' value='Editar Prescrição'>".
                              "<span class='glyphicon glyphicon-pencil'></span></button>";
            $html .= "</td></tr></div>";

            }}

            return $html;
          } .= "<td>

                    <button  data-id = '".$prescription["uniqid"]."'
                             data-operation = \"COMMENT_THIS\"
                             data-pat_id = '".$prescription["patientID"]."'
                             type = 'button' onClick = 'showCommentForm(this)'
                             class= 'btn btn-default' value='Editar Prescrição'>".
                              "<span class='glyphicon glyphicon-comment'></span>
                    </button>


                  </td>";

            //Botão de edição
            $html .="<td>

                    <button  data-id =" . (string)$prescription['uniqid'] ."
                             data-operation = \"PRESCRIPTION_EDIT\"
                             data-patient =" . (string)$prescription['patientID'] ."
                             type = 'button' onClick = 'prescriptionHandler(this)'
                             class= 'btn btn-default' value='Editar Prescrição'>".
                              "<span class='glyphicon glyphicon-pencil'></span></button>";
            $html .= "</td></tr></div>";

            }}

            return $html;
          }

}
?>
