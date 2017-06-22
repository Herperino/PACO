<!--Chegou-se à pagina via GET-->

<?php if($_SESSION['id'] != 3):?>
<div class = 'container'>
    <div class= 'page-header'>
        <h2>Em construção </h2> 
    </div>
    <img src='https://i.giphy.com/3DnDRfZe2ubQc.gif'/>
</div>

<!-- Não é um usuário comum -->
<?php else: ?>

<!--h1>O FAMOSO DEUS DA PROGRAMAÇÂO</h1-->
<div class = "container" id = "notas">
	<!--img src="https://cdn-images-1.medium.com/max/800/1*uizrmPyTSyJIcxB8nQk8WA.gif" /-->
	
	
</div>
	<br>	
	<div class='panel panel-default'> 
		<div class='panel-heading'>Pacientes</div>
            <div class='panel-body'>
            Abaixo você encontra todos os comentários sobre seus pacientes +
             </div> 
            <table id = "lista" class='table list-group'>
                  
            </table> +
    </div>
<input class = 'btn btn-warning' onclick="makeCommentList()" value="Mostrar pacientes"/>
<!-- Construir aqui -->
<?php endif?>
