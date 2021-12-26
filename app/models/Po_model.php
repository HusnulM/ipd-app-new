<?php

class Po_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getNextPONumber($object){
		$this->db->query("CALL sp_NextNriv('$object')");
		return $this->db->single();
    }

    public function checkGrStatus($ponum){
        
    }
    
    public function getPOHeader($ponum){
        $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.ponum = '$ponum'");
        return $this->db->single();
    }

    public function getPODetail($ponum){
        $this->db->query("SELECT * FROM t_po02 WHERE ponum = '$ponum'");
        return $this->db->resultSet();
    }

    public function getOpenPOitem($ponum){
        $this->db->query("SELECT *, ponum as 'refnum', poitem as 'refitem', '' as 'fromwhs', '-' as 'towhs' FROM t_po02 WHERE ponum = '$ponum' AND grstatus IS NULL");
        return $this->db->resultSet();
    }

    public function getOrderHeaderPrint($ponum){
        $this->db->query("SELECT a.*, b.namavendor, b.alamat, fGetApproveDatePO(a.ponum) as 'appdate' FROM t_po01 as a inner join t_vendor as b on a.vendor = b.vendor WHERE ponum = '$ponum'");
        return $this->db->single();
    }

    public function getPOitemPrint($ponum){
        $this->db->query("SELECT a.*, b.partnumber FROM t_po02 as a left join t_material as b on a.material = b.material WHERE a.ponum = '$ponum'");
        return $this->db->resultSet();
    }
    
    public function listopenpo(){
        $user = $_SESSION['usr']['user'];
        $dept = $_SESSION['usr']['department'];

        // if($_SESSION['usr']['userlevel'] === 'SysAdmin'){
        //     $this->db->query("SELECT * FROM v_po001 WHERE approvestat in('0','1')");
        // }else{
        //     $this->db->query("SELECT * FROM v_po001 WHERE approvestat in('0','1') and createdby = '$user'");
        // }
        $this->db->query("SELECT distinct `a`.`ponum` AS `ponum`,`a`.`ext_ponum` AS `ext_ponum`,`a`.`potype` AS `potype`,`a`.`podat` AS `podat`,`a`.`vendor` AS `vendor`,`a`.`note` AS `note`,`a`.`currency` AS `currency`,`a`.`appby` AS `appby`,`a`.`completed` AS `completed`,`a`.`createdon` AS `createdon`,`a`.`createdby` AS `createdby`,`b`.`supplier_name` AS `supplier_name`,`fGetUserDepartment`(`a`.`createdby`) AS `department`,`a`.`warehouse` AS `warehouse` from ((`t_po01` `a` join `t_supplier` `b` on(`a`.`vendor` = `b`.`supplier_id`))) where `a`.`final_approve` = 'N' and a.createdby = '$user'");
        return $this->db->resultSet();
    }

    public function updatepo($data, $ponum){
        $podata = $this->getPOHeader($ponum);
        $this->delete($ponum);
        $this->createpo($data, $ponum, $podata['podat']);
    }

    public function generatenopo($ponum){
        $this->db->query("SELECT fGeneratePONUM('$ponum') as 'ponum'");
        return $this->db->single();
    }

    public function checkpoitemapproved($ponum){
        $this->db->query("SELECT COUNT(*) as 'rows' FROM t_po02 WHERE ponum='$ponum' AND approvestat <> '1'");
        return $this->db->single();
    }

    public function getApprovedPR($whs){
        $this->db->query("SELECT * FROM v_pr005 WHERE warehouse='$whs'");
		return $this->db->resultSet();
    }

    public function createpo($data, $ponum, $createdon = null){
        // try {
            $no = 0;
            $date      = date("Y-m-d h:m:s");
            if($createdon == null){
                $createdon = $date;
            }

            // $ponumber = $this->generatenopo($ponum);
            $ponumber = $ponum;

            $query1 = "INSERT INTO t_po01(ponum,ext_ponum,potype,podat,vendor,note,approvestat,currency,warehouse,createdon,createdby)
                       VALUES(:ponum,:ext_ponum,:potype,:podat,:vendor,:note,:approvestat,:currency,:warehouse,:createdon,:createdby)";
            
            $this->db->query($query1);
            $this->db->bind('ponum',       $ponum);
            $this->db->bind('ext_ponum',   $ponum);
            $this->db->bind('potype',      null);
            $this->db->bind('podat',       $data['podate']);
            $this->db->bind('vendor',      $data['vendor']);
            $this->db->bind('note',        $data['note']);
            $this->db->bind('approvestat', '1');
            $this->db->bind('currency',    'PHP');
            $this->db->bind('warehouse',   null);
            $this->db->bind('createdon',   $createdon);
            $this->db->bind('createdby',   $_SESSION['usr']['user']);
            $this->db->execute();
    
            $material = $data['itm_material'];
            $matdesc  = $data['itm_matdesc'];
            $quantity = $data['itm_qty'];
            $unit     = $data['itm_unit'];
            $price    = $data['itm_price'];
            // $ppn      = $data['itm_ppn'];
            // $disc     = $data['itm_discount'];
            $prnum    = $data['itm_prnum'];
            $pritem   = $data['itm_pritem'];
    
            $query2 = "INSERT INTO t_po02(ponum,poitem,material,matdesc,quantity,unit,price,grqty,requestnum,request_item,approvestat,createdon,createdby)
                       VALUES(:ponum,:poitem,:material,:matdesc,:quantity,:unit,:price,:grqty,:requestnum,:request_item,:approvestat,:createdon,:createdby)";
            $this->db->query($query2);
            $item = 0;
            for($i = 0; $i < count($material); $i++){
                $item = $item + 1;
                $this->db->bind('ponum',       $ponum);
                $this->db->bind('poitem',      $item);
                $this->db->bind('material',    $material[$i]);
                $this->db->bind('matdesc',     $matdesc[$i]);

                $_menge = "";
                $_menge = str_replace(".", "",  $quantity[$i]);
                $_menge = str_replace(",", ".", $_menge);

                $this->db->bind('quantity',    $_menge);
                $this->db->bind('unit',        $unit[$i]);
                $_price = "";
                $_price = str_replace(".", "",  $price[$i]);
                $_price = str_replace(",", ".", $_price);
    
                $this->db->bind('price',       $_price);
                
                $this->db->bind('grqty',       '0');
    
                if(!isset($prnum[$i])){
                    $this->db->bind('requestnum',       null);
                    $this->db->bind('request_item',      null);
                }else{
                    $this->db->bind('requestnum',       $prnum[$i]);
                    $this->db->bind('request_item',     $pritem[$i]);
                }
                $this->db->bind('approvestat', '1');
                $this->db->bind('createdon',   $createdon);
                $this->db->bind('createdby',   $_SESSION['usr']['user']);
                $this->db->execute();
            }
            return $this->db->rowCount();
        // }catch (Exception $e) {
        //     $message = 'Caught exception: '.  $e->getMessage(). "\n";
        //     Flasher::setErrorMessage($message,'error');
        //     return 0;
        // }
    }

    public function kirimnotifpr($ponum){
        $toemail = 'husnulmub@gmail.com'; //email penerima
        $pesan   = 'Silahkan approve pr '. $ponum ; //isi email
        
        $email    = 'erpms100@gmail.com'; //email pengirim, silahkan diganti dengan email sendiri
        $password = 's_erp.v100'; //password gmail
        
        $to_id = $toemail;
        $message = $pesan;
        $subject = 'Purchase Order '. $ponum ;
        $mail = new PHPMailer;
        $mail->FromName = "ERP System";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = $password;
        $mail->addAddress($to_id);
        $mail->Subject = $subject;
        // $mail->msgHTML($message);
        $mail->IsHTML(true);
        $mail->Body = "
        <html>
        <head></head>
        <body>
            <p>Dear Bapak/Ibu,</p><br>
            <p>Mohon untuk melakukan approve/reject untuk PO ". $ponum .".</p>
            <br>https://erp.pilardwijaya.com/<br>
            <p>Terimakasih,</p>
            <p>Staff</p>
        </body>
        </html>
        ";
        if (!$mail->send()) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
            return $error; 
        }
        else {
            return "Email terkirim";
        }
    }

    public function deletepoitem($ponum,$poitem){
        $this->db->query('DELETE FROM t_po02 WHERE ponum=:ponum and poitem=:poitem');
        $this->db->bind('ponum', $ponum);
        $this->db->bind('poitem',$poitem);
        $this->db->execute();
  
        return $this->db->rowCount();
    }

    public function delete($ponum){
        $this->db->query('DELETE FROM t_po01 WHERE ponum=:ponum');
        $this->db->bind('ponum',$ponum);
        $this->db->execute();
  
        return $this->db->rowCount();
    }

    public function delete_error($ponum){
        $this->db->query('DELETE FROM t_po01 WHERE ponum=:ponum');
        $this->db->bind('ponum',$ponum);
        $this->db->execute();
    }

    public function approvepo($ponum){
        $query = "UPDATE t_po01 set approvestat=:approvestat WHERE ponum=:ponum";
        $this->db->query($query);
      
        $this->db->bind('ponum',  $ponum);
        $this->db->bind('approvestat', '2');
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function rejectpo($ponum){
        $query = "UPDATE t_po01 set approvestat=:approvestat WHERE ponum=:ponum";
        $this->db->query($query);
      
        $this->db->bind('ponum',  $ponum);
        $this->db->bind('approvestat', '3');
        $this->db->execute();

        return $this->db->rowCount();
    }
}