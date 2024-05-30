<?php

namespace App\Controllers;
// responsável por tudo antes do usuário entrar na aplicação
//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] :'';
		// só para relembrar que o $this->view é o objeto vazio 
		$this->render('index');
	}
	public function inscreverse(){
		$this->view->usuario = array(
			'nome'=> '',
			'email'=>'',
			'senha'=>'',
		);
		$this->view->erroCadastro = false;
		$this->render('inscreverse');
	}
	public function registrar(){
		//isso por si só não retorna nada
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome'])->
		__set('email', $_POST['email'])->
		__set('senha', md5($_POST['senha']));
		if($usuario->verificaDados() && count($usuario->checaUsuarioRepetido()) ==0){
			$usuario->salvar();
			$this->render('cadastro');
		}else{
			// isso funciona pois, quando acessamos alguma coisa por meio da rota ele passa pela view que é um
			// objeto que nós podemos ir definindo ao longo da aplicação
			$this->view->usuario = array(
				'nome'=> $_POST['nome'],
				'email'=> $_POST['email'],
				'senha'=> $_POST['senha'],
			);
			$this->view->erroCadastro= true;
			$this->render('inscreverse');
		}
	}

}

