<?php

class Prtype extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('prtype','Read');
        if ($check){
            $data['title'] = 'prtype Master';
            $data['menu']  = 'prtype Master';     

            $data['whs'] = $this->model('Prtype_model')->getList();   

            $this->view('templates/header_a', $data);
            $this->view('prtype/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function create(){
        $check = $this->model('Home_model')->checkUsermenu('prtype','Create');
        if ($check){
            $data['title'] = 'Add New prtype';
            $data['menu']  = 'Add New prtype';      

            $this->view('templates/header_a', $data);
            $this->view('prtype/create', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function edit($id){
        $check = $this->model('Home_model')->checkUsermenu('prtype','Read');
        if ($check){
            $data['title'] = 'Change prtype Master';
            $data['menu']  = 'Change prtype Master';     

            $data['whs'] = $this->model('Prtype_model')->getById($id);   
            // echo json_encode($data['whs']);
            $this->view('templates/header_a', $data);
            $this->view('prtype/edit', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function save(){
		if( $this->model('Prtype_model')->save($_POST) > 0 ) {
			Flasher::setMessage('New prtype created','','success');
			header('location: '. BASEURL . '/prtype');
			exit;			
		  }else{
			Flasher::setMessage('Create new prtype fail','','danger');
			header('location: '. BASEURL . '/prtype');
			exit;	
		  }
	}

    public function update(){
		if( $this->model('Prtype_model')->update($_POST) > 0 ) {
			Flasher::setMessage('prtype updated','','success');
			header('location: '. BASEURL . '/prtype');
			exit;			
		}else{
			Flasher::setMessage('Update prtype fail','','danger');
			header('location: '. BASEURL . '/prtype');
			exit;	
		}
	}

    public function delete($whsid){
		if( $this->model('Prtype_model')->delete($whsid) > 0 ) {
			Flasher::setMessage('prtype deleted','','success');
			header('location: '. BASEURL . '/prtype');
			exit;			
		}else{
			Flasher::setMessage('Delete prtype fail','','danger');
			header('location: '. BASEURL . '/prtype');
			exit;	
		}
	}

    public function getprtypebyprtype($prtype){
        $data = $this->model('Prtype_model')->getprtypeByPrType($prtype);
        echo json_encode($data);
    }
}