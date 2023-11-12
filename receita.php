<?php
require "Assets/conexao.php";

//Session Login
session_start();


//Select Receita
$id = $_GET["id"];
$comandoDadosReceita = "Select * from receita where idReceita = $id";
$result = mysqli_query($conexao, $comandoDadosReceita) or die(mysqli_error($conexao));
$Receitas = mysqli_fetch_assoc($result);

//Select DCNTs
$comandoDcnts = "SELECT R.nomeReceita, DC.nomeDoenca
FROM Receita R
JOIN Receita_DoencaCronica RDC ON R.idReceita = RDC.idReceita
JOIN Doenca_Cronica DC ON RDC.idDoenca = DC.idDoenca
WHERE R.idReceita = $id;";
$execComandoDcnts = mysqli_query($conexao, $comandoDcnts);
$dadosDcnts = mysqli_fetch_all($execComandoDcnts);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="receita.css" rel="stylesheet">
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
            <div id="primeira_linha">
                <div id="coluna_esquerda">
                    <h1><?= $Receitas['nomeReceita'] ?></h1>
                    <?php
                        if(!empty($dadosDcnts)):
                            echo("<h1>Caracteristicas</h1>");
                            echo("<h3>Indicado para Usuarios que Sofrem Com :</h3>");
                            if (mysqli_num_rows($execComandoDcnts) >= 0) : 
                                foreach($dadosDcnts as $dadosDcnts) :?>
                                    <li><?=$dadosDcnts['1']?></li>
                            <?php endforeach;  
                            endif; 
                        endif;
                    ?>
                </div>
                <div id="coluna_direita">
                    <img src="<?= $Receitas['img'] ?>" alt="">
                </div>
            </div>
            <div id="linha_ingredientes">
                <h1>Ingredientes</h1>
                <ul>
                    <?php
                    $ingredientes = explode("&", $Receitas["ingredientes"]);
                    foreach ($ingredientes as $ingrediente) {
                        echo "<li>$ingrediente</li>";
                    }
                    ?>
                </ul>
            </div>
            <div id="linha_modopreparo">
                <h1>Modo de Preparo</h1>
                <ul>
                    <?php
                    $modoPreparo = explode("&", $Receitas["modoPreparo"]);
                    foreach ($modoPreparo as $modoPreparo) {
                        echo "<li>$modoPreparo</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </main>





    <!--
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Dividir a string de ingredientes em um array
            $ingredientes = explode(", ", $row["ingredientes"]);
    
            // Exibir nome da receita
            echo "<h2>" . $row["nomeReceita"] . "</h2>";
            
            // Exibir lista de ingredientes
            echo "<ul>";
            foreach ($ingredientes as $ingrediente) {
                echo "<li>$ingrediente</li>";
            }
            echo "</ul>";
            
            // Exibir modo de preparo e imagem (substitua pelos seus campos)
            echo "<p>Modo de Preparo: " . $row["modoPreparo"] . "</p>";
            echo "<img src='" . $row["img"] . "' alt='Imagem da Receita'>";
        }
    } else {
        echo "Nenhuma receita encontrada.";
    }
    -->


    <?php
    require "assets/footer.php";
    ?>
</body>

</html>