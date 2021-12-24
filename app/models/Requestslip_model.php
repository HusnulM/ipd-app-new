<?php

class Requestslip_model{

    private $db;

    public function __construct()
    {
		  $this->db = new Database;
    }

    public function getOpenRequest(){
      $user = $_SESSION['usr']['user'];
      $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.request_status = '1' and a.createdby = '$user'");
		  return $this->db->resultSet();
    }

    public function getRequestForPO(){
      $this->db->query("SELECT DISTINCT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id INNER JOIN t_request_slip02 as c on a.requestnum = c.requestnum WHERE a.final_approve = 'Y' and c.po_created = 'N'");
		  return $this->db->resultSet();
    }

    public function getRequestItemForPO($reqnum){
      $this->db->query("SELECT a.*, b.matdesc FROM t_request_slip02 as a left join t_material as b on a.material = b.material WHERE a.po_created = 'N' and a.requestnum='$reqnum'");
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

    public function getAttachment($reqnum){
      $this->db->query("SELECT * FROM t_request_slip03 WHERE requestnum='$reqnum'");
		  return $this->db->resultSet();
    }

    public function save($data, $reqnum){
      

      // Header Data
      $query = "INSERT INTO t_request_slip01 (requestnum,request_date,request_by,request_note,request_status,deptid,createdon,createdby) 
                      VALUES(:requestnum,:request_date,:request_by,:request_note,:request_status,:deptid,:createdon,:createdby)";
        $this->db->query($query);
        
        $this->db->bind('requestnum',     $reqnum);
        $this->db->bind('request_date',   $data['reqdate']);
        $this->db->bind('request_by',     $data['requestor']);
        $this->db->bind('request_note',   $data['reqnote']);
        $this->db->bind('request_status', '1');
        $this->db->bind('deptid',         $data['department']);
        // $this->db->bind('efile',          $filename);
        $this->db->bind('createdon',      date('Y-m-d'));
        $this->db->bind('createdby',      $_SESSION['usr']['user']);
        $this->db->execute();
      // Items Data
      $matnr = $data['itm_material'];
      $menge = $data['itm_qty'];
      // $lgort = $data['warehouse'];
      $meins = $data['itm_unit'];
      // $netpr = $data['itm_price'];
      // $meins = $data['itm_unit'];
      $rows = 0;
      $query2 = "INSERT INTO t_request_slip02(requestnum,request_item,material,quantity,unit,unit_price,createdon,createdby)
        VALUES(:requestnum,:request_item,:material,:quantity,:unit,:unit_price,:createdon,:createdby)";
        $this->db->query($query2);
        for($i = 0; $i < count($matnr); $i++){
          $rows = $rows + 1;
          $this->db->bind('requestnum',        $reqnum);
          $this->db->bind('request_item',      $rows);
          $this->db->bind('material',          $matnr[$i]);            
          $_menge = "";
          $_menge = str_replace(",", "",  $menge[$i]);
          $this->db->bind('quantity',     $_menge);
          $this->db->bind('unit',         $meins[$i]);

          // $_price = "";
          // $_price = str_replace(",", "",  $netpr[$i]);
          $this->db->bind('unit_price',   0);
          $this->db->bind('createdon',    date('Y-m-d'));
          $this->db->bind('createdby',    $_SESSION['usr']['user']);
          $this->db->execute();
        }
      
      $query3 = "INSERT INTO t_request_slip03(requestnum,efile)
        VALUES(:requestnum,:efile)";
      $this->db->query($query3);

      $filename      = $_FILES['attachment']['name'];
      for($i = 0; $i < sizeof($filename); $i++){
        
        $namafile      = $reqnum."-".$filename[$i];
        $location      = "./efile/request-slip/". $namafile;
        $temp          = $_FILES['attachment']['tmp_name'][$i];
        move_uploaded_file($temp, $location);

        $this->db->bind('requestnum',    $reqnum);
        $this->db->bind('efile',         $namafile);
        $this->db->execute();
      }
      // $fileType      = pathinfo($location,PATHINFO_EXTENSION);
      // $acak          = rand(000000,999999);	

      //   if(isset($_FILES['attachment']['name'])){
      //     move_uploaded_file($temp, $location);
      //   }

      return $this->db->rowCount();
    }

    public function saveprice($data){
      // Header Data
      $query = "UPDATE t_request_slip01 SET request_status=:request_status WHERE requestnum=:requestnum";
      $this->db->query($query);
        
      $this->db->bind('requestnum',     $data['requestnum']);
      $this->db->bind('request_status', '2');
      $this->db->execute();

      // Items Data
      $matnr = $data['itm_material'];
      $reqitem = $data['itm_no'];
      // $meins = $data['itm_unit'];
      $netpr = $data['itm_price'];

      $query2 = "UPDATE t_request_slip02 SET unit_price=:unit_price WHERE requestnum=:requestnum AND request_item=:request_item";
        $this->db->query($query2);
        for($i = 0; $i < count($matnr); $i++){
          $this->db->bind('requestnum',        $data['requestnum']);
          $this->db->bind('request_item',      $reqitem[$i]);

          $_price = "";
          $_price = str_replace(",", "",  $netpr[$i]);
          $this->db->bind('unit_price',   $_price);
          $this->db->execute();
        }

      return 1;
    }

    public function updatepostatus($reqnum, $reqitem){
      $query2 = "UPDATE t_request_slip02 SET po_created=:po_created WHERE requestnum=:requestnum AND request_item=:request_item";
      $this->db->bind('requestnum',        $reqnum);
      $this->db->bind('request_item',      $reqitem);
      $this->db->bind('po_created',        'N');
      $this->db->query($query2);
      $this->db->execute();
    }
}