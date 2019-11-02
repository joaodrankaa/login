<?php
//iniciando a sessão
session_start();
//conexão com o banco de dados
require_once 'configBD.php';

function verificar_entrada($entrada)
{
    $saida = htmlspecialchars($entrada);
    $saida = stripslashes($saida);
    $saida = trim($saida);
    return $saida; //retorna a saida limpa
}

//teste se existe a ação
if (isset($_POST['action'])) {

    if ($_POST['action'] == 'cadastro') {
        //teste se ação é igual a cadastro
        #echo "\n<p>cadastro</p>"; //pre-formatada
        #echo "\n<pre>";
        #print_r($_POST);
        #echo "\n</pre>";
        //pegando dados do formulario
        $nomeCompleto = verificar_entrada($_POST['nomeCompleto']);
        $nomeDoUsuario = verificar_entrada($_POST['nomeDoUsuario']);
        $emailUsuario = verificar_entrada($_POST['emailUsuario']);
        $urlImagem = verificar_entrada($_POST['urlImagem']);
        $senhaDoUsuario = verificar_entrada($_POST['senhaDoUsuario']);
        $senhaUsuarioConfirmar = verificar_entrada($_POST['senhaUsuarioConfirmar']);
        $dataCriado = date("Y-m-d"); //data atual no formato banco de dados

        //codificando as senhas
        $senhaCodificada = sha1($senhaDoUsuario);
        $senhaConfirmarCod = sha1($senhaUsuarioConfirmar);

        //teste de captura de dados
        // echo "<p>Nome completo: $nomeCompleto </p>";
        // echo "<p>Nome de usuário: $nomeDoUsuario </p>";
        // echo "<p>E-mail: $emailUsuario </p>";
        // echo "<p>Senha : $senhaCodificada</p>";
        // echo "<p>Data de criação: $dataCriado</p>";
        if ($senhaCodificada != $senhaConfirmarCod) {
            echo "<p class='text-danger'>Senhas não conferem.</p>";
            exit();
        } else {
            //as senhas conferem , verificar se o usuario já
            //existe no banco de dados
            $sql = $connect->prepare("SELECT nomeDoUsuario, emailUsuario 
            FROM usuario WHERE nomeDoUsuario = ? OR emailUsuario = ?");
            $sql->bind_param("ss", $nomeDoUsuario, $emailUsuario);
            $sql->execute();
            $resultado = $sql->get_result();
            $linha = $resultado->fetch_array(MYSQLI_ASSOC);

            //verificando a existencia do usuario no banco
            if ($linha['nomeDoUsuario'] == $nomeDoUsuario) {
                echo "<p class='text-danger'> Usuario indisponivel </p>";
            } elseif ($linha['emailUsuario'] == $emailUsuario) {
                echo "<p class='text-danger'> E-mail indisponivel </p>";
            } else {
                //usuario pode ser cadastrado no banco de dados
                $sql = $connect->prepare("INSERT into usuario (nomeDoUsuario,nomeCompleto
                ,emailUsuario,urlImagem, senhaDoUsuario,dataCriado)values(?,?,?,?,?,?)");
                $sql->bind_param(
                    "ssssss",
                    $nomeDoUsuario,
                    $nomeCompleto,
                    $emailUsuario,
                    $urlImagem,
                    $senhaCodificada,
                    $dataCriado
                );
                if ($sql->execute()) {
                    echo "<p class='text-success'>Usuário cadastrado</p>";
                } else {
                    echo "<p class='text-danger'> Usuário não cadastrado</p>";
                    echo "<p class='text-danger'> Algo deu errado</p>";
                }
            }
            # code...
        }
    } else if ($_POST['action'] == 'login') {
        $nomeUsuario = verificar_entrada($_POST['nomeUsuario']);
        $senhaUsuario = verificar_entrada($_POST['senhaUsuario']);
        $senha = sha1($senhaUsuario); //senha codificada

        $sql = $connect->prepare("SELECT * FROM usuario WHERE senhaDoUsuario = ? 
        AND nomeDoUsuario =?");
        $sql->bind_param("ss", $senha, $nomeUsuario);

        $sql->execute();

        $busca = $sql->fetch();

        if ($busca != null) {
            $_SESSION['nomeDoUsuario'] = $nomeUsuario;

            if (!empty($_POST['lembrar'])) {
                //se lembrar nao estiver vazia!
                //ou seja , a pessoa quer ser lembrada!
                setcookie("nomeDoUsuario", $nomeUsuario, time() + (60 * 60 * 24 * 30));
                setcookie("senhaDoUsuario", $senhaUsuario, time() + (60 * 60 * 24 * 30));
            } else {
                //a pessoa nao quer ser lembrada
                setcookie("nomeDoUsuario", "");
                setcookie("senhaDoUsuario", "");
            }

            echo "ok";
        } else {
            echo "<p class='text-danger'>";
            echo "falhou a entrada do sistema. Nome de usuario ou senha invalidos";
            echo "</p>";
            exit();
        }
    } else if ($_POST['action'] == 'senha') {
        //senão, teste se ação é recuperar senha
        echo "\n<p>senha</p>"; //pre-formatada
        echo "\n<pre>";
        print_r($_POST);
        echo "\n</pre>";
    } else {
        header("location:index.php");
    }
} else {
    //redirecionando para o index.php,negado o acesso 
    //a esse arquivo diretamente
    header("location:index.php");
}
