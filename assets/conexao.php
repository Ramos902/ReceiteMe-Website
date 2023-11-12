<?php
    $conexao = mysqli_connect('localhost','root','','receiteme');
    if($conexao == false){
        echo(mysqli_connect_error());
    }
?>