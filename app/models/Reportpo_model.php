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

    public function getExportPO($strdate, $enddate,$openpo){
        if($openpo === "O"){
            $this->db->query("SELECT distinct a.*, c.poitem, c.material, c.matdesc, c.quantity, c.grqty, c.unit, c.price, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id inner join t_po02 as c on a.ponum = c.ponum WHERE a.podat BETWEEN '$strdate' AND '$enddate' AND a.ponum in(SELECT ponum from v_po02 WHERE openqty > 0) and a.final_approve ='Y' and a.is_rejected ='N'");
        }elseif($openpo === "R"){
            $this->db->query("SELECT a.*, c.poitem, c.material, c.matdesc, c.quantity, c.grqty, c.unit, c.price, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id inner join t_po02 as c on a.ponum = c.ponum WHERE a.podat BETWEEN '$strdate' AND '$enddate' AND a.ponum in(SELECT ponum from t_movement_02)");
        }else{
            $this->db->query("SELECT a.*, c.poitem, c.material, c.matdesc, c.quantity, c.grqty, c.unit, c.price, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id inner join t_po02 as c on a.ponum = c.ponum WHERE a.podat BETWEEN '$strdate' AND '$enddate'");
        }
		return $this->db->resultSet();
    }

    public function getReportGRPO($strdate, $enddate){
        $this->db->query("SELECT * FROM v_grpo01 WHERE movement_date BETWEEN '$strdate' AND '$enddate'");
        return $this->db->resultSet();
    }

    public function getReportOpenPO($strdate, $enddate){
        $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.podat BETWEEN '$strdate' AND '$enddate' AND a.ponum in(SELECT ponum from v_po02 WHERE openqty > 0) and a.final_approve ='Y' and a.is_rejected ='N'");
        return $this->db->resultSet();
    }

    public function getReportPendingApprove($strdate, $enddate){
        $this->db->query("SELECT a.*, c.supplier_name, b.poitem, b.material, b.matdesc, b.quantity, b.unit FROM t_po01 as a inner join t_po02 as b on a.ponum = b.ponum inner join t_supplier as c on a.vendor = c.supplier_id WHERE a.podat BETWEEN '$strdate' AND '$enddate' and a.final_approve = 'N'");
        return $this->db->resultSet();
    }
}