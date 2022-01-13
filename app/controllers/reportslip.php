<?php

class Reportslip extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('reportslip','Read');
        if ($check){
			$data['title']    = 'Report Request Slip';
			$data['menu']     = 'Report Request Slip';

            $data['department']     = $this->model('Department_model')->getList();
            $data['departmentuser'] = $this->model('Department_model')->getByByUser();

			$this->view('templates/header_a', $data);
			$this->view('reportslip/index', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }  
	}

    public function display($strdate, $enddate, $dept){
        $check = $this->model('Home_model')->checkUsermenu('reportslip','Read');
        if ($check){
			$data['title']    = 'Report Request Slip';
			$data['menu']     = 'Report Request Slip';

            // $data['department']     = $this->model('Department_model')->getList();
            // $data['departmentuser'] = $this->model('Department_model')->getByByUser();
            $data['strdate'] = $strdate;
			$data['enddate'] = $enddate;
            $data['dept']    = $dept;

			$this->view('templates/header_a', $data);
			$this->view('reportslip/display', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }

	public function printslip($params){
		$url    = parse_url($_SERVER['REQUEST_URI']);
        $data   = parse_str($url['query'], $params);
		$reqnum = $params['reqnum'];

		$data['header']   = $this->model('Requestslip_model')->getRequestHeader($reqnum);
		$data['poitem']   = $this->model('Requestslip_model')->getRequestDetail($reqnum);
		$this->view('requestslip/printoutslip', $data);
		// echo json_encode($data['poitem']);
	}

    public function getheaderdata($strdate, $enddate, $dept){
		$data['data'] = $this->model('Reportslip_model')->getHeader($strdate, $enddate, $dept);
		echo json_encode($data);
	}

	public function getdetaildata($reqnum){
		$data = $this->model('Reportslip_model')->getDetail($reqnum);
		echo json_encode($data);
	}
}