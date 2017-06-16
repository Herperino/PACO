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

<h1>O FAMOSO DEUS DA PROGRAMAÇÂO</h1>
<?php $comment->showComment(); ?>
<img src="https://cdn-images-1.medium.com/max/800/1*uizrmPyTSyJIcxB8nQk8WA.gif" />
<!-- Construir aqui -->
<?php endif?>
