<?php

class Reports extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

	public function index(){
		header('location:'. BASEURL);
	}

    public function transaction(){
		$check = $this->model('Home_model')->checkUsermenu('reports/transaction','Read');
        if ($check){
			$data['title']    = 'Report Transaction Process';
			$data['menu']     = 'Report Transaction Process';

			$this->view('templates/header_a', $data);
			$this->view('reports/transaction', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
    }

    public function transactionview($strdate, $enddate){
		$check = $this->model('Home_model')->checkUsermenu('reports/transaction','Read');
        if ($check){
			$data['title']    = 'Report Transaction Process';
			$data['menu']     = 'Report Transaction Process';

			$data['rdata']   = $this->model('Report_model')->rtransaction($strdate, $enddate);
			$data['strdate'] = $strdate;
			$data['enddate'] = $enddate;

			$this->view('templates/header_a', $data);
			$this->view('reports/transactionview', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
    }

	public function reportpr(){
		$check = $this->model('Home_model')->checkUsermenu('reports/reportpr','Read');
        if ($check){
			$data['title']    = 'Report Purchase Request';
			$data['menu']     = 'Report Purchase Request';

			$data['department']     = $this->model('Department_model')->getList();
			$data['departmentuser'] = $this->model('Department_model')->getByByUser();
			
			$this->view('templates/header_a', $data);
			$this->view('reports/laporanpr', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
    }

    public function reportprview($strdate, $enddate, $dept, $status){
		$check = $this->model('Home_model')->checkUsermenu('reports/reportpr','Read');
        if ($check){
			$data['title']    = 'Report Purchase Request';
			$data['menu']     = 'Report Purchase Request';
	
			$data['strdate'] = $strdate;
			$data['enddate'] = $enddate;
			$data['deptid']  = $dept;
			$data['status']  = $status;
	
			// $data['prdata']  = $this->model('Laporan_model')->getPR($strdate, $enddate, $status);
	
			$this->view('templates/header_a', $data);
			$this->view('reports/laporanprview', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
	}
	
	public function laporanprdata($strdate, $enddate, $dept, $status){
		// echo $dept;
		$data = $this->model('Report_model')->getPR($strdate, $enddate, $dept, $status);
		echo json_encode($data);
    }

	public function budgetissuing(){
		$check = $this->model('Home_model')->checkUsermenu('reports/budgetissuing','Read');
        if ($check){
			$data['title']    = 'Report Budget Issuing';
			$data['menu']     = 'Report Budget Issuing';

			$data['department'] = $this->model('Department_model')->getList();

			$this->view('templates/header_a', $data);
			$this->view('reports/budgetissuing', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
    }

    public function budgetissuingview($deptid, $month, $year){
		$check = $this->model('Home_model')->checkUsermenu('reports/budgetissuing','Read');
        if ($check){
			$data['title']    = 'Report Budget Issuing';
			$data['menu']     = 'Report Budget Issuing';
	
			$data['rdata']  = $this->model('Report_model')->getBudgetHistory($deptid, $month, $year);
			$data['dept']   = $deptid;
			$data['month']  = $month;
			$data['year']   = $year;
	
			$this->view('templates/header_a', $data);
			$this->view('reports/budgetissuingview', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }          
	}
}