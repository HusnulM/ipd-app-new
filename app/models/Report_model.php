<?php

class Report_model{
    private $db;

    public function __construct()
    {
		$this->db = new Database;
    }

    public function rtransaction($strdate, $enddate){
        $this->db->query("SELECT * FROM v_report_transaction where createdon BETWEEN '$strdate' AND '$enddate'");
        return $this->db->resultSet();
    }

    public function getPR($strdate, $enddate, $deptid, $status){
      // echo json_encode($deptid);
      if($deptid === "0"){
        $this->db->query("SELECT a.*, b.department, c.description as prtypename FROM t_pr01 as a inner join t_department as b on a.deptid = b.id left join t_prtype as c on a.prtype = c.prtype where a.createdon BETWEEN '$strdate' AND '$enddate'");
      }else{
        $this->db->query("SELECT a.*, b.department, c.description as prtypename FROM t_pr01 as a inner join t_department as b on a.deptid = b.id left join t_prtype as c on a.prtype = c.prtype where a.createdon BETWEEN '$strdate' AND '$enddate' AND a.deptid = '$deptid'");
      }
      return $this->db->resultSet();
    }

    public function getBudgetHistory($deptid, $month, $year){
      if($deptid === "ALL"){
        if($month === "ALL"){
          $this->db->query("SELECT a.createdon,a.deptid,a.refnum,sum(a.amount) as amount, fGetDepatment(a.deptid) as 'deptname', b.prtype, c.description FROM t_budget_history as a inner join t_pr01 as b on a.refnum = b.prnum inner join t_prtype as c on b.prtype = c.prtype where YEAR(a.createdon) = '$year' and budget_type ='D' group by a.createdon,a.deptid,a.refnum,b.prtype,c.description order by a.deptid, b.prtype, a.createdon");
          return $this->db->resultSet();
        }else{
          $this->db->query("SELECT a.createdon,a.deptid,a.refnum,sum(a.amount) as amount, fGetDepatment(a.deptid) as 'deptname', b.prtype, c.description FROM t_budget_history as a inner join t_pr01 as b on a.refnum = b.prnum inner join t_prtype as c on b.prtype = c.prtype where MONTH(a.createdon) = '$month' AND YEAR(a.createdon) = '$year' and budget_type ='D' group by a.createdon,a.deptid,a.refnum,b.prtype,c.description order by a.deptid, b.prtype, a.createdon");
            return $this->db->resultSet();
        }
      }else{
        if($month === "ALL"){
          $this->db->query("SELECT a.createdon,a.deptid,a.refnum,sum(a.amount) as amount, fGetDepatment(a.deptid) as 'deptname', b.prtype, c.description FROM t_budget_history as a inner join t_pr01 as b on a.refnum = b.prnum inner join t_prtype as c on b.prtype = c.prtype where a.deptid = '$deptid' AND YEAR(a.createdon) = '$year' and budget_type ='D' group by a.createdon,a.deptid,a.refnum,b.prtype,c.description order by a.deptid, b.prtype, a.createdon");
          return $this->db->resultSet();
        }else{
          $this->db->query("SELECT a.createdon,a.deptid,a.refnum,sum(a.amount) as amount, fGetDepatment(a.deptid) as 'deptname', b.prtype, c.description FROM t_budget_history as a inner join t_pr01 as b on a.refnum = b.prnum inner join t_prtype as c on b.prtype = c.prtype where a.deptid = '$deptid' AND MONTH(a.createdon) = '$month' AND YEAR(a.createdon) = '$year' and budget_type ='D' group by a.createdon,a.deptid,a.refnum,b.prtype,c.description order by a.deptid, b.prtype, a.createdon");
          return $this->db->resultSet();
        }
      }
    }
}