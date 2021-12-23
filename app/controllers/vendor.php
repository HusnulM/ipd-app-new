<?php

class Vendor extends Controller{
  public function __construct(){
		if( isset($_SESSION['usr']) ){
		}else{
			header('location:'. BASEURL);
		}
  }
    
    public function index(){
      $check = $this->model('Home_model')->checkUsermenu('vendor','Read');
      if ($check){
        $data['title'] = 'Master Vendor';
        $data['menu']  = 'Master Vendor';
        $data['menu-dsc'] = '';

        $data['setting']  = $this->model('Setting_model')->getgensetting();
        $data['appmenu']  = $this->model('Home_model')->getUsermenu();   
        
        $data['vendor']  = $this->model('Vendor_model')->getListVendor();

        $this->view('templates/header_a', $data);
        $this->view('vendor/index', $data);
        $this->view('templates/footer_a');
      }else{
        $this->view('templates/401');
      }
    }

    public function create(){
      $check = $this->model('Home_model')->checkUsermenu('vendor','Create');
      if ($check){
        $data['title'] = 'Tambah Vendor';
        $data['menu']  = 'Tambah Vendor';
        $data['menu-dsc'] = '';

        $data['setting']  = $this->model('Setting_model')->getgensetting();
        $data['appmenu']  = $this->model('Home_model')->getUsermenu();   

        $this->view('templates/header_a', $data);
        $this->view('vendor/create', $data);
        $this->view('templates/footer_a');
      }else{
        $this->view('templates/401');
      }      
  }

  public function carivendor($nama){
		$data = $this->model('Vendor_model')->getVendorByName($nama);
		foreach ($data as $row):
			$row_out[] = array(
				'label'   => $row['vendor'] . ' ' . $row['namavendor'],
        'value'   => $row['namavendor'],	
			);
		endforeach;	
		echo json_encode($row_out);
	}
  
  public function edit($vendor){
    $check = $this->model('Home_model')->checkUsermenu('vendor','Update');
      if ($check){
        $data['title'] = 'Edit Vendor';
        $data['menu']  = 'Edit Vendor';

        $data['setting']  = $this->model('Setting_model')->getgensetting();
        $data['appmenu']  = $this->model('Home_model')->getUsermenu();   

        $data['vendor']    = $this->model('Vendor_model')->getVendorByKode($vendor);

        $this->view('templates/header_a', $data);
        $this->view('vendor/edit', $data);
        $this->view('templates/footer_a');
      }else{
        $this->view('templates/401');
      }    
  }

  public function vendorlist(){
    $data['data'] = $this->model('Vendor_model')->getListVendor();
		echo json_encode($data);
  }
	
	public function save(){
		if( $this->model('Vendor_model')->save($_POST) > 0 ) {
      Flasher::setMessage('Data Vendor Berhasil di simpan','','success');
      header('location: '. BASEURL . '/vendor');
      exit;			
    }else{
      Flasher::setMessage('Gagal menyimpan data vendor,','','danger');
      header('location: '. BASEURL . '/vendor');
      exit;	
    }
  }

  public function update(){
		if( $this->model('Vendor_model')->update($_POST) > 0 ) {
			Flasher::setMessage('Data Vendor Berhasil di edit','','success');
			header('location: '. BASEURL . '/vendor');
			exit;			
		  }else{
			Flasher::setMessage('Gagal edit data vendor,','','danger');
			header('location: '. BASEURL . '/vendor');
			exit;	
		  }
	}
  
  public function delete($vendor){
    $check = $this->model('Home_model')->checkUsermenu('vendor','Delete');
      if ($check){
        if( $this->model('Vendor_model')->delete($vendor) > 0 ) {
          Flasher::setMessage('Data Vendor Berhasil','di Hapus','success');
          header('location: '. BASEURL . '/vendor');
          exit;			
        }else{
          Flasher::setMessage('Gagal menghapus data vendor,','','danger');
          header('location: '. BASEURL . '/vendor');
          exit;	
        }
      }else{
        $this->view('templates/401');
      }    
  }
}