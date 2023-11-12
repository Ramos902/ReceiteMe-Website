<?php
require "assets/conexao.php";

session_start();
//Registrar Usuario

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="login_registro.css" rel="stylesheet">
    <link href="fullsite.css" rel="stylesheet">
    <link href="assets/header_footer.css" rel="stylesheet">
    <title>ReceiteMe</title>
</head>

<body>
    <header id="menu-principal">
        <div id="logo">
            <a href="index.php">
                <img src=".\images\site\logoSiteOrange.png" height="130px" width="220px" alt="LogoReceiteMe">
            </a>
        </div>
    </header>
    <main>
        <div id="divCadastro">
            <div id="divLogin">
                <h1>Login</h1>
                <div class="divForm">
                    <form action="" method="POST" class="formClass">
                        <div class="inputBox">
                            <input type="text" id="email" name="email" required>
                            <span>E-mail</span>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="senha" id="password" required>
                            <span>Senha</span>
                        </div>
                        <a href="./recuperarSenha.php">Esqueci Minha Senha</a>
                        <div class="errorPhp">
                            <?php
                            if (isset($_POST['submitLogin'])) {
                                if (strlen($_POST['email']) > 255 || strlen($_POST['senha']) > 255) {
                                    echo ("<p>Algum campo excede o Limite de Caracteres Permitidos!</p>");
                                } else {
                                    $email = $_POST['email'];
                                    $senha = $_POST['senha'];
                                    $verificadorEmail = mysqli_query($conexao, "SELECT * FROM usuario WHERE email = '$email';");

                                    $linhasEmail = mysqli_num_rows($verificadorEmail);
                                    if ($linhasEmail == 1) {
                                        $verificarConta = mysqli_query($conexao, "SELECT * FROM usuario WHERE email = '$email' AND senha = '$senha'");
                                        if (mysqli_num_rows($verificarConta) == 1) {
                                            $dadosConta = mysqli_fetch_assoc($verificadorEmail);
                                            if ($dadosConta['adm'] == "s") {
                                                $_SESSION['adm'] = true;
                                            }else{
                                                $_SESSION['adm'] = false;
                                            }
                                            $_SESSION['logado'] = true;
                                            $_SESSION['id_usuario'] = $dadosConta['idUsuario'];
                                            header('Location:./index.php');
                                            exit();
                                        } else {
                                            echo ("<p>Senha Errada</p>");
                                        }
                                    } else {
                                        echo ("<p>Conta não Encontrada</p>");
                                    }
                                }
                            }
                            ?>
                        </div>
                        <input type="hidden" name="submitLogin" value="1">
                        <input type="submit" class="buttonSubmit" value="Enviar">
                    </form>
                </div>
            </div>
            <div id="divRegistro">
                <h1>Registre-se</h1>
                <div class="divForm" id="divFormRegistro">
                    <form action="./login_registro.php" method="POST" class="formClass">
                        <div class="inputBox">
                            <input type="text" name="nome" id="nome" required>
                            <span>Nome</span>
                        </div>
                        <div class="inputBox">
                            <input type="text" id="email" name="emailReg" required>
                            <span>E-mail</span>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="senha" id="password1" required>
                            <span>Senha</span>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="senhaConfirm" id="password2" required>
                            <span>Validar Senha</span>
                        </div>

                        <div class="errorPhp">
                            <?php
                            if (isset($_POST['submitRegistro'])) {
                                if (strlen($_POST['emailReg']) > 255 || strlen($_POST['senha']) > 255 || strlen($_POST['senhaConfirm']) > 255 || strlen($_POST['nome']) > 255) {
                                    echo ("<p>Algum campo excede o Limite de Caracteres Permitidos!</p>");
                                } else {
                                    $email = $_POST['emailReg'];
                                    $verificadorEmail = mysqli_query($conexao, "SELECT COUNT(*) as total FROM usuario WHERE email = '$email';");
                                    $linhasEmail = mysqli_fetch_assoc($verificadorEmail);
                                    if ($linhasEmail['total'] == 0) {
                                        if ($_POST['senha'] == $_POST['senhaConfirm'] && strlen($_POST['senha']) >= 8) {
                                            $senha = $_POST['senha'];
                                            $nome = $_POST['nome'];

                                            $insertUsuario = "INSERT INTO usuario(email, senha, nome, img, adm) VALUES ('$email','$senha','$nome','images/site/Usuario.png', 'n');";
                                            $resultado = mysqli_query($conexao, $insertUsuario);
                                            if ($resultado) {
                                                echo ("<p>Conta criada com Sucesso</p>");
                                            } else {
                                                echo ("<p>Ocorreu um Erro na Criação da Conta</p>");
                                            }
                                        } else {
                                            echo ("<p>Senhas não Coincidem ou não Atinge minimo de 8 Caracteres</p>");
                                        }
                                    } else {
                                        echo ("<p>Esse email já existe</p>");
                                    }
                                }
                            }
                            ?>
                        </div>
                        <input type="hidden" name="submitRegistro" value="1">
                        <input type="submit" class="buttonSubmit" value="Enviar">
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>