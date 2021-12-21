<?php

class Inventory_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getMaterialLists(){
        
        $this->db->query("SELECT * FROM t_material");
		return $this->db->resultSet();
    }

    public function getNextNumber($object){
		$this->db->query("CALL sp_NextNriv('$object')");
		return $this->db->single();
	}
    
    public function getStockByMaterial($material){
        $this->db->query("SELECT quantity FROM t_inventory_stock WHERE material = '$material'");
		return $this->db->single();
    }

    public function getStock(){
        $this->db->query("SELECT a.material, b.matdesc, a.warehouseid, c.warehousename, b.supplier, b.brand, a.quantity, b.matunit, a.quantity*b.stdprice as inventory_value FROM t_inventory_stock as a inner join t_material as b on a.material = b.material inner join t_warehouse as c on a.warehouseid = c.warehouseid");
		return $this->db->resultSet();
    }

    public function getIssuingStock($strdate, $endate){
        $this->db->query("SELECT a.material, a.matdesc, a.warehouseid, f.warehousename, d.department, b.movement_date, a.quantity, a.unit, a.unit_price, a.quantity*a.unit_price as issuing_value FROM t_movement_02 as a inner join t_movement_01 as b on a.movement_number = b.movement_number and a.movement_year = b.movement_year left join t_pr01 as c on a.prnum = c.prnum left join t_department as d on c.deptid = d.id
        inner join t_warehouse as f on a.warehouseid = f.warehouseid where a.movement_type = '601' AND b.movement_date BETWEEN '$strdate' AND '$endate'");
		return $this->db->resultSet();
    }

    public function saveadjustment($data, $nextnumber){

        $insertHeader = "INSERT INTO t_movement_01 (movement_number,movement_year,movement_date,movement_type,movement_note,createdon,createdby) 
                          VALUES(:movement_number,:movement_year,:movement_date,:movement_type,:movement_note,:createdon,:createdby)";
        $this->db->query($insertHeader);
        $this->db->bind('movement_number', $nextnumber);
        $this->db->bind('movement_year',   date('Y'));
        $this->db->bind('movement_date',   $data['adjdate']);
        $this->db->bind('movement_type',   '561');
        $this->db->bind('movement_note',   $data['note']);
        $this->db->bind('createdon',       date('Y-m-d'));
        $this->db->bind('createdby',       $_SESSION['usr']['user']);
        $this->db->execute();

        $matnr = $data['itm_material'];
        $maktx = $data['itm_matdesc'];
        $menge = $data['itm_qty'];
        $meins = $data['itm_unit'];
        $rowItem = 0;

        $queryInsert = "INSERT INTO t_movement_02 (movement_number,movement_year,movement_item,movement_type,warehouseid,material,matdesc,quantity,unit,unit_price,createdon,createdby) 
                          VALUES(:movement_number,:movement_year,:movement_item,:movement_type,:warehouseid,:material,:matdesc,:quantity,:unit,:unit_price,:createdon,:createdby)";

        $this->db->query($queryInsert);
        for($i = 0; $i < sizeof($matnr); $i++){
            $rowItem += 1;
            $this->db->bind('movement_number',$nextnumber);
            $this->db->bind('movement_year',  date('Y'));
            $this->db->bind('movement_item',  $rowItem);
            $this->db->bind('movement_type',  '561');
            $this->db->bind('warehouseid',    $data['warehouse']);
            $this->db->bind('material',       $matnr[$i]);
            $this->db->bind('matdesc',        $maktx[$i]);
            $this->db->bind('quantity',       $menge[$i]);
            $this->db->bind('unit',           $meins[$i]);
            $this->db->bind('unit_price',     0);
            $this->db->bind('createdon',      date('Y-m-d'));
            $this->db->bind('createdby',      $_SESSION['usr']['user']);
            $this->db->execute();
        }
        return $this->db->rowCount();
    }

    public function savedeliver($data, $nextnumber){
        $insertHeader = "INSERT INTO t_movement_01 (movement_number,movement_year,movement_date,movement_type,movement_note,createdon,createdby) 
                          VALUES(:movement_number,:movement_year,:movement_date,:movement_type,:movement_note,:createdon,:createdby)";
        $this->db->query($insertHeader);
        $this->db->bind('movement_number', $nextnumber);
        $this->db->bind('movement_year',   date('Y'));
        $this->db->bind('movement_date',   $data['deliverdate']);
        $this->db->bind('movement_type',   '101');
        $this->db->bind('movement_note',   $data['note']);
        $this->db->bind('createdon',       date('Y-m-d'));
        $this->db->bind('createdby',       $_SESSION['usr']['user']);
        $this->db->execute();

        $matnr = $data['itm_material'];
        $maktx = $data['itm_matdesc'];
        $menge = $data['itm_qty'];
        $meins = $data['itm_unit'];
        $rowItem = 0;

        $queryInsert = "INSERT INTO t_movement_02 (movement_number,movement_year,movement_item,movement_type,warehouseid,material,matdesc,quantity,unit,unit_price,createdon,createdby) 
                          VALUES(:movement_number,:movement_year,:movement_item,:movement_type,:warehouseid,:material,:matdesc,:quantity,:unit,:unit_price,:createdon,:createdby)";

        $this->db->query($queryInsert);
        for($i = 0; $i < sizeof($matnr); $i++){
            $rowItem += 1;
            $this->db->bind('movement_number',$nextnumber);
            $this->db->bind('movement_year',  date('Y'));
            $this->db->bind('movement_item',  $rowItem);
            $this->db->bind('movement_type',  '101');
            $this->db->bind('warehouseid',    $data['warehouse']);
            $this->db->bind('material',       $matnr[$i]);
            $this->db->bind('matdesc',        $maktx[$i]);
            $this->db->bind('quantity',       $menge[$i]);
            $this->db->bind('unit',           $meins[$i]);
            $this->db->bind('unit_price',     0);
            $this->db->bind('createdon',      date('Y-m-d'));
            $this->db->bind('createdby',      $_SESSION['usr']['user']);
            $this->db->execute();
        }
        return $this->db->rowCount();
    }


    public function deletedocs($docnum){
        $year = date('Y');
        $this->db->query("DELETE FROM t_movement_01 WHERE movement_number = '$docnum' and movement_year = '$year'");
        $this->db->execute();
    }
}