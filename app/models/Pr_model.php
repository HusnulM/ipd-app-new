<?php

class Pr_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getPrType(){
        $this->db->query("SELECT * FROM t_prtype");
        return $this->db->resultSet();
    }

    public function getPrTypeByType($prtype){
        $this->db->query("SELECT * FROM t_prtype WHERE prtype = '$prtype'");
        return $this->db->single();
    }

    public function getApprovalLevel($user, $creator){
        $this->db->query("SELECT level from t_approval where object ='PR' and approval = '$user' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function countOpenPR(){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT COUNT(DISTINCT(a.prnum)) as 'total' FROM t_pr02 as a inner join t_pr01 as b on a.prnum = b.prnum WHERE a.final_approve = 'N' and (a.createdby in(SELECT creator from t_approval where object ='PR' AND approval = '$user' and doctype = b.prtype) OR a.createdby = '$user')");
        return $this->db->single();
    }

    public function getOpenPR(){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT DISTINCT a.prnum, a.prtype, d.description as prtypename, a.prdate, a.note, a.requestby, a.deptid, c.department, a.currency From t_pr01 as a INNER JOIN t_pr02 as b on a.prnum = b.prnum INNER JOIN t_department as c on a.deptid = c.id left join t_prtype as d on a.prtype = d.prtype WHERE b.approvestat = 1 and a.createdby = '$user' ORDER BY a.prnum");
        return $this->db->resultSet();
    }

    public function getCurrency(){
        $this->db->query("SELECT * FROM t_currency");
        return $this->db->resultSet();
    }

    public function getCurrencyByCode($curr){
        $this->db->query("SELECT * FROM t_currency WHERE currency='$curr'");
        return $this->db->single();
    }

    public function getApprovedPR(){
        $user = $_SESSION['usr']['user'];
        $dept = $_SESSION['usr']['department'];
        $this->db->query("SELECT * FROM v_pr005");
		return $this->db->resultSet();
    }

    public function getNextNumber($object){
		$this->db->query("CALL sp_NextNriv('$object')");
		return $this->db->single();
    }    

    public function getPRheader($prnum){
		$this->db->query("SELECT a.*, b.department, fGetUserName(a.createdby) as 'crtby', fGetApproveDatePR(a.prnum) as 'appdate' From t_pr01 as a left join t_department as b on a.deptid = b.id Where a.prnum = '$prnum'");
		return $this->db->single();
    }

    public function getPRitem($prnum){
		$this->db->query("SELECT a.*, fGetUserName(a.approveby) as appby, b.image From t_pr02 as a left join t_material as b on a.material = b.material Where a.prnum = '$prnum' and a.approvestat <>'R'");
		return $this->db->resultSet();
    }

    public function getPR01($prnum){
		$this->db->query("SELECT * From t_pr01 Where prnum = '$prnum'");
		return $this->db->single();
    }

    public function checkStockwhs($material, $whs, $inputqty){
        // $myArray = explode(',', $material);
        // $myWhs   = explode(',', $whs);
        // $array   = implode("','",$myArray);
        // $inpwhs  = implode("','",$myWhs);

        $myArray = explode(',', $material);
        $myWhs   = explode(',', $whs);
        $array   = implode("','",$myArray);
        $inpwhs  = implode("','",$myWhs);

        $_menge = "";
        $_menge = str_replace(".", "",  $inputqty);
        $_menge = str_replace(",", ".", $_menge);
        
        $this->db->query("SELECT *, '$_menge' as 'inputqty' FROM t_inventory_stock WHERE material = '$material' AND warehouseid = '$whs'");
        return $this->db->single();
    }

    public function checkinventorystock($data){
        $matnr = $data['itm_material'];
        $menge = $data['itm_qty'];
        $lgort = $data['warehouse'];
        $meins = $data['itm_unit'];
        $ind   = "";
        $errmsg = array();
        for($i = 0; $i < count($matnr); $i++){
            $_menge = "";
            $_menge = str_replace(",", "",  $menge[$i]);
            $stock   = $this->checkStockwhs($matnr[$i], $lgort, $_menge);

            if($stock['quantity']*1 < ( $stock['inputqty']*1)){
                array_push($errmsg,(object)[
                    "message" => "Deficit Quantity for Material <b>". $stock['material'] . "</b> in Warehouse <b>". $stock['warehouseid'] . "</b>. Available Stock Is ". $stock['quantity']
                ]);

                $ind = "X";
            }
        }     

        return $errmsg;
    }

    public function updatepr($data, $prnum){
        $date = $this->getPR01($prnum);
        $this->delete($prnum);
        return $this->savepr($data, $prnum, $date['createdon']);
    }

    public function savepr($data, $prnum, $createdon = null){
        $no = 0;
        $matnr = $data['itm_material'];
        $maktx = $data['itm_matdesc'];
        $menge = $data['itm_qty'];
        $meins = $data['itm_unit'];
        $price = $data['itm_price'];
        $txz01 = $data['itm_remark'];

        $query1 = "INSERT INTO t_pr01(prnum,prtype,note,prdate,approvestat,deptid,currency,warehouse,requestby,createdon,createdby)
                   VALUES(:prnum,:prtype,:note,:prdate,:approvestat,:deptid,:currency,:warehouse,:requestby,:createdon,:createdby)
                   ON DUPLICATE KEY UPDATE prtype=:prtype, note=:note, prdate=:prdate,approvestat=:approvestat,deptid=:deptid,currency=:currency,warehouse=:warehouse,requestby=:requestby,createdon=:createdon,createdby=:createdby";
        
        if($createdon == null){
            $createdon = date('Y-m-d');
        }

        $this->db->query($query1);
		$this->db->bind('prnum',      $prnum);
        $this->db->bind('prtype',     $data['prtype']);
        $this->db->bind('note',       $data['note']);
        $this->db->bind('prdate',     $data['reqdate']);
        $this->db->bind('approvestat','1');
        $this->db->bind('deptid',     $data['department']);
        $this->db->bind('currency',   $data['currency']);
        $this->db->bind('warehouse',  $data['warehouse']);
        $this->db->bind('requestby',  $data['requestor']);
		$this->db->bind('createdon',  date('Y-m-d H:m:s'));
        $this->db->bind('createdby',  $_SESSION['usr']['user']);
        $this->db->execute();
        $rows = 0;

        $query2 = "INSERT INTO t_pr02(prnum,pritem,material,matdesc,quantity,unit,price,currency,warehouse,approvestat,remark,deptid,final_approve,createdon,createdby)
        VALUES(:prnum,:pritem,:material,:matdesc,:quantity,:unit,:price,:currency,:warehouse,:approvestat,:remark,:deptid,:final_approve,:createdon,:createdby)
        ON DUPLICATE KEY UPDATE material=:material, matdesc=:matdesc, quantity=:quantity, unit=:unit, price=:price,currency=:currency, warehouse=:warehouse,approvestat=:approvestat, remark=:remark, deptid=:deptid, final_approve=:final_approve";
        $this->db->query($query2);
        for($i = 0; $i < count($matnr); $i++){
            $rows = $rows + 1;
            $this->db->bind('prnum',        $prnum);
			$this->db->bind('pritem',       $rows);
			$this->db->bind('material',     $matnr[$i]);
			$this->db->bind('matdesc',      $maktx[$i]);
            
            $_menge = "";
            $_menge = str_replace(",", "",  $menge[$i]);
            // $_menge = str_replace(",", ".", $_menge);
            $this->db->bind('quantity',     $_menge);
            $this->db->bind('unit',         $meins[$i]);

            $_price = "";
            $_price = str_replace(",", "",  $price[$i]);
            $this->db->bind('price',        $_price);
            $this->db->bind('currency',     $data['currency']);
            $this->db->bind('warehouse',    $data['warehouse']);
            $this->db->bind('approvestat',  '1');
            $this->db->bind('remark',       $txz01[$i]);
            $this->db->bind('deptid',       $data['department']);
            $this->db->bind('final_approve',  'N');
            $this->db->bind('createdon',    $createdon);
            $this->db->bind('createdby',    $_SESSION['usr']['user']);
            $this->db->execute();
        }

        return $this->db->rowCount();
    }

    public function uploadfile($refdoc, $item, $temp, $location, $filename, $fileType){
        $date       = date("Y-m-d");
        $query1 = "INSERT INTO t_files(object,refdoc,item,filename,filetype,filepath,createdby,createdon)
                   VALUES(:object,:refdoc,:item,:filename,:filetype,:filepath,:createdby,:createdon)
                   ON DUPLICATE KEY UPDATE filename=:filename,filetype=:filetype,filepath=:filepath,createdby=:createdby,createdon=:createdon";
        
        $this->db->query($query1);
        $this->db->bind('object',     'PR');
        $this->db->bind('refdoc',     $refdoc);
        $this->db->bind('item',       $item);
        $this->db->bind('filename',   $filename);
        $this->db->bind('filetype',   $fileType);
        $this->db->bind('filepath',   $location);
        $this->db->bind('createdby',  $_SESSION['usr']['user']);
        $this->db->bind('createdon',  $date);
        $this->db->execute();
        
        return $this->db->rowCount();
    }

    public function getFirstApproval($creator){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='PR' and creator = '$creator' order by level asc limit 1");
        return $this->db->single();
    }

    public function sendprNotif($prnum){
        $prhead = $this->getPRheader($prnum);
        $prdata = $this->getPRitem($prnum);
        $approval = $this->getFirstApproval($prhead['createdby']);

        $toemail  = $approval['email'];      
        $email    = '';
        $password = '';
        
        $to_id = $toemail;

        $subject = 'Purchase Requisition '. $prnum ;
        $mail = new PHPMailer;
        $mail->FromName = "Purchase Request System";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = $password;
        $mail->addAddress($to_id);
        $mail->Subject = $subject;
        $mail->IsHTML(true);

        $mailBody = "
        <html>
        <head>
            <style>
                table {
                    border-collapse: collapse;
                    width: 100%;
                }
            
                th, td {
                    border: 1px solid #ddd;
                    text-align: left;
                    padding: 8px;
                    color:black
                }
            
                tr:nth-child(even){background-color: #f2f2f2}
            
                th {
                    background-color: #4CAF50;
                    color: white;
                }
            </style>
        </head>
        <body>
            <p>Dear Mr/Ms,</p>
            New Purchase Request Created, Please Review Purchase Reqeuest For Approve <br>
            
            <table>
                <thead>
                    <th>Purchase Request</th>
                    <th>Item</th>
                    <th>Part Code</th>
                    <th>Part Name</th>
                    <th>Quantity</th>
                    <th>Price Unit</th>
                </thead>
            <tbody>
            ";
            
        foreach($prdata as $row){
            $quantity = 0;
            if (strpos($row['quantity'], '.000') !== false) {
                $quantity = number_format($row['quantity'], 0, ',', '.');
            }else{
                $quantity = number_format($row['quantity'], 3, ',', '.');
            }
            $mailBody .= "
            <tr> 
              <td>".$row['prnum']."</td>
              <td>".$row['pritem']."</td>
              <td>".$row['material']."</td>
              <td>".$row['matdesc']."</td>
              <td style='text-align:right;'>".$quantity. " ". $row['unit'] ." </td>
              <td style='text-align:right;'>".$row['price']."</td>
            </tr>";  
        }    
            
        $mailBody .= "</tbody></table><br><p>Thanks.</p>
        </body>
        </html>
        ";
        
        $headers = "From:" . $email ."\r\n";    
        $headers .= "Content-type: text/html". "\r\n";

        $mail->Body = $mailBody;
        if (!$mail->send()) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
            // return $error; 
        }
        else {
            // return "Email terkirim";
        }
    }

    public function delete($prnum){
        $this->db->query('DELETE FROM t_pr01 WHERE prnum=:prnum');
        $this->db->bind('prnum',$prnum);
        $this->db->execute();
  
        return $this->db->rowCount();
    }

    public function deletepritem($prnum, $pritem){
        $this->db->query('DELETE FROM t_pr02 WHERE prnum=:prnum AND pritem=:pritem');
        $this->db->bind('prnum',  $prnum);
        $this->db->bind('pritem', $pritem);
        $this->db->execute();
  
        return $this->db->rowCount();
    }

    public function approvepr($prnum){
        $query = "UPDATE t_pr01 set status=:status, appby=:appby WHERE prnum=:prnum";
        $this->db->query($query);
      
        $this->db->bind('prnum',  $prnum);
        $this->db->bind('status', '2');
        $this->db->bind('appby',  $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function rejectpr($prnum){
        $query = "UPDATE t_pr01 set status=:status WHERE prnum=:prnum";
        $this->db->query($query);
      
        $this->db->bind('prnum',  $prnum);
        $this->db->bind('status', '3');
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function close($prnum){
        $query = "UPDATE t_pr01 set status=:status WHERE prnum=:prnum";
        $this->db->query($query);
      
        $this->db->bind('prnum',  $prnum);
        $this->db->bind('status', '4');
        $this->db->execute();

        return $this->db->rowCount();
    }
}