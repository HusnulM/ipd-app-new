<?php

class Reportslip_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getHeader($strdate, $enddate, $dept){
        if($dept === "ALL"){
            $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.createdon BETWEEN '$strdate' AND '$enddate'");
        }else{
            $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.createdon BETWEEN '$strdate' AND '$enddate' AND a.deptid = '$dept'");
        }
		return $this->db->resultSet();
    }

    public function getDetail($reqnum){
        $this->db->query("SELECT a.*, b.matdesc, b.brand, b.supplier, b.stdprice, b.image FROM t_request_slip02 as a left join t_material as b on a.material = b.material WHERE a.requestnum='$reqnum'");
        return $this->db->resultSet();
    }

    public function getExportData($strdate, $enddate, $dept){
        if($dept === "ALL"){
            $this->db->query("SELECT a.*, b.department, c.request_item, c.material, d.matdesc, c.quantity, c.unit FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id inner join t_request_slip02 as c on a.requestnum = c.requestnum inner join t_material as d on c.material = d.material WHERE a.createdon BETWEEN '$strdate' AND '$enddate'");
        }else{
            $this->db->query("SELECT a.*, b.department, c.request_item, c.material, d.matdesc, c.quantity, c.unit FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id inner join t_request_slip02 as c on a.requestnum = c.requestnum inner join t_material as d on c.material = d.material WHERE a.createdon BETWEEN '$strdate' AND '$enddate' AND a.deptid = '$dept'");
        }
		return $this->db->resultSet();
    }
}