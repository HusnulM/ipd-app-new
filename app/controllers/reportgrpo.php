<?php

class Reportgrpo extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('reportgrpo','Read');
        if ($check){
			$data['title']    = 'Report Receipt Purchase Order';
			$data['menu']     = 'Report Receipt Purchase Order';

            $data['department']     = $this->model('Department_model')->getList();
            $data['departmentuser'] = $this->model('Department_model')->getByByUser();

			$this->view('templates/header_a', $data);
			$this->view('receiptpo/index', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }  
	}

    public function display($strdate, $enddate){
        $check = $this->model('Home_model')->checkUsermenu('reportgrpo','Read');
        if ($check){
			$data['title']    = 'Report Receipt Purchase Order';
			$data['menu']     = 'Report Receipt Purchase Order';

            $data['grdata']   = $this->model('Reportgrpo_model')->getGRPoData($strdate, $enddate);

			$this->view('templates/header_a', $data);
			$this->view('receiptpo/display', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }
}