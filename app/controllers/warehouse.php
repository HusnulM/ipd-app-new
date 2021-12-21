<?php

class Warehouse extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('warehouse','Read');
        if ($check){
            $data['title'] = 'Warehouse Master';
            $data['menu']  = 'Warehouse Master';     

            $data['whs']    = $this->model('Warehouse_model')->getList();  
            $data['prtype'] = $this->model('Prtype_model')->getList();    

            $this->view('templates/header_a', $data);
            $this->view('warehouse/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function create(){
        $check = $this->model('Home_model')->checkUsermenu('warehouse','Create');
        if ($check){
            $data['title'] = 'Add New Warehouse';
            $data['menu']  = 'Add New Warehouse';      

            $this->view('templates/header_a', $data);
            $this->view('warehouse/create', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function edit($id){
        $check = $this->model('Home_model')->checkUsermenu('warehouse','Read');
        if ($check){
            $data['title'] = 'Change Warehouse Master';
            $data['menu']  = 'Change Warehouse Master';     

            $data['whs'] = $this->model('Warehouse_model')->getById($id);   
            // echo json_encode($data['whs']);
            $this->view('templates/header_a', $data);
            $this->view('warehouse/edit', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function save(){
		if( $this->model('Warehouse_model')->save($_POST) > 0 ) {
			Flasher::setMessage('New warehouse created','','success');
			header('location: '. BASEURL . '/warehouse');
			exit;			
		}else{
			Flasher::setMessage('Create new warehouse fail','','danger');
			header('location: '. BASEURL . '/warehouse');
			exit;	
		}
	}

    public function update(){
		if( $this->model('Warehouse_model')->update($_POST) > 0 ) {
			Flasher::setMessage('warehouse updated','','success');
			header('location: '. BASEURL . '/warehouse');
			exit;			
		}else{
			Flasher::setMessage('Update warehouse fail','','danger');
			header('location: '. BASEURL . '/warehouse');
			exit;	
		}
	}

    public function delete($whsid){
		if( $this->model('Warehouse_model')->delete($whsid) > 0 ) {
			Flasher::setMessage('warehouse deleted','','success');
			header('location: '. BASEURL . '/warehouse');
			exit;			
		}else{
			Flasher::setMessage('Delete warehouse fail','','danger');
			header('location: '. BASEURL . '/warehouse');
			exit;	
		}
	}


    public function savewhsassignment($whscode, $prtype){
		if( $this->model('Warehouse_model')->savewhsassignment($whscode, $prtype) > 0 ) {
			echo json_encode('true');			
			exit;			
		}else{
			echo json_encode('false');
			exit;	
		}
	}

    public function deletewhsassignment($whscode, $prtype){
		if( $this->model('Warehouse_model')->deletewhsassignment($whscode, $prtype) > 0 ) {
			echo json_encode('true');			
			exit;			
		}else{
			echo json_encode('false');
			exit;	
		}
	}

    public function getwarehousebyprtype($prtype){
        $data = $this->model('Warehouse_model')->getWarehouseByPrType($prtype);
        echo json_encode($data);
    }

    public function getwarehouseprtypeassignment($whscode){
        $data = $this->model('Warehouse_model')->getWarehousePrTypeAssignement($whscode);
        echo json_encode($data);
    }
}