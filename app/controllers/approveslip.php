<?php

class Approveslip extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('approveslip','Read');
        if ($check){
            $data['title'] = 'Open Request Slip';
            $data['menu']  = 'Open Request Slip';     

            $data['reqdata']       = $this->model('Approveslip_model')->getOpenRequest();

            $this->view('templates/header_a', $data);
            $this->view('approveslip/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function requestdetail($requestnum){
        $check = $this->model('Home_model')->checkUsermenu('approveslip','Read');
        if ($check){
            $data['title'] = 'Request Slip Detail';
            $data['menu']  = 'Request Slip Detail';     

            $data['reqheader']   = $this->model('Approveslip_model')->getRequestHeader($requestnum);
			$data['reqdetails']  = $this->model('Approveslip_model')->getRequestDetail($requestnum);
            $data['attachments'] = $this->model('Requestslip_model')->getAttachment($requestnum);

            $this->view('templates/header_a', $data);
            $this->view('approveslip/detail', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function approveslip(){
        // $this->model('Requestslip_model')->saveprice($_POST);
        if( $this->model('Approveslip_model')->approverequestslip($_POST['requestnum']) > 0 ) {
            $this->model('Approveslip_model')->sendApprovalNotif($_POST['requestnum']);
            Flasher::setMessage('Request Slip '. $_POST['requestnum'] .' Approved','','success');
			header('location: '. BASEURL . '/approveslip');
            exit;			
        }else{
            Flasher::setMessage('Approve Request Slip Fail','','danger');
			header('location: '. BASEURL . '/approveslip');
            exit;	
        }
    }

    public function reject(){
        // $this->model('Requestslip_model')->saveprice($_POST);
        if( $this->model('Approveslip_model')->rejectrequestslip($_POST) > 0 ) {
            // $this->model('Approveslip_model')->sendApprovalNotif($_POST['requestnum']);
            Flasher::setMessage('Request Slip '. $_POST['requestnum'] .' Rejected','','success');
			header('location: '. BASEURL . '/approveslip');
            exit;			
        }else{
            Flasher::setMessage('Reject Request Slip Fail','','danger');
			header('location: '. BASEURL . '/approveslip');
            exit;	
        }
    }
}