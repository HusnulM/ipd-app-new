<?php

class Vendor_model{
    private $db;
	  private $table = 't_supplier';

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getListVendor()
    {
      $this->db->query('SELECT * FROM t_supplier');
		  return $this->db->resultSet();
    }

    public function getVendorByKode($vendor)
    {
      $this->db->query("SELECT * FROM t_supplier WHERE supplier_id ='$vendor'");
		  return $this->db->single();
    }

    public function getVendorByName($name)
    {
        $this->db->query("SELECT * FROM t_supplier WHERE namavendor like '%" . $name . "%'");
		return $this->db->resultSet();
    }

    public function getNextNumber($object){
		$this->db->query("call sp_NextNriv('$object')");
		return $this->db->single();
	}
    
    public function save($data){

            $currentDate = date('Y-m-d');
            $vendor = $this->getNextNumber('VENDOR');
            $query = "INSERT INTO t_supplier (supplier_id, supplier_name, telephone, email, address, createdby, createdon) 
                    VALUES(:supplier_id, :supplier_name, :telephone, :email, :address, :createdby, :createdon)";
            $this->db->query($query);
            
            $this->db->bind('supplier_id',    $vendor['nextnumb']);
            $this->db->bind('supplier_name',$data['namavendor']);
            $this->db->bind('telephone',    $data['alamat']);
            $this->db->bind('email',    $data['telp']);
            $this->db->bind('address',     $data['email']);
            $this->db->bind('createdby', $_SESSION['usr']['user']);
            $this->db->bind('createdon', $currentDate);
            $this->db->execute();

            // $this->db->commit();

            return $this->db->rowCount();     
    }

    public function  update($data){
		$query = "UPDATE t_supplier set supplier_name=:supplier_name, address=:address, telephone=:telephone, email=:email WHERE supplier_id=:supplier_id";
		$this->db->query($query);
		
        $this->db->bind('supplier_id',    $data['vendor']);
        $this->db->bind('supplier_name',$data['namavendor']);
        $this->db->bind('address',    $data['alamat']);
        $this->db->bind('telephone',    $data['telp']);
        $this->db->bind('email',     $data['email']);
		$this->db->execute();

		return $this->db->rowCount();
    }

    public function delete($vendor){
        $this->db->query('DELETE FROM t_supplier WHERE supplier_id=:supplier_id');
		$this->db->bind('supplier_id',$vendor);
		$this->db->execute();

		return $this->db->rowCount();
    }
}