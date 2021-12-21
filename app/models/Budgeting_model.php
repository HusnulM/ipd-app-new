<?php

class Budgeting_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getBudgetCurrentPeriod(){
        $period = date('Y');
        $this->db->query("SELECT a.*, b.department as deptname, a.amount+issuing_amount as allocated, (amount+issuing_amount)-issuing_amount as balance FROM t_budget_summary as a inner join t_department as b on a.department = b.id WHERE a.budget_period='$period'");
        return $this->db->resultSet();
    }

    public function getBudgetByDept(){
        $dept = $_SESSION['usr']['department'];
        $period = date('Y');
        $this->db->query("SELECT a.*, b.department as deptname FROM t_budget_summary as a inner join t_department as b on a.department = b.id WHERE a.budget_period='$period' AND a.department = '$dept'");
        return $this->db->single();
    }

    public function getBudgetIssuedPerDept($deptid, $year){
        $this->db->query("SELECT a.createdon,a.deptid,sum(a.amount) as amount, fGetDepatment(a.deptid) as 'deptname', b.prtype, c.description FROM t_budget_history as a inner join t_pr01 as b on a.refnum = b.prnum inner join t_prtype as c on b.prtype = c.prtype where a.deptid = '$deptid' AND YEAR(a.createdon) = '$year' and budget_type ='D' group by a.createdon,a.deptid,b.prtype,c.description");
        return $this->db->resultSet();
    }

    public function saveAllocation($data){
        $query = "INSERT INTO t_budget (deptid,budget_period,amount,currency,budget_status,createdon,createdby) 
        VALUES(:deptid,:budget_period,:amount,:currency,:budget_status,:createdon,:createdby)";
        $this->db->query($query);

        $_amount = "";
        $_amount = str_replace(",", "",  $data['amount']);
        // $_amount = str_replace(",", ".", $_amount);

        $this->db->bind('deptid',        $data['department']);
        $this->db->bind('budget_period', $data['period']);
        $this->db->bind('amount',        $_amount);
        $this->db->bind('currency',      'PHP');
        $this->db->bind('budget_status', '1');
        $this->db->bind('createdon',     date('Y-m-d'));
        $this->db->bind('createdby',     $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }
}