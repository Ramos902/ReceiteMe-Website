<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CriarDCNT'])) {
    $nomeDoenca = mysqli_real_escape_string($conexao, $_POST['nomeDoenca']);

    $sql = "INSERT INTO Doenca_Cronica (nomeDoenca) VALUES ('$nomeDoenca')";

    // Executa a consulta SQL
    if ($conexao->query($sql) === TRUE) {
        header('Location: dashboardDCNT.php');
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="editar.css" rel="stylesheet">
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
            div#primeira_linha {
                display: flex;
            }

            div#coluna_esquerda {
                width: 80%;
            }

            .inputBox input{
                display: flex;
                flex-direction: column;
                width: 50%;
            }
            div #divEnviar{
                flex-direction: column;
                width: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
        <div id="conteudo">
            <div id="primeira_linha">
                <div id="coluna_esquerda">
                    <h1>Criar Doença Cronica não Transmissivel</h1>
                    <form action="criarDCNT.php" method="POST" enctype="multipart/form-data">
                        <div class="inputBox">
                            <label for="nomeDoenca">Nome da Doença:</label>
                            <input id="nomeDoenca" type="text" name="nomeDoenca" required>
                        </div>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" name="CriarDCNT" value="Criar DCNT">
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