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

	public function __construct($assunto,$conteudo,$sessao){ //Construtor

		$this->author = $_SESSION['id'];
		$this->subject = $assunto;
		$this->content = $conteudo;
		$this->uniqid = uniqid("cmt");
		$this->sessid = "ss".$sessao;
	}

	public function databaseIt($conn){

		//Query aqui
		$query = "INSERT INTO public.\"comments\"(uniqid,autor,conteudo, assunto, id_sessao) 
							  VALUES ('".$this->uniqid."','".$this->author."','".$this->content."','".$this->subject."','".$this->sessid."')";
		pg_query($conn, $query);

		echo "Inserido no banco com id =" . $this->uniqid;
	}

	public function getId(){
		return $this->uniqid;
	}

	public function setSubject($assunto){

		$this->subject = $assunto;

	}

	public function setSubject($conteudo){

		$this->content = $conteudo;

	}

	public function showComment(){

		//pg_query("SELECT * FROM public.\"comments\" WHERE uniqid =".$this->uniqid."")
		$author = $this->author;
 		$content = $this->content;
 		$subject = $this->subject;
 		$uniqid = $this->uniqid;

		echo("<br>

			<div class ='jumbotron'>
				$author disse:
				<hr>
				$content

				<br>
				sobre:
				$subject;
				<BR>
				<small>id de comentário:$uniqid</small>
			<br>
			</div>
			");

		//return $comment;
	}
}

?>