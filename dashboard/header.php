<header id="menu-principal">
    <div id="logo">
        <a href="../index.php">
            <img src="../images/site/logoSiteOrange.png" height="130px" width="220px" alt="LogoReceiteMe">
        </a>
    </div>
    <div id="DivPesquisa">
        <label for="BarraPesquisa"></label>
        <form action="../index.php?src=<?php if (isset($_GET['search'])) {
                                            echo ($_GET['search']);
                                        } else {
                                        } ?>" method="get">
            <input type="text" id="Pesquisa" name="search" placeholder="Pesquise sua Receita">
        </form>
    </div>
    <div id="BoxDireita">
        <?php if (isset($_SESSION['logado'])) : ?>
            <div class="DivItensDireita" id="minhaAcc">
                <?php
                //Select Usuario
                $selectUsuario = "SELECT idUsuario FROM usuario where idUsuario = '$_SESSION[id_usuario]';";
                $execSelectUsuario = mysqli_query($conexao, $selectUsuario);
                $dadosUsuario = mysqli_fetch_assoc($execSelectUsuario);
                ?>
                <a href="../contaUsuario.php?">
                    <div class="DivItensDireita" id="usuario">
                        <img class="imgMenuDireita" src="../images/site/Usuario.png" alt="User">
                    </div>
            </div>
            </a>
    </div>
<?php else : ?>
    <div class="DivItensDireita" id="usuario">
        <a href="../login_registro.php">
            <img class="imgMenuDireita" src="../images/site/Usuario.png" alt="User">
        </a>
    </div>
<?php endif; ?>
</div>
</header>