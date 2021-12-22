<?php

class Approveslip_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getApprovalLevel($user, $creator,$prtype){
        $this->db->query("SELECT level from t_approval where object ='RS' and approval = '$user' and doctype = '$prtype' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function getMaxApprovalLevel($creator, $approval, $prtype){
        $this->db->query("SELECT level from t_approval where object ='RS' and doctype = '$prtype' and creator = '$creator' order by level desc limit 1");
        return $this->db->single();
    }

    public function getNextApproval($creator, $level, $prtype){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='RS' and creator = '$creator' and a.level = '$level' and doctype = '$prtype' order by level asc limit 1");
        return $this->db->single();
    }

    public function getOpenRequest(){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.createdby in(SELECT creator from t_approval where object ='RS' and approval = '$user') and a.request_status in(SELECT level from t_approval where object ='RS' and approval = '$user' and creator = a.createdby)");
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

    public function approverequestslip($reqnum){
        $user     = $_SESSION['usr']['user'];

        $prdata = $this->getRequestHeader($reqnum);

        $level    = $this->getApprovalLevel($user,$prdata['createdby'],'');
        $maxlevel = $this->getMaxApprovalLevel($prdata['createdby'],$user,'');

        $approvestat = 0;
        $approvestat = $level['level']+1;

        $query = "UPDATE t_request_slip01 set request_status=:request_status, final_approve=:final_approve WHERE requestnum=:requestnum";
        $this->db->query($query);

        if($level['level'] === $maxlevel['level']){
            $this->db->bind('requestnum',     $reqnum);
            $this->db->bind('request_status', $approvestat);
            $this->db->bind('final_approve',  'Y');
            $this->db->execute();
        }else{
            $this->db->bind('requestnum',     $reqnum);
            $this->db->bind('request_status', $approvestat);
            $this->db->bind('final_approve',  'N');
            $this->db->execute();
        }

        return $this->db->rowCount();
    }
}