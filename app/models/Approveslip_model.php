<?php

class Approveslip_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getOpenRequest(){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.request_status = '1' and a.createdby = '$user'");
        return $this->db->resultSet();
    }
  
    public function getRequestForPO(){
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.request_status = '2'");
        return $this->db->resultSet();
    }
  
    public function getRequestHeader($reqnum){
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.requestnum='$reqnum'");
        return $this->db->single();
    }
  
    public function getRequestDetail($reqnum){
        $this->db->query("SELECT a.*, b.matdesc, b.brand, b.supplier, b.stdprice, b.image FROM t_request_slip02 as a left join t_material as b on a.material = b.material WHERE a.requestnum='$reqnum'");
        return $this->db->resultSet();
    }
}