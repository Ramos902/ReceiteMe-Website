<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

if (isset($_GET['id'])) {
    $idDoenca = $_GET['id'];

    // Select DCNT
    $sql = "SELECT * FROM doenca_cronica WHERE idDoenca = $idDoenca";
    $result = $conexao->query($sql);
    // Verificar se o Select retornou resultados
    if ($result->num_rows > 0) {
        $doenca = $result->fetch_assoc();
        $nomeDCNT = $doenca['nomeDoenca'];
    } else {
        header('Location: index.php');
    }
}

// Verificar se o Update foi enviado
if (isset($_POST['AtualizarDCNT'])) {
    $idDoenca = $_GET['id'];
    $nomeDCNT = $_POST['nomeDoenca'];

    // Atualizar a DCNT no banco de dados
    $sql = "UPDATE doenca_cronica SET nomeDoenca='$nomeDCNT' WHERE idDoenca=$idDoenca";

    if (mysqli_query($conexao, $sql) == true) {
        header('Location: dashboardDCNT.php');
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="editar.css" rel="stylesheet">
    <link href="editarReceita.css" rel="stylesheet">
    <link href="../fullsite.css" rel="stylesheet">
    <link href="header_footer.css" rel="stylesheet">
    <title>ReceiteMe</title>
</head>

<body>
    <?php
    require "./header.php";
    ?>
    <main>
        <style>
            div#primeira_linha{
                display: flex;
            }
            div#coluna_esquerda{
                width: 70%;
            }
            div#coluna_direita{
                text-align: end;
                width: 40%;
            }
            div#coluna_direita img{
                background-color: white;
                width: 100%;
                border-radius: 5%;
            }
        </style>
        <div id="conteudo">
            <div id="primeira_linha">
                <div id="coluna_esquerda">
                    <h1>Editar Doença Crônica não Transmissivel</h1>
                    <form action="editarDCNT.php?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="inputBox">
                            <label for="nomeDoenca">Nome da DCNT:</label>
                            <input id="nomeDoenca" type="text" name="nomeDoenca" value="<?= $nomeDCNT ?>" required>
                        </div>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" class="enviar" name="AtualizarDCNT" value="Atualizar DCNTs">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
    require "footer.php";
    ?>
</body>

</html>