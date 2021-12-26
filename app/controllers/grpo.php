<?php

class Grpo extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
	}

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('grpo','Read');
        if ($check){
			$data['title'] = 'Receipt Purchase Order';
			$data['menu']  = 'Receipt Purchase Order';

			// $data['podata']  = $this->model('Po_model')->listopenpo();
            $data['whslist']      = $this->model('Warehouse_model')->getList();
	
			$this->view('templates/header_a', $data);
			$this->view('grpo/index', $data);
			$this->view('templates/footer_a');
		}else{
			$this->view('templates/401');
		}
    }

    public function listpotogr(){
        $data['data'] = $this->model('Grpo_model')->getApprovedPO();
        echo json_encode($data);
    }

    public function getopenpoitem($params){
        $url = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
		$ponum = $params['ponum'];

		$data = $this->model('Grpo_model')->getPOitemtoGR($ponum);
		echo json_encode($data);
    }

    public function post(){
        $nextNumb = $this->model('Home_model')->getNextNumber('GRPO');
        // $this->model('Grpo_model')->post($_POST, $nextNumb['nextnumb']);
        if( $this->model('Grpo_model')->post($_POST, $nextNumb['nextnumb']) > 0 ) {
            $return = array(
                "msgtype" => "1",
                "message" => "Inventory Movement Posted!",
                "docnum"  => $nextNumb['nextnumb']
            );
            echo json_encode($return);
            exit;			
        }else{
            $return = array(
                "msgtype" => "3",
                "message" => "Error!",
                "data"    => Flasher::errorMessage()
            );
            $this->model('Grpo_model')->delete($nextNumb['nextnumb']);
            echo json_encode($return);
            exit;	
        }        
    }
}