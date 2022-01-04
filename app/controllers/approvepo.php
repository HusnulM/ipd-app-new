<?php

class Approvepo extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('approvepo','Read');
        if ($check){
			$data['title'] = 'Approve Purchase Order';
			$data['menu']  = 'Approve Purchase Order';   
			
			$data['podata']  = $this->model('Approvepo_model')->getOpenPO();
	
			$this->view('templates/header_a', $data);
			$this->view('approvepo/index', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
        }  
    }
    
    public function detail($ponum){
		$check = $this->model('Home_model')->checkUsermenu('approvepo','Read');
        if ($check){
			$data['title'] = 'Detail Purchase Order';
			$data['menu']  = 'Detail Purchase Order';

			// $data['prhead']   = $this->model('Approvepo_model')->getPRheader($ponum);
			// $data['pritem']   = $this->model('Pr_model')->getPRitem($ponum);
			// $data['prdata']    = $this->model('Approvepo_model')->getOpenPRByNum($ponum);
			// $data['approvelevel'] = $this->model('Approvepo_model')->getApprovalLevel($_SESSION['usr']['user'],$data['prhead']['createdby'],$data['prhead']['prtype']);
			// $data['cdept']  = $this->model('Department_model')->getById($data['prhead']['deptid']);
			// $data['ccurr']  = $this->model('Pr_model')->getCurrencyByCode($data['prhead']['currency']);
			// $data['cprtype']    	= $this->model('Pr_model')->getPrTypeByType($data['prhead']['prtype']);
			// // $data['whs']            = $this->model('Warehouse_model')->getList();   
			// $data['cwhs']           = $this->model('Warehouse_model')->getById($data['prhead']['warehouse']);   

			$data['ponum'] = $ponum;
            $data['pohead']   = $this->model('Po_model')->getPOHeader($ponum);
			$data['vendor']   = $this->model('Supplier_model')->getSupplierByID($data['pohead']['vendor']);
			$data['attachments'] = $this->model('Approvepo_model')->getAttachment($ponum);
			$data['totalprice']  = $this->model('Approvepo_model')->getPoTotalPrice($ponum);
			// echo json_encode($data);
			$this->view('templates/header_a', $data);
			$this->view('approvepo/detail', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
		}
    }
    
    public function approve($ponum){
        if( $this->model('Approvepo_model')->approvepo($ponum) > 0 ) {
			Flasher::setMessage('PO', $ponum . ' Approved' ,'success');
			header('location: '. BASEURL . '/approvepo');
			exit;			
		}else{
			Flasher::setMessage('Approve PO', $ponum . ' Failed','danger');
			header('location: '. BASEURL . '/approvepo');
			exit;	
		}
    }

    public function reject($ponum){
        if( $this->model('Approvepo_model')->approvepo($ponum) > 0 ) {
			Flasher::setMessage('PO', $ponum . ' Approved' ,'success');
			header('location: '. BASEURL . '/approvepo');
			exit;			
		}else{
			Flasher::setMessage('Approve PO', $ponum . ' Failed','danger');
			header('location: '. BASEURL . '/approvepo');
			exit;	
		}
    }
}