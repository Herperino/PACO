<!-- This is the view model for the registration page-->

<?php if($_SERVER['REQUEST_METHOD'] == "GET"):?>
<div class ="container">
    <div class = "row">
        <div class = 'offset-md-2 col-x-10 col-md-8'>
            <h1>Registre-se no PACO</h1>

            <div class ="form-group">

            <form id="register" action="register.php" method="POST" onsubmit ="validate()" >
                <label for="regisid">Nome</label>
                <input type="text" class="form-control" name="regisid" placeholder="Seu nome aqui" required/>
                <small class="form-text text-muted">Seu email será usado como login no PACO</small><br><br>

                <label for="email">E-mail</label>
                <input type="text" class="form-control" name="email" placeholder="email@email.com" required/>
                <small class="form-text text-muted">Nós do PACO nunca enviaremos emails pedindo informações pessoais</small></small><br><br>
                <label for="regispwd">Senha</label>
                <input type="password" class="form-control" name="regispwd" placeholder="********" required/>
                <label for="confirmation">Confirme sua senha</label>
                <input type="password" class="form-control" name="confirmation" placeholder="********" required><br>

                <label for="testebeta">Insira aqui sua chave de autorização ao teste do PACO</label>
                <input type="password" class="form-control" name="testebeta" placeholder="" required><br><br>
                <input class='btn btn-success'type="submit" value= "Registrar">
            </form>
          </div> <!--end form-->
        </div><!--end columns-->
</div>
</div>
</?>
<?php else: ?>
   <div class='container'><h2 class='alert alert-success'> Cadastro realizado com sucesso</h2></div>
<?php endif?>
