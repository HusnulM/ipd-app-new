<?php

class Approvepr extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('approvepr','Read');
        if ($check){
			$data['title'] = 'Approve Purchase Request';
			$data['menu']  = 'Approve Purchase Request';   
			
			$data['prdata']  = $this->model('Approvepr_model')->getOpenPR();
	
			$this->view('templates/header_a', $data);
			$this->view('approvepr/index', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
        }  
    }
    
    public function detail($prnum){
		$check = $this->model('Home_model')->checkUsermenu('approvepr','Read');
        if ($check){
			$data['title'] = 'Detail Purchase Request';
			$data['menu']  = 'Detail Purchase Request';

			$data['prhead']   = $this->model('Approvepr_model')->getPRheader($prnum);
			$data['pritem']   = $this->model('Pr_model')->getPRitem($prnum);
			$data['prdata']    = $this->model('Approvepr_model')->getOpenPRByNum($prnum);
			$data['approvelevel'] = $this->model('Approvepr_model')->getApprovalLevel($_SESSION['usr']['user'],$data['prhead']['createdby'],$data['prhead']['prtype']);
			$data['cdept']  = $this->model('Department_model')->getById($data['prhead']['deptid']);
			$data['ccurr']  = $this->model('Pr_model')->getCurrencyByCode($data['prhead']['currency']);
			$data['cprtype']    	= $this->model('Pr_model')->getPrTypeByType($data['prhead']['prtype']);
			// $data['whs']            = $this->model('Warehouse_model')->getList();   
			$data['cwhs']           = $this->model('Warehouse_model')->getById($data['prhead']['warehouse']);   

			$data['prnum'] = $prnum;
			
			$this->view('templates/header_a', $data);
			$this->view('approvepr/detail', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
		}
    }
    
    public function approve($prnum){
        if( $this->model('Approvepr_model')->approvepr($prnum) > 0 ) {
			Flasher::setMessage('PR', $prnum . ' Approved' ,'success');
			header('location: '. BASEURL . '/approvepr');
			exit;			
		}else{
			Flasher::setMessage('Approve PR', $prnum . ' Failed','danger');
			header('location: '. BASEURL . '/approvepr');
			exit;	
		}
    }

	public function approvepritem($prnum){
		$exec = $this->model('Approvepr_model')->checkBudgetExceeded($prnum,$_POST['pritem']);
		// echo json_encode($exec);
		if($exec == 1){	
			if( $this->model('Approvepr_model')->approvepritem($prnum,$_POST['pritem']) > 0 ) {
				$return = array(
					"msgtype" => "1",
					"message" => "PR Approved",
					"docnum"  => $prnum
				);
				echo json_encode($return);
				exit;			
			}else{
				$return = array(
					"msgtype" => "2",
					"message" => "Approve PR Failed",
					"docnum"  => ""
				);
				echo json_encode($return);
				exit;	
			}
		}else{
			$return = array(
				"msgtype" => "2",
				"message" => "Approve PR Failed, Exceeded Budget ". number_format($exec['exceeded'], 2, '.', ','). ", Existing Budget ". number_format($exec['budget'], 2, '.', ','). " Total Price ". number_format($exec['issuing'], 2, '.', ','),
				"docnum"  => ""
			);
			echo json_encode($return);
			exit;
		}
	}

	public function rejectpritem($prnum){
		if( $this->model('Approvepr_model')->rejectpritem($prnum,$_POST['pritem']) > 0 ) {
			$return = array(
				"msgtype" => "1",
				"message" => "PR Item Rejected",
				"docnum"  => $prnum
			);
			echo json_encode($return);
			exit;			
		}else{
			$return = array(
				"msgtype" => "2",
				"message" => "Reject PR Failed",
				"docnum"  => ""
			);
			echo json_encode($return);
			exit;	
		}
	}

    public function reject($prnum){
        if( $this->model('Approvepr_model')->rejectpr($prnum) > 0 ) {
			Flasher::setMessage('PR', $prnum . ' Rejected' ,'success');
			header('location: '. BASEURL . '/approvepr');
			exit;			
		}else{
			Flasher::setMessage('Reject PR', $prnum . ' Failed','danger');
			header('location: '. BASEURL . '/approvepr');
			exit;	
		}
    }
}