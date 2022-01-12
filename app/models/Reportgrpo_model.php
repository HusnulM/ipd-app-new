<?php

class Reportgrpo_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getGRPoData($strdate, $enddate){
        $this->db->query("SELECT * FROM v_report_grpo WHERE movement_date BETWEEN '$strdate' AND '$enddate'");
		return $this->db->resultSet();
    }
}