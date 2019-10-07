<?php


//teste se existe a ação
    if(isset($_POST['action'])){

    if($_POST['action'] =='cadastro'){
//teste se ação é igual a cadastro
        echo "\n<p>cadastro</p>";//pre-formatada
        echo "\n<pre>";
        print_r($_POST);
        echo "\n</pre>";

    }else if($_POST['action'] =='login'){
        //senão, teste se ação e login
        echo "\n<p>login</p>"; //pre-formatada
        echo "\n<pre>";
        print_r($_POST);
        echo "\n</pre>";

    }else if($_POST['action'] =='senha'){
        //senão, teste se ação é recuperar senha
        echo "\n<p>senha</p>"; //pre-formatada
        echo "\n<pre>";
        print_r($_POST);
        echo "\n</pre>";

    }else{
            header("location:index.php");
        }
}else{
    //redirecionando para o index.php,negado o acesso 
    //a esse arquivo diretamente
        header("location:index.php");
}