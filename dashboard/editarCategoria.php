<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Select Categoria
    $sql = "SELECT * FROM categoria WHERE idCategoria = $idCategoria";
    $result = $conexao->query($sql);
    // Verificar se o Select retornou resultados
    if ($result->num_rows > 0) {
        $Categoria = $result->fetch_assoc();
        $nomeCategoria = $Categoria['nomeCategoria'];
    } else {
        header('Location: index.php');
    }
}

// Verificar se o Update foi enviado
if (isset($_POST['AtualizarCategoria'])) {
    $idCategoria = $_GET['id'];
    $nomeCategoria = $_POST['nomeCategoria'];

    if ($_FILES['imgFile']['error'] === 0) {
        $destino = "images/site/categorias/" . $_FILES['imgFile']['name'];
        $arquivo_tmp = $_FILES['imgFile']['tmp_name'];
        move_uploaded_file($arquivo_tmp, ("../images/site/categorias/" . $_FILES['imgFile']['name']));
    }

    $sql = "UPDATE categoria SET nomeCategoria='$nomeCategoria', imgCategoria='$destino' WHERE idCategoria=$idCategoria";

    if (mysqli_query($conexao, $sql) == true) {
        header('Location: dashboardCategoria.php');
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
                    <form action="editarCategoria.php?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="inputBox">
                            <label for="nomeCategoria">Nome da Categoria:</label>
                            <input id="nomeCategoria" type="text" name="nomeCategoria" value="<?= $nomeCategoria ?>" required>
                        </div>
                        <div class="inputBox">
                            <label for="imagemCategoria">Suba a Nova Imagem aqui!</label><br>
                            <input id="imagemCategoria" type="file" name="imgFile">
                        </div>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" class="enviar" name="AtualizarCategoria" value="Atualizar Categoria">
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