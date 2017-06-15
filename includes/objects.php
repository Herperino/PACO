<?php
/* ----------------------------------------------------
 *  Arquivo de objetos
 * ---------------------------------------------------*/

/*------------------------
	Comment é um objeto que representa um comentário 
	no banco de dados. Quando instanciado, requer
	Assunto, Conteúdo. x

	Permite se inserir no banco de dados através do método
	addComment;

	Permite ser editado através do método:
	editComment;
 -----------------------*/
class Comment{

	public $author; //Autor do comentário
	public $content; //Conteudo do comentário
	public $subject; //Assunto do comentário (prescrição, resultado)
	private $uniqid; //id gerada aleatóriamente
	public $sessid = "ss_default"  ; //id de sessão default ("ss" + id de usuário)

	public function __construct($assunto,$conteudo){ //Construtor

		$this->author = $_SESSION['id'];
		$this->subject = $assunto;
		$this->content = $conteudo;
		$this->uniqid = uniqid("cmt");		
	}

	public function databaseIt($conn){

		//$query = "" //Query aqui

		echo "Inserido no banco com id =" . $this->uniqid;
		//pg_query($conn, $query);
	}

	public function getId(){
		return $this->uniqid;
	}

	public function showComment(){

		//pg_query("SELECT * FROM public.\"comments\" WHERE uniqid =".$this->uniqid."")



		echo("<br>

			<div class ='jumbotron'>
				$author disse:
				<hr>
				$content

				sobre:
				$subject;

				<small>id de comentário:$uniqid</small>
			<br>
			");

		return $comment;
	}
}

?>