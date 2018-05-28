<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laportresamsat extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	private $folder = 'laporan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
        $this->load->model('Transaksiesamsat');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'LAPORAN TRANSAKSI e-SAMSAT NASIONAL';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan transaksi e samsat nasional';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function actreport()
	{
		$param = $this->uri->uri_to_assoc(2);
		
		$postth = $this->Thanggaran->getDataByID($param['ta'])->row()->th_anggaran;
		$postbulan = $this->Bulan->getDataById($param['bln'])->bulan;
		$postjenis = $param['jenis'];

		$data['title'] = 'LAPORAN TRANSAKSI e-SAMSAT NASIONAL';
		//$data['subtitle'] = '';
		//$data['paraf_1'] = $this->Paraf->getParafPD('rd2d', $postinduk, 1 );
		//$data['paraf_2'] = $this->Paraf->getParafPD('rd2d', $postinduk, 3 );

		// REKAP
		if ( $postjenis== 0 ) {
			//$data['arrData'] = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi);
			//$data['arrDataLalu'] = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi);
			$result_laporan = $this->Transaksiesamsat->result_laporan();
			$data['resultdata'] = $result_laporan['now'];
			$data['before_resultdata'] = $result_laporan['before'];
			$data['header'] = 'REKAP TRANSAKSI PENERIMAAN PKB<br>e-SAMSAT NASIONAL';
			$data['subheader'] = 'Bulan : '. $postbulan .' '. $postth;
			$data['jenis_pungutan'] = $this->Transaksiesamsat->get_jenis_pungutan();
			$data['dinamisContent'] = $this->folder.'e samsat nasional/cetak rekap';
			$data['namafile'] = 'rekap laporan transaksi e-samsat nasional';
			
			//$data['namafile'] = 'rekap laporan transaksi e-samsat nasional';
		} 
		// JURNAL
		elseif ( $postbentuk == 1 ) {
			$data['header_periode'] = 'BULAN / TAHUN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$data['header_lokasi'] = $postinduk == $postlokasi ? 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postlokasi)->row()->lokasi) : 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postinduk)->row()->lokasi) .'('. $this->Lokasi->getDataById($postlokasi)->row()->lokasi .')';
			$data['header_lokasi2'] = $postinduk == $postlokasi ? 'SAMSAT INDUK + SAMKEL + PATEN + GERAI' : 'SAMSAT PEMBANTU';
			$data['postinduk'] = $postinduk;
			$data['arrData'] = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi, '012');
			$data['arrDataLalu'] = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi, '012');
			$data['dinamisContent'] = $this->folder.'pd 06 pd 07 pd 02/cetak pd 03';
			$data['namafile'] = 'jurnal laporan transaksi e-samsat nasional';
		}

		if ( $param['type'] == 'frame'){
            $this->load->view($this->Opsisite->getDataSite() ['cetak_template'], $data);    
        } elseif ( $param['type'] == 'pdf'){
            $this->pdf_rekap( $data );
        } elseif ( $param['type'] == 'excel'){
            $this->load->view($this->Opsisite->getDataSite() ['excel_template'], $data);
        }
	}

	public function pdf_rekap( $data )
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$resultdata = $data['resultdata'];
		$before_resultdata = $data['before_resultdata'];
		$this->load->library("Pdf");
		// create new PDF document
	    $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);   
	  
	    // set document information
	    $pdf->SetCreator(PDF_CREATOR);
	    $pdf->SetAuthor('FEBRIANTO ADI WIJAYA');
	    $pdf->SetTitle($data['namafile']);
	    //$pdf->SetSubject('TCPDF Tutorial');
	    //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');   
	  
	    // set default header data
	    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
	    $pdf->setFooterData(array(0,64,0), array(0,64,128)); 
	  
	    // set header and footer fonts
	    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	  
	    // set default monospaced font
	    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
	  
	    // set margins
	    $pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP - 15, PDF_MARGIN_RIGHT);
	    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);    
	  
	    // set auto page breaks
	    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); 
	  
	    // set image scale factor
	    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  
	  	  
	    // ---------------------------------------------------------    
	  
	    // set default font subsetting mode
	    $pdf->setFontSubsetting(false);

	    // set array for viewer preferences
		$preferences = array(
		    'HideToolbar' => true,
		    'HideMenubar' => true,
		    'HideWindowUI' => true,
		    'FitWindow' => true,
		    'CenterWindow' => true,
		    'DisplayDocTitle' => true,
		    'NonFullScreenPageMode' => 'UseNone', // UseNone, UseOutlines, UseThumbs, UseOC
		    'ViewArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
		    'ViewClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
		    'PrintArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
		    'PrintClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
		    'PrintScaling' => 'AppDefault', // None, AppDefault
		    'Duplex' => 'DuplexFlipLongEdge', // Simplex, DuplexFlipShortEdge, DuplexFlipLongEdge
		    'PickTrayByPDFSize' => true
		    //'PrintPageRange' => array(1,1,2,3),
		    //'NumCopies' => 2
		);

		// Check the example n. 60 for advanced page settings

		// set pdf viewer preferences
		$pdf->setViewerPreferences($preferences);
	  
	    // Set font
	    // dejavusans is a UTF-8 Unicode font, if you only need to
	    // print standard ASCII chars, you can use core fonts like
	    // helvetica or times to reduce file size.
	   	$pdf->SetFont('cid0jp', '', 10);  
	  
	    // Add a page
	    // This method has several options, check the source code documentation for more information.
	    $pdf->AddPage('P', 'F4'); 
	  
	    // set text shadow effect
	    //$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));    
	  	
	    // Set some content to print
	    $html = '
	    <style>
		    .h2 {
		        font-weight:bold;
		        font-size:14px;
				text-align : center;
		        color: #0B0A75;
		    }
			.h3 {
		        font-weight:bold;
		        font-size:12px;
				text-align : center;
		    }
		    .h4 {
		        font-weight:bold;
		        font-size:14px;
		        text-align : center;
		        color: #692718;
		    }
			table.tabel-data { border-collapse : collapse; width: 100%; table-layout:fixed; }
		    table.tabel-data tr th { border:1px solid #000; background: #DDDDDD; padding:0.5em 0.3em; font-weight:bold; text-align:center; }
		    table.tabel-data tr td { border:1px solid #000; padding:0.5em 0.3em; vertical-align: top; word-wrap:break-word; }
		</style>
		<span class="h4">'. $data['header'] .'</span><br>
		<span class="h4">'. $data['subheader'] .'</span>
<br>
<br>
<small>PENERIMAAN YANG TELAH DITERIMA / TERCATAT DALAM RKUD</small>
<br>
<table style="font-size: 10px;" cellpadding="5" width="100%" class="tabel-data">
<thead style="font-weight: bold; text-align: center;">
<tr bgcolor="#DDDDDD">
<th rowspan="3" width="50px">NO.</th>
<th rowspan="3" width="150px">JENIS PUNGUTAN</th>
<th colspan="3" width="340px">PENETAPAN PKB</th>
<th rowspan="2" width="120px">JUMLAH</th>
</tr>
<tr bgcolor="#DDDDDD">
<th rowspan="2" width="100px">OBYEK</th>
<th width="120px">POKOK</th>
<th width="120px">SANKSI</th>
</tr>
<tr bgcolor="#DDDDDD">
<th width="120px">Rp</th>
<th width="120px">Rp</th>
<th width="120px">Rp</th>
</tr>
</thead>
<tbody>';
$no = 1;
$pokok = 0;
$denda = 0;
$jumlah = 0;
$totoby = 0;
$totpokok = 0;
$totdenda = 0;
$totjumlah = 0;

foreach ( $data['jenis_pungutan'] as $jenis )
{
	$oby = empty($resultdata[$jenis->id_jenis]['oby']) ? 0 : $resultdata[$jenis->id_jenis]['oby'];
	$pokok = empty($resultdata[$jenis->id_jenis]['pokok']) ? 0 : $resultdata[$jenis->id_jenis]['pokok'];
	$denda = empty($resultdata[$jenis->id_jenis]['denda']) ? 0 : $resultdata[$jenis->id_jenis]['denda'];
	$jumlah = empty($resultdata[$jenis->id_jenis]['jumlah']) ? 0 : $resultdata[$jenis->id_jenis]['jumlah'];
	$html .= '
	<tr>
	<td align="right" width="50px">'. $no .'. </td>
	<td align="center" width="150px">'. $jenis->id_jenis .'</td>
	<td align="center" width="100px">'. number_format($oby, 0) .'</td>
	<td align="right" width="120px">'. number_format($pokok, 0) .'</td>
	<td align="right" width="120px">'. number_format($denda, 0) .'</td>
	<td align="right" width="120px">'. number_format($jumlah, 0) .'</td>
	</tr>';
	$no++;
	$totoby += $oby;
	$totpokok += $pokok;
	$totdenda += $denda;
	$totjumlah += $jumlah;
}	
$html .= '
</tbody>
<tfoot>
<tr>
<td colspan="2"><b>JUMLAH BULAN / PERIODE INI</b></td>
<td align="center"><b>'. number_format($totoby, 0) .'</b></td>
<td align="right"><b>'. number_format($totpokok, 0) .'</b></td>
<td align="right"><b>'. number_format($totdenda, 0) .'</b></td>
<td align="right"><b>'. number_format($totjumlah, 0) .'</b></td>
</tr>
<tr>
<td colspan="2"><b>JUMLAH S/D BULAN /PERIODE LALU</b></td>
<td align="center"><b>'. number_format($before_resultdata->oby, 0) .'</b></td>
<td align="right"><b>'. number_format($before_resultdata->pokok, 0) .'</b></td>
<td align="right"><b>'. number_format($before_resultdata->denda, 0) .'</b></td>
<td align="right"><b>'. number_format($before_resultdata->jumlah, 0) .'</b></td>
</tr>
<tr>
<td colspan="2"><b>JUMLAH S/D BULAN /PERIODE INI</b></td>
<td align="center"><b>'. number_format($totoby + $before_resultdata->oby, 0) .'</b></td>
<td align="right"><b>'. number_format($totpokok + $before_resultdata->pokok, 0) .'</b></td>
<td align="right"><b>'. number_format($totdenda + $before_resultdata->denda, 0) .'</b></td>
<td align="right"><b>'. number_format($totjumlah + $before_resultdata->jumlah, 0) .'</b></td>
</tr>
</tfoot>
</table>';

	  
	    // Print text using writeHTMLCell()
	    //$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true); 
	    $pdf->writeHTML($html, true, false, true, false, ''); 
	  
	    // ---------------------------------------------------------    
	  
	    // Close and output PDF document
	    // This method has several options, check the source code documentation for more information.
	    $pdf->Output( time() . $data['namafile'] .'.pdf', 'I');  
	}

	private function rekap_excel($data)
	{
		$resultdata = $data['resultdata'];
		$before_resultdata = $data['before_resultdata'];

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$getarr = $this->Laporan->get_rekap();

		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');

		/*$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'TCPDF';
		$rendererLibraryPath = dirname(__FILE__) . '/../libraries/' . $rendererLibrary;*/

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("app apt wafe")
		                 ->setDescription("description");

		/*if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values at the top of this script as appropriate for your directory structure'
			);
		}*/

		// Assign cell values
		$objPHPExcel->getActiveSheet()->setTitle('app apt wafe');
 		$objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
        $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object

        $objget->setCellValueByColumnAndRow(0, 1, $data['header']);
    	$objget->mergeCells('A1:F1');
    	$objget->setCellValueByColumnAndRow(0, 2, $data['subheader']);
    	$objget->mergeCells('A2:F2');

    	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);

    	$style = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
	        'font' => array(
	        	'bold' => true,
	        	//'color' => array( 'rgb' => '0B0A75'),
	        	'size' => 14
	        	)
	    );
	    $objget->getStyle("A1:F2")->applyFromArray($style);

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'No.');
	    $objget->mergeCells('A4:A6');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'JENIS PUNGUTAN');
	    $objget->mergeCells('B4:B6');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'PENETAPAN PKB');
	    $objget->mergeCells('C4:E4');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'JUMLAH');
	    $objget->mergeCells('F4:F5');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 5, 'OBYEK');
	    $objget->mergeCells('C5:C6');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 5, 'POKOK');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 5, 'SANKSI');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 6, 'Rp');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 6, 'Rp');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 6, 'Rp');

        $objPHPExcel->getActiveSheet()
			->getStyle('A9:L11')
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'alignment' => array(
		                'horizontal' => 'center' ,
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'bold' => true
			        	)
			    )
		);

		$no = 1;
		$pokok = 0;
		$denda = 0;
		$jumlah = 0;
		$totoby = 0;
		$totpokok = 0;
		$totdenda = 0;
		$totjumlah = 0;
		$col_td = 0;
	    $row_td = 12;
		foreach ( $data['jenis_pungutan'] as $jenis )
		{
			$oby = empty($resultdata[$jenis->id_jenis]['oby']) ? 0 : $resultdata[$jenis->id_jenis]['oby'];
			$pokok = empty($resultdata[$jenis->id_jenis]['pokok']) ? 0 : $resultdata[$jenis->id_jenis]['pokok'];
			$denda = empty($resultdata[$jenis->id_jenis]['denda']) ? 0 : $resultdata[$jenis->id_jenis]['denda'];
			$jumlah = empty($resultdata[$jenis->id_jenis]['jumlah']) ? 0 : $resultdata[$jenis->id_jenis]['jumlah'];

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td, $row_td, $no);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 1, $row_td, $jenis->id_jenis);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 2, $row_td, $oby);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 3, $row_td, $pokok);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 4, $row_td, $denda);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 1, $row_td, $jumlah);

			$no++;
			$totoby += $oby;
			$totpokok += $pokok;
			$totdenda += $denda;
			$totjumlah += $jumlah;
			$row_td++;
		}

	    /*$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_td, 'J U M L A H');
	    $objget->mergeCells('A'. $row_td .':B'. $row_td);

	    $col_tf = 2;
	    $row_tf = $row_td;
	    $total_all_oby = 0;
        $total_all_pkb = 0;
	    foreach( $arr['getstatus']->result() as $f )
        {
            $total_oby = count($arr_subtotal[$f->id_sts_validasi]['oby']) == 0 ? 0 : array_sum($arr_subtotal[$f->id_sts_validasi]['oby']);
            $total_pkb = count($arr_subtotal[$f->id_sts_validasi]['pkb']) == 0 ? 0 : array_sum($arr_subtotal[$f->id_sts_validasi]['pkb']);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_tf, $row_tf, $total_oby);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_tf + 1, $row_tf, $total_pkb);
            $col_tf += 2;

            $total_all_oby += $total_oby;
            $total_all_pkb += $total_pkb;
        }
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_tf, $row_tf, $total_all_oby);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_tf + 1, $row_tf, $total_all_pkb);

        $objPHPExcel->getActiveSheet()->getStyle('C6:L'. $row_tf)->getNumberFormat()->setFormatCode('#,##0');
	    $objPHPExcel->getActiveSheet()
			->getStyle('A'. $row_tf .':B'. $row_tf)
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'alignment' => array(
		                'horizontal' => 'center' ,
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'bold' => true
			        	)
			    )
		);
	    $objPHPExcel->getActiveSheet()
				->getStyle('A'. $row_tf .':L'. $row_tf)
				->applyFromArray(
				    array(
				        'font' => array(
				        	'bold' => true
				        	),
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => 'DDDDDD')
				        )
				    )
			);
		$objPHPExcel->getActiveSheet()
			->getStyle('K11:L'. $row_tf)
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'font' => array(
			        	'bold' => true
			        	)
			    )
		);
		//Border All
		$objPHPExcel->getActiveSheet()
			->getStyle('A9:L'. $row_tf)
			->applyFromArray(
			    array(
			        'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN
				          )
				      )
			    )
		);

		$getparaf = $arr['getparaf'];	
		$row_ttd = $row_tf+2;
		if( $getparaf != '' && !empty($getparaf) ){
			$objPHPExcel->getActiveSheet()->setCellValue('A'. $row_ttd, 'Mengetahui,');
			$objget->mergeCells('A'. $row_ttd .':D'. $row_ttd);
			//$objPHPExcel->getActiveSheet()->setCellValue('J'. $row_ttd)->setValue();
			//$objget->mergeCells('J'. $row_ttd .':L'. $row_ttd);
			$row_baris_1 = $row_ttd+1;
			$objPHPExcel->getActiveSheet()->setCellValue('A'. $row_baris_1, 'Kepala UPPD '. $arr['getlokasi']);
			$objget->mergeCells('A'. $row_baris_1 .':D'. $row_baris_1);
			$objPHPExcel->getActiveSheet()->setCellValue('J'. $row_baris_1, 'Kepala Seksi RPP');
			$objget->mergeCells('J'. $row_baris_1 .':L'. $row_baris_1);
			
			$row_baris_2 = $row_ttd+7;
			$objPHPExcel->getActiveSheet()->setCellValue('A'. $row_baris_2, $getparaf['paraf1']['nama']);
			$objPHPExcel->getActiveSheet()->getStyle('A'. $row_baris_2)->getFont()->setUnderline(true);
			$objget->mergeCells('A'. $row_baris_2 .':D'. $row_baris_2);
			$objPHPExcel->getActiveSheet()->setCellValue('J'. $row_baris_2, $getparaf['paraf2']['nama']);
			$objPHPExcel->getActiveSheet()->getStyle('J'. $row_baris_2)->getFont()->setUnderline(true);
			$objget->mergeCells('J'. $row_baris_2 .':L'. $row_baris_2);
			
			$row_baris_3 = $row_ttd+8;
			$objPHPExcel->getActiveSheet()->setCellValue('A'. $row_baris_3, $getparaf['paraf1']['nip']);
			$objget->mergeCells('A'. $row_baris_3 .':D'. $row_baris_3);
			$objPHPExcel->getActiveSheet()->setCellValue('J'. $row_baris_3, $getparaf['paraf2']['nip']);
			$objget->mergeCells('J'. $row_baris_3 .':L'. $row_baris_3);

			$objPHPExcel->getActiveSheet()
				->getStyle('A'. $row_ttd .':L'. $row_baris_3)
				->applyFromArray(
					array(
						'alignment' => array(
							'horizontal' => 'center' ,
							'vertical' => 'center'
						)
					)
			);
		}*/
		// Save it as an excel 2003 file
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //Nama File
        header('Content-Disposition: attachment;filename="'. $arr['namafile'] .'.xlsx"');

        //Download
        $objWriter->save("php://output");
	}
}
