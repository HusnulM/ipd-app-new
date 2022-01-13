<?php

class Reportpo_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getPOHeader($strdate, $enddate,$openpo){
        if($openpo === "O"){
            $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.podat BETWEEN '$strdate' AND '$enddate' AND a.ponum in(SELECT ponum from v_po02 WHERE openqty > 0)");
        }elseif($openpo === "R"){
            $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.podat BETWEEN '$strdate' AND '$enddate' AND a.ponum in(SELECT ponum from t_movement_02)");
        }else{
            $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.podat BETWEEN '$strdate' AND '$enddate'");
        }
		return $this->db->resultSet();
    }

    public function getPODetail($ponum){
        $this->db->query("SELECT * FROM t_po02 WHERE ponum = '$ponum'");
        return $this->db->resultSet();
    }
}