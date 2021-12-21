<?php

class Prtype_model{

    private $db;

    public function __construct(){
		  $this->db = new Database;
    }
    
    public function getList(){
        $this->db->query('SELECT * FROM t_prtype');
		return $this->db->resultSet();
    }

    public function getById($id){
        $this->db->query("SELECT * FROM t_prtype WHERE prtype='$id'");
		return $this->db->single();
    }


    public function  save($data){
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO t_prtype (prtype,description,createdon,createdby) 
                  VALUES(:prtype,:description,:createdon,:createdby)";
        $this->db->query($query);
        $this->db->bind('prtype',      $data['prtype']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('createdon',   $currentDate);
        $this->db->bind('createdby',   $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function  update($data){
        $query = "UPDATE t_prtype set description=:description WHERE prtype=:prtype";
        $this->db->query($query);
      
        $this->db->bind('prtype',      $data['prtype']);
        $this->db->bind('description', $data['description']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function delete($id){
      $this->db->query("DELETE FROM t_prtype WHERE prtype=:prtype");

      $this->db->bind('prtype', $id);
      $this->db->execute();

      return $this->db->rowCount();
    }
}