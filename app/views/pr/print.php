<?php

// memanggil library FPDF
require('fpdf/fpdf.php');
// intance object dan memberikan pengaturan halaman PDF
$pdf = new FPDF('P','mm','A4');
// membuat halaman baru
$pdf->AddPage();
// setting jenis font yang akan digunakan
$pdf->SetFont('Arial','B',16);
// mencetak string 

$pdf->Cell(190,7, $data['setting']['company'] ,0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,7,'PURCHASE REQUISITION',0,1,'C');
 
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,7,'',0,1);    
    $pdf->Cell(35,5,'Created Date',0);
    $pdf->Cell(4,5,':',0);
    $pdf->Cell(72,5, $data['header']['createdon'],0);

    $pdf->Cell(25,5,'Project',0);
    $pdf->Cell(4,5,':',0);
    $pdf->MultiCell(40,3,$data['header']['namaproject'],0);

    $pdf->Cell(10,7,'',0,1);
    $pdf->Cell(35,5,'Requirement Date',0);
    $pdf->Cell(4,5,':',0);
    $pdf->Cell(72,5,$data['header']['tglrequest'],0);

    $pdf->Cell(25,5,'Created By',0);
    $pdf->Cell(4,5,':',0);
    $pdf->Cell(72,5,$data['header']['namauser'],0);

    $pdf->Cell(10,7,'',0,1);
    $pdf->Cell(35,5,'PR Number',0);
    $pdf->Cell(4,5,':',0);
    $pdf->Cell(72,5,$data['header']['prnum'],0);

    $pdf->Cell(25,5,'Header Text',0);
    $pdf->Cell(4,5,':',0);
    $pdf->MultiCell(40,5,$data['header']['note'],0);

    if($data['header']['status'] === '2'){
        $pdf->Cell(10,7,'',0,1);
        $pdf->Cell(35,5,'Approved By',0);
        $pdf->Cell(4,5,':',0);
        $pdf->Cell(72,5,$data['header']['approveby'],0);
    }else if($data['header']['status'] === '3'){
        $pdf->Cell(10,7,'',0,1);
        $pdf->Cell(35,5,'Rejected By',0);
        $pdf->Cell(4,5,':',0);
        $pdf->Cell(72,5,'Admin',0);
    }

$pdf->Cell(10,7,'',0,1);
$pdf->SetFont('Arial','',8);
$pdf->Cell(8,6,'No',1,0);
$pdf->Cell(75,6,'Item Name',1,0);
$pdf->Cell(15,6,'Quantity',1,0,'R');
$pdf->Cell(10,6,'Unit',1,0);
// $pdf->Cell(20,6,'Price',1,0,'R');
// $pdf->Cell(22,6,'Total Price',1,0,'R');
$pdf->Cell(77,6,'Item Remark',1,1);

    foreach($data['pritem'] as $row) :
        $cellWidth=75; //lebar sel
        $cellHeight=6; //tinggi sel satu baris normal
        
        //periksa apakah teksnya melibihi kolom?
        if($pdf->GetStringWidth($row['remark']) < $cellWidth){
            //jika tidak, maka tidak melakukan apa-apa
            $line=1;
        }else{
            //jika ya, maka hitung ketinggian yang dibutuhkan untuk sel akan dirapikan
            //dengan memisahkan teks agar sesuai dengan lebar sel
            //lalu hitung berapa banyak baris yang dibutuhkan agar teks pas dengan sel
            
            $textLength=strlen($row['remark']);	//total panjang teks
            $errMargin=5;		//margin kesalahan lebar sel, untuk jaga-jaga
            $startChar=0;		//posisi awal karakter untuk setiap baris
            $maxChar=0;			//karakter maksimum dalam satu baris, yang akan ditambahkan nanti
            $textArray=array();	//untuk menampung data untuk setiap baris
            $tmpString="";		//untuk menampung teks untuk setiap baris (sementara)
            
            while($startChar < $textLength){ //perulangan sampai akhir teks
                //perulangan sampai karakter maksimum tercapai
                while( 
                $pdf->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
                ($startChar+$maxChar) < $textLength ) {
                    $maxChar++;
                    $tmpString=substr($row['remark'],$startChar,$maxChar);
                }
                //pindahkan ke baris berikutnya
                $startChar=$startChar+$maxChar;
                //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                array_push($textArray,$tmpString);
                //reset variabel penampung
                $maxChar=0;
                $tmpString='';
                
            }
            //dapatkan jumlah baris
            $line=count($textArray);
        }
        
        //tulis selnya
        $pdf->SetFillColor(255,255,255);
        // $pdf->Cell(1,($line * $cellHeight),$no++,1,0,'C',true); //sesuaikan ketinggian dengan jumlah garis
        $pdf->Cell(8, ($line * $cellHeight),$row['pritem'],1,0);       

        //memanfaatkan MultiCell sebagai ganti Cell
        //atur posisi xy untuk sel berikutnya menjadi di sebelahnya.
        //ingat posisi x dan y sebelum menulis MultiCell
        $xPos=$pdf->GetX();
        $yPos=$pdf->GetY();
        $pdf->MultiCell($cellWidth,$cellHeight,$row['namabrg'],1);
        
        //kembalikan posisi untuk sel berikutnya di samping MultiCell 
        //dan offset x dengan lebar MultiCell
        $pdf->SetXY($xPos + $cellWidth , $yPos);
        $pdf->Cell(15,($line * $cellHeight),$row['jumlah'],1,0,'R');
        $pdf->Cell(10,($line * $cellHeight),$row['satuan'],1,0);
        
        // $pdf->Cell(20,($line * $cellHeight), number_format($row['harga'],0,",","."),1,0,'R');
        // $pdf->Cell(22,($line * $cellHeight),number_format($row['harga'] * $row['jumlah'],0,",","."),1,0,'R');
        $pdf->Cell(77,($line * $cellHeight),$row['remark'],1,1);
    endforeach; 

$pdf->Output();
?>