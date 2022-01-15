<?php

class Reportpo extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

	public function index(){
		$check = $this->model('Home_model')->checkUsermenu('reportpo','Read');
        if ($check){
			$data['title']    = 'Report Purchase Order';
			$data['menu']     = 'Report Purchase Order';

            $data['department']     = $this->model('Department_model')->getList();
            $data['departmentuser'] = $this->model('Department_model')->getByByUser();

			$this->view('templates/header_a', $data);
			$this->view('reportpo/index', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }  
	}

    public function display($strdate, $enddate, $openpo){
        $check = $this->model('Home_model')->checkUsermenu('reportpo','Read');
        if ($check){
			$data['title']    = 'Report Purchase Order';
			$data['menu']     = 'Report Purchase Order';

            // $data['department']     = $this->model('Department_model')->getList();
            // $data['departmentuser'] = $this->model('Department_model')->getByByUser();
			$data['strdate'] = $strdate;
			$data['enddate'] = $enddate;
			$data['openpo']  = $openpo;

			$this->view('templates/header_a', $data);
			$this->view('reportpo/display', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }

	public function printpo($params){
		$url = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
		$ponum = $params['ponum'];

		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['header']   = $this->model('Po_model')->getOrderHeaderPrint($ponum);
		$data['poitem']   = $this->model('Po_model')->getPOitemPrint($ponum);
		$this->view('po/printout', $data);
		// echo json_encode($data['poitem']);
	}

	public function getheaderdata($strdate, $enddate, $openqty){
		$data['data'] = $this->model('Reportpo_model')->getPOHeader($strdate, $enddate, $openqty);
		echo json_encode($data);
	}

	public function getpodetail($ponum){
		$data = $this->model('Reportpo_model')->getPODetail($ponum);
		echo json_encode($data);
	}

	public function getattachment($ponum){
		$data = $this->model('Approvepo_model')->getAttachment($ponum);
		echo json_encode($data);
	}
}