<?php

class Approveslip_model{

	private $db;

	public function __construct()
	{
		$this->db = new Database;
    }

    public function getApprovalLevel($user, $creator,$prtype){
        $this->db->query("SELECT level from t_approval where object ='RS' and approval = '$user' and doctype = '$prtype' and creator = '$creator' limit 1");
        return $this->db->single();
    }

    public function getMaxApprovalLevel($creator, $approval, $prtype){
        $this->db->query("SELECT level from t_approval where object ='RS' and doctype = '$prtype' and creator = '$creator' order by level desc limit 1");
        return $this->db->single();
    }

    public function getNextApproval($creator, $level){
        $this->db->query("SELECT a.object, a.level, a.creator, a.approval, b.email from t_approval as a inner JOIN t_user as b on a.approval = b.username where object ='RS' and creator = '$creator' and a.level = '$level' order by level asc limit 1");
        return $this->db->single();
    }

    public function getOpenRequest(){
        $user = $_SESSION['usr']['user'];
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.createdby in(SELECT creator from t_approval where object ='RS' and approval = '$user') and a.request_status in(SELECT level from t_approval where object ='RS' and approval = '$user' and creator = a.createdby)");
        return $this->db->resultSet();
    }
  
    public function getRequestForPO(){
        $this->db->query("SELECT a.*, b.department FROM t_request_slip01 as a left join t_department as b on a.deptid = b.id WHERE a.request_status = '2'");
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

    public function approverequestslip($reqnum){
        $user     = $_SESSION['usr']['user'];

        $prdata = $this->getRequestHeader($reqnum);

        $level    = $this->getApprovalLevel($user,$prdata['createdby'],'');
        $maxlevel = $this->getMaxApprovalLevel($prdata['createdby'],$user,'');

        $approvestat = 0;
        $approvestat = $level['level']+1;

        $query = "UPDATE t_request_slip01 set request_status=:request_status, final_approve=:final_approve WHERE requestnum=:requestnum";
        $this->db->query($query);

        if($level['level'] === $maxlevel['level']){
            $this->db->bind('requestnum',     $reqnum);
            $this->db->bind('request_status', $approvestat);
            $this->db->bind('final_approve',  'Y');
            $this->db->execute();
        }else{
            $this->db->bind('requestnum',     $reqnum);
            $this->db->bind('request_status', $approvestat);
            $this->db->bind('final_approve',  'N');
            $this->db->execute();
        }

        $insertApproveStatus = "INSERT t_request_slip04 (requestnum,approve_level,approve_date,approve_by) values (:requestnum,:approve_level,:approve_date,:approve_by)";
        $this->db->query($insertApproveStatus);

        $this->db->bind('requestnum',     $reqnum);
        $this->db->bind('approve_level',  $level['level']);
        $this->db->bind('approve_date',   date('Y-m-d'));
        $this->db->bind('approve_by',     $_SESSION['usr']['user']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function rejectrequestslip($data){
        $reqnum = $data['requestnum'];
        $note   = $data['reject-note'];

        $user     = $_SESSION['usr']['user'];

        $prdata = $this->getRequestHeader($reqnum);

        $level    = $this->getApprovalLevel($user,$prdata['createdby'],'');
        $maxlevel = $this->getMaxApprovalLevel($prdata['createdby'],$user,'');

        $approvestat = 0;
        $approvestat = $level['level']+1;

        $query = "UPDATE t_request_slip01 set request_status=:request_status, final_approve=:final_approve, is_rejected=:is_rejected, reject_note=:reject_note WHERE requestnum=:requestnum";
        $this->db->query($query);

        $this->db->bind('requestnum',     $reqnum);
        $this->db->bind('request_status', '99');
        $this->db->bind('final_approve',  'Y');
        $this->db->bind('is_rejected',    'Y');
        $this->db->bind('reject_note',    $note);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getMailConfig(){
        $this->db->query("SELECT * FROM t_email_config limit 1");
        return $this->db->single();
    }
  
    public function sendApprovalNotif($reqnum){
        $prhead   = $this->getRequestHeader($reqnum);
        $prdata   = $this->getRequestDetail($reqnum);
        $approval = $this->getNextApproval($prhead['createdby'],$prhead['request_status']);
        if($approval){
            $mailConfig = $this->getMailConfig();
      
            // echo json_encode($mailConfig);
      
            $toemail  = $approval['email'];      
            $email    = $mailConfig['username'];
            $password = $mailConfig['password'];
            
            $to_id = $toemail;
      
            $subject = 'Request Slip Approval '. $reqnum ;
            $mail = new PHPMailer;
            // $mail->FromName = $mailConfig['sender_name'];
            $mail->FromName = 'Request Slip Approval';
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
                Request Slip Created/Updated, Please Review For Approval <br><br>
                
                <table>
                    <thead>
                        
                        <th>No</th>
                        <th>Material</th>
                        <th>Description</th>
                        <th>Quantity</th>
                    </thead>
                <tbody>
                ";
                
            foreach($prdata as $row){
              $icount += 1;
                $quantity = 0;
                if (strpos($row['quantity'], '.000') !== false) {
                    $quantity = number_format($row['quantity'], 0, ',', '.');
                }else{
                    $quantity = number_format($row['quantity'], 3, ',', '.');
                }
                $mailBody .= "
                <tr> 
                  
                  <td>".$icount."</td>
                  <td>".$row['material']."</td>
                  <td>".$row['matdesc']."</td>
                  <td style='text-align:right;'>".$quantity. " ". $row['unit'] ." </td>
                </tr>";  
            }    
                
            $mailBody .= "</tbody></table><br><p>Thanks.</p>
            <br><a href='". BASEURL ."/approveslip' target='_blank'>". BASEURL ."</a>";
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