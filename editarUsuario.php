<?php
//Conexao
require "assets/conexao.php";

//Session Login
session_start();

//Submit Alterações do Usuario
if (isset($_POST['salvarUsuario'])) {
    $idUsuario = $_SESSION['id_usuario'];
    $execUsuario = mysqli_query($conexao, "Select * from usuario WHERE idUsuario=$idUsuario") or die(mysqli_error($conexao));
    $dadosUsuario = mysqli_fetch_assoc($execUsuario);
    //Alocação da imagem
    if ($_FILES['imgFile']['error'] === 0) {
        $destino = "./images/usuarios/" . $_FILES['imgFile']['name'];
        $arquivo_tmp = $_FILES['imgFile']['tmp_name'];
        move_uploaded_file($arquivo_tmp, ("./images/usuarios/" . $_FILES['imgFile']['name']));
    } else {
        $destino = $dadosUsuario['img'];
    }
    //Insert Usuario
    $nomeUsuario = mysqli_real_escape_string($conexao, $_POST['nomeUsuario']);
    $emailUsuario = mysqli_real_escape_string($conexao, $_POST["emailUsuario"]);
    $senhaUsuario = mysqli_real_escape_string($conexao, $_POST["senhaUsuario"]);
    
    if ($_SESSION['adm'] == true) {
        $admStts = 's';
    } else {
        $admStts = 'n';
    };

    $result = mysqli_query($conexao,    "UPDATE usuario 
                                        SET email='$emailUsuario', senha='$senhaUsuario', nome='$nomeUsuario', img='$destino', adm='$admStts'
                                        WHERE idUsuario=$idUsuario;");
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header("Location:./contaUsuario.php");
        exit();
    }
}

//Submit alterações das DCNTs
if (isset($_POST['AttDcnts']) && isset($_POST['doenca_ids'])) {
    $doencasSelecionadas = $_POST['doenca_ids'];
    $idUsuario = $_SESSION['id_usuario'];
    //Atualizar novas DCNTs
    $sqlExcluirAntigos = "DELETE FROM Usuario_DoencaCronica WHERE idUsuario = '$idUsuario'";
    mysqli_query($conexao, $sqlExcluirAntigos);
    foreach ($doencasSelecionadas as $doencaId) {
        $sqlInserir = "INSERT INTO Usuario_DoencaCronica (idUsuario, idDoenca) VALUES ('$idUsuario', '$doencaId')";
        $result = mysqli_query($conexao, $sqlInserir);
    }
    //Excluir caso seja necessario
    $sqlExcluir = "DELETE FROM Usuario_DoencaCronica WHERE idUsuario = '$idUsuario' AND idDoenca NOT IN (" . implode(",", $doencasSelecionadas) . ")";
    $result = mysqli_query($conexao, $sqlExcluir);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header("Location:./contaUsuario.php");
        exit();
    }
}
if(isset($_POST['AttDcnts']) && empty($_POST['doenca_ids'])){
    $idUsuario = $_SESSION['id_usuario'];
    $sqlLimpar = "DELETE FROM Usuario_DoencaCronica WHERE idUsuario ='$idUsuario'";
    $result = mysqli_query($conexao, $sqlLimpar);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header("Location:./contaUsuario.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="editarUsuario.css" rel="stylesheet">
    <link href="fullsite.css" rel="stylesheet">
    <link href="assets/header_footer.css" rel="stylesheet">
    <title>ReceiteMe</title>
</head>

<body>
    <?php
    require "assets/header.php";
    ?>
    <main>
        <div id="conteudo">
            <h1>Editar Perfil</h1>
            <div id="primeira_linha">
                <div id="coluna_esquerda">
                    <form action="editarUsuario.php" method="POST" enctype="multipart/form-data">
                        <?php
                        $idUsuario = $_SESSION['id_usuario'];
                        $execUsuario = mysqli_query($conexao, "Select * from usuario WHERE idUsuario=$idUsuario") or die(mysqli_error($conexao));
                        $dadosUsuario = mysqli_fetch_assoc($execUsuario);
                        ?>
                        <div id="divInputs">
                            <div class="inputBox">
                                <label for="nome">Nome : </label>
                                <input id="nome" type="text" name="nomeUsuario" class="Inputs" value="<?= $dadosUsuario["nome"] ?>"><br>
                            </div>
                            <div class="inputBox">
                                <label for="email">Email : </label>
                                <input id="email" type="text" name="emailUsuario" class="Inputs" value="<?= $dadosUsuario['email'] ?>"><br>
                            </div>
                            <div class="inputBox">
                                <label for="senha">Senha : </label>
                                <input id="senha" type="text" name="senhaUsuario" class="Inputs" value="<?= $dadosUsuario['senha'] ?>"><br>
                            </div>
                            <div class="inputBox">
                                <div id="imagemUsuario">
                                    <label for="imagemUsuario">Suba a Nova Imagem aqui!</label><br>
                                    <input id="imagemUsuario" type="file" name="imgFile">
                                </div>
                                <div id="divEnviar">
                                    <input type="submit" class="enviar" value="Salvar Usuario" name="salvarUsuario">
                                </div>
                            </div>
                        </div>
                    </form>
                    <h2>Editar DCNTs</h2>
                    <form action="editarUsuario.php" method="post">
                        <p>Selecione as Doenças Crônicas:</p>
                        <?php
                        //Select doencas relacionados com usuario
                        $selectDoencasUsuario = "SELECT U.idUsuario,
                                            U.nome AS nomeUsuario,
                                            DC.idDoenca,
                                            DC.nomeDoenca AS nomeDoenca
                                            FROM Usuario U
                                            JOIN Usuario_DoencaCronica UD ON U.idUsuario = UD.idUsuario    
                                            JOIN Doenca_Cronica DC ON UD.idDoenca = DC.idDoenca;";
                        $execSelectDoencasUsuario = mysqli_query($conexao, $selectDoencasUsuario);
                        $dadosDoencasUsuario = mysqli_fetch_all($execSelectDoencasUsuario);

                        // Select para obter a lista de doenças crônicas
                        $selectDoencas = "SELECT idDoenca, nomeDoenca FROM Doenca_Cronica";
                        $execSelectDoencas = mysqli_query($conexao, $selectDoencas);

                        if ($execSelectDoencas->num_rows > 0) {
                            while ($linhaDoencas = $execSelectDoencas->fetch_assoc()) :
                                $doencaId = $linhaDoencas['idDoenca'];
                                $doencaNome = $linhaDoencas['nomeDoenca'];
                                $usuarioId = $_SESSION['id_usuario'];

                                $isChecked = false;
                                foreach ($dadosDoencasUsuario as $dados) {
                                    if ($dados[0] == $usuarioId && $dados[2] == $doencaId) {
                                        $isChecked = true;
                                        break;
                                    }
                                }
                        ?>
                                <input id="doenca_id_<?= $doencaId ?>" type='checkbox' name='doenca_ids[]' value='<?= $doencaId ?>' <?= $isChecked ? 'checked' : '' ?>>
                                <label for="doenca_id_<?= $doencaId ?>"><?= $doencaNome ?></label><br>
                        <?php
                            endwhile;
                        } else {}
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
                    <img src="<?= $dadosUsuario['img'] ?>" alt="FotoPerfil">
                </div>
            </div>
        </div>
    </main>
    <?php
    require "assets/footer.php";
    ?>
</body>

</html>