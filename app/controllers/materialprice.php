<?php

class Materialprice extends Controller{

	public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }

	public function index(){
		$check = $this->model('Home_model')->checkUsermenu('materialprice','Read');
        if ($check){
			$data['title']    = 'Material Price History';
			$data['menu']     = 'Material Price History';

            // $data['department']     = $this->model('Department_model')->getList();
            // $data['departmentuser'] = $this->model('Department_model')->getByByUser();

			$this->view('templates/header_a', $data);
			$this->view('materialprice/report01', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        }  
	}

    public function display($params){
        $url = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
        // $month    = $params['month'];
        $year     = $params['year'];
        $material = $params['material'];

        $check = $this->model('Home_model')->checkUsermenu('materialprice','Read');
        if ($check){
			$data['title']    = 'Material Price History';
			$data['menu']     = 'Material Price History';

            $data['rdata']    = $this->model('Materialprice_model')->getMaterialPriceHistory($year, $material);
            // $data['departmentuser'] = $this->model('Department_model')->getByByUser();
			// $data['strdate'] = $strdate;
			// $data['enddate'] = $enddate;
			$data['year']  = $year;
			$data['material']  = $material;

			$this->view('templates/header_a', $data);
			$this->view('materialprice/report01view', $data);
			$this->view('templates/footer_a');
		}else{
            $this->view('templates/401');
        } 
    }

	public function exportdata($params){
		$url = parse_url($_SERVER['REQUEST_URI']);
        $data = parse_str($url['query'], $params);
        // $month    = $params['month'];
        $year     = $params['year'];
        $material = $params['material'];

		$data['expdata']    = $this->model('Materialprice_model')->getMaterialPriceHistory($year, $material);

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

		$excel->setActiveSheetIndex(0)->setCellValue('A1', 'Year '. $year);
		$excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai F1
		$excel->setActiveSheetIndex(0)->setCellValue('E1', 'Cost');
		$excel->getActiveSheet()->mergeCells('E1:P1'); // Set Merge Cell pada kolom A1 sampai F1

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A2', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B2', "Supplier");
		$excel->setActiveSheetIndex(0)->setCellValue('C2', "Material");
		$excel->setActiveSheetIndex(0)->setCellValue('D2', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('E2', "January");
		$excel->setActiveSheetIndex(0)->setCellValue('F2', "February");
		$excel->setActiveSheetIndex(0)->setCellValue('G2', "March");
		$excel->setActiveSheetIndex(0)->setCellValue('H2', "April");
		$excel->setActiveSheetIndex(0)->setCellValue('I2', "May");
		$excel->setActiveSheetIndex(0)->setCellValue('J2', "June");
		$excel->setActiveSheetIndex(0)->setCellValue('K2', "July");
		$excel->setActiveSheetIndex(0)->setCellValue('L2', "August");
		$excel->setActiveSheetIndex(0)->setCellValue('M2', "September");
		$excel->setActiveSheetIndex(0)->setCellValue('N2', "October");
		$excel->setActiveSheetIndex(0)->setCellValue('O2', "November");
		$excel->setActiveSheetIndex(0)->setCellValue('P2', "December");

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E1:P1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('A2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G2')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('I2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('J2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('K2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('L2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('M2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('N2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('O2')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('P2')->applyFromArray($style_col);
		// $excel->getActiveSheet()->getStyle('Q2')->applyFromArray($style_col);
		
		$no = 1; 
		$numrow = 3;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['supplier_name']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['material']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['matdesc']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['Jan']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['Feb']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['Mar']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['Apr']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $h['May']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $h['Jun']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $h['Jul']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $h['Aug']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $h['Sep']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $h['Oct']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $h['Nov']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $h['Dec']);

			
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
			$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_row);	
			$excel->getActiveSheet()->getStyle('O'.$numrow)->applyFromArray($style_row);	
			$excel->getActiveSheet()->getStyle('P'.$numrow)->applyFromArray($style_row);			
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Material Price Purchase History");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Material-Price-Purchase-History.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
}