<?php

class Exportdata extends Controller{

    public function __construct(){
		if( isset($_SESSION['usr']) ){
			
		}else{
			header('location:'. BASEURL);
		}
    }
	public function exportransaction($strdate, $enddate){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Report_model')->rtransaction($strdate, $enddate);   

		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Trasaction Data")
             ->setSubject("Trasaction Data")
             ->setDescription("Trasaction Data")
             ->setKeywords("Trasaction Data");
		
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PROD DATE");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "TEN NO");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "MODEL");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "PART CODE");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "LOT / SERIAL NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "AOI SMT-BOTTOM (1st)"); 
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "AOI SMT-TOP (2nd)");
        $excel->setActiveSheetIndex(0)->setCellValue('I1', "SMT SI");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "ICT");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "QPIT");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "AOI HW-TOP");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "AOI HW-BOTTOM");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "FVI");
		$excel->setActiveSheetIndex(0)->setCellValue('O1', "QQA");
		$excel->setActiveSheetIndex(0)->setCellValue('P1', "Error Process");
		$excel->setActiveSheetIndex(0)->setCellValue('Q1', "DEFECT NAME");
		$excel->setActiveSheetIndex(0)->setCellValue('R1', "LOCATION");
		$excel->setActiveSheetIndex(0)->setCellValue('S1', "CAUSE");
		$excel->setActiveSheetIndex(0)->setCellValue('T1', "ACTION");
		$excel->setActiveSheetIndex(0)->setCellValue('U1', "REPAIR-DEFECT");
		$excel->setActiveSheetIndex(0)->setCellValue('V1', "REPAIR-LOCATION");
		$excel->setActiveSheetIndex(0)->setCellValue('X1', "REPAIR-ACTION");
		$excel->setActiveSheetIndex(0)->setCellValue('Y1', "AFTER REPAIR-ICT");
		$excel->setActiveSheetIndex(0)->setCellValue('Z1', "AFTER REPAIR-QPIT");
		$excel->setActiveSheetIndex(0)->setCellValue('AA1', "AFTER REPAIR-AOI TOP");
		$excel->setActiveSheetIndex(0)->setCellValue('AB1', "AFTER REPAIR-BOT");
		$excel->setActiveSheetIndex(0)->setCellValue('AC1', "AFTER REPAIR-FVI");
		$excel->setActiveSheetIndex(0)->setCellValue('AD1', "QQA");
		$excel->setActiveSheetIndex(0)->setCellValue('AE1', "QQA REMARK");												


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
		$excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('O1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('P1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('Q1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('R1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('S1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('T1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('U1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('V1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('X1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('W1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('Y1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('Z1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('AA1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('AB1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('AC1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('AD1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('AE1')->applyFromArray($style_col);
        // $excel->getActiveSheet()->getStyle('H4')->applyFromArray($style_col);

		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['createdon']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, '');
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['partmodel']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['partnumber']);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['serial_no']);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['process1']);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['process2']);
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $h['process3']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $h['process4']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $h['process5']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $h['process6']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $h['process7']);
			$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, $h['process8']);
			$excel->setActiveSheetIndex(0)->setCellValue('O'.$numrow, $h['process9']);
			$excel->setActiveSheetIndex(0)->setCellValue('P'.$numrow, $h['error_process']);
			$excel->setActiveSheetIndex(0)->setCellValue('Q'.$numrow, $h['defect_name']);
			$excel->setActiveSheetIndex(0)->setCellValue('R'.$numrow, $h['location']);
			$excel->setActiveSheetIndex(0)->setCellValue('S'.$numrow, $h['cause']);
			$excel->setActiveSheetIndex(0)->setCellValue('T'.$numrow, $h['action']);
			$excel->setActiveSheetIndex(0)->setCellValue('U'.$numrow, $h['repair_defect']);
			$excel->setActiveSheetIndex(0)->setCellValue('V'.$numrow, $h['repair_location']);
			$excel->setActiveSheetIndex(0)->setCellValue('X'.$numrow, $h['repair_action']);
			$excel->setActiveSheetIndex(0)->setCellValue('Y'.$numrow, $h['repair1']);
			$excel->setActiveSheetIndex(0)->setCellValue('Z'.$numrow, $h['repair2']);
			$excel->setActiveSheetIndex(0)->setCellValue('AA'.$numrow, $h['repair3']);
			$excel->setActiveSheetIndex(0)->setCellValue('AB'.$numrow, $h['repair4']);
			$excel->setActiveSheetIndex(0)->setCellValue('AC'.$numrow, $h['repair5']);
			$excel->setActiveSheetIndex(0)->setCellValue('AD'.$numrow, $h['repair6']);
			$excel->setActiveSheetIndex(0)->setCellValue('AE'.$numrow, $h['remark']);
			
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

			$excel->getActiveSheet()->getStyle('Q'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('R'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('S'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('T'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('U'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('V'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('X'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('W'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('Y'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('Z'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AA'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AB'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AC'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AD'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('AE'.$numrow)->applyFromArray($style_row);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Transaction Report");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TransactionReport.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
		
	}

	public function exportstocks(){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Inventory_model')->getStock();  

		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Stock Report")
             ->setSubject("Stock Report")
             ->setDescription("Stock Report")
             ->setKeywords("Stock Report");
		
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "MATERIAL");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DESCRIPTION");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "SUPPLIER");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "WAREHOUSE");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "QUANTITY");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "UNIT");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "VALUE");


		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G1')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H1')->applyFromArray($style_col);

		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['material']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['matdesc']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['supplier']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['warehousename']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['matunit']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['inventory_value']);
			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Stock Report");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="stock-report.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function exportissuedstocks($strdate, $enddate){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Inventory_model')->getIssuingStock($strdate, $enddate); 

		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Issued Stock Report")
             ->setSubject("Issued Stock Report")
             ->setDescription("Issued Stock Report")
             ->setKeywords("Issued Stock Report");
		
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "MATERIAL");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "DESCRIPTION");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "DEPARTMENT");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "WAREHOUSE");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "ISSUING DATE");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "QUANTITY");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "UNIT");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "VALUE");


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
		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['material']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['matdesc']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['department']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['warehousename']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['movement_date']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['unit']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $h['issuing_value']);
			
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
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Issued Stock Report");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Issued-stock-report.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function exportbudgetissued($deptid, $month, $year){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Report_model')->getBudgetHistory($deptid, $month, $year); 

		$excel = new PHPExcel();
		$excel->getProperties()->setCreator($_SESSION['usr']['user'])
             ->setLastModifiedBy($_SESSION['usr']['user'])
             ->setTitle("Budget Issuing Report")
             ->setSubject("Budget Issuing Report")
             ->setDescription("Budget Issuing Report")
             ->setKeywords("Budget Issuing Report");
		
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

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A1', "NO"); 
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "Issuing Date");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "Department");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Reference");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "Allocation");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Issuing Amount");


		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E1')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F1')->applyFromArray($style_col);
		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['createdon']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['deptname']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['refnum']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['description']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['amount']);
			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Budget Issuing Report");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Budget-Issuing-Report.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}

	public function exportreportpo($strdate, $enddate, $openqty){
		$data['setting']  = $this->model('Setting_model')->getgensetting();
		$data['expdata']  = $this->model('Reportpo_model')->getExportPO($strdate, $enddate, $openqty);  
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
		$excel->setActiveSheetIndex(0)->setCellValue('B1', "PO Number");
		$excel->setActiveSheetIndex(0)->setCellValue('C1', "PO Item");
		$excel->setActiveSheetIndex(0)->setCellValue('D1', "Supplier");
		$excel->setActiveSheetIndex(0)->setCellValue('E1', "PO Date");
		$excel->setActiveSheetIndex(0)->setCellValue('F1', "Material");
		$excel->setActiveSheetIndex(0)->setCellValue('G1', "Description");
		$excel->setActiveSheetIndex(0)->setCellValue('H1', "Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('I1', "Receipt Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('J1', "Open Quantity");
		$excel->setActiveSheetIndex(0)->setCellValue('K1', "Unit");
		$excel->setActiveSheetIndex(0)->setCellValue('L1', "Unit Price");
		$excel->setActiveSheetIndex(0)->setCellValue('M1', "Total Price");
		$excel->setActiveSheetIndex(0)->setCellValue('N1', "Status");


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
		$excel->getActiveSheet()->getStyle('N1')->applyFromArray($style_col);
		
		$no = 1; 
		$numrow = 2;
		foreach($data['expdata'] as $i => $h){ // Ambil semua data dari hasil eksekusi $sql
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $h['ponum']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $h['poitem']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $h['supplier_name']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $h['podat']);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $h['material']);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $h['matdesc']);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $h['quantity']);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $h['grqty']);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $h['quantity']-$h['grqty']);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $h['unit']);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $h['price']);
			$excel->setActiveSheetIndex(0)->setCellValue('M'.$numrow, $h['quantity']*$h['price']);

			if($h['approvestat'] == 1 && $h['final_approve'] === 'N' && $h['is_rejected'] === 'N'){
				$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, 'Open');
			}elseif($h['is_rejected'] === 'Y'){
				$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, 'Rejected');
			}elseif($h['final_approve'] === 'Y'){
				$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, 'Approved');
			}else{
				$excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, 'Partial Approved');
			}
			
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

			if($h['approvestat'] == 1 && $h['final_approve'] === 'N' && $h['is_rejected'] === 'N'){
				$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_cell_bgcolor_yellow);
			}elseif($h['is_rejected'] === 'Y'){
				$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_cell_bgcolor_red);
			}elseif($h['final_approve'] === 'Y'){
				// $excel->setActiveSheetIndex(0)->setCellValue('N'.$numrow, 'Approved');
				$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_cell_bgcolor_green);
			}else{
				$excel->getActiveSheet()->getStyle('N'.$numrow)->applyFromArray($style_cell_bgcolor_yellow);
			}
			
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Purchase Order");
		$excel->setActiveSheetIndex(0);
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="ReportPurchaseOrder.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$write->save('php://output');
	}
}