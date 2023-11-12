<?php
require "../assets/conexao.php";

//Session Login
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['CriarReceita'])) {
        // Dados do formulário
        $nomeReceita = $_POST['nomeReceita'];
        $ingredientes = $_POST['ingredientes'];
        $modoPreparo = $_POST['modoPreparo'];
        $categoria = $_POST['categoria'];
        $status = 'inativo'; // Definindo o status como 'ativo', você pode ajustar conforme necessário

        //Alocação da imagem
        $destino = "images/receitas/" . $_FILES['imgFile']['name'];
        $arquivo_tmp = $_FILES['imgFile']['tmp_name'];
        move_uploaded_file($arquivo_tmp, ("../images/receitas/" . $_FILES['imgFile']['name']));

        // Inserindo no banco de dados usando prepared statement
        $sqlInserirReceita = "INSERT INTO Receita (nomeReceita, ingredientes, modoPreparo, img, categoria, status) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sqlInserirReceita);
        $stmt->bind_param("ssssis", $nomeReceita, $ingredientes, $modoPreparo, $destino, $categoria, $status);
        $stmt->execute();

        

        if (isset($_POST['doenca_ids'])) {
            $doencasSelecionadas = $_POST['doenca_ids'];
            $idReceita = $stmt->insert_id;
        
            // Inserir as novas relações
            foreach ($doencasSelecionadas as $doencaId) {
                // Usando declaração preparada para evitar injeção de SQL
                $sqlInserir = "INSERT INTO Receita_DoencaCronica (idReceita, idDoenca) VALUES (?, ?)";
                $stmtInserir = $conexao->prepare($sqlInserir);
        
                // Verificar se a preparação da declaração foi bem-sucedida
                if ($stmtInserir) {
                    // Associar parâmetros e executar a inserção
                    $stmtInserir->bind_param("ii", $idReceita, $doencaId);
                    $stmtInserir->execute();
        
                    // Fechar a declaração preparada
                    $stmtInserir->close();
                }
            }
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_SESSION['adm'] == false) {
                    header('Location: ../index.php');
                } else {
                    header("Location:./dashboardReceita.php");
                exit();
                };
                
            }
        }
        /*if ($stmt->execute()) {
            // Inserção bem-sucedida
            $idNovaReceita = $stmt->insert_id;

            // Inserir relação com doenças crônicas
            foreach ($doenca_ids as $doenca_id) {
                $sqlInserirDoencaRelacionada = "INSERT INTO Receita_DoencajCronica (idReceita, idDoenca) VALUES (?, ?)";
                $stmtDoenca = $conexao->prepare($sqlInserirDoencaRelacionada);
                $stmtDoenca->bind_param("ii", $idNovaReceita, $doenca_id);
                echo($idNovaReceita . " = " . $doenca_id),
                $stmtDoenca->execute();
                $stmtDoenca->close();
            }
            foreach ($doencasSelecionadas as $doencaId) {
                $sqlInserir = "INSERT INTO Receita_DoencaCronica (idReceita, idDoenca) VALUES ('$idNovaReceita', '$doencaId')";
                $result = mysqli_query($conexao, $sqlInserir);
            }
            $stmt->close();
            //header('Location: dashboardReceita.php'); // Redirecionar para uma página de sucesso
            exit();
        } else {
            // Erro na execução da inserção
            echo $stmt->error;
        }*/
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="editarReceita.css" rel="stylesheet">
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
                width: 50%;
            }
        </style>
        <div id="conteudo">
            <div id="primeira_linha">
                <div id="coluna_esquerda">
                    <h1>Criar Receita</h1>
                    <form action="criarReceita.php" method="POST" enctype="multipart/form-data">
                        <div class="inputBox">
                            <label for="nomeReceita">Nome da Receita:</label>
                            <input id="nomeReceita" type="text" name="nomeReceita" required>
                        </div>
                        <div class="inputBox">
                            <label for="ingredientes">Ingredientes:</label>
                            <textarea id="ingredientes" name="ingredientes" required></textarea>
                        </div>
                        <div class="inputBox">
                            <label for="modoPreparo">Modo de Preparo:</label>
                            <textarea id="modoPreparo" name="modoPreparo" required></textarea>
                        </div>
                        <div class="inputBox">
                            <label for="categoria">Categoria:</label><br>
                            <select id="categoria" name="categoria">
                                <?php
                                $sqlCategorias = "SELECT * FROM Categoria";
                                $resultCategorias = $conexao->query($sqlCategorias);
                                while ($categoria = $resultCategorias->fetch_assoc()) {
                                    echo "<option value='" . $categoria['idCategoria'] . "'>" . $categoria['nomeCategoria'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <label>Selecione as Doenças Cronicas Relacionadas:</label><br>
                        <?php
                        // Select para obter a lista de doenças crônicas
                        $selectDoencas = "SELECT idDoenca, nomeDoenca FROM Doenca_Cronica";
                        $execSelectDoencas = mysqli_query($conexao, $selectDoencas);

                        if ($execSelectDoencas->num_rows > 0) {
                            while ($linhaDoencas = $execSelectDoencas->fetch_assoc()) {
                                $doencaNome = $linhaDoencas['nomeDoenca'];
                                $isChecked = false;
                        ?>
                                <input id="doenca_id_<?= $linhaDoencas['idDoenca'] ?>" type='checkbox' name='doenca_ids[]' value='<?= $linhaDoencas['idDoenca'] ?>'>
                                <label for="doenca_id_<?= $linhaDoencas['idDoenca'] ?>"><?= $doencaNome ?></label><br>
                        <?php
                            }
                        }
                        ?><br>
                        <div class="inputBox">
                            <label for="imagemUsuario">Suba a Nova Imagem aqui!</label><br>
                            <input id="imagemUsuario" type="file" name="imgFile" required>
                        </div>
                        <div class="inputBox">
                            <div id="divEnviar">
                                <input type="submit" name="CriarReceita" value="Criar Receita">
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