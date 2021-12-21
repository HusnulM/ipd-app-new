<?php

class Approvepr_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getPRheader($prnum){
		$this->db->query("SELECT a.*, fGetUserName(a.createdby) as 'crtby' From t_pr01 as a Where a.prnum = '$prnum'");
		return $this->db->single();
    }

    public function getOpenPR(){
        $user = $_SESSION['usr']['user'];        
        $this->db->query("SELECT distinct a.prnum, b.prtype, c.description as prtypename, b.prdate, b.note, b.requestby FROM v_pr004 as a inner join t_pr01 as b on a.prnum = b.prnum left join t_prtype as c on b.prtype = c.prtype WHERE a.createdby in(SELECT creator from t_approval where object ='PR' and approval = '$user' and doctype = b.prtype) and a.approvestat in(SELECT level from t_approval where object ='PR' and approval = '$user' and doctype = b.prtype and creator = a.createdby) and final_approve NOT IN('X')");
        return $this->db->resultSet();
    }

    public function getApprovalLevel($user, $creator,$prtype){
        $this->db->query("SELECT level from t_approval where object ='PR' and approval = '$user' and doctype = '$prtype' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function getMaxApprovalLevel($creator, $approval, $prtype){
        $this->db->query("SELECT level from t_approval where object ='PR' and doctype = '$prtype' and creator = '$creator' order by level desc limit 1");
        return $this->db->single();
    }

    public function getOpenPRByNum($prnum){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT a.*, b.image FROM v_pr004 as a left join t_material as b on a.material = b.material WHERE a.prnum = '$prnum' and a.approvestat in(SELECT level from t_approval where object ='PR' and approval = '$user') Order BY a.prnum, a.pritem");
        return $this->db->resultSet();
    }

    public function getNextApproval($creator, $level, $prtype){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='PR' and creator = '$creator' and a.level = '$level' and doctype = '$prtype' order by level asc limit 1");
        return $this->db->single();
    }

    public function approvepr($prnum){
        $query = "UPDATE t_pr01 set approvestat=:approvestat, appby=:appby WHERE prnum=:prnum";
        $this->db->query($query);
      
        $this->db->bind('prnum',       $prnum);
        $this->db->bind('approvestat', '2');
        $this->db->bind('appby',       $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function checkBudget($deptid, $period){
        $this->db->query("SELECT a.*, b.department as deptname FROM t_budget_summary as a inner join t_department as b on a.department = b.id WHERE a.department = '$deptid' AND a.budget_period='$period'");
        return $this->db->single();
    }

    public function getPRApprovedItem($prnum, $pritem){
        $this->db->query("SELECT * FROM t_pr02 WHERE prnum = '$prnum' AND pritem in('$pritem') Order BY prnum, pritem");
        return $this->db->resultSet();
    }

    public function checkBudgetExceeded($prnum, $data){
        $pritem = join("','",$data);
        $prdata = $this->getPRheader($prnum);

        $budget = $this->checkBudget($prdata['deptid'],date('Y'));

        $pritemdata = $this->getPRApprovedItem($prnum, $pritem);

        $totalPrice = 0;
        foreach($pritemdata as $row){
            $totalPrice = $totalPrice + ($row['quantity']*$row['price']);
        }

        if($totalPrice > $budget['amount']){
            $return = array(
                "budget"   => $budget['amount'],
                "issuing"  => $totalPrice,
                "exceeded" => $totalPrice - $budget['amount']
            );
            return $return;
        }else{
            return 1;
        }
    }

    public function approvepritem($prnum, $data){
        $user     = $_SESSION['usr']['user'];
        $prdata = $this->getPRheader($prnum);
        $level    = $this->getApprovalLevel($user,$prdata['createdby'],$prdata['prtype']);
        $pritem = join("','",$data);   

        $pritemdata = $this->getPRApprovedItem($prnum, $pritem);
        $maxlevel = $this->getMaxApprovalLevel($prdata['createdby'],$user,$prdata['prtype']);

        $finalapprove = null;

        $approvestat = $level['level']+1;        

        $date = date('Y-m-d');
        if($level['level'] === $maxlevel['level']){
            $finalapprove = 'X';
            $query  = "UPDATE t_pr02 set approvestat='$approvestat', approveby='$user', final_approve='$finalapprove', approvedate='$date' WHERE prnum='$prnum' and pritem in('$pritem')";
        }else{
            $query  = "UPDATE t_pr02 set approvestat='$approvestat', approveby='$user', approvedate='$date' WHERE prnum='$prnum' and pritem in('$pritem')";
        }
        
        
        $this->db->query($query);
        $this->db->execute();

        if($finalapprove === "X"){
            $ivnum = $this->getNextNumber('INVENTORY');
            $insertHeader = "INSERT INTO t_movement_01 (movement_number,movement_year,movement_date,movement_type,movement_note,createdon,createdby) 
                          VALUES(:movement_number,:movement_year,:movement_date,:movement_type,:movement_note,:createdon,:createdby)";
            $this->db->query($insertHeader);
            $this->db->bind('movement_number', $ivnum['nextnumb']);
            $this->db->bind('movement_year',   date('Y'));
            $this->db->bind('movement_date',   date('Y-m-d'));
            $this->db->bind('movement_type',   '601');
            $this->db->bind('movement_note',   'Issuing Parts');
            $this->db->bind('createdon',       date('Y-m-d'));
            $this->db->bind('createdby',       $_SESSION['usr']['user']);
            $this->db->execute();

            // $matnr = $data['itm_material'];
            // $maktx = $data['itm_matdesc'];
            // $menge = $data['itm_qty'];
            // $meins = $data['itm_unit'];
            $rowItem = 0;

            $queryInsert = "INSERT INTO t_movement_02 (movement_number,movement_year,movement_item,movement_type,warehouseid,material,matdesc,quantity,unit,unit_price,prnum,pritem,createdon,createdby) 
                            VALUES(:movement_number,:movement_year,:movement_item,:movement_type,:warehouseid,:material,:matdesc,:quantity,:unit,:unit_price,:prnum,:pritem,:createdon,:createdby)";

            $this->db->query($queryInsert);
            foreach($pritemdata as $row){
                $rowItem += 1;
                $this->db->bind('movement_number',$ivnum['nextnumb']);
                $this->db->bind('movement_year',  date('Y'));
                $this->db->bind('movement_item',  $rowItem);
                $this->db->bind('movement_type',  '601');
                $this->db->bind('warehouseid',    $row['warehouse']);
                $this->db->bind('material',       $row['material']);
                $this->db->bind('matdesc',        $row['matdesc']);
                $this->db->bind('quantity',       $row['quantity']);
                $this->db->bind('unit',           $row['unit']);
                $this->db->bind('unit_price',     $row['price']);
                $this->db->bind('prnum',          $row['prnum']);
                $this->db->bind('pritem',         $row['pritem']);
                $this->db->bind('createdon',      date('Y-m-d'));
                $this->db->bind('createdby',      $_SESSION['usr']['user']);
                $this->db->execute();
            }
            // Insert Inventory Movement / History
            // $queryInsert = "INSERT INTO t_inventory (ivnum,ivyear,ivitem,material,matdesc,quantity,matunit,unit_price,movement_date,movement_type,refrence,refnum,refitem,deptid,createdon,createdby) 
            //               VALUES(:ivnum,:ivyear,:ivitem,:material,:matdesc,:quantity,:matunit,:unit_price,:movement_date,:movement_type,:refrence,:refnum,:refitem,:deptid,:createdon,:createdby)";
    
            // $ivnum = $this->getNextNumber('INVENTORY');
            // $this->db->query($queryInsert);
            // $rowItem = 0;
            // foreach($pritemdata as $row){
            //     $rowItem += 1;
            //     $this->db->bind('ivnum',         $ivnum['nextnumb']);
            //     $this->db->bind('ivyear',        date('Y'));
            //     $this->db->bind('ivitem',        $rowItem);
            //     $this->db->bind('material',      $row['material']);
            //     $this->db->bind('matdesc',       $row['matdesc']);
            //     $this->db->bind('quantity',      $row['quantity']);
            //     $this->db->bind('matunit',       $row['unit']);
            //     $this->db->bind('unit_price',    $row['price']);
            //     $this->db->bind('movement_date', date('Y-m-d'));
            //     $this->db->bind('movement_type', '101');
            //     $this->db->bind('refrence',      'PR');
            //     $this->db->bind('refnum',        $row['prnum']);
            //     $this->db->bind('refitem',       $row['pritem']);
            //     $this->db->bind('deptid',        $row['deptid']);
            //     $this->db->bind('createdon',     date('Y-m-d'));
            //     $this->db->bind('createdby',     $_SESSION['usr']['user']);
            //     $this->db->execute();
            // }
            // return $this->db->rowCount();
        }else{
            $nextLevel    = $level['level']+1;
            $this->sendprNotif($prnum, $nextLevel);
        }

        return $this->db->rowCount();
    }

    public function getPROpenItem($prnum, $level){
		$this->db->query("SELECT *, fGetUserName(approveby) as appby From t_pr02 Where prnum = '$prnum' and approvestat='$level'");
		return $this->db->resultSet();
    }

    public function sendprNotif($prnum, $level){
        $prhead = $this->getPRheader($prnum);
        $prdata = $this->getPROpenItem($prnum, $level);
        $approval = $this->getNextApproval($prhead['createdby'], $level, $prhead['prtype']);

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
                    <tr>
                    <th>Purchase Request</th>
                    <th>Item</th>
                    <th>Part Code</th>
                    <th>Part Name</th>
                    <th>Quantity</th>
                    <th>Price Unit</th>
                    </tr>
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

    public function getNextNumber($object){
		$this->db->query("CALL sp_NextNriv('$object')");
		return $this->db->single();
	} 

    public function rejectpritem($prnum, $data){
        $date = date('Y-m-d');
        $pritem = join("','",$data);   
        $user   = $_SESSION['usr']['user'];
        $query  = "UPDATE t_pr02 set approvestat='R', approveby='$user', final_approve='X', approvedate='$date' WHERE prnum='$prnum' and pritem in('$pritem')";
        $this->db->query($query);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function rejectpr($prnum){
        $query = "UPDATE t_pr01 set approvestat=:approvestat, appby=:appby WHERE prnum=:prnum";
        $this->db->query($query);
      
        $this->db->bind('prnum',       $prnum);
        $this->db->bind('approvestat', 'R');
        $this->db->bind('appby',        $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }
}