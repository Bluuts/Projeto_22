<?php

include("../conectadb.php");

session_start();
// COLETA DE DADOS DE CLIENTE NA SESSÃO
$idcliente = $_SESSION['idcliente'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nomeproduto = $_POST['nomeproduto'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    // TOTAL ITEM É SOMA DO PREÇO UNITÁRIO * QUANTIDADE
    $totalitem = ($preco * $quantidade);

    // GERAR UM HASH PARA DEFINIR UM CARRINHO ÚNICO E EXCLUSIVO
    $numerocarrinho = md5($idcliente . date('d-m-Y H:i:s'));

    // VALIDA CLIENTE
    if ($idcliente <= 0) {
        echo "<script>window.alert('VOCÊ PRECISA LOGAR PARA ADD ITEM AO CARRINHO');</script>";
        echo "<script>window.location.href='loja.php';</script>";
    } else {
        // VERIFICA SE O CLIENTE TEM CARRINHO ABERTO
        $sql = "SELECT COUNT(car_numero_carrinho) FROM itens_carrinho INNER JOIN clientes ON fk_cli_id = cli_id WHERE cli_id = '$idcliente' AND car_finalizado = 'n'";
        $retorno = mysqli_query($link, $sql);

        while ($tbl = mysqli_fetch_array($retorno)) {
            $cont = $tbl[0];

            if ($cont == 0) {
                // SE O CARRINHO NÃO EXISTE NO BANCO, GERA UM NOVO MD5 E INSERE OS ITENS
                // NA TABELA

                $sql = "INSERT INTO itens_carrinho (fk_pro_id, car_item_quantidade, fk_cli_id, car_total_carrinho, car_numero_carrinho, car_finalizado) VALUES ($id, '$quantidade', '$idcliente', '$totalitem', '$numerocarrinho', 'n')";

                mysqli_query($link, $sql);

                $_SESSION['carrinho'] = $numerocarrinho;
                echo "<script>window.alert('PRODUTO ADICIONADO AO CARRINHO');</script>";
                echo "<script>window.location.href='loja.php';</script>";
            } else {
                // SE O CARRINHO EXISTE, CONSULTA O NÚMERO DO CARRINHO PARA ADICIONAR ITENS AO CARRINHO
                $sql = "SELECT DISTINCT(car_numero_carrinho) FROM itens_carrinho WHERE fk_cli_id = '$idcliente' AND car_finalizado = 'n'";
                $retorno = mysqli_query($link, $sql);

                while ($tbl = mysqli_fetch_array($retorno)) 
                {
                    $numerocarrinhocliente = $tbl[0];

                    $_SESSION['carrinhoid'] = $numerocarrinhocliente;

                    $sql = "INSERT INTO itens_carrinho (fk_pro_id, car_item_quantidade, fk_cli_id, car_total_carrinho, car_numero_carrinho, car_finalizado) VALUES ($id, '$quantidade', '$idcliente', '$totalitem', '$numerocarrinhocliente', 'n')";

                    mysqli_query($link, $sql);

                    echo "<script>window.alert('PRODUTO ADICIONADO AO CARRINHO $numerocarrinhocliente');</script>";
                    echo "<script>window.location.href='loja.php';</script>";
                }
            }

        }
    }
    
}

// TRAZENDO DADOS VIA URL (GET)
$id = $_GET['id'];
$sql = "SELECT * FROM produtos WHERE pro_id = '$id'";
$retorno = mysqli_query($link, $sql);
while ($tbl = mysqli_fetch_array($retorno)) {
    $nomeproduto = $tbl[1];
    $descricao = $tbl[2];
    $preco = $tbl[5];
    $imagem_atual = $tbl[7];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/estiloadm.css">
    <title>VER PRODUTO</title>
</head>

<body>
    <header>
        <!-- MENU DA LOJA -->
        <nav>
            <ul class="menu">
                <li><a href="loja.php">HOME</a></li><!-- BOTÃO HOME -->
                <li><a href="#">PRODUTOS</a></li>

                <!-- VALIDA SE CLIENTE LOGOU -->
                <?php
                if (isset($_SESSION['idcliente'])):
                ?>
                    <form class="formmenu" action="logout.php" method="post">
                        <h3 class="menuright2">
                            Olá <?= $_SESSION['nomecliente']; ?>
                        </h3>
                        <li class="menu-right"><a href="perfil.php?id=<?= $idcliente ?>">PERFIL</a></li>
                        <li class="menu-right"><a href="logout.php">LOGOUT</a></li>
                    </form>
                <?php else: ?>
                    <form class="formmenu">
                        <li class="menu-right"><a href="logincliente.php" style="float: right">LOGIN</a></li>
                        <li class="menu-right"><a href="../cadastracliente.php">CADASTRAR</a></li>
                    </form>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <!-- FIM DA LOJA -->

    <div class="formulario">
        <form class="visualizaproduto" action="verproduto.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="nome" value="<?= $id ?>">

            <label>NOME</label><!-- Oculta o ID do produto -->
            <input type="text" name="nomeproduto" id="nome" value="<?= $nomeproduto ?>" readonly>

            <label>DESCRIÇÃO</label>
            <textarea name="descricao" readonly><?= $descricao ?></textarea>

            <label>QUANTIDADE</label>
            <input type="number" id="quantidade" name="quantidade">

            <label>PREÇO</label>
            <input type="number" name="preco" id="preco" value="<?= $preco ?>">

            <br>

            <input type="submit" value="ADICIONAR AO CARRINHO">

        </form>
    </div>
    <div>
        <!-- PEÇA ESSENCIAL PARA COLETAR A IMAGEM ATUAL -->
        <img class="imagem_atual" src="data:image/jpeg;base64,<?= $imagem_atual ?>">
    </div>


</body>

</html>
