<?php

class Requestslip extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('requestslip','Read');
        if ($check){
            $data['title'] = 'Request Slip';
            $data['menu']  = 'Request Slip';     

            $data['reqdata']       = $this->model('Requestslip_model')->getOpenRequest();

            $this->view('templates/header_a', $data);
            $this->view('requestslip/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function create(){
        $check = $this->model('Home_model')->checkUsermenu('requestslip','Read');
        if ($check){
            $data['title'] = 'Create Request Slip';
            $data['menu']  = 'Create Request Slip';     

            // $data['whs'] = $this->model('Prtype_model')->getList();   
            $data['currency']       = $this->model('Pr_model')->getCurrency();
            $data['department']     = $this->model('Department_model')->getList();
			$data['departmentuser'] = $this->model('Department_model')->getByByUser();

            $this->view('templates/header_a', $data);
            $this->view('requestslip/create', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function requestlist(){
        $check = $this->model('Home_model')->checkUsermenu('requestslip/requestlist','Read');
        if ($check){
            $data['title'] = 'Request Slip';
            $data['menu']  = 'Request Slip';     

            // $data['whs'] = $this->model('Prtype_model')->getList();   
            $data['reqdata']       = $this->model('Requestslip_model')->getOpenRequest();

            $this->view('templates/header_a', $data);
            $this->view('requestslip/requestlist', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function requestdetail($requestnum){
        $check = $this->model('Home_model')->checkUsermenu('requestslip/requestlist','Create');
        if ($check){
            $data['title'] = 'Request Slip Detail';
            $data['menu']  = 'Request Slip Detail';     

            $data['reqheader']   = $this->model('Requestslip_model')->getRequestHeader($requestnum);
			$data['reqdetails']  = $this->model('Requestslip_model')->getRequestDetail($requestnum);

            $this->view('templates/header_a', $data);
            $this->view('requestslip/detail', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function submitpo(){
        $check = $this->model('Home_model')->checkUsermenu('requestslip/submitpo','Read');
        if ($check){
            $data['title'] = 'Submit Request Slip for PO';
            $data['menu']  = 'Submit Request Slip for PO';     

            $data['reqdata']   = $this->model('Requestslip_model')->getRequestForPO();

            $this->view('templates/header_a', $data);
            $this->view('requestslip/requestforpo', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function getmateriallist(){
        $data['data'] = $this->model('Material_model')->getListBarang();
		echo json_encode($data);
    }

    public function save(){
        $nextNumb = $this->model('Pr_model')->getNextNumber('REQ_SLIP');
        if( $this->model('Requestslip_model')->save($_POST, $nextNumb['nextnumb']) > 0 ) {
            $result = ["msg"=>"sukses", $nextNumb];

            $result = array(
                "msgtype" => "1",
                "message" => "Request Slip Created",
                "reqnum"  => $nextNumb['nextnumb']
            );
            echo json_encode($result);
            // echo json_encode($nextNumb['nextnumb']);
            exit;			
        }else{
            // $result = ["msg"=>"error"];
            $result = array(
                "msgtype" => "2",
                "message" => "Create Request Slip Failed"
            );
            echo json_encode($result);
            exit;	
        }
	}

    public function saveprice(){
        // $this->model('Requestslip_model')->saveprice($_POST);
        if( $this->model('Requestslip_model')->saveprice($_POST) > 0 ) {
            Flasher::setMessage('Request Slip '. $_POST['requestnum'] .' Price Submitted','','success');
			header('location: '. BASEURL . '/requestslip/requestlist');
            exit;			
        }else{
            Flasher::setMessage('Submit Request Slip Price Fail','','danger');
			header('location: '. BASEURL . '/requestslip/requestlist');
            exit;	
        }
    }
}