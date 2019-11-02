<?php
session_start();
require_once "configBD.php";

if (isset($_SESSION['nomeDoUsuario'])) {
    //logado
    $usuario = $_SESSION['nomeDoUsuario'];
    $sql = $connect->prepare("SELECT * FROM  usuario WHERE nomeDoUsuario = ?");
    $sql->bind_param("s", $usuario);
    $sql->execute();
    $resultado = $sql->get_result();
    $linha = $resultado->fetch_array(MYSQLI_ASSOC);

    $nomeDoUsuario = $linha['nomeDoUsuario'];
    $nomeCompleto = $linha['nomeCompleto'];
    $emailUsuario = $linha['emailUsuario'];
    $urlImagem = $linha['urlImagem'];
    $dataCriado = $linha['dataCriado'];


    //converçao de data
    $dataCriado =  date('d/m/Y', strtotime($dataCriado));
} else {
    //se nao esiver logado , redirecionar para index
    header("location: index.php");
}
