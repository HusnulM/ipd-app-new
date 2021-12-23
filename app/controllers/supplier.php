<?php

class Supplier extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
	}

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('supplier','Read');
        if ($check){
			$data['title'] = 'Supplier Master';
			$data['menu']  = 'Supplier Master';

			$data['supplier']  = $this->model('Supplier_model')->supplierLists();
	
			$this->view('templates/header_a', $data);
			$this->view('supplier/index', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		}
    }

    public function create(){
        $check = $this->model('Home_model')->checkUsermenu('supplier','Create');
        if ($check){
          $data['title'] = 'Create Supplier';
          $data['menu']  = 'Create Supplier';
  
          $this->view('templates/header_a', $data);
          $this->view('supplier/create', $data);
          $this->view('templates/footer_a');
        }else{
          $this->view('templates/401');
        }      
    }

    public function edit($suppid){
        $check = $this->model('Home_model')->checkUsermenu('supplier','Update');
        if ($check){
            $data['title'] = 'Edit Vendor';
            $data['menu']  = 'Edit Vendor';
    
            $data['vendor']    = $this->model('Supplier_model')->getSupplierByID($suppid);
    
            $this->view('templates/header_a', $data);
            $this->view('supplier/edit', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }    
    }

    public function supplierlist(){
        $data['data'] = $this->model('Supplier_model')->supplierLists();
		echo json_encode($data);
    }

    public function save(){
		if( $this->model('Supplier_model')->save($_POST) > 0 ) {
            Flasher::setMessage('New Supplier Created','','success');
            header('location: '. BASEURL . '/supplier');
            exit;			
        }else{
            Flasher::setMessage('Failed create new supplier','','danger');
            header('location: '. BASEURL . '/supplier');
            exit;	
        }
    }

    public function update(){
		if( $this->model('Supplier_model')->update($_POST) > 0 ) {
			Flasher::setMessage('Supplier updated','','success');
			header('location: '. BASEURL . '/supplier');
			exit;			
		}else{
			Flasher::setMessage('Failed update supplier','','danger');
			header('location: '. BASEURL . '/supplier');
			exit;	
		}
	}
  
    public function delete($vendor){
        $check = $this->model('Home_model')->checkUsermenu('supplier','Delete');
        if ($check){
            if( $this->model('Supplier_model')->delete($vendor) > 0 ) {
                Flasher::setMessage('Supplier '. $vendor .' deleted','','success');
                header('location: '. BASEURL . '/supplier');
                exit;			
            }else{
                Flasher::setMessage('Failed delete supplier','','danger');
                header('location: '. BASEURL . '/supplier');
                exit;	
            }
        }else{
            $this->view('templates/401');
        }    
  }
}