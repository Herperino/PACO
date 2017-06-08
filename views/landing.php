<?php
    if(!empty($_SESSION['id']))
    {
        redirect("/acompanhamento.php");
    }
    
    else
    {   
        print("<div class = 'jumbotron' style='background-color: transparent !important;'>
                    <div class='container'>
                        <br><br>
                            <div class= 'title' style='font-family:Helvetica' >
                                <h1> PACO </h1>
                                <h3> Programa de acompanhamento farmacoterapêutico</h3>
                                 <br><br>
                                <div class ='row'>
                                    <div class = 'content col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                        O Acompanhamento Farmacoterapêutico é definido como uma prática personalizada na qual o 
                                        farmacêutico tem a responsabilidade de orientar o paciente, 
                                        além de detectar, prevenir e resolver todos os problemas relacionados com medicamentos (PRM) de uma maneira
                                        contínua, sistemática e documentada, em colaboração com o paciente e equipe multiprofissional. 
                                        O <strong>programa de acompanhamento farmacêutico</strong> é uma ferramenta elaborada por mim (Leon Nascimento)
                                        para realizar o acompanhamento de prescrições de pacientes internados em ambiente hospitalar ou ambulatorial
                                    
                                        <br><br>
                                        <div>
                                            <a href='register.php'><button type='button' class='btn btn-success'> Inscreva-se </button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>    
                </div>
            
        ");
        
    }
?>