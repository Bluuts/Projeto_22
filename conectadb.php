<?php
    ## ARQUIVO DE ACESSO AO BANCO DE DADOS ##
    ## ALERTA EM MODO PROFICIONAL ARQUIVO DEVE SE MANTER OCULTO E PROTEGIDO ##

    ## LOCALIZA O PC COM O SERVIDO OU O BANCO (NOME DO COMPUTADOR)
    $servidor = "localhost";
    ## NOME DO BANCO
    $banco = "projeto22";
    ## USUARIO DE ACESSO
    $usuario = "admin";
    ##SENHA DO USUARIO
    $senha = "123";
    ##LINK DE CONEXÃO
    $link = mysqli_connect($servidor, $usuario, $senha, $banco);

?>  