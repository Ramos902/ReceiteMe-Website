<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Select Usuario 
    $sql = "SELECT idUsuario, nome, adm FROM usuario WHERE idUsuario = $idUsuario";
    $result = $conexao->query($sql);
    // Verificar se o Select retornou resultados
    if ($result->num_rows > 0) {
        $Usuario = $result->fetch_assoc();
        $nomeUsuario = $Usuario['nome'];
        $adm = $Usuario['adm'];
    } else {
        header('Location: index.php');
    }
}

// Verificar se o Update foi enviado
if (isset($_POST['AtualizarUsuario'])) {
    $idUsuario = $_GET['id'];
    $adm = $_POST['administrador'];

    // Atualizar a Usuario no banco de dados
    $sql = "UPDATE usuario SET adm='$adm' WHERE idUsuario=$idUsuario";

    if (mysqli_query($conexao, $sql) == true) {
        header('Location: dashboardUsuario.php');
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
        <div id="conteudo">
            <div id="primeira_linha">
                <style>
                    div#primeira_linha{
                        width: 500px;
                    }

                </style>
                <h1>Editar Usuario</h1>
                <h2>Nome: <?=$Usuario['nome']?></h2>
                <form action="editarUsuario.php?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                    <div class="inputBox">
                        <label for="Administrador">Administrador:</label><br>
                        <select id="Administrador" name="administrador">
                            <option value="s" <?= ($adm == 's') ? 'selected' : '' ?>>Sim</option>
                            <option value="n" <?= ($adm == 'n') ? 'selected' : '' ?>>Não</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <div id="divEnviar">
                            <input type="submit" name="AtualizarUsuario" value="Salvar Usuario">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>