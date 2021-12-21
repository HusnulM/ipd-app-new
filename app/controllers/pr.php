<?php

class Pr extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){

		}else{
			header('location:'. BASEURL);
		}
	}

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('pr','Read');
        if ($check){
			$data['title'] = 'Purchase Request';
			$data['menu']  = 'Purchase Request'; 
			
			$data['prdata']   = $this->model('Pr_model')->getOpenPR();
	
			$this->view('templates/header_a', $data);
			$this->view('pr/index', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
        }  
	}
	
	public function create(){
		$check = $this->model('Home_model')->checkUsermenu('pr','Create');
        if ($check){
			$data['title'] = 'Create Purchase Request';
			$data['menu'] = 'Create Purchase Request';
			
			$data['department']     = $this->model('Department_model')->getList();
			$data['departmentuser'] = $this->model('Department_model')->getByByUser();
			$data['currency']       = $this->model('Pr_model')->getCurrency();
			$data['prtype']			= $this->model('Pr_model')->getPrType();
			$data['whs']            = $this->model('Warehouse_model')->getList();   
	
			$this->view('templates/header_a', $data);
			$this->view('pr/create', $data);
			$this->view('templates/footer_a');            
        }else{
            $this->view('templates/401');
		}
	}

	public function detail($prnum){
		$check = $this->model('Home_model')->checkUsermenu('pr','Read');
        if ($check){
			$data['title'] = 'Detail Purchase Request';
			$data['menu']  = 'Detail Purchase Request';

			$data['prhead']   = $this->model('Pr_model')->getPRheader($prnum);
			$data['pritem']   = $this->model('Pr_model')->getPRitem($prnum);
			$data['department'] = $this->model('Department_model')->getList();
			$data['currency']   = $this->model('Pr_model')->getCurrency();
			$data['prtype']			= $this->model('Pr_model')->getPrType();
			$data['cprtype']    	= $this->model('Pr_model')->getPrTypeByType($data['prhead']['prtype']);
			$data['whs']            = $this->model('Warehouse_model')->getList();   
			$data['cwhs']           = $this->model('Warehouse_model')->getById($data['prhead']['warehouse']);   
			$data['cdept']  = $this->model('Department_model')->getById($data['prhead']['deptid']);
			$data['ccurr']  = $this->model('Pr_model')->getCurrencyByCode($data['prhead']['currency']);
			
			$this->view('templates/header_a', $data);
			$this->view('pr/detail', $data);
			$this->view('templates/footer_a');

		}else{
			$this->view('templates/401');
		}
	}
	
	public function getapprovedpr(){
		$data['data'] = $this->model('Pr_model')->getApprovedPR();
		echo json_encode($data);
	}

	public function getoutstandingpr(){
		$data = $this->model('Pr_model')->getOutstandingPR();
		echo json_encode($data);
	}

	public function getpritem($prnum){
		$data = $this->model('Pr_model')->getPRitem($prnum);
		echo json_encode($data);
	}

	public function savepr(){
		$checkstock = $this->model('Pr_model')->checkinventorystock($_POST);
		if(sizeof($checkstock) > 0){
			$result = array(
				"msgtype" => "3",
				"message" => "Check inventory stock",
				"data"    => $checkstock
			);
			echo json_encode($result);
			exit;	
		}else{
			$nextNumb = $this->model('Pr_model')->getNextNumber('PR');
			if( $this->model('Pr_model')->savepr($_POST, $nextNumb['nextnumb']) > 0 ) {
				$result = ["msg"=>"sukses", $nextNumb];
				$this->model('Pr_model')->sendprNotif($nextNumb['nextnumb']);

				$result = array(
					"msgtype" => "1",
					"message" => "PR Created",
					"prnum"   => $nextNumb['nextnumb']
				);
				echo json_encode($result);
				// echo json_encode($nextNumb['nextnumb']);
				exit;			
			}else{
				// $result = ["msg"=>"error"];
				$result = array(
					"msgtype" => "2",
					"message" => "Create PR Failed"
				);
				echo json_encode($result);
				exit;	
			}
		}
	}

	public function uploadfile($prnum,$pritem){
		/* Getting file name */
		$filename      = $_FILES['file']['name'];
		$filename      = $filename;
		$location      = "./images/prfiles/". $filename;
		$temp          = $_FILES['file']['tmp_name'];
		$fileType      = pathinfo($location,PATHINFO_EXTENSION);
		$acak          = rand(000000,999999);		

		$this->model('Pr_model')->uploadfile($prnum, $pritem, $temp, $location, $filename, $fileType);
		move_uploaded_file($temp, $location);
	}

	public function printpr($prnum){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['header']   = $this->model('Pr_model')->getPRheader($prnum);
		$data['pritem']   = $this->model('Pr_model')->getPRitem($prnum);
		$this->view('pr/cetakpr', $data);
	}

	public function approvepr($prnum){
		if( $this->model('Pr_model')->approvepr($prnum) > 0 ) {
			Flasher::setMessage('PR', $prnum . ' Approved' ,'success');
			header('location: '. BASEURL . '/pr');
			exit;			
		}else{
			Flasher::setMessage('Approve PR', $prnum . ' Failed','danger');
			header('location: '. BASEURL . '/pr');
			exit;	
		}
	}

	public function rejectpr($prnum){
		if( $this->model('Pr_model')->rejectpr($prnum) > 0 ) {
			Flasher::setMessage('PR '. $prnum, 'Rejected' ,'success');
			header('location: '. BASEURL . '/pr');
			exit;			
		}else{
			Flasher::setMessage('Reject PR,', $prnum . ' Failed','danger');
			header('location: '. BASEURL . '/pr');
			exit;	
		}
	}

	public function updatepr($prnum){
		$checkstock = $this->model('Pr_model')->checkinventorystock($_POST);
		if(sizeof($checkstock) > 0){
			$result = array(
				"msgtype" => "3",
				"message" => "Check inventory stock",
				"data"    => $checkstock
			);
			echo json_encode($result);
			exit;	
		}else{
			if( $this->model('Pr_model')->updatepr($_POST, $prnum) > 0 ) {
				// $result = ["msg"=>"sukses", $prnum];
				// echo json_encode($prnum);
				
				$result = array(
					"msgtype" => "1",
					"message" => "PR Created",
					"prnum"   => $prnum
				);
				echo json_encode($result);
				exit;			
			}else{
				// $result = ["msg"=>"error"];
				// echo json_encode($result);
				$result = array(
					"msgtype" => "2",
					"message" => "Update PR Failed"
				);
				echo json_encode($result);
				exit;	
			}
		}
	}

	public function deletepritem($prnum, $pritem){
		if( $this->model('Pr_model')->deletepritem($prnum, $pritem) > 0 ) {
			$result = ["msg"=>"sukses", $prnum];
			echo json_encode($prnum);
			exit;			
		}else{
			$result = ["msg"=>"error"];
            echo json_encode($result);
			exit;	
		}
	}

	public function delete($prnum){
		$data['prhead']  = $this->model('Pr_model')->getPRheader($prnum);
		if($data['prhead']['approvestat'] == '1'){
			if( $this->model('Pr_model')->delete($prnum) > 0 ) {
				Flasher::setMessage('PR '. $prnum .' Berhasil','dihapus','success');
				header('location: '. BASEURL . '/pr');
				exit;			
			}else{
				Flasher::setMessage('Gagal menghapus PR,','','danger');
				header('location: '. BASEURL . '/pr');
				exit;	
			}
		}else{
			Flasher::setMessage('PR sudah di Approve Tidak Bisa di Hapus','','danger');
			header('location: '. BASEURL . '/pr');
			exit;	
		}
	}

	public function close($prnum){
		if( $this->model('Pr_model')->close($prnum) > 0 ) {
			Flasher::setMessage('PR '. $prnum ,' Closed','success');
			header('location: '. BASEURL . '/pr/outstandingpr');
			exit;			
		}else{
			Flasher::setMessage('error,','','danger');
			header('location: '. BASEURL . '/pr/outstandingpr');
			exit;	
		}
	}

	public function getmateriallist($whs){
		$data['data'] = $this->model('Material_model')->getMaterialWithStock($whs);
		echo json_encode($data);
	}

	public function export_excel($prnum){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['header']   = $this->model('Pr_model')->getPRheader($prnum);
		$data['pritem']   = $this->model('Pr_model')->getPRitem($prnum);
		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Purchase Requisition")
             ->setSubject("Purchase Requisition")
             ->setDescription("Purchase Requisition")
             ->setKeywords("Purchase Requisition");
		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
			'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
			'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
			'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
			'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		$style_aligment_left = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		
		$excel->setActiveSheetIndex(0)->setCellValue('A1', $data['setting']['company']);
		$excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai F1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$excel->setActiveSheetIndex(0)->setCellValue('A2', "Purchase Requisition"); 
		$excel->getActiveSheet()->mergeCells('A2:F2'); // Set Merge Cell pada kolom A1 sampai F1
		$excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		$excel->setActiveSheetIndex(0)->setCellValue('A4', "Created Date");
		$excel->setActiveSheetIndex(0)->setCellValue('B4', $data['header']['createdon']);
		// $excel->setActiveSheetIndex(0)->setCellValue('D4', "Project");
		// $excel->setActiveSheetIndex(0)->setCellValue('E4', $data['header']['namaproject']);
		
		$excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('D4')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('E4')->getFont()->setBold(TRUE);

		// $objDrawing = new PHPExcel_Worksheet_Drawing();
        // $objDrawing->setName('Logo ');
        // $objDrawing->setDescription('Logo ');
        // $objDrawing->setPath('./images/aws-logo.png');
        // $objDrawing->setResizeProportional(true);
        // $objDrawing->setWidth(100);
        // $objDrawing->setCoordinates('F1');
        // $objDrawing->setWorksheet($excel->getActiveSheet());

		$excel->setActiveSheetIndex(0)->setCellValue('A5', "Requirement Date");
		$excel->setActiveSheetIndex(0)->setCellValue('B5', $data['header']['prdate']);
		
		$excel->setActiveSheetIndex(0)->setCellValue('D4', "Warehouse");
		$excel->setActiveSheetIndex(0)->setCellValue('E4', $data['header']['whsname']);

		$excel->setActiveSheetIndex(0)->setCellValue('D5', "Created By");
		$excel->setActiveSheetIndex(0)->setCellValue('E5', $data['header']['crtby']);

		$excel->setActiveSheetIndex(0)->setCellValue('A6', "PR Number");
		$excel->setActiveSheetIndex(0)->setCellValue('B6', $data['header']['prnum'],PHPExcel_Cell_DataType::TYPE_STRING);

		$excel->getActiveSheet()->getStyle('B6')->applyFromArray($style_aligment_left);
		$excel->setActiveSheetIndex(0)->setCellValue('D6', "Note");
		$excel->setActiveSheetIndex(0)->setCellValue('E6', $data['header']['note']);

		
		$excel->getActiveSheet()->getStyle('A5')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('D5')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('E5')->getFont()->setBold(TRUE);

		$excel->getActiveSheet()->getStyle('A6')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('B6')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('D6')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('E6')->getFont()->setBold(TRUE);

		$excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(TRUE);
		$excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(TRUE);
		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A9', "NO"); // 
		$excel->setActiveSheetIndex(0)->setCellValue('B9', "Material"); // 
		$excel->setActiveSheetIndex(0)->setCellValue('C9', "Description"); // 
		$excel->setActiveSheetIndex(0)->setCellValue('D9', "Quantity"); // 
		$excel->setActiveSheetIndex(0)->setCellValue('E9', "Unit"); // 
		$excel->setActiveSheetIndex(0)->setCellValue('F9', "Remark"); // 
		
		$excel->getActiveSheet()->getStyle('A9')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B9')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C9')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D9')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E9')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F9')->applyFromArray($style_col);
		// Set height baris ke 1, 2 dan 3
		$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
		$excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
		$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
		// Buat query untuk menampilkan semua data siswa
		
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 10; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($data['pritem'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['material'],PHPExcel_Cell_DataType::TYPE_STRING);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['matdesc'],PHPExcel_Cell_DataType::TYPE_STRING);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['unit']);
			
			// Khusus untuk no telepon. kita set type kolom nya jadi STRING
			$excel->setActiveSheetIndex(0)->setCellValueExplicit('F'.$numrow, $h['remark'], PHPExcel_Cell_DataType::TYPE_STRING);
			
			// $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['jumlah']);
			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			
			$excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$excel->getActiveSheet()->getColumnDimension('A')->setWidth(15); // Set width kolom A
		$excel->getActiveSheet()->getColumnDimension('B')->setWidth(50); // Set width kolom B
		$excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Set width kolom C
		$excel->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Set width kolom D
		$excel->getActiveSheet()->getColumnDimension('E')->setWidth(55); // Set width kolom E
		$excel->getActiveSheet()->getColumnDimension('F')->setWidth(30); // Set width kolom F
		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data Purchase Requisition");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="PR-'. $prnum .'.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
    
}