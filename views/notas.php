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
<div class = "container" id = "notas">
    <div class = 'page-header'><h2>Notas</h2></div>
        <h4>Pacientes em acompanhamento por <?php echo $_SESSION['username'] ?></h4>
	<br>   
    <div class='panel panel-default'> 
        <div class='panel-heading'>Pacientes</div>
            <div class='panel-body'>
            Abaixo você encontra todos os comentários sobre seus pacientes
             </div> 
            <table id = "lista" class='table list-group'>
                  <tr>
                      <th>ID</th>
                      <th>Nome</th>
                      <th style="text-align:centercd">Comentários</th>
                  </tr>
            </table>           
        </div>
    <script>
     $(document).ready(makeCommentList());
    </script>
</div>
	

<!-- Construir aqui -->
<?php endif?>
