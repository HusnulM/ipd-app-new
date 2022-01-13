<?php

class Approvepo_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getAttachment($ponum){
        $this->db->query("SELECT * from t_po03 where ponum = '$ponum'");
        return $this->db->resultSet();
    }

    public function getPoTotalPrice($ponum){
        $this->db->query("SELECT cast(sum(totalprice) as decimal(15,2)) as 'price' from v_po02 where ponum = '$ponum'");
        return $this->db->single();
    }

    public function getApprovalLevel($user, $creator,$prtype){
        $this->db->query("SELECT level from t_approval where object ='PO' and approval = '$user' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function getMaxApprovalLevel($creator, $approval, $prtype){
        $this->db->query("SELECT level from t_approval where object ='PO' and creator = '$creator' order by level desc limit 1");
        return $this->db->single();
    }

    public function getNextApproval($creator, $level, $prtype){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='PO' and creator = '$creator' and a.level = '$level' order by level asc limit 1");
        return $this->db->single();
    }

    public function getOpenPO(){
        $user     = $_SESSION['usr']['user'];
		$this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.final_approve = 'N' and is_rejected = 'N' and a.createdby in(SELECT creator from t_approval where object ='PO' and approval = '$user') and a.approvestat in(SELECT level from t_approval where object ='PO' and approval = '$user' and creator = a.createdby)");
		return $this->db->resultSet();
    }

    public function getPOHeader($ponum){
        $this->db->query("SELECT a.*, b.supplier_name, fGetUserName(a.createdby) as 'crtby' FROM t_po01 as a INNER JOIN t_supplier as b on a.vendor = b.supplier_id WHERE a.ponum = '$ponum'");
		return $this->db->single();
    }

    public function getPODetail($ponum){
        $this->db->query("SELECT * FROM t_po02 WHERE ponum = '$ponum'");
        return $this->db->resultSet();
    }

    public function approvepo($ponum){
        $user     = $_SESSION['usr']['user'];

        $podata   = $this->getPOHeader($ponum);

        $level    = $this->getApprovalLevel($user,$podata['createdby'],'');
        $maxlevel = $this->getMaxApprovalLevel($podata['createdby'],$user,'');

        $approvestat = 0;
        $approvestat = $level['level']+1;

        $query = "UPDATE t_po01 set approvestat=:approvestat,final_approve=:final_approve WHERE ponum=:ponum";
        $this->db->query($query);

        if($level['level'] === $maxlevel['level']){
            $this->db->bind('ponum',         $ponum);
            $this->db->bind('approvestat',   $approvestat);
            $this->db->bind('final_approve', 'Y');
            $this->db->execute();
        }else{
            $this->db->bind('ponum',         $ponum);
            $this->db->bind('approvestat',   $approvestat);
            $this->db->bind('final_approve', 'N');
            $this->db->execute();
        }

        return $this->db->rowCount();
    }

    public function rejectpo($data){
        $reqnum = $data['ponum'];
        $note   = $data['reject-note'];

        $user     = $_SESSION['usr']['user'];

        // $prdata = $this->getRequestHeader($reqnum);

        // $level    = $this->getApprovalLevel($user,$prdata['createdby'],'');
        // $maxlevel = $this->getMaxApprovalLevel($prdata['createdby'],$user,'');

        // $approvestat = 0;
        // $approvestat = $level['level']+1;

        $query = "UPDATE t_po01 set approvestat=:approvestat,appby=:appby,final_approve=:final_approve,is_rejected=:is_rejected,reject_note=:reject_note WHERE ponum=:ponum";
        $this->db->query($query);

        $this->db->bind('ponum',         $reqnum);
        $this->db->bind('approvestat',   '99');
        $this->db->bind('appby',         $user);
        $this->db->bind('final_approve', 'Y');
        $this->db->bind('is_rejected',   'Y');
        $this->db->bind('reject_note',   $note);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function sendApprovalNotif($reqnum){

        $prhead   = $this->getPOHeader($reqnum);
        $prdata   = $this->getPODetail($reqnum);
        $approval = $this->getNextApproval($prhead['createdby'],$prhead['approvestat'],'');
        if($approval){
            $mailConfig = $this->getMailConfig();
      
            // echo json_encode($mailConfig);
      
            $toemail  = $approval['email'];      
            $email    = $mailConfig['username'];
            $password = $mailConfig['password'];
            
            $to_id = $toemail;
      
            $subject = 'Purchase Order Approval '. $reqnum ;
            $mail = new PHPMailer;
            // $mail->FromName = $mailConfig['sender_name'];
            $mail->FromName = 'Purchase Order Approval';
            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->Port = 587;
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->SMTPAuth = true;
            $mail->Username = $email;
            $mail->Password = $password;
            $mail->addAddress($to_id);
            $mail->Subject = $subject;
            $mail->IsHTML(true);
            $icount = 0;
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
                Purchase Order Created/Updated, Please Review For Approval <br><br>
                
                <table>
                    <thead>                    
                        <th>No</th>
                        <th>Material</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                    </thead>
                <tbody>
                ";
                
            foreach($prdata as $row){
              $icount += 1;
                $quantity = 0;
                if (strpos($row['quantity'], '.000') !== false) {
                    $quantity = number_format($row['quantity'], 0);
                }else{
                    $quantity = number_format($row['quantity'], 3);
                }
                $mailBody .= "
                <tr> 
                  
                  <td>".$icount."</td>
                  <td>".$row['material']."</td>
                  <td>".$row['matdesc']."</td>
                  <td style='text-align:right;'>". $quantity. " ". $row['unit'] ." </td>
                  <td style='text-align:right;'>". number_format($row['price'], 2) ." </td>
                </tr>";  
            }    
                
            $mailBody .= "</tbody></table><br><p>Thanks.</p>
            <br><a href='". BASEURL ."/approvepo' target='_blank'>". BASEURL ."</a>";
            $mailBody .= "
            </body>
            </html>
            ";
            
            $headers = "From:" . $email ."\r\n";    
            $headers .= "Content-type: text/html". "\r\n";
      
            $mail->Body = $mailBody;
            if (!$mail->send()) {
                $error = "Mailer Error: " . $mail->ErrorInfo;
                // echo $error; 
            }
            else {
                // echo "Email terkirim";
            }
        }
  
    }
}