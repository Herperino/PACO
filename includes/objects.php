<?php
/* ----------------------------------------------------
 *  Arquivo de objetos
 * ---------------------------------------------------*/

/*------------------------
	Comment é um objeto que representa um comentário 
	no banco de dados. Quando instanciado, requer
	Assunto, Conteúdo. x

	Permite se inserir no banco de dados através do método
	databaseIt;

	Permite ser editado através do método:
	updateIt;
 -----------------------*/
class Comment{

	private $author; //Autor do comentário
	public $content; //Conteudo do comentário
	public $subject; //Assunto do comentário (prescrição, resultado)
	private $uniqid; //id gerada aleatóriamente
	private $sessid = "ss_default"  ; //id de sessão default ("ss" + id de usuário)

	/*-------------------------------------

	Construtor default do objeto

	--------------------------------------*/
	public function __construct($assunto,$conteudo,$id){ //Construtor

		$this->author = $_SESSION['id'];
		$this->subject = $assunto;
		$this->content = $conteudo;
		$this->uniqid = uniqid("cmt");
		$this->sessid = "ss".$id;
	}
	/*--------------------------------------

	Restaura um objeto à partir do seu ID

	--------------------------------------*/
	public static function restoreComment($id){

		$obj = new Comment("", "","");

		$original = pg_query("SELECT * FROM public.\"comments\" WHERE uniqid = $id");

		$params = pg_fetch_all($original);

		$obj->content = $params[0]['conteudo'];
		$obj->subject = $params[0]['assunto'];
		$obj->author = $params[0]['autor'];
		$obj->sessid = $params[0]['id_sessao'];
		$obj->setUniqid($id);

		return $obj;
	}

	/*--------------------------------------

	Insere um comentário no banco de dados

	----------------------------------------*/
	public function databaseIt($conn){

		//Query aqui
		$query = "INSERT INTO public.\"comments\"(uniqid,autor,conteudo, assunto, id_sessao) 
							  VALUES ('".$this->uniqid."','".$this->author."','".$this->content."','".$this->subject."','".$this->sessid."')";
		pg_query($conn, $query);

		echo("FUNCIONOU E O ID inserido foi $uniqid");
	}

	/*-----------------------------------

	Atualiza uma entrada no banco de dados

	------------------------------------*/

	public function updateIt($conn){

		//Query aqui
		$query = "UPDATE public.\"comments\" SET conteudo = '". $this->content."'assunto = '".$this->subject."'";
		pg_query($conn, $query);
	}

	public function getUniqid(){
		return $this->uniqid;
	}


	private function setUniqid($uniqid){

		$this->uniqid = $uniqid;

	}
}

?>