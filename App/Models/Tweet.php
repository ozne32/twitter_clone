<?php
namespace App\Models;

use MF\Model\Model;
class Tweet extends Model{
    private $id;
    private $id_usuario;
    private $tweet;
    private $data;
    public function __get($attr){
        return $this->$attr;
    }
    public function __set($attr,$value){
        $this->$attr = $value;
        return $this;
    }
    //essencialmente vai ter os métodos de salvar um tweet e recuperar um
    //salvar
    public function salvar(){
        $query = 'insert into tweets(id_usuario, tweets) values(:id_usuario,:tweets)';
        $smtm = $this->db->prepare($query);
        $smtm->bindValue(':id_usuario', $this->id_usuario);
        $smtm->bindValue(':tweets', $this->tweet);
        $smtm->execute(); 
        return $this;
    }
    //recuperar
    public function getAll(){
        // o negócio das datas pelo que entendi, tem as variáveis, e as coisas que você coloca que não foram acompanhadas
        // são como você quer que fique
        $query = "SELECT 
        t.id, t.id_usuario, t.tweets, DATE_FORMAT(t.data, '%d/%m/%y %H:%i') as data, u.nome 
        from tweets as t
        left join usuarios as u 
        on t.id_usuario = u.id
        where id_usuario = ?
        order by t.data desc";
        $smtm= $this->db->prepare($query);
        $smtm->bindValue(1, $this->id_usuario);
        $smtm->execute();
        return $smtm->fetchAll(\PDO::FETCH_OBJ);
    }
}