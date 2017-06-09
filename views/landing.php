<?php
    if(!empty($_SESSION['id']))
    {
        redirect("/acompanhamento.php");
    }
    
    else
    {   
        print("<div class = 'jumbotron bg1'>
                    <div class='container'>
                        <br>
                            <div class= 'title'>
                                <h1> PACO </h1>
                                <h3> Programa de acompanhamento farmacoterapêutico</h3>
                                <br>
                                Todos os seus pacientes em um só lugar.
                                <br>
                                <br>
                                <small>Acompanhe, avalie, discuta.</small>

                                <br>
                                <br>
                                <br>
                                <div>
                                    <a href='register.php'><button type='button' class='btn btn-success'> Inscreva-se </button></a>
                                </div>
                            </div>
                    </div>    
                </div>
                <div class = 'jumbotron' id = 'Sobre'>
                    <div class = 'container'>
                        <div class ='row'>
                            <div class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12'>    
                                                 
                                <div class = 'row'>
                                    <div class = 'icons col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                                            <h2>Acompanhe</h2>
                                            <img class = 'ico' src='https://image.flaticon.com/icons/svg/1/1755.svg' alt='View details free icon' title='View details free icon'>

                                        </div>
                                    <div class = 'icons col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                                            <h2>Avalie</h2>
                                            <img class ='ico' src='https://image.flaticon.com/icons/svg/344/344074.svg' alt='Evaluation free icon' title='Evaluation free icon'>
                                        </div>

                                    <div class = 'icons col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                                                <h2>Discuta<h2>
                                                <img class ='ico' src='https://image.flaticon.com/icons/svg/134/134807.svg' alt='Chat free icon' title='Chat free icon'>
                                        </div>
                                </div>

                                <p class= 'content'> O Acompanhamento Farmacoterapêutico é definido como uma prática personalizada na qual o farmacêutico tem a responsabilidade de orientar o paciente, além de detectar, prevenir e resolver todos os problemas relacionados com medicamentos (PRM) de uma maneira contínua, sistemática e documentada, em colaboração com o paciente e equipe multiprofissional. O <strong>programa de acompanhamento farmacêutico</strong> é uma ferramenta elaborada por mim (Leon Nascimento) para facilitar o acompanhamento de prescrições de pacientes internados em ambiente hospitalar ou ambulatorial.
                                </p>                 
                                        
                            </div>
                        </div>
                    </div>
                </div>"
        );
        
    }
?>