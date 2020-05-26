<?php

namespace App\Models;

use MF\Model\Model;

class tweet extends Model {

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    //salvar
    public function salvar(){

        $query = "INSERT INTO tweets(id_usuario, tweet)VALUES(:id_usuario, :tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();

        return $this;
    }

    //mostrar
    public function getAll(){
        $query = "
        SELECT 
            t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(T.DATA, '%d/%m/%Y %H:%i') as data
        FROM tweets as t
        left join usuarios as u on (t.id_usuario = u.id)
            where 
        t.id_usuario = :id_usuario
        OR t.id_usuario in (SELECT id_usuario_seguindo FROM usuarios_seguidores
            WHERE id_usuario = :id_usuario)
        ORDER BY 
            t.data desc
        
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));        
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function remover_tweet($id_tweet){
        $query = 'DELETE FROM tweets WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id',  $id_tweet);        
        $stmt->execute();

        return $this;
    }
}




?>