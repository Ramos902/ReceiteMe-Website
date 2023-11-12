<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

//Select Categoria
$comandoDadosCategoria = mysqli_query($conexao, "
SELECT c.idCategoria, c.nomeCategoria, c.imgCategoria, COUNT(r.idReceita) AS numeroDeReceitas 
FROM Categoria c 
LEFT JOIN Receita r ON c.idCategoria = r.categoria 
GROUP BY c.idCategoria, c.imgCategoria; 
");

/*Apagar Categoria*/
if (isset($_GET['apagarCategoria'])) {
    $id = $_GET['apagarCategoria'];
    $execDelete = mysqli_query($conexao, "DELETE FROM Categoria WHERE idCategoria = $id;");
    header('Location: dashboardCategoria.php');
}

?>

<style>
    /*Cabeçalho*/
    div.cabecalho {
        width: 100%;
        flex-wrap: wrap;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 2%;
        text-align: center;
    }

    div.idDoenca {
        width: 5%;
    }

    div.ImagemCategoria {
        width: 15%;
    }

    div.ImagemCategoria img {
        width: 100%;
        object-fit: cover;
        border-radius: 5%;
    }

    div.NomeDoenca {
        width: 30%;
    }

    div.numeroDeReceitas {
        width: 30%;
    }

    div.OpcoesCategoria {
        width: 10%;
    }

    /*Conteudo*/
    div.conteudo {
        width: 100%;
        min-height: 100px;
        flex-wrap: wrap;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 2%;
        border: 1px solid black;
        border-radius: 10px;
        padding: 10px 0px;
        margin: 10px 0px;
    }
</style>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="dashboard.css" rel="stylesheet">
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
                        <h1>Categorias</h1>
                        <a href="./criarCategoria.php"><span>Criar Categoria</span></a>
                    </div>
                    <div class="cabecalho">
                        <div class="idDoenca">
                            <p>ID</p>
                        </div>
                        <div class="ImagemCategoria">
                            <p>Imagem</p>
                        </div>
                        <div class="NomeDoenca">
                            <p>Nome da Doença</p>
                        </div>
                        <div class="numeroDeReceitas">
                            <p>Receitas Relacionadas</p>
                        </div>
                        <div class="OpcoesCategoria">
                            <p>Opções</p>
                        </div>
                        <?php while ($Categoria = mysqli_fetch_assoc($comandoDadosCategoria)) : ?>
                            <div class="conteudo">
                                <div class="idDoenca">
                                    <p><?= $Categoria['idCategoria'] ?></p>
                                </div>
                                <div class="ImagemCategoria">
                                    <img src="../<?= $Categoria['imgCategoria'] ?>" width="125" height="auto" alt="ImagemCategoria">
                                </div>
                                <div class="NomeDoenca">
                                    <p><?= $Categoria['nomeCategoria'] ?></p>
                                </div>
                                <div class="numeroDeReceitas">
                                    <p><?= $Categoria['numeroDeReceitas'] ?></p>
                                </div>
                                <div class="OpcoesCategoria">
                                <a href="editarCategoria.php?id=<?= $Categoria['idCategoria'] ?>">Editar</a>
                                    <a href="dashboardCategoria.php?apagarCategoria=<?= $Categoria['idCategoria'] ?>">Excluir</a>
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