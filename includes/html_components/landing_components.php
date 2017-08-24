<?php

    //Define uma templateque vai ser usada na montagem do about_div
    // $icon_template = function($icon_url,$texto)
    // {
    //       return "<div class = 'icons col-xs-12 col-sm-4 col-md-4 col-lg-4'>
    //                 <h2>Acompanhe</h2>
    //                 <img class = 'ico' src='$icon_url'>
    //                 <br><br>
    //                 <small>$texto</small>
    //               </div>";
    //
    // };

    //Div título do landing
    $title_div = "<div class='container title' id='texto_titulo'>
                    <br>
                      <div>
                          <h1> PACO </h1>
                          <h3> Programa de acompanhamento farmacoterapêutico</h3>

                          <br>
                          Todos os seus pacientes em um só lugar.
                          <br><br>

                          <small>Acompanhe, avalie, discuta.</small>

                          <br><br><br>

                          <div>
                              <a href='register.php'><button type='button' class='btn btn-success'> Inscreva-se </button></a>
                          </div>
                      </div>
                  </div>";

    //Div 'sobre' do landing
    $about_div = "<div class = 'container'>
        <div class ='row'>
            <div class = 'col-xs-12 col-sm-12 col-md-12 col-lg-12'>

                <div class = 'row'>"
                    .$icon_template('https://image.flaticon.com/icons/svg/1/1755.svg','Adicione prescrições e resultados laboratoriais de forma fácil').
                    .$icon_template('https://image.flaticon.com/icons/svg/344/344074.svg','Visualize prescrições anteriores e contraste com resultados laboratoriais').
                    .$icon_template('https://image.flaticon.com/icons/svg/134/134807.svg','Comente e discuta sobre os seus pacientes de maneira rápida e simples').
                "</div>

                <br><br>
                <small> O Acompanhamento Farmacoterapêutico é definido como uma prática personalizada na qual o farmacêutico tem a responsabilidade de orientar o paciente, além de detectar, prevenir e resolver todos os problemas relacionados com medicamentos (PRM) de uma maneira contínua, sistemática e documentada, em colaboração com o paciente e equipe multiprofissional. O <strong>programa de acompanhamento farmacêutico</strong> é uma ferramenta elaborada para facilitar o acompanhamento de prescrições de pacientes internados em ambiente hospitalar ou ambulatorial.
                </small>

            </div>
        </div>
    </div>";
?>
