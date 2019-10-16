<?php
//conexao com o banco de dados
require_once 'configBD.php';

function verificar_entrada($entrada){
    //filtrando a entrada
    $saida = htmlspecialchars($entrada);
    $saida = stripslashes($saida);
    $saida = trim($saida);
    return $saida;//retorna a saida limpa
}



//teste se existe a ação
    if(isset($_POST['action'])){

    if($_POST['action'] =='cadastro'){
//teste se ação é igual a cadastro
        #echo "\n<p>cadastro</p>";//pre-formatada
        #echo "\n<pre>";
        #print_r($_POST);
        #echo "\n</pre>";
        $nomeCompleto = verificar_entrada($_POST['nomeCompleto']);
        $nomeDoUsuario = verificar_entrada($_POST['nomeDoUsuario']);
        $emailUsuario = verificar_entrada($_POST['emailUsuario']);
        $senhaDoUsuario = verificar_entrada($_POST['senhaDoUsuario']);
        $senhaUsuarioConfirmar = verificar_entrada($_POST['senhaUsuarioConfirmar']);
        $dataCriado = date("Y-m-d");//data atual no formato banco de dados

        //condificando as senhas
        $senhaCondificada = sha1($senhaDoUsuario);
        $senhaCondificadaCod = sha1($senhaUsuarioConfirmar);

        //teste de captura de dados
        // echo "<p>Nome Completo: $nomeCompleto </p>";
        // echo "<p>Nome De Usuario: $nomeDoUsuario</p>";
        // echo "<p>E-mail: $emailUsuario </p>";
        // echo "<p>Senha: $senhaCondificada</p>";
        // echo "<p>Data de criação: $dataCriado</p>";
        if($senhaCondificada != $senhaCondificadaCod){
            echo "<p class='text-danger'>Senhas nao conferem.</p>";
            exit();
        }else{
            //as senhas conferem,verificar se o usuario existe no banco de dados
            $sql = $connect ->prepare("SELECT nomeDoUsuario, emailUsuario FROM usuario WHERE nomeDoUsuario = ? OR emailUsuario = ? ");
            $sql->bind_param("ss", $nomeDoUsuario, $emailUsuario);
            $sql->execute();
            $resultado = $sql->get_result();
            $linha = $resultado->fetch_array(MYSQLI_ASSOC);

            //Verificar a existencia  do usuario no banco
            if($linha['nomeDoUsuario']== $nomeDoUsuario){
                echo "<p class='text-danger'>Usuario indisponivel </p>";
            }elseif ($linha['emailUsuario'] == $emailUsuario) {
                echo "<p class='text-danger'> E-mail indisponivel </p>";
            
            }else{
                //usuario pode ser cadastrado no banco de dados
                $sql = $connect->prepare("INSERT into usuario (nomeDoUsuario,
                nomeCompleto, emailUsuario, senhaDoUsuario, dataCriado)values(?,?,?,?,?)");
                $sql->bind_param("sssss", $nomeDoUsuario, $nomeCompleto, $emailUsuario, $senhaCondificada, $dataCriado);
                if($sql->execute()){
                    echo "<p class='text-success'>Usuario cadastrado</p>";

                }else{
                    echo "<p class='text-danger'>Usuario nao cadastrado</p>";
                    
                    echo "<p class='text-danger'>Algo deu errado</p>";

                }



            }
        }


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