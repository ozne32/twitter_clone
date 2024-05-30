<?php
namespace App\Models;
//essa parte que está fazendo a comunicação com o banco de dados, que está retornando como db
use MF\Model\Model;
class Usuario extends Model{
    private $id;
    private $nome;
    private $email;
    private $senha;
    public function __get($attr){
        return $this->$attr;
    }
    public function __set($attr,$val){
        $this->$attr = $val;
        return $this;
    }
    //salvar no banco de dados
    public function salvar(){
        $query = 'insert into usuarios(nome,email,senha) values(:nome,:email,:senha)';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome', $this->nome);
        $smtm->bindValue(':email', $this->email);
        $smtm->bindValue(':senha', $this->senha); //mais para frente vamos incrementar o md5 que vai criptografar 
        // essa senha
        $smtm->execute();
        return $this; //retornar o objeto usuario
    }
    //verificar se tem o tanto de caracteres que eu acho necessário 
    public function verificaDados(){
        $verifica = true;
        if(strlen($this->nome) <3 ||strlen($this->email) <3||strlen($this->senha) <3){
            $verifica = false;
        }
        return $verifica;
    }
    //verificar se já existe no banco de dados
    public function checaUsuarioRepetido(){
        $query = 'select nome, email from usuarios where email = :email';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue('email', $this->email);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_OBJ);
    }
    public function autenticar(){
        $query = 'select id,nome,email, senha from usuarios where email = :email && senha = :senha';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':email', $this->email);
        $smtm->bindValue(':senha', $this->senha);
        $smtm->execute();
        $usuario = $smtm->fetch(\PDO::FETCH_OBJ);
        if($usuario->id != '' && $usuario->nome != ''){
            $this->__set('id', $usuario->id)->
            __set('nome', $usuario->nome);
        }
        return $this;
    }
    public function getAll(){
        // utiliza-se o like, pois assim ele procura por ocorrência de partes do array
        $query = 'select
            u.id,u.nome,u.email,
            (
                select count(*)
                from usuarios_seguidores as us
                where
                us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
            ) as seguindo_sn
            from usuarios as u
            where 
            nome like :nome and id != :id_usuario';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':nome', '%'.$this->nome.'%');
        $smtm->bindValue(':id_usuario',$this->id);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_OBJ);
    }
    public function seguirUsuario($id_usuario_seguindo){
        echo $id_usuario_seguindo;
        echo $this->id;
        $query = 'INSERT into usuarios_seguidores(id_usuario, id_usuario_seguindo) values(:id_usuario,:id_usuario_seguindo)';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id_usuario',$this->id);
        $smtm->bindValue(':id_usuario_seguindo',$id_usuario_seguindo);
        $smtm->execute();
        return true;
    }
    public function deixarSeguirUsuario($id_usuario_seguindo){
        $query = 'DELETE from usuarios_seguidores where id_usuario = ? and id_usuario_seguindo = ?';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(1,$this->id);
        $smtm->bindValue(2,$id_usuario_seguindo);
        $smtm->execute();

        return true;
    }
}