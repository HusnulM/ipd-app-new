<?php

class Grpo_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getApprovedPO(){
        $this->db->query("SELECT distinct a.*, b.supplier_name FROM t_po01 as a inner join t_supplier as b on a.vendor = b.supplier_id inner join t_po02 as c on a.ponum = c.ponum WHERE a.final_approve = 'Y' and a.is_rejected = 'N' and c.pocomplete = 'N'");
        return $this->db->resultSet();
    }

    public function getPOitemtoGR($ponum){
        $this->db->query("SELECT * FROM t_po02 WHERE ponum = '$ponum' and pocomplete = 'N'");
        return $this->db->resultSet();
    }

    public function post($data, $mblnr){
        try {
            $ind = "";
            $matnr = $data['itm_material'];
            $maktx = $data['itm_matdesc'];
            $menge = $data['itm_qty'];
            $meins = $data['itm_unit'];
            // $txz01 = $data['itm_remark'];
            $lgort = $data['itm_whs'];
            // $lgort2  = $data['itm_whs2'];
            $refnum  = $data['itm_refnum'];
            $refitem  = $data['itm_refitem'];
            // $batchnum = $data['itm_batch'];

            $user  = $_SESSION['usr']['user'];
            $year  = date('Y');

            $query1 = "INSERT INTO t_movement_01(movement_number,movement_year,movement_date,movement_type,movement_note,createdby,createdon)
                            VALUES(:movement_number,:movement_year,:movement_date,:movement_type,:movement_note,:createdby,:createdon)";
        
            $this->db->query($query1);
            $this->db->bind('movement_number', $mblnr);
            $this->db->bind('movement_year',   date('Y'));
            $this->db->bind('movement_date',   $data['mvdate']);
            $this->db->bind('movement_type',   $data['immvt']);
            $this->db->bind('movement_note',   $data['note']);
            $this->db->bind('createdby',       $_SESSION['usr']['user']);
            $this->db->bind('createdon',       date('Y-m-d'));
            $this->db->execute();
            $rows = 0;

            if($data['immvt'] === "101"){
            
                $query2 = "INSERT INTO t_movement_02(movement_number,movement_year,movement_item,movement_type,batchnumber,material,matdesc,quantity,unit,ponum,poitem,warehouseid,warehouseto,createdon,createdby)
                VALUES(:movement_number,:movement_year,:movement_item,:movement_type,:batchnumber,:material,:matdesc,:quantity,:unit,:ponum,:poitem,:warehouseid,:warehouseto,:createdon,:createdby)";
                $this->db->query($query2);
                for($i = 0; $i < count($matnr); $i++){
                    $rows = $rows + 1;
                    $this->db->bind('movement_number', $mblnr);
                    $this->db->bind('movement_year',   date('Y'));
                    $this->db->bind('movement_item',   $rows);
                    $this->db->bind('movement_type',   $data['immvt']);
                    $this->db->bind('batchnumber',     null);
                        
                    $this->db->bind('material', $matnr[$i]);
                    $this->db->bind('matdesc',  $maktx[$i]);
                        
                    $_menge = "";
                    $_menge = str_replace(",", "",  $menge[$i]);
                    
                    $this->db->bind('quantity', $_menge);
                    $this->db->bind('unit',     $meins[$i]);
                    $this->db->bind('ponum',     $refnum[$i]);
                    $this->db->bind('poitem',    $refitem[$i]);
                    
                    $this->db->bind('warehouseid',   $lgort[$i]);
                    if($data['immvt'] === "101"){
                        $this->db->bind('warehouseto', null);
                    }elseif($data['immvt'] === "201" || $data['immvt'] === "211"){
                        $this->db->bind('warehouseto', $lgort2[$i]);
                    }elseif($data['immvt'] === "261"){
                        $this->db->bind('warehouseto', null);
                    }
                        
                    $this->db->bind('createdon',   date('Y-m-d'));
                    $this->db->bind('createdby',   $_SESSION['usr']['user']);
                    $this->db->execute();
                }
            }

            $return = array(
                "msgtype" => "1",
                "message" => "Post Success",
                "data"    => null
            );

            return 1;         
    
        } catch (Exception $e) {
            $message = 'Caught exception: '.  $e->getMessage(). "\n";
            Flasher::setErrorMessage($message,'error');
            $return = array(
                "msgtype" => "0",
                "message" => $message,
                "data"    => $message
            );
            return $return;
        }
      
    }

    public function delete($mblnr){
        $year = date('Y');
        $this->db->query("DELETE FROM t_movement_01 WHERE movement_number='$mblnr' and movement_year='$year'");
        $this->db->execute();
    }
}