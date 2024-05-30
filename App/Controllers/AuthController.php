<?php
namespace App\Controllers;
// responsável por tudo que faz a autenticação do usuário e cookies
//classes do miniframework
use MF\Controller\Action;
use MF\Model\Container;
class AuthController extends Action{
    public function autenticar(){
        // criando a classe usuários
        $usuario = Container::getModel('Usuario');
        //colocando os valores nela
        $usuario->__set('email', $_POST['email'])->
        __set('senha',md5($_POST['senha']));
        $validacao = $usuario->autenticar();
        if($validacao->id != '' && $validacao->nome != ''){
            session_start();
            $_SESSION['nome'] = $validacao->nome;
            $_SESSION['id']= $validacao->id;
            header('location:/timeline');
        }else{
            header('location:/?login=erro');
        };
    }
    public function sair(){
        session_start();
        session_destroy();
        header('location:/');
    }
}