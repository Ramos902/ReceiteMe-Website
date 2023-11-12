<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

//Select Usuarios
$comandoDadosUsuario = mysqli_query($conexao, "
SELECT u.idUsuario, u.email, u.nome, u.img, u.adm, GROUP_CONCAT(dc.nomeDoenca SEPARATOR ', ') as doencas_cronicas
FROM Usuario u
LEFT JOIN Usuario_DoencaCronica udc ON u.idUsuario = udc.idUsuario
LEFT JOIN Doenca_Cronica dc ON udc.idDoenca = dc.idDoenca
GROUP BY u.idUsuario, u.email, u.nome, u.img, u.adm;
");

//Apagar Usuario
if (isset($_GET['apagarUsuario'])) {
    $id = $_GET['apagarUsuario'];
    $execDelete = mysqli_query($conexao, "DELETE FROM usuario WHERE idUsuario = $id");
    header('Location: dashboardUsuario.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="dashboard.css" rel="stylesheet">
    <link href="dashboardUsuario.css" rel="stylesheet">
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
                        <h1>Usuários</h1>
                        <a href="../login_registro.php"><span>Criar Usuario</span></a>
                    </div>
                    <div class="cabecalho">
                        <div class="idUsuario">
                            <p>ID</p>
                        </div>
                        <div class="ImagemUsuario">
                            <p>Imagem</p>
                        </div>
                        <div class="NomeUsuario">
                            <p>Nome do Usuário</p>
                        </div>
                        <div class="EmailUsuario">
                            <p>Email</p>
                        </div>
                        <div class="DCNTsUsuario">
                            <p>DCNTs do Usuário</p>
                        </div>
                        <div class="AdmUsuario">
                            <p>Adm</p>
                        </div>
                        <div class="OpcoesUsuario">
                            <p>Opções</p>
                        </div>
                        <?php while ($Usuarios = mysqli_fetch_assoc($comandoDadosUsuario)) : ?>
                            <div class="conteudo">
                                <div class="idUsuario">
                                    <p><?= $Usuarios['idUsuario'] ?></p>
                                </div>
                                <div class="ImagemUsuario">
                                    <img src="../<?= $Usuarios['img'] ?>" width="200" height="auto" alt="ImagemUsuario">
                                </div>
                                <div class="NomeUsuario">
                                    <p><?= $Usuarios['nome'] ?></p>
                                </div>
                                <div class="EmailUsuario">
                                    <p><?= $Usuarios['email'] ?></p>
                                </div>
                                <div class="DCNTsUsuario">
                                    <?php
                                    if (!empty($Usuarios['doencas_cronicas'])) {
                                        $doencasArray = explode(',', $Usuarios['doencas_cronicas']);
                                        foreach ($doencasArray as $doenca) {
                                            $doenca = trim($doenca); ?>
                                            <li><?= $doenca ?></li>
                                    <?php }
                                    }
                                    ?>
                                </div>
                                <div class="AdmUsuario">
                                    <span>
                                        <?php
                                        if ($Usuarios['adm'] == "s") {
                                            echo ("<span style='color:green;'>Sim</span>");
                                        } else {
                                            echo ("<span style='color:red;'>Não</span>");
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="OpcoesUsuario">
                                    <a href="editarUsuario.php?id=<?= $Usuarios['idUsuario'] ?>">Editar</a>
                                    <a href="dashboardUsuario.php?apagarUsuario=<?= $Usuarios['idUsuario'] ?>">Excluir</a>
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