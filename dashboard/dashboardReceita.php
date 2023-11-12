<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

//Select Receitas
$comandoDadosReceita = mysqli_query($conexao, "
SELECT R.idReceita, R.img AS imgReceita, R.nomeReceita, C.nomeCategoria, GROUP_CONCAT(DC.nomeDoenca) AS nomeDoencas, R.status
FROM Receita R
JOIN Categoria C ON R.categoria = C.idCategoria
LEFT JOIN Receita_DoencaCronica RDC ON R.idReceita = RDC.idReceita
LEFT JOIN Doenca_Cronica DC ON RDC.idDoenca = DC.idDoenca
GROUP BY R.idReceita;
");

//Apagar Receita
if (isset($_GET['apagarReceita'])) {
    $id = $_GET['apagarReceita'];
    
    // Excluir todas as relações da receita antes de recriar
    $sqlExcluirAntigos = "DELETE FROM Receita_DoencaCronica WHERE idReceita = '$id'";
    mysqli_query($conexao, $sqlExcluirAntigos);

    
    $execDelete = mysqli_query($conexao, "DELETE FROM receita WHERE idReceita = $id");
    header('Location: dashboardReceita.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="dashboard.css" rel="stylesheet">
    <link href="dashboardReceita.css" rel="stylesheet">
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
                        <h1>Receitas</h1>
                        <a href="criarReceita.php?"><span>Criar Receita</span></a>
                    </div>
                    <div class="cabecalhoReceitas">
                        <div class="idReceita">
                            <p>ID</p>
                        </div>
                        <div class="ImagemReceita">
                            <p>Imagem</p>
                        </div>
                        <div class="NomeReceita">
                            <p>Nome da Receita</p>
                        </div>
                        <div class="CategoriaReceita">
                            <p>Categoria</p>
                        </div>
                        <div class="DCNTsReceita">
                            <p>DCNTs da Receita</p>
                        </div>
                        <div class="SttsReceita">
                            <p>Status</p>
                        </div>
                        <div class="OpcoesReceita">
                            <p>Opções</p>
                        </div>
                        <?php while ($Receitas = mysqli_fetch_assoc($comandoDadosReceita)) : ?>
                            <div class="conteudoReceitas">
                                <div class="idReceita">
                                    <p><?= $Receitas['idReceita'] ?></p>
                                </div>
                                <div class="ImagemReceita">
                                    <img src="../<?= $Receitas['imgReceita'] ?>" width="200" height="auto" alt="ImagemReceita">
                                </div>
                                <div class="NomeReceita">
                                    <a href="../receita.php?id=<?= $Receitas['idReceita'] ?>" class="receita-linha">
                                        <p><?= $Receitas['nomeReceita'] ?></p>
                                    </a>
                                </div>
                                <div class="CategoriaReceita">
                                    <p><?= $Receitas['nomeCategoria'] ?></p>
                                </div>
                                <div class="DCNTsReceita">
                                    <?php
                                    if (!empty($Receitas['nomeDoencas'])) {
                                        $doencasArray = explode(',', $Receitas['nomeDoencas']);
                                        foreach ($doencasArray as $doenca) {
                                            $doenca = trim($doenca); ?>
                                            <li><?= $doenca ?></li>
                                    <?php }
                                    }
                                    ?>
                                </div>
                                <div class="SttsReceita">
                                    <span>
                                        <?php
                                        if ($Receitas['status'] == "ativo") {
                                            echo ("<span style='color:green;'>Ativo</span>");
                                        } else {
                                            echo ("<span style='color:red;'>Inativo</span>");
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="OpcoesReceita">
                                    <a href="editarReceita.php?id=<?=$Receitas['idReceita']?>">Editar</a>
                                    <a href="dashboardReceita.php?apagarReceita=<?=$Receitas['idReceita']?>">Excluir</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
    </main>
    <?php
    require "footer.php";
    ?>
</body>

</html>