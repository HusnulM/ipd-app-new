<?php

class Stockreport extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('stockreport','Read');
        if ($check){
            $data['title'] = 'Stock Report';
            $data['menu']  = 'Stock Report';     

            $data['stock'] = $this->model('Inventory_model')->getStock();   

            $this->view('templates/header_a', $data);
            $this->view('inventory/stockreport/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }      
    }

    public function issuingstock(){
        $check = $this->model('Home_model')->checkUsermenu('stockreport/issuingstock','Read');
        if ($check){
            $data['title'] = 'Issued Stock Report';
            $data['menu']  = 'Issued Stock Report';     

            $this->view('templates/header_a', $data);
            $this->view('inventory/stockreport/issuingstock', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }      
    }

    public function issuingstockview($strdate, $endate){
        $check = $this->model('Home_model')->checkUsermenu('stockreport/issuingstock','Read');
        if ($check){
            $data['title'] = 'Issued Stock Report';
            $data['menu']  = 'Issued Stock Report';     

            $data['stock'] = $this->model('Inventory_model')->getIssuingStock($strdate, $endate);
            $data['strdate'] = $strdate;
            $data['enddate'] = $endate;

            $this->view('templates/header_a', $data);
            $this->view('inventory/stockreport/issuingstockview', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }  
    }
}