<?php

class Approvepo_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getAttachment($ponum){
        $this->db->query("SELECT * from t_po03 where ponum = '$ponum'");
        return $this->db->resultSet();
    }

    public function getPoTotalPrice($ponum){
        $this->db->query("SELECT cast(sum(totalprice) as decimal(15,2)) as 'price' from v_po02 where ponum = '$ponum'");
        return $this->db->single();
    }

    public function getApprovalLevel($user, $creator,$prtype){
        $this->db->query("SELECT level from t_approval where object ='PO' and approval = '$user' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function getMaxApprovalLevel($creator, $approval, $prtype){
        $this->db->query("SELECT level from t_approval where object ='PO' and creator = '$creator' order by level desc limit 1");
        return $this->db->single();
    }

    public function getNextApproval($creator, $level, $prtype){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='PO' and creator = '$creator' and a.level = '$level' order by level asc limit 1");
        return $this->db->single();
    }

    public function getOpenPO(){
        $user     = $_SESSION['usr']['user'];
		$this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.final_approve = 'N' and is_rejected = 'N' and a.createdby in(SELECT creator from t_approval where object ='PO' and approval = '$user') and a.approvestat in(SELECT level from t_approval where object ='PO' and approval = '$user' and creator = a.createdby)");
		return $this->db->resultSet();
    }

    public function getPOHeader($ponum){
        $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.ponum = '$ponum'");
		return $this->db->single();
    }

    public function approvepo($ponum){
        $user     = $_SESSION['usr']['user'];

        $podata   = $this->getPOHeader($ponum);

        $level    = $this->getApprovalLevel($user,$podata['createdby'],'');
        $maxlevel = $this->getMaxApprovalLevel($podata['createdby'],$user,'');

        $approvestat = 0;
        $approvestat = $level['level']+1;

        $query = "UPDATE t_po01 set approvestat=:approvestat,final_approve=:final_approve WHERE ponum=:ponum";
        $this->db->query($query);

        if($level['level'] === $maxlevel['level']){
            $this->db->bind('ponum',         $ponum);
            $this->db->bind('approvestat',   $approvestat);
            $this->db->bind('final_approve', 'Y');
            $this->db->execute();
        }else{
            $this->db->bind('ponum',         $ponum);
            $this->db->bind('approvestat',   $approvestat);
            $this->db->bind('final_approve', 'N');
            $this->db->execute();
        }

        return $this->db->rowCount();
    }
}