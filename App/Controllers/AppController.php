<?php
// responsável por tudo que acontece após o login
namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;
class AppController extends Action{
    public function timeline(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $this->view->tweets = $tweet->getAll();
        $this-> render('timeline');
        
        
    }
    public function postarTweet(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id'])->
        __set('tweet', $_POST['texto_tweet']);
        $tweet->salvar();
        header('location: /timeline');
        
    }
    public function validaAutenticacao(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == ''){
            header('location: /?login=erro');
        }
    }
    public function quemSeguir(){
        $this->validaAutenticacao();        
        $nome_pessoa = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor']: '';
        if($nome_pessoa != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $nome_pessoa);
            $usuario->__set('id', $_SESSION['id']);
            $this->view->usuarios = $usuario->getAll();
        }
        $this->render('/quemSeguir');
    }
    public function acao(){
        $this->validaAutenticacao();
        print_r($_GET);
        // pega a classe com a conexão já
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        //acao-->seguir ou deixar de seguir
        $acao = isset($_GET['acao'])? $_GET['acao']:'';
        $id_usuario_seguindo = isset($_GET['id_usuario'])? $_GET['id_usuario']:'';
        echo $id_usuario_seguindo;
        if($acao == 'seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);
        }else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }
        header('location:/quemSeguir');
    }
}