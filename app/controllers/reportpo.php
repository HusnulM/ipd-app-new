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

    public function display($strdate, $enddate){
        $check = $this->model('Home_model')->checkUsermenu('reportpo','Read');
        if ($check){
			$data['title']    = 'Report Purchase Order';
			$data['menu']     = 'Report Purchase Order';

            // $data['department']     = $this->model('Department_model')->getList();
            // $data['departmentuser'] = $this->model('Department_model')->getByByUser();
			$data['strdate'] = $strdate;
			$data['enddate'] = $enddate;

			$this->view('templates/header_a', $data);
			$this->view('reportpo/display', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }

	public function getheaderdata($strdate, $enddate){
		$data['data'] = $this->model('Reportpo_model')->getPOHeader($strdate, $enddate);
		echo json_encode($data);
	}

	public function getpodetail($ponum){
		$data = $this->model('Reportpo_model')->getPODetail($ponum);
		echo json_encode($data);
	}
}