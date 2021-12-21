<?php

class Material_model{

  private $db;
	  private $table = 't_barang';

    public function __construct()
    {
		  $this->db = new Database;
    }
    
    public function getListBarang()
    {
      $this->db->query("SELECT * FROM t_material");
		  return $this->db->resultSet();
    }

    public function getMaterialWithStock($whs)
    {
      $this->db->query("SELECT a.*, b.quantity FROM t_material as a left join t_inventory_stock as b on a.material = b.material where b.warehouseid = '$whs'");
		  return $this->db->resultSet();
    }

    public function checkauthdisplayprice(){
      $user = $_SESSION['usr']['user'];
      $this->db->query("SELECT count(*) as 'rows' FROM t_user_object_auth WHERE ob_auth = 'OB_MATPRICE' AND username = '$user'");
		  return $this->db->single();
    }

    public function getusdtoidr(){
      $this->db->query("SELECT fCurrencyConvertion('USD','IDR') as 'kurs'");
      return $this->db->single(); 
    }

    public function getListBarangWithStock(){
      $this->db->query("SELECT material, matdesc, partnumber, partname, matunit, FORMAT(fGetMaterialTotalStock(material), '.', '@') as 'stock' FROM t_material");
		  return $this->db->resultSet();
    }

    public function getListMatType(){
      $this->db->query("SELECT * FROM t_materialtype");
		  return $this->db->resultSet();
    }

    public function geMatTypeById($mattype){
      $this->db->query("SELECT * FROM t_materialtype WHERE mattype = '$mattype'");
		  return $this->db->single();
    }

    public function getBarangByKode($kodebrg)
    {
      $this->db->query("SELECT * FROM t_material WHERE material='$kodebrg'");
		  return $this->db->single();
    }

    public function getBarangBaseUomByKode($kodebrg, $buom)
    {
      $this->db->query("SELECT * FROM t_material2 WHERE material='$kodebrg' and altuom <> '$buom'");
		  return $this->db->resultSet();
    }

    public function getNextNumber($object){
      $this->db->query("call sp_NextNriv('$object')");
      return $this->db->single();
    }

    public function getmaterialunit($matnr, $exclunit){
      $this->db->query("SELECT * FROM t_material2 where material = '$matnr' and altuom not in('$exclunit')");
      return $this->db->resultSet();
    }

    public function  save($data){
        $filename      = $_FILES['image']['name'];
        // $filename      = $filename;
        $filename      = $data['kodebrg']."-".$filename;
        $location      = "./images/material-images/". $filename;
        $temp          = $_FILES['image']['tmp_name'];
        $fileType      = pathinfo($location,PATHINFO_EXTENSION);
        $acak          = rand(000000,999999);	

        // echo json_encode($filename);

        $currentDate = date('Y-m-d h:m:s');

        $_price = "";
        $_price = str_replace(",", "",  $data['stdprice']);
        
        $query = "INSERT INTO t_material (material,brand,matdesc,supplier,matunit,stdprice,image,createdon,createdby) 
                      VALUES(:material,:brand,:matdesc,:supplier,:matunit,:stdprice,:image,:createdon,:createdby)";
        $this->db->query($query);
        
        $this->db->bind('material',  $data['kodebrg']);
        $this->db->bind('brand',     $data['brand']);
        $this->db->bind('matdesc',   $data['namabrg']);
        $this->db->bind('supplier',  $data['supplier']);
        $this->db->bind('matunit',   $data['satuan']);
        $this->db->bind('stdprice',  $_price);
        $this->db->bind('image',     $filename);
        $this->db->bind('createdon', $currentDate);
        $this->db->bind('createdby', $_SESSION['usr']['user']);
        $this->db->execute();

        if(isset($_FILES['image']['name'])){
          move_uploaded_file($temp, $location);
        }
        
        return $this->db->rowCount();
    }

    public function  saveupdate($data){
      
      $filename      = $_FILES['image']['name'];
      $filename      = $data['kodebrg']."-".$filename;
      $location      = "./images/material-images/". $filename;
      $temp          = $_FILES['image']['tmp_name'];
      $fileType      = pathinfo($location,PATHINFO_EXTENSION);
      $acak          = rand(000000,999999);	

      $currentDate = date('Y-m-d h:m:s');

      $_price = "";
      $_price = str_replace(",", "",  $data['stdprice']);
      
      $query = "INSERT INTO t_material (material,brand,matdesc,supplier,matunit,stdprice,image,createdon,createdby) 
                    VALUES(:material,:brand,:matdesc,:supplier,:matunit,:stdprice,:image,:createdon,:createdby)
                    ON DUPLICATE KEY UPDATE brand=:brand,matdesc=:matdesc,supplier=:supplier,matunit=:matunit,stdprice=:stdprice,image=:image";
      $this->db->query($query);
      
      $this->db->bind('material',  $data['kodebrg']);
      $this->db->bind('brand',     $data['brand']);
      $this->db->bind('matdesc',   $data['namabrg']);
      $this->db->bind('supplier',  $data['supplier']);
      $this->db->bind('matunit',   $data['satuan']);
      $this->db->bind('stdprice',  $_price);
      $this->db->bind('image',     $filename);
      $this->db->bind('createdon', $currentDate);
      $this->db->bind('createdby', $_SESSION['usr']['user']);
      $this->db->execute();

      if(isset($_FILES['image']['name'])){
        move_uploaded_file($temp, $location);

        if(isset($data['oldimage'])){
          unlink("./images/material-images/".$data['oldimage']);
        }
      }
      
      return $this->db->rowCount();
  }

    public function savealtuom($kodebrg, $data){

      $altuom = $data['altuom'];
      $currentDate = date('Y-m-d h:m:s');

      $query = "INSERT INTO t_material2 (material,altuom,convalt,baseuom,convbase,createdon,createdby) 
                      VALUES(:material,:altuom,:convalt,:baseuom,:convbase,:createdon,:createdby)
                      ON DUPLICATE KEY UPDATE convalt=:convalt,baseuom=:baseuom,convbase=:convbase";
      $this->db->query($query);
      
      for($i=0; $i<sizeof($altuom); $i++){
        $this->db->bind('material',  $kodebrg);
        $this->db->bind('altuom',    $altuom[$i]);
        $this->db->bind('convalt',   $data['altuomval'][$i]);
        $this->db->bind('baseuom',   $data['baseuom'][$i]);
        $this->db->bind('convbase',  $data['baseuomval'][$i]);
        $this->db->bind('createdon', $currentDate);
        $this->db->bind('createdby', $_SESSION['usr']['user']);
        $this->db->execute();
      }

      return $this->db->rowCount();
    }

    public function  update($data){
      $currentDate = date('Y-m-d h:m:s');
        $query = "INSERT INTO t_material (material,matdesc,mattype,partname,partnumber,color,size,matunit,minstock,orderunit,stdprice,stdpriceusd,active,createdon,createdby) 
                      VALUES(:material,:matdesc,:mattype,:partname,:partnumber,:color,:size,:matunit,:minstock,:orderunit,:stdprice,:stdpriceusd,:active,:createdon,:createdby)
              ON DUPLICATE KEY UPDATE matdesc=:matdesc,mattype=:mattype,partname=:partname,partnumber=:partnumber,color=:color,size=:size,matunit=:matunit,minstock=:minstock,orderunit=:orderunit,stdprice=:stdprice,stdpriceusd=:stdpriceusd";
        $this->db->query($query);

        if($data['inp_min_stock'] === ""){
          $data['inp_min_stock'] = 0;
        }
        
        $this->db->bind('material',  $data['kodebrg']);
        $this->db->bind('matdesc',   $data['namabrg']);
        $this->db->bind('mattype',   $data['mattype']);
        $this->db->bind('partname',  $data['partname']);
        $this->db->bind('partnumber',$data['partnumber']);
        $this->db->bind('color',     null);
        $this->db->bind('size',      null);
        $this->db->bind('matunit',   $data['satuan']);
        $this->db->bind('minstock',  0);
        $this->db->bind('orderunit', $data['satuan']);
        $this->db->bind('stdprice',  0);
        $this->db->bind('stdpriceusd',  '0');

        $this->db->bind('active',    '1');
        $this->db->bind('createdon', $currentDate);
        $this->db->bind('createdby', $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function delete($kodebrg){
      $this->db->query("DELETE FROM t_material WHERE material=:material");
      $this->db->bind('material',$kodebrg);
      $this->db->execute();

      return $this->db->rowCount();
    }

    public function updatekursusdidr($newvalue){
        $newcurs = "";
        $newcurs = str_replace(".", "",  $newvalue);
        $query = "UPDATE t_kurs set kurs2=:kurs2 WHERE currency1=:currency1 AND currency2=:currency2";
        $this->db->query($query);
      
        $this->db->bind('currency1',   'USD');
        $this->db->bind('currency2',   'IDR');
        $this->db->bind('kurs2',        $newcurs);
        $this->db->execute();

        return $this->db->rowCount();
    }
}