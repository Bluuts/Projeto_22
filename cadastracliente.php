

<?php
#cli_id
#cli_cpf
#cli_nome
#cli_senha
#cli_datanasc
#cli_telefone
#cli_logradouro
#cli_numero
#cli_cidade
#cli_ativo

include("conectadb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $datanasc = $_POST['datanasc'];
    $telefone = $_POST['telefone'];
    $logradouro = $_POST['logradouro'];
    $numero = $_POST['numero'];
    $cidade = $_POST['cidade'];
    $ativo = $_POST['ativo'];


    #VALIDAÇÃO DE USUÁRIO. VERIFICA SE USUÁRIO JÁ EXISTE
    $sql = "SELECT COUNT(cli_id) FROM clientes WHERE cli_nome ='$nome' AND cli_senha = '$senha'";
    $retorno = mysqli_query($link, $sql);

    while ($tbl = mysqli_fetch_array($retorno)) {
        $cont = $tbl[0];
    }

    #VALIDAÇÃO DE TRUE E FALSE
    if ($cont == 1) {
        echo "<script>window.alert('CLIENTE JÁ CADASTRADO');</script>";
    } else {
        $sql = "INSERT INTO clientes (cli_cpf, cli_nome, cli_senha, cli_datanasc, cli_telefone, cli_logradouro, cli_numero, cli_cidade, cli_ativo)
        VALUES ('$cpf', '$nome', '$senha', '$datanasc', '$telefone', '$logradouro', '$numero', '$cidade', 'n')";
        mysqli_query($link, $sql);

        #CADASTROU USUÁRIO E EXIBE MENSAGEM NA TELA E DIRECIONA PARA LISTA USUÁRIO
        echo "<script>window.alert('CLIENTE CADASTRADO COM SUCESSO');</script>";
        echo "<script>window.location.href='listausuario.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/estiloadm.css">

    <title>CADASTRA CLIENTE</title>
</head>

<body>
    <div>
        <ul class="menu">
            <li><a href="cadastrausuario.php">CADASTRA USUARIO</a></li>
            <li><a href="cadastracliente.php">CADASTRA CLIENTE</a></li>
            <li><a href="listausuario.php">LISTA USUARIO</a></li>
            <li><a href="cadastraproduto.php">CADASTRA PRODUTO</a></li>
            <li><a href="listaproduto.php">LISTA PRODUTO</a></li>
            <li><a href="listacliente.php">LISTA CLIENTE</a></li>
            <li class="menuloja"><a href="logout.php">SAIR</a></li>
            <li class="menuloja"><a href="./areacliente/loja.php">LOJA</a></li>
        </ul>
    </div>

    <div>
        <form action="cadastracliente.php" method="post">
            <input type="number" name="cpf" id="cpf" placeholder="CPF">
            <br>
            <input type="text" name="nome" id="nome" placeholder="NOME USUÁRIO">
            <br>
            <input type="password" name="senha" id="senha" placeholder="SENHA">
            <br>
            <input type="date" name="datanasc" id="datanasc" placeholder="DATA DE NASCIMENTO">
            <br>
            <input type="number" name="telefone" id="telefone" placeholder="TELEFONE">
            <br>
            <input type="text" name="logradouro" id="logradouro" placeholder="LOGRADOURO">
            <br>
            <input type="number" name="numero" id="numero" placeholder="NÚMERO">
            <br>
            <input type="text" name="cidade" id="cidade" placeholder="CIDADE">
            <br>
            <input type="submit" name="cadastrar" id="cadastrar" value="CADASTRAR">
        </form>
    </div>

</html>