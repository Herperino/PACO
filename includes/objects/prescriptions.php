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
}
?>
