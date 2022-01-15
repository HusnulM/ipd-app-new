<?php

class Reportgrpo extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

    public function index(){
		$check = $this->model('Home_model')->checkUsermenu('reportgrpo','Read');
        if ($check){
			$data['title']    = 'Report Receipt Purchase Order';
			$data['menu']     = 'Report Receipt Purchase Order';

            $data['department']     = $this->model('Department_model')->getList();
            $data['departmentuser'] = $this->model('Department_model')->getByByUser();

			$this->view('templates/header_a', $data);
			$this->view('receiptpo/index', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }  
	}

    public function display($strdate, $enddate){
        $check = $this->model('Home_model')->checkUsermenu('reportgrpo','Read');
        if ($check){
			$data['title']    = 'Report Receipt Purchase Order';
			$data['menu']     = 'Report Receipt Purchase Order';

            $data['grdata']   = $this->model('Reportgrpo_model')->getGRPoData($strdate, $enddate);
			$data['strdate']  = $strdate;
			$data['enddate']  = $enddate;

			$this->view('templates/header_a', $data);
			$this->view('receiptpo/display', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }

	public function exportreportgrpo($strdate, $enddate){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Reportgrpo_model')->getGRPoData($strdate, $enddate);
		// echo json_encode($data['expdata']);
		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Report Purchase Oder")
             ->setSubject("Report Purchase Oder")
             ->setDescription("Report Purchase Oder")
             ->setKeywords("Report Purchase Oder");
		
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

		$style_cell_bgcolor_red = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			)
		);

		$style_cell_bgcolor_green = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '80FF00')
			)
		);

		$style_cell_bgcolor_yellow = array(
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'F5E131')
			)
		);		

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Receipt Number");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Receipt Date");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Receipt Note");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Supplier");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Material");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Receipt Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Unit Price");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Total Price");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "PO Number");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "PO Item");
		// $excel->setActiveSheetIndex(0)->setCellValue('N1', "Status");


		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('I1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('J1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('K1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('L1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('M1')->applyFromArray($style_col);
		// $excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);
		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['movement_number']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['movement_date']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['movement_note']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['supplier_name']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['material']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['matdesc']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $h['unit']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $h['unit_price']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $h['unit_price']*$h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $h['ponum']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $h['poitem']);

			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('M'.$numrow)->applyFromArray($style_row);			
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Receipt Purchase Order");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Receipt-Purchase-Order.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
}