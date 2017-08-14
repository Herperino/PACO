<?php

class Patient{

  private $status;
  private $id;
  private $nome;
  private $dono;
  private $identificador;
  private $idade;
  private $ultima_att;

  public function __construct(){}

  public function getName($conn){

    return $nome;
  }

  public static function restorePatient($uniqid){

    $patient = new Patient();

    //Recebe o ID de paciente via POST
    $query = pg_query($conn, "SELECT * FROM public.\"patients\" WHERE
              uniqid = '".$uniqid."'");

    $patients = pg_fetch_all($query); //Obtem a linha que contém o paciente

    $patient->id = $patientID;
    $patient->name = ($patients[0]['patientname']);
    $patient->status = ($patients[0]['p_status']);
    $patient->dono = ($patients[0]['userid']);
    $patient->identificador = ($patients[0]['patientid']);
    $patient->idade = ($patients[0]['idade']);

    return patient;

  }
  /**-----------------------------------------------
  *  Inclui um novo paciente no banco de dados.
  *  Informações do paciente, como $patientage e $patientID são oriundas
  *  do formulário enviado por POST.
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  public static function addPatient($conn){

      $patient = new Patient();

      //Variáveis obtidas via POST
      $paciente->nome = $_POST['patient_name'];
      $paciente->idade = $_POST['patient_age'];
      $pacient->id = uniqid('ptt');
      $paciente->idenficador= ltrim($_POST['new_id'],"0"); //Remove 'trailing zeroes'
      $paciente->dono = $_SESSION['id'];
      $paciente->status = 1;

      $collision = checkCollision($paciente->idenficador, "patients");

      if ($collision == TRUE)
        return false; //Retorna falso em caso de colisão

      else
        return $paciente; //Retorna um objeto paciente se a inserção ocorrer com sucesso
  }

  public function databaseIt(){
    //Insere um novo paciente no banco de dados
    pg_query($conn,"INSERT INTO public.\"patients\"(id,patientid, patientname, patientage,userid, p_status)
    values (DEFAULT,'". $paciente->idenficador ."','".$paciente->nome."','".$paciente->idade."','".$paciente->dono."', '1')");
  }

  /**-----------------------------------------------
  *  Altera o status de acompanhamento de paciente no banco de dados.
  *  Informações do paciente são passadas por $patientID.
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  public function changeStatus($patientID, $conn){

    //Altera o status conforme o status atual
    if($this->status == 1) {

      pg_query($conn,"UPDATE public.\"patients\"
                      SET p_status = 0
                      WHERE patientid = '".$patientID."'
                      AND userid = '".$_SESSION['id']."'");
    }
    else {

      pg_query($conn,"UPDATE public.\"patients\"
                      SET p_status = 1
                      WHERE patientid = '".$patientID."'
                      AND userid = '".$_SESSION['id']."'");
    }

  }

  /**-----------------------------------------------
  *  Inclui um altera os dados de paciente no banco de dados.
  *  Informações do paciente, como $patientage e $patientID são oriundas
  *  do formulário enviado por POST.
  *
  *  Também são feitas alterações no banco de dados onde o ID de paciente
  *  é utilizado (prescriptions e labref)
  *
  *  Requer uma conexão ($conn) ativa com o banco de dados para funcionar
  *----------------------------------------------*/
  public function editPatient($patientID, $conn){

    //Se o ID de paciente é mantido, atualiza seus dados
    if ($this->identificador == $patientID){

        pg_query($conn,"UPDATE public.\"patients\" SET
          patientname ='". $_POST['patient_name'] ."',
          patientage = ". $_POST['patient_age'] ."

          WHERE uniqid = '".$this->id."'");
    }

    //Se o ID de paciente é diferente, atualiza as tabelas
    else{
      $this->identificador = $_POST['new_id'];

      //Verifica se há colisão entre o novo ID com os ids no banco de dados
      $collision = checkCollision($this->identificador, "patients");

      if ($collision == TRUE) //Se houve colisão de IDs
        return false; //Retorna FALSE se não tiver sucesso em alterar o conteúdo

      else{ //Se não houver colisão de IDs

        //As queries para atualização de patients, prescriptions e labref
        pg_query($conn,"UPDATE public.\"patients\" SET
          patientid = '". $this->identificador ."',
          patientname = '". $this->nome  ."',
          patientage = ". $_POST['patient_age'] ."
          WHERE patientid = '".$_POST['patientID'] ."'
          AND userid = '".$_SESSION['id']."'");

        pg_query($conn,"UPDATE public.\"prescriptions\" SET
          patientid = '". $this->identificador ."'
          WHERE patientid ='". $this->identificador."'
          AND \"userID\" = '".$_SESSION['id']."'");

        pg_query($conn,"UPDATE public.\"labref\" SET
          patientid = '". $this->identificador ."'
          WHERE patientid ='". $_POST['patientID']."'
          AND userid = '".$_SESSION['id']."'");
      }
    }

    return true; //Retorna TRUE se tiver sucesso em alterar o conteúdo
  }

  public function remover($uniqid){
    pg_query($conn, "DELETE FROM public.\"patients\" WHERE patientid ='".$uniqid."'");
  }

  /**
  * Retorna um array contendo todos os pacientes de um usuario;
  */
  public static function showAllPatients($user){

    //Busca o banco de dados pelo ID do usuário ordenando pela última modificação
    $query = "SELECT * FROM public.\"patients\" WHERE userid = '".$user."' ORDER BY p_status DESC,lastactive DESC ";
    $data = pg_query($conn, $query);

    //Reúne pacientes em um array
    $patients = pg_fetch_all($data);

    //Retorna os pacientes como um objeton em notação Javascript (JSON)
    return patients;
  }

  /**------------------------------------
  *  Verifica se ocorre uma colisão entre um id que pretende
  *  ser incluído ou alterado e um id já existente no banco de
  *  dados
  *
  * $id é o ID a ser verificado e $table é uma string com o nome da tabela
  *----------------------------------------*/
  private function checkCollision($id, $table){

    //Verifica se há colisão de IDs
    $check = pg_query("SELECT * FROM public.\"".$table."\"
                       WHERE patientid = '".$id."'
                       AND userid = '".$_SESSION['id']."'");

    $collision = pg_fetch_all($check); //TRUE se o tamanho do array retornado é maior que 1;

    return $collision != false;
  }


}
?>
