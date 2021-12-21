<?php

class Warehouse_model{

    private $db;

    public function __construct(){
		  $this->db = new Database;
    }
    
    public function getList(){
        $this->db->query('SELECT * FROM t_warehouse');
		return $this->db->resultSet();
    }

    public function getById($id){
        $this->db->query("SELECT * FROM t_warehouse WHERE warehouseid='$id'");
		return $this->db->single();
    }

    public function getWarehouseByPrType($prtype){
        $this->db->query("SELECT a.prtype, b.description, a.warehouseid, c.warehousename FROM t_whs_prtype as a inner join t_prtype as b on a.prtype = b.prtype inner join t_warehouse as c on a.warehouseid = c.warehouseid WHERE a.prtype='$prtype'");
		return $this->db->resultSet();
    }

    public function getWarehousePrTypeAssignement($whscode){
        $this->db->query("SELECT a.prtype, b.description, a.warehouseid, c.warehousename FROM t_whs_prtype as a inner join t_prtype as b on a.prtype = b.prtype inner join t_warehouse as c on a.warehouseid = c.warehouseid WHERE a.warehouseid='$whscode'");
		return $this->db->resultSet();
    }

    public function savewhsassignment($whscode, $prtype){
        $querydelete = "DELETE FROM t_whs_prtype WHERE prtype='$prtype' AND warehouseid='$whscode'";
        $this->db->query($querydelete);
        $this->db->execute();

        $query = "INSERT INTO t_whs_prtype (prtype,warehouseid) 
                  VALUES(:prtype,:warehouseid)";
        $this->db->query($query);
        $this->db->bind('prtype',        $prtype);
        $this->db->bind('warehouseid',   $whscode);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function deletewhsassignment($whscode, $prtype){
        $query = "DELETE FROM t_whs_prtype WHERE prtype='$prtype' AND warehouseid='$whscode'";
        $this->db->query($query);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function  save($data){
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO t_warehouse (warehouseid,warehousename,createdon,createdby) 
                  VALUES(:warehouseid,:warehousename,:createdon,:createdby)";
        $this->db->query($query);
        $this->db->bind('warehouseid',   $data['warehouseid']);
        $this->db->bind('warehousename', $data['warehousename']);
        $this->db->bind('createdon',     $currentDate);
        $this->db->bind('createdby',     $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function  update($data){
        $query = "UPDATE t_warehouse set warehousename=:warehousename WHERE warehouseid=:warehouseid";
        $this->db->query($query);
      
        $this->db->bind('warehouseid',   $data['warehouseid']);
        $this->db->bind('warehousename', $data['warehousename']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function delete($id){
      $this->db->query("DELETE FROM t_warehouse WHERE warehouseid=:warehouseid");

      $this->db->bind('warehouseid', $id);
      $this->db->execute();

      return $this->db->rowCount();
    }
}