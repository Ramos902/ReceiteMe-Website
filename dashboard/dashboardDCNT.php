<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

//Select DCNTs
$comandoDadosDCNT = mysqli_query($conexao, "
SELECT dc.idDoenca, dc.nomeDoenca, 
       COUNT(DISTINCT ud.idUsuario) AS quantidade_usuarios,
       COUNT(DISTINCT rd.idReceita) AS quantidade_receitas
FROM Doenca_Cronica dc
LEFT JOIN Usuario_DoencaCronica ud ON dc.idDoenca = ud.idDoenca
LEFT JOIN Receita_DoencaCronica rd ON dc.idDoenca = rd.idDoenca
GROUP BY dc.idDoenca, dc.nomeDoenca;
");

/*Apagar DCNT*/
if (isset($_GET['apagarDCNT'])) {
    $id = $_GET['apagarDCNT'];
    $execDelete = mysqli_query($conexao, "DELETE FROM Receita_DoencaCronica WHERE idDoenca = $id;");
    $execDelete = mysqli_query($conexao, "DELETE FROM Usuario_DoencaCronica WHERE idDoenca = $id;");
    $execDelete = mysqli_query($conexao, "DELETE FROM Doenca_Cronica WHERE idDoenca = $id;");   
    header('Location: dashboardDCNT.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="dashboard.css" rel="stylesheet">
    <link href="dashboardDCNT.css" rel="stylesheet">
    <link href="../fullsite.css" rel="stylesheet">
    <link href="header_footer.css" rel="stylesheet">
    <title>ReceiteMe</title>
</head>

<body>
    <?php
    require "./header.php";
    ?>
    <main>
        <div id="conteudo">
            <h1>Painel de Controle</h1>
            <div id="linha">
                <div id="coluna_esquerda">
                    <h1>Serviços</h1>
                    <li><a href="dashboardReceita.php">Receitas</a></li>
                    <li><a href="dashboardUsuario.php">Usuarios</a></li>
                    <li><a href="dashboardDCNT.php">DCNTs</a></li>
                    <li><a href="dashboardCategoria.php">Categorias</a></li>
                </div>
                <div id="coluna_direita">
                    <div id="titulo">
                        <h1>Doenças Crônicas não Transmissiveis</h1>
                        <a href="criarDCNT.php?"><span>Criar DCNT</span></a>
                    </div>
                    <div class="cabecalho">
                        <div class="idDoenca">
                            <p>ID</p>
                        </div>
                        <div class="NomeDoenca">
                            <p>Nome da Doença</p>
                        </div>
                        <div class="QuantidadeUsuario">
                            <p>Usuarios Relacionados</p>
                        </div>
                        <div class="QuantidadeReceitas">
                            <p>Receitas Relacionadas</p>
                        </div>
                        <div class="OpcoesDCNTs">
                            <p>Opções</p>
                        </div>
                        <?php while ($DCNTs = mysqli_fetch_assoc($comandoDadosDCNT)) : ?>
                            <div class="conteudo">
                                <div class="idDoenca">
                                    <p><?= $DCNTs['idDoenca'] ?></p>
                                </div>
                                <div class="NomeDoenca">
                                    <p><?= $DCNTs['nomeDoenca'] ?></p>
                                </div>
                                <div class="QuantidadeUsuarios">
                                    <p><?= $DCNTs['quantidade_usuarios'] ?></p>
                                </div>
                                <div class="QuantidadeReceitas">
                                    <p><?= $DCNTs['quantidade_receitas'] ?></p>
                                </div>
                                <div class="OpcoesDCNTs">
                                    <a href="editarDCNT.php?id=<?=$DCNTs['idDoenca']?>">Editar</a>
                                    <a href="dashboardDCNT.php?apagarDCNT=<?= $DCNTs['idDoenca'] ?>">Excluir</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    require "footer.php";
    ?>
</body>

</html>