<?php

class Budgeting extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
      $check = $this->model('Home_model')->checkUsermenu('budgeting','Read');
      if ($check){
        $data['title'] = 'Budget Allocation';
        $data['menu']  = 'Budget Allocation';

        $data['budget'] = $this->model('Budgeting_model')->getBudgetCurrentPeriod();
        // echo json_encode($data['budget']);

        $this->view('templates/header_a', $data);
        $this->view('budget/index_new', $data);
        $this->view('templates/footer_a');            
      }else{
        $this->view('templates/401');
      }  
    }

    public function readBudgeting(){
      $check = $this->model('Home_model')->checkUsermenu('budgeting','Read');
      if ($check){
        $data['data'] = $this->model('Budgeting_model')->getBudgetCurrentPeriod();
        echo json_encode($data);
      }else{
        echo json_encode('');
      }
    }

    public function readBudgetDetail($deptid, $year){
      $data = $this->model('Budgeting_model')->getBudgetIssuedPerDept($deptid, $year);
      echo json_encode($data);
    }

    public function create(){
      $check = $this->model('Home_model')->checkUsermenu('budgeting','Create');
      if ($check){
        $data['title'] = 'Add Budget Allocation';
        $data['menu']  = 'Add Budget Allocation';

        $data['department'] = $this->model('Department_model')->getList();

        $this->view('templates/header_a', $data);
        $this->view('budget/create', $data);
        $this->view('templates/footer_a');            
      }else{
        $this->view('templates/401');
      }  
    }

    public function save(){
      if( $this->model('Budgeting_model')->saveAllocation($_POST) > 0 ) {
        Flasher::setMessage('Budget Allocation Added','','success');
        header('location: '. BASEURL . '/budgeting');
        exit;			
      }else{
        Flasher::setMessage('Budget Allocation Failed','','danger');
        header('location: '. BASEURL . '/budgeting');
        exit;	
      }
    }
}