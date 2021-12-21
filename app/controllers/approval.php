<?php

class Approval extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('approval','Read');
        if ($check){
			$data['title'] = 'Mapping Approval PR/PO';
			$data['menu']  = 'Mapping Approval PR/PO';
			
			$data['usrapp']  = $this->model('Approval_model')->getmappingapproval();
	
			$this->view('templates/header_a', $data);
			$this->view('approval/index', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
        }  
    }

    public function create(){
		$check = $this->model('Home_model')->checkUsermenu('approval','Create');
        if ($check){
			$data['title'] = 'Mapping Approval PR/PO';
			$data['menu']  = 'Mapping Approval PR/PO'; 
			
            $data['userc']  = $this->model('Approval_model')->getusercreator();
            $data['usera']  = $this->model('Approval_model')->getuserapproval();
	
			$this->view('templates/header_a', $data);
			$this->view('approval/create', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
        }  
    }

    public function save(){
		if( $this->model('Approval_model')->save($_POST) > 0 ) {
			Flasher::setMessage('Mapping Approval Created','','success');
			header('location: '. BASEURL . '/approval');
			exit;			
		}else{
			Flasher::setMessage('Create Mapping Approval Failed','','danger');
			header('location: '. BASEURL . '/approval');
			exit;	
		}
    }
    
    public function delete($object,$doctype,$creator,$approval){
		if( $this->model('Approval_model')->delete($object,$doctype,$creator,$approval) > 0 ) {
			Flasher::setMessage('Mapping Approval Deleted','','success');
			header('location: '. BASEURL . '/approval');
			exit;			
		}else{
			Flasher::setMessage('Delete Mapping Approval Failed','','danger');
			header('location: '. BASEURL . '/approval');
			exit;	
		}
	}
}