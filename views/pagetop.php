<!DOCTYPE html>

<HTML lang ="PT">
    <HEAD>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">        
        <!-- http://getbootstrap.com/ -->
        <link href="/css/bootstrap.min.css" rel="stylesheet"/>

        <!-- File's CSS stylesheet -->
        <link href = "/css/style.css" rel="stylesheet"/>

        <!-- Stacktable's files -->
        <link href = "/css/Stacktable.css" rel="stylesheet"/>
        <script src="/js/stacktable.js"></script>

        <!-- http://jquery.com/ -->
        <script src="/js/libs/jquery-1.11.3.min.js"></script>

        <!-- https://github.com/twitter/typeahead.js/ -->
        <script src="/js/typeahead.jquery.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <script src="/js/bootstrap.min.js"></script>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

        <!-- Local script files -->
        <script src="js/paco_main.js"></script>

        <script src="js/paco_visuals.js"></script>

        <!-- Fake-select.js by Takien on GitHub -->
        <script src="js/fake-select.js"></script>

        <Title> PACO | Programa de Acompanhamento Farmacêutico</Title>
</HEAD>
    <BODY>
       <nav class="marginless navbar navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>

              <a class="navbar-brand" href="index.php"><span class="glyphicons glyphicons-medicine"></span>PACO</a>
            </div>
             <?php

             if(isset($_SESSION['id'])){

                print("<div class='collapse navbar-collapse' id='myNavbar'>

                        <ul class='nav navbar-nav'>
                          <li><a href='acompanhamento.php'>Prescrições</a></li>
                          <li><a href='labref.php'>Laboratório</a></li>
                          <li><a href='notas.php'> Observações</a></li>
                        </ul>


                    <form id='signin' class='navbar-form navbar-right' role='form' action='logout.php' method='post'>
                        <button type='submit' class='btn btn-primary'>Sair</button>
                   </form>
                   </div>");
             }
             else{
               print("<div class='collapse navbar-collapse' id='myNavbar'>

                    <form id='signin' class='navbar-form navbar-right' role='form' action='login.php' method='post'>
                    <div class='input-group'>
                        <span class='input-group-addon'></span>
                        <input id='login' type='text' class='form-control' name='id' value='' placeholder='Login'>
                    </div>

                    <div class='input-group'>
                        <span class='input-group-addon'></span>
                        <input id='password' type='password' class='form-control' name='password' value='' placeholder='Senha'>
                    </div>

                    <button type='submit' class='btn btn-primary'>Entrar</button>
               </form>
               </div>");
               }

             ?>
          </div>
        </nav>
<?php
     if(isset($_SESSION['id']))
        print("<div id = 'pagemid' class= 'container-fluid'>");
    else
        print("<div id = 'pagemid'");
 ?>
