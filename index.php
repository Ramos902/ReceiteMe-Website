<?php
//Conexao
require "assets/conexao.php";

//Session Login
session_start();

//Select Receitas
$selectReceita = "SELECT r.idReceita, r.nomeReceita, r.img, c.nomeCategoria, r.status
FROM Receita r
JOIN Categoria c ON r.categoria = c.idCategoria;

";
$comandoDadosReceita = mysqli_query($conexao, $selectReceita) or die(mysqli_error($conexao));

//Select Categorias
$selectCategoria = "Select * from categoria";
$comandoDadosCategoria = mysqli_query($conexao, $selectCategoria) or die(mysqli_error($conexao));

//Search Box
if (isset($_GET['search'])) {
    $pesquisa = $_GET['search'];

    $selectReceita = "SELECT r.*, c.nomeCategoria 
                      FROM receita r 
                      LEFT JOIN categoria c ON r.categoria = c.idCategoria 
                      WHERE r.nomeReceita LIKE '%$pesquisa%'
                         OR c.nomeCategoria LIKE '%$pesquisa%'
                         OR EXISTS (SELECT 1 
                                    FROM receita_doencaCronica rd 
                                    JOIN doenca_cronica dc ON rd.idDoenca = dc.idDoenca 
                                    WHERE rd.idReceita = r.idReceita 
                                      AND dc.nomeDoenca LIKE '%$pesquisa%')";

    $comandoDadosReceita = mysqli_query($conexao, $selectReceita) or die(mysqli_error($conexao));
}

//Selecionar Categoria
if (isset($_GET['cat'])) {
    $categoria = $_GET['cat'];

    $selectReceita = "SELECT r.*, c.nomeCategoria
    FROM Receita r
    JOIN Categoria c ON r.categoria = c.idCategoria
    WHERE c.idCategoria = $categoria;";

    $comandoDadosReceita = mysqli_query($conexao, $selectReceita) or die(mysqli_error($conexao));

    if (($comandoDadosReceita && mysqli_num_rows($comandoDadosReceita) > 0)) {
        $CatPesquisa = mysqli_fetch_assoc($comandoDadosReceita);
    } else {
        // Se não houver nenhuma linha, inicializar $CatPesquisa como um array vazio
        $CatPesquisa = array();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="index.css" rel="stylesheet">
    <link href="fullsite.css" rel="stylesheet">
    <link href="assets/header_footer.css" rel="stylesheet">
    <script src="index_script.js"></script>
    <title>ReceiteMe</title>
</head>

<body>
    <?php
    require "assets/header.php";
    ?>
    <main>
        <div id="conteudo">
            <!--Slider-->
            <div id="slider">
                <input type="radio" name="radio-btn" id="radio1" checked>
                <input type="radio" name="radio-btn" id="radio2">
                <input type="radio" name="radio-btn" id="radio3">

                <!--Imagens do Slider-->
                <div class="slides">
                    <div class="slide first">
                        <img src="./images/site/slider/image_1.png" alt="Imagem1">
                    </div>
                    <div class="slide">
                        <img src="./images/site/slider/image_2.png" alt="Imagem2">
                    </div>
                    <div class="slide">
                        <img src="./images/site/slider/image_3.png" alt="Imagem3">
                    </div>
                </div>

                <div id="navigation-auto">
                    <div id="auto-btn1"></div>
                    <div id="auto-btn2"></div>
                    <div id="auto-btn3"></div>
                </div>

                <div id="navigation">
                    <label for="radio1" class="navigators"></label>
                    <label for="radio2" class="navigators"></label>
                    <label for="radio3" class="navigators"></label>
                </div>
            </div>

            <div id="categoria">
                <h1>Categorias</h1>
                <div id="categoria_imagens">
                    <?php
                    while ($Categorias = mysqli_fetch_assoc($comandoDadosCategoria)) :
                    ?>
                        <a href="index.php?cat=<?= $Categorias['idCategoria'] ?>">
                            <img src="<?= $Categorias['imgCategoria'] ?>" alt="ImagemCat" width="150" height="150">
                            <p><?= $Categorias['nomeCategoria'] ?></p>
                        </a>
                    <?php
                    endwhile;
                    ?>
                </div>
            </div>
            <div id="receitas_recomendadas">
                <?php
                if (isset($_GET['search'])) :
                ?>
                    <h1>Resultado da Pesquisa - "<?= $pesquisa ?>"</h1>
                <?php elseif (isset($_GET['cat'])) :

                    if (isset($CatPesquisa) && is_array($CatPesquisa) && !empty($CatPesquisa)) {
                        print("<h1>Resultados da Categoria - " . $CatPesquisa['nomeCategoria'] . " </h1>");
                    } else {
                        print("<h1>Nenhuma receita encontrada para esta categoria</h1>");
                    }
                else :
                    print("<h1>Receitas Recomendadas</h1>");
                endif;
                ?>
                <div id="cardReceita">
                    <?php
                    mysqli_data_seek($comandoDadosReceita, 0);
                    while ($Receitas = mysqli_fetch_assoc($comandoDadosReceita)) :
                        if ($Receitas['status'] == "ativo") :
                    ?>
                            <div class="conteudoReceitas">
                                <a href="receita.php?id=<?= $Receitas['idReceita'] ?>" class="receita-linha">
                                    <div class="ImagemReceita">
                                        <img src="<?= $Receitas['img'] ?>" width="200" height="auto" alt="ImagemReceita">
                                    </div>
                                    <div class="NomeReceita">
                                        <p><?= $Receitas['nomeReceita'] ?></p>
                                    </div>
                                    <div class="DescricaoReceita">
                                        <p><?= $Receitas['nomeCategoria'] ?></p>
                                    </div>
                                </a>
                            </div>
                    <?php else : endif;
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php
    require "assets/footer.php";
    ?>

</body>