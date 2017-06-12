<?php
/* ----------------------------------------------------
 *  Arquivo de objetos
 * ---------------------------------------------------*/

/*------------------------
	Comment é um objeto que representa um comentário 
	no banco de dados. Quando instanciado, requer
	Assunto, Conteúdo. 

	Permite se inserir no banco de dados através do método
	addComment;

	Permite ser editado através do método:
	editComment;
 -----------------------*/
class Comment{

	public $author; //Autor do comentário
	public $content; //Conteudo do comentário
	public $subject; //Assunto do comentário (prescrição, resultado)
	private $id; //id gerada aleatóriamente

	public function Comment($assunto,$conteudo,$ide){ //Construtor

		$author = $autor;
		$subject = $assunto;
		$content = $conteudo;
		$id = $ide;
		$conn = $conn;
	}

	public function databaseIt($conn){

		$query = "" //Query aqui

		echo "Inserido no banco com id =" . $id;
		//pg_query($conn, $query);
	}

	public function getId(){
		return $id;
	}

	public function showComment($id){

		pg_query("SELECT * FROM public.\"\"   ")

		return $comment;
	}
}

?>