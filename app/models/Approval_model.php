<?php

class Approval_model{

    private $db;

    public function __construct()
	{
		$this->db = new Database;
    }

    public function getmappingapproval(){
        $this->db->query("SELECT * FROM t_approval ORDER BY object, creator, level");
        return $this->db->resultSet();
    }

    public function getuserapproval(){
        $this->db->query("SELECT * FROM v_user");
        return $this->db->resultSet();
    }

    public function getusercreator(){
        $this->db->query("SELECT * FROM v_user");
        return $this->db->resultSet();
    }

    public function  save($data){
        $query = "INSERT INTO t_approval (object,doctype,level,creator,approval) 
                      VALUES(:object,:doctype,:level,:creator,:approval)";
        $this->db->query($query);
        
        $this->db->bind('object',   $data['object']);
        $this->db->bind('doctype',  $data['doctype']);
        $this->db->bind('level',    $data['level']);
        $this->db->bind('creator',  $data['creator']);
        $this->db->bind('approval', $data['approval']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function delete($object,$doctype,$creator,$approval){
        $query = "DELETE FROM t_approval WHERE object=:object AND doctype=:doctype AND creator=:creator AND approval=:approval";
        $this->db->query($query);
      
        $this->db->bind('object',   $object);
        $this->db->bind('doctype',  $doctype);
        $this->db->bind('creator',  $creator);
        $this->db->bind('approval', $approval);
        $this->db->execute();

        return $this->db->rowCount();
    }
}