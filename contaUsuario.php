<?php
//Conexao
require "assets/conexao.php";

//Session Login
session_start();

//Dados Conta
$select = "SELECT * FROM usuario WHERE idUsuario = '$_SESSION[id_usuario]';";
$execSelect = mysqli_query($conexao, $select);
$dadosConta = mysqli_fetch_assoc($execSelect);

//Doencas Alimentares
$selectDoencas = "SELECT DC.nomeDoenca AS nomeDoenca
                    FROM Doenca_Cronica DC
                    JOIN Usuario_DoencaCronica UD ON DC.idDoenca = UD.idDoenca
                    WHERE UD.idUsuario = $_SESSION[id_usuario];";
$execSelectDoencas = mysqli_query($conexao, $selectDoencas);
$dadosContaDoencas = mysqli_fetch_all($execSelectDoencas);

//Sair da Conta
if (isset($_GET['sair'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
}

//Apagar Conta
if (isset($_GET['apagar'])) {
    $id = $_SESSION['id_usuario'];
    $execDelete = mysqli_query($conexao, "DELETE FROM usuario WHERE idUsuario = $id");
    session_unset();
    session_destroy();
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="contaUsuario.css" rel="stylesheet">
    <link href="fullsite.css" rel="stylesheet">
    <link href="assets/header_footer.css" rel="stylesheet">
    <title>ReceiteMe</title>
</head>

<body>
    <?php
    require "assets/header.php";
    ?>
    <main>
        <div id="conteudo">
            <div id="coluna_esquerda">
                <div id="divInfo">
                    <h1>Nome: <?= $dadosConta['nome'] ?></h1>
                    <p>Email: <?= $dadosConta['email'] ?></p>
                    <h2>DCNTs Cadastradas</h2>
                    <?php if (mysqli_num_rows($execSelectDoencas) >= 0) : 
                            foreach($dadosContaDoencas as $dadosContaDoencas) :?>
                            
                        <li><?=$dadosContaDoencas['0']?></li>
                    <?php endforeach;  else : endif; ?>
                </div>
                <div>
                    <div id="divExit">
                        <a href="contaUsuario.php?sair=<?= $_SESSION['id_usuario'] ?>" type="submit">Sair da Conta</a>
                        <a href="editarUsuario.php?id=<?= $_SESSION['id_usuario'] ?>" type="submit">Editar Informações</a>
                        <a href="contaUsuario.php?apagar=<?= $_SESSION['id_usuario'] ?>" type="submit">Apagar Conta</a>
                    </div>
                    <?php if ($_SESSION['adm'] == true) : ?>
                        <div>
                            <div id="divPainelControl">
                                <h2>Painel de Controle de ADM</h2>
                                <span><a href="./dashboard/dashboardReceita.php" class="buttonSubmit">Acessar</a></span>
                            </div>
                        </div>
                    <?php else : endif; ?>
                </div>
            </div>
            <div id="coluna_direita">
                <img src="<?=$dadosConta['img']?>" alt="FotoPerfil">
            </div>
    </main>
</body>
<?php
require "assets/footer.php"
?>

</html>