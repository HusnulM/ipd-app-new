<?php

class Approveslip extends Controller {
    public function __construct(){
        if( isset($_SESSION['usr']) ){
        }else{
            header('location:'. BASEURL);
        }
    }

    public function index(){
        $check = $this->model('Home_model')->checkUsermenu('approveslip','Read');
        if ($check){
            $data['title'] = 'Open Request Slip';
            $data['menu']  = 'Open Request Slip';     

            $data['reqdata']       = $this->model('Approveslip_model')->getOpenRequest();

            $this->view('templates/header_a', $data);
            $this->view('approveslip/index', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }

    public function requestdetail($requestnum){
        $check = $this->model('Home_model')->checkUsermenu('approveslip','Read');
        if ($check){
            $data['title'] = 'Request Slip Detail';
            $data['menu']  = 'Request Slip Detail';     

            $data['reqheader']   = $this->model('Approveslip_model')->getRequestHeader($requestnum);
			$data['reqdetails']  = $this->model('Approveslip_model')->getRequestDetail($requestnum);

            $this->view('templates/header_a', $data);
            $this->view('approveslip/detail', $data);
            $this->view('templates/footer_a');
        }else{
            $this->view('templates/401');
        }
    }
}