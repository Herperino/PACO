<?php
    if(!empty($_SESSION['id']))
    {
        redirect("/acompanhamento.php");
    }

    else
    {
        //Requer os componentes HTML para montar a página
        require_once('../includes/html_components/landing_components.php');

        //Monta a página com base nos componentes acima
        print("<div class = 'jumbotron bg1' id='Titulo'>"
                .$title_div.
                "</div>
                <div class = 'jumbotron' id = 'Sobre'>"
                .$about_div.
                "</div>");
    }
?>
