<?php

class Inventory extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function adjustment(){
        $check = $this->model('Home_model')->checkUsermenu('inventory/adjustment','Read');
        if ($check){
            $data['title'] = 'Stock Adjustment';
            $data['menu']  = 'Stock Adjustment';     

            // $data['defect'] = $this->model('Defect_model')->getDefectList();   
            $data['whs']            = $this->model('Warehouse_model')->getList(); 

            $this->view('templates/header_a', $data);
            $this->view('inventory/adjustment/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }      
    }

    public function saveadjustment(){
        $nextNumb = $this->model('Inventory_model')->getNextNumber('INVENTORY');
		if( $this->model('Inventory_model')->saveadjustment($_POST, $nextNumb['nextnumb']) > 0 ) {
			$result = ["msg"=>"success", "docnum" => $nextNumb['nextnumb']];
			echo json_encode($result);
			exit;			
		}else{
            $this->model('Inventory_model')->deletedocs($nextNumb['nextnumb']);
			$result = ["msg"=>"error"];
            echo json_encode($result);
			exit;	
		}
    }

    public function savedeliver(){
        $nextNumb = $this->model('Inventory_model')->getNextNumber('INVENTORY');
		if( $this->model('Inventory_model')->savedeliver($_POST, $nextNumb['nextnumb']) > 0 ) {
			$result = ["msg"=>"success", "docnum" => $nextNumb['nextnumb']];
			echo json_encode($result);
			exit;			
		}else{
            $this->model('Inventory_model')->deletedocs($nextNumb['nextnumb']);
			$result = ["msg"=>"error"];
            echo json_encode($result);
			exit;	
		}
    }

    public function deliver(){
        $check = $this->model('Home_model')->checkUsermenu('inventory/deliver','Read');
        if ($check){
            $data['title'] = 'Parts Deliver';
            $data['menu']  = 'Parts Deliver';     

            $data['whs']            = $this->model('Warehouse_model')->getList(); 

            $this->view('templates/header_a', $data);
            $this->view('inventory/deliver/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }      
    }

    public function getAllmaterial(){
        $data['data'] = $this->model('Inventory_model')->getMaterialLists();   
        echo json_encode($data);
    }

    public function getMaterialQty($params){
        $url = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
        $material = $params['material'];

        $data = $this->model('Inventory_model')->getStockByMaterial($material);
        echo json_encode($data);
    }
}