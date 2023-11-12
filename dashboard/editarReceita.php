<?php
require "../assets/conexao.php";

//Session Login
session_start();

if ($_SESSION['adm'] == false) {
    header('Location: ../index.php');
} else {
};

if (isset($_GET['id'])) {
    $idReceita = $_GET['id'];

    // Select receita 
    $sql = "SELECT * FROM Receita WHERE idReceita = $idReceita";
    $result = $conexao->query($sql);
    // Verificar se o Select retornou resultados
    if ($result->num_rows > 0) {
        $receita = $result->fetch_assoc();
        $nomeReceita = $receita['nomeReceita'];
        $ingredientes = $receita['ingredientes'];
        $modoPreparo = $receita['modoPreparo'];
        $categoriaAtual = $receita['categoria'];
        $status = $receita['status'];
        $img = $receita['img'];
    } else {
        header('Location: index.php');
    }
}

// Verificar se o Update foi enviado
if (isset($_POST['AtualizarReceita'])) {
    $idReceita = $_GET['id'];
    $nomeReceita = $_POST['nomeReceita'];
    $ingredientes = $_POST['ingredientes'];
    $modoPreparo = $_POST['modoPreparo'];
    $novaCategoria = $_POST['categoria'];
    $status = $_POST['status'];

    //Alocação da imagem
    if ($_FILES['imgFile']['error'] === 0) {
        $destino = "images/receitas/" . $_FILES['imgFile']['name'];
        $arquivo_tmp = $_FILES['imgFile']['tmp_name'];
        move_uploaded_file($arquivo_tmp, ("../images/receitas/" . $_FILES['imgFile']['name']));
    } else {
        $destino = $img;
    }

    // Atualizar a receita no banco de dados
    $sql = "UPDATE Receita SET nomeReceita='$nomeReceita', ingredientes='$ingredientes', modoPreparo='$modoPreparo', img='$destino' , categoria='$novaCategoria', status='$status' WHERE idReceita=$idReceita";

    if (mysqli_query($conexao, $sql) == true) {
        header('Location: dashboardReceita.php');
    }
}

//Fazer o Update das DCNTs
if (isset($_POST['AttDcnts']) && isset($_POST['doenca_ids'])) {
    $doencasSelecionadas = $_POST['doenca_ids'];
    $idReceita = $_GET['id'];

    // Excluir todas as relações da receita antes de recriar
    $sqlExcluirAntigos = "DELETE FROM Receita_DoencaCronica WHERE idReceita = '$idReceita'";
    mysqli_query($conexao, $sqlExcluirAntigos);

    // Inserir as novas relações
    foreach ($doencasSelecionadas as $doencaId) {
        $sqlInserir = "INSERT INTO Receita_DoencaCronica (idReceita, idDoenca) VALUES ('$idReceita', '$doencaId')";
        $result = mysqli_query($conexao, $sqlInserir);
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header("Location:./dashboardReceita.php");
        exit();
    }
}
// Se nenhum checkbox for marcado, excluir todas as relações da receita
if (isset($_POST['AttDcnts']) && empty($_POST['doenca_ids'])) {
    $idReceita = $_GET['id']; // Obtenha o ID da receita da consulta GET
    $sqlLimpar = "DELETE FROM Receita_DoencaCronica WHERE idReceita ='$idReceita'";
    $result = mysqli_query($conexao, $sqlLimpar);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header("Location:./dashboardReceita.php");
        exit();
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
                width: 60%;
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
                    <h1>Editar Receita</h1>
                    <form action="editarReceita.php?id=<?= $_GET['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="inputBox">
                            <label for="nomeReceita">Nome da Receita:</label>
                            <input id="nomeReceita" type="text" name="nomeReceita" value="<?= $nomeReceita ?>" required>
                        </div>
                        <div class="inputBox">
                            <label for="ingredientes">Ingredientes:</label>
                            <textarea id="ingredientes" name="ingredientes" required><?= $ingredientes ?></textarea>
                        </div>
                        <div class="inputBox">
                            <label for="modoPreparo">Modo de Preparo:</label>
                            <textarea id="modoPreparo" name="modoPreparo" required><?= $modoPreparo ?></textarea>
                        </div>
                        <div class="inputBox">
                            <label for="categoria">Categoria:</label><br>
                            <select id="categoria" name="categoria">
                                <?php
                                $sqlCategorias = "SELECT * FROM Categoria";
                                $resultCategorias = $conexao->query($sqlCategorias);
                                while ($categoria = $resultCategorias->fetch_assoc()) {
                                    $selected = ($categoria['idCategoria'] == $categoriaAtual) ? "selected" : "";
                                    echo "<option value='" . $categoria['idCategoria'] . "' $selected>" . $categoria['nomeCategoria'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="inputBox">
                            <label for="status">Status:</label><br>
                            <select id="status" name="status">
                                <option value="ativo" <?= ($status == 'ativo') ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= ($status == 'inativo') ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="inputBox">
                            <label for="imagemUsuario">Suba a Nova Imagem aqui!</label><br>
                            <input id="imagemUsuario" type="file" name="imgFile">
                        </div>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" name="AtualizarReceita" value="Salvar Receita">
                            </div>
                        </div>
                    </form>
                    <form action="editarReceita.php?id=<?= $idReceita ?>" method="post">
                        <p>Selecione as Doenças Crônicas:</p>
                        <?php
                        // Select doencas relacionadas com a receita
                        $selectDoencasReceita = "SELECT RD.idReceita_Doenca, DC.idDoenca, DC.nomeDoenca 
                             FROM Receita_DoencaCronica RD 
                             JOIN Doenca_Cronica DC ON RD.idDoenca = DC.idDoenca
                             WHERE RD.idReceita = $idReceita";

                        $execSelectDoencasReceita = mysqli_query($conexao, $selectDoencasReceita);
                        $dadosDoencasReceita = mysqli_fetch_all($execSelectDoencasReceita);

                        // Select para obter a lista de doenças crônicas
                        $selectDoencas = "SELECT idDoenca, nomeDoenca FROM Doenca_Cronica";
                        $execSelectDoencas = mysqli_query($conexao, $selectDoencas);

                        if ($execSelectDoencas->num_rows > 0) {
                            while ($linhaDoencas = $execSelectDoencas->fetch_assoc()) {
                                $doencaId = $linhaDoencas['idDoenca'];
                                $doencaNome = $linhaDoencas['nomeDoenca'];
                                $isChecked = false;
                                foreach ($dadosDoencasReceita as $dados) {
                                    if ($dados[1] == $doencaId) {
                                        $isChecked = true;
                                        break;
                                    }
                                }
                        ?>
                                <input id="doenca_id_<?= $doencaId ?>" type='checkbox' name='doenca_ids[]' value='<?= $doencaId ?>' <?= $isChecked ? 'checked' : '' ?>>
                                <label for="doenca_id_<?= $doencaId ?>"><?= $doencaNome ?></label><br>
                        <?php
                            }
                        }
                        ?>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" class="enviar" name="AttDcnts" value="Atualizar DCNTs">
                            </div>
                        </div>
                    </form>
                </div>
                <div id="coluna_direita">
                    <h2>Imagem Atual</h2>
                    <img src="../<?php echo ($img); ?>" alt="FotoReceita">
                </div>
            </div>
        </div>
    </main>
    <?php
    require "footer.php";
    ?>
</body>

</html>