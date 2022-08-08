<?php

class Materialprice_model{

    private $db;

    public function __construct()
    {
		$this->db = new Database;
    }
    
    public function getMaterialPriceHistory($year, $material)
    {   
        $this->db->query("CALL sp_GetMaterialPriceHistory('$year','$material')");
        return $this->db->resultSet();
        // if($material === 'ALL'){
        // }else{
        //     $this->db->query("SELECT a.*, b.supplier_name, c.matdesc FROM v_material_price_history as a inner join t_supplier as b on a.vendor = b.supplier_id inner join t_material as c on a.material = c.material WHERE a.month = '$month' AND a.year = '$year' AND a.material = '$material' order by a.material");
        //     return $this->db->resultSet();
        // }
    }
}