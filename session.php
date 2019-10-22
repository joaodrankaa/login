<?php
session_start();
require_once "configBD.php";

if(isset($_SESSION['nomeDoUsuario'])){
    //logado

}else{
    //se nao tiver logado, redirecionado para index
    header("location: index.php");

}