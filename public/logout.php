<?php

    //Requer as configurações
    require("../includes/config.php"); 

    //Esvazia as funções de sessão
        $_SESSION = [];

        // Expira o cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // Destrói a sessão
        session_destroy();;

    // redireciona usuário para landing
    redirect("/index.php");
?>
