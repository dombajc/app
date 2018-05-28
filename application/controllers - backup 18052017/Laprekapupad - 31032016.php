<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laprekapupad extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekapitulasi per upad';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postTriwulan = $param['tw'];
		$postAnggaran = $param['ta'];
		$postLokasi = $param['lok'];

		$data['title'] = 'LAPORAN REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI';
		$data['subtitle'] = '';
		$data['postLokasi'] = $postLokasi;

		if ( $postLokasi == '05' ) {
			$data['dinamisContent'] = $this->folder.'cetak laporan rekapitulasi per upad - khusus pusat';
			$data['postTriwulan'] = $postTriwulan;
			$data['arrPegawai'] = $this->getArrPegawaiPusatByFilter($postLokasi, $postTriwulan, $postAnggaran);
			$data['arrTrx'] = $this->getArrTransaksiPusat($postLokasi, $postAnggaran);
			$data['getNama'] = 'KANTOR PUSAT';
			$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
			$data['getTriwulan'] = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;
			$data['namafile'] = 'LAPORAN REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI PUSAT';
		} else {
			$data['dinamisContent'] = $this->folder.'cetak laporan rekapitulasi per upad';
			$data['postTriwulan'] = $postTriwulan;
			$data['arrPegawai'] = $this->getArrPegawaiByFilter($postLokasi, $postTriwulan, $postAnggaran);
			$data['arrTrx'] = $this->getArrTransaksi($postLokasi, $postAnggaran);
			$data['getNama'] = $postLokasi == '99' ? '-- Keseluruhan --' : $this->Lokasi->getDataByIdLokasi($postLokasi)->row()->lokasi;
			$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
			$data['getTriwulan'] = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;
			$data['namafile'] = 'LAPORAN REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI';
		}

		

		if ( $param['type'] == 'frame'){
            $this->load->view($this->Opsisite->getDataSite() ['cetak_template'], $data);    
        } elseif ( $param['type'] == 'cetak'){
            //$this->load->view($this->dir_cetak . 'fullbasic', $data);
        } elseif ( $param['type'] == 'excel'){
            $this->load->view($this->Opsisite->getDataSite() ['excel_template'], $data);
        }
	}

	public function get_pdf()
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		$param = $this->uri->uri_to_assoc(2);
		$postTriwulan = $param['tw'];
		$postAnggaran = $param['ta'];
		$postLokasi = $param['lok'];
		
		$getTahun = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$getTriwulan = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('LAPORAN REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'F4');

		if ( $postLokasi == '05' ) {
			$arrPegawai = $this->getArrPegawaiPusatByFilter($postLokasi, $postTriwulan, $postAnggaran);
			$arrTrx = $this->getArrTransaksiPusat($postLokasi, $postAnggaran);
			$getNama = 'KANTOR PUSAT';
			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<center>
<h3>REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI<br>
'. $getNama .'<br>
TRIWULAN '. $getTriwulan .' - TAHUN '. $getTahun .'</h2>
</center>
<br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
	<thead>
		<tr>
			<th rowspan="2" align="center" width="5%">No</th>
			<th rowspan="2" align="center" width="20%">Nama Pegawai</th>
			<th rowspan="2" align="center" width="15%">NIP</th>
			<th rowspan="2" align="center" width="10%">Lokasi D2D</th>
			<th rowspan="2" align="center" width="8%">Target Minimal</th>
			<th colspan="3" align="center">Jumlah Obyek</th>
			<th rowspan="2" align="center" width="8%">Jumlah</th>
			<th rowspan="2" align="center" width="8%">%</th>
		</tr>
		<tr>';
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					$html .= "<th align='center'>Bulan ". $th['bulan'] ."</th>";
				}
		$html .= "</tr>
	</thead>
	<tbody>";
			
			$arrJumlahPerBulan = array();
			$total_target = 0;
			$total_keseluruhan = 0;
			$no = 1;
			foreach( $arrPegawai as $pegawai ) {
				$html .='<tr nobr="true">
					<td align="right" width="5%">'. $no .'</td>
					<td width="20%">'. $pegawai['nama_pegawai'] .'</td>
					<td align="center" width="15%">'. $pegawai['nip'] .'</td>
					<td align="center" width="10%">'. $pegawai['nama_lokasi'] .'</td>
					<td align="right" width="8%">'. number_format($pegawai['total'], 0) .'</td>';
				
				$jmlperpegawai = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah']) ? 0 : $arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah'];
					$html .= '<td align="right">'. $jml .'</td>';
					$jmlperpegawai += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}
				$html .='<td align="right" width="8%">'. number_format($jmlperpegawai, 0) .'</td>';
				$html .='<td align="right" width="8%">'. number_format( ($jmlperpegawai / $pegawai['total']) * 100, 2 ) .'</td>';
				$html .='</tr>';
				$no++;
				$total_target += $pegawai['total'];
				$total_keseluruhan += $jmlperpegawai;
			}
	$persen_total = $total_keseluruhan > 0 && $total_target > 0 ? ($total_keseluruhan / $total_target) * 100 : 0;
	$html .= '</tbody>
	<tfoot>
		<tr>
			<td colspan="4" width="50%">Total Keseluruhan</td>
			<td align="right" width="8%">'. number_format($total_target, 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[1], 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[2], 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[3], 0) .'</td>
			<td align="right" width="8%">'. number_format($total_keseluruhan, 0) .'</td>
			<td align="right" width="8%">'. number_format( $persen_total , 2 ) .'</td>
		</tr>
	</tfoot>
</table>
<br>
<br>';

		} else {
			$arrPegawai = $this->getArrPegawaiByFilter($postLokasi, $postTriwulan, $postAnggaran);
			$arrTrx = $this->getArrTransaksi($postLokasi, $postAnggaran);
			$getNama = $postLokasi == '99' ? '-- Keseluruhan --' : $this->Lokasi->getDataByIdLokasi($postLokasi)->row()->lokasi;
			$html = '<style type="text/css" media="print,screen">
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
    #judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:10pt;">
REKAPITULASI KEGIATAN DOOR TO DOOR PEGAWAI<br>
'. $getNama .'<br>
TRIWULAN '. $getTriwulan .' - TAHUN '. $getTahun .'
</td>
</tr>
</table>
<br><br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
	<thead>
		<tr>
			<th rowspan="2" align="center" width="5%">No</th>
			<th rowspan="2" align="center" width="20%">Nama Pegawai</th>
			<th rowspan="2" align="center" width="15%">NIP</th>
			<th rowspan="2" align="center" width="10%">Homebase</th>
			<th rowspan="2" align="center" width="8%">Target Minimal</th>
			<th colspan="3" align="center">Jumlah Obyek</th>
			<th rowspan="2" align="center" width="8%">Jumlah</th>
			<th rowspan="2" align="center" width="8%">%</th>
		</tr>
		<tr>';
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					$html .= "<th align='center'>Bulan ". $th['bulan'] ."</th>";
				}
		$html .= "</tr>
	</thead>
	<tbody>";
			
			$arrJumlahPerBulan = array();
			$total_target = 0;
			$total_keseluruhan = 0;
			$no = 1;
			foreach( $arrPegawai as $pegawai ) {
				$html .='<tr nobr="true">
					<td align="right" width="5%">'. $no .'</td>
					<td width="20%">'. $pegawai['nama_pegawai'] .'</td>
					<td align="center" width="15%">'. $pegawai['nip'] .'</td>
					<td align="center" width="10%">'. $pegawai['nama_homebase'] .'</td>
					<td align="right" width="8%">'. number_format($pegawai['total'], 0) .'</td>';
				
				$jmlperpegawai = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah']) ? 0 : $arrTrx[$pegawai['id_mutasi']][$td['id_bulan']]['jumlah'];
					$html .= '<td align="right">'. $jml .'</td>';
					$jmlperpegawai += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}
				$html .='<td align="right" width="8%">'. number_format($jmlperpegawai, 0) .'</td>';
				$html .='<td align="right" width="8%">'. number_format( ($jmlperpegawai / $pegawai['total']) * 100, 2 ) .'</td>';
				$html .='</tr>';
				$no++;
				$total_target += $pegawai['total'];
				$total_keseluruhan += $jmlperpegawai;
			}
	$persen_total = $total_keseluruhan > 0 && $total_target > 0 ? ($total_keseluruhan / $total_target) * 100 : 0;
	$html .= '</tbody>
	<tfoot>
		<tr>
			<td colspan="4" width="50%">Total Keseluruhan</td>
			<td align="right" width="8%">'. number_format($total_target, 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[1], 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[2], 0) .'</td>
			<td align="right">'. number_format($arrJumlahPerBulan[3], 0) .'</td>
			<td align="right" width="8%">'. number_format($total_keseluruhan, 0) .'</td>
			<td align="right" width="8%">'. number_format( $persen_total , 2 ) .'</td>
		</tr>
	</tfoot>
</table>
<br>
<br>
<table style="width:100%;">
<tr>
<td style="width:50%; text-align:center; font-size:9pt;">
'. $this->Paraf->getParafLaporan('rd2d', $postLokasi, 1 ) .'
</td>
<td style="width:50%; text-align:center; font-size:9pt;">
'. $this->Paraf->getParafLaporan('rd2d', $postLokasi, 2 ) .'
</td>
</tr>
</table>';
		}

		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('pdfexample.pdf', 'I');
	}

	private function getArrPegawaiByFilter($postLokasi, $postTriwulan, $postAnggaran)
	{
		$where = $postLokasi == '99' ? '' : " AND v_mutasi.`id_lokasi`='". $postLokasi ."'";
		$cmd = "SELECT
			nama_pegawai,nip,v_mutasi.nama_homebase,v_mutasi.id_mutasi,
			t_target_d2d.`total`
			FROM
			v_mutasi
			LEFT JOIN t_pegawai ON
			t_pegawai.`id_pegawai`=v_mutasi.`id_pegawai`
			LEFT JOIN t_target_d2d ON
			t_target_d2d.`id_jabatan`=v_mutasi.`id_jabatan`
			WHERE t_target_d2d.`id_anggaran`='". $postAnggaran ."' AND t_target_d2d.`id_triwulan`='". $postTriwulan ."'". $where ." order by v_mutasi.id_jabatan,id_sts_pegawai";

		return $this->db->query($cmd)->result_array();
	}

	private function getArrPegawaiPusatByFilter($postLokasi, $postTriwulan, $postAnggaran)
	{
		$cmd = "SELECT
			nama_pegawai,nip,v_mutasi.nama_lokasi,v_mutasi.id_mutasi,
			t_target_d2d.`total`
			FROM
			v_mutasi
			LEFT JOIN t_pegawai ON
			t_pegawai.`id_pegawai`=v_mutasi.`id_pegawai`
			LEFT JOIN t_target_d2d ON
			t_target_d2d.`id_jabatan`=v_mutasi.`id_jabatan`
			WHERE t_target_d2d.`id_anggaran`='". $postAnggaran ."' AND t_target_d2d.`id_triwulan`='". $postTriwulan ."' AND v_mutasi.`id_homebase`='". $postLokasi ."' order by v_mutasi.id_jabatan,id_sts_pegawai";

		return $this->db->query($cmd)->result_array();
	}

	private function getArrTransaksi($postLokasi, $postAnggaran)
	{
		$array = array();
		$where = $postLokasi == '99' ? '' : " AND v_mutasi.`id_lokasi`='". $postLokasi ."'";
		$cmd = "SELECT
			t_trx_d2d.`id_mutasi`,t_trx_d2d.`id_bulan`,jumlah
			FROM
			t_trx_d2d
			LEFT JOIN v_mutasi ON
			v_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE id_anggaran='". $postAnggaran ."'". $where;

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$array[$row->id_mutasi][$row->id_bulan]['jumlah']=$row->jumlah;
		}

		return $array;
	}

	private function getArrTransaksiPusat($postLokasi, $postAnggaran)
	{
		$array = array();
		$where = $postLokasi == '99' ? '' : "";
		$cmd = "SELECT
			t_trx_d2d.`id_mutasi`,t_trx_d2d.`id_bulan`,jumlah
			FROM
			t_trx_d2d
			LEFT JOIN v_mutasi ON
			v_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE id_anggaran='". $postAnggaran ."' AND v_mutasi.`id_homebase`='". $postLokasi ."'";

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$array[$row->id_mutasi][$row->id_bulan]['jumlah']=$row->jumlah;
		}

		return $array;
	}

	public function get_excel_copy()
	{
		$this->load->library("PHPExcel");

		$objReader = PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(FCPATH.'templates/Rekapitulasi Kegiatan D2D Pegawai.xls');

		$objPHPExcel->getActiveSheet()->setCellValue('D1', PHPExcel_Shared_Date::PHPToExcel(time()));
		
		$postTriwulan = '01';
		$postAnggaran = '1458627189';
		$postLokasi = '0501';
		$arrPegawai = $this->getArrPegawaiByFilter($postLokasi, $postTriwulan, $postAnggaran);
		$arrTrx = $this->getArrTransaksi($postLokasi, $postAnggaran);

		$arr = array();
		foreach($arrPegawai as $peg) {
			$arr[] = array(
				'nama_pegawai' => $peg['nama_pegawai'],
				'nip' => $peg['nip'],
				'jabatan' => $peg['jabatan'],
				'total' => $peg['total']
			);
		}

		$baseRow = 9;



		$i = 0;
		foreach ($arrPegawai as $key => $value) {
			$row = $baseRow + $i;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row, $i+1)
			                              ->setCellValueByColumnAndRow(1,$row, $value['nama_pegawai'])
			                              ->setCellValueByColumnAndRow(2,$row, $value['nip'])
			                              ->setCellValueByColumnAndRow(3,$row, $value['jabatan'])
			                              ->setCellValueByColumnAndRow(4,$row, $value['total']);
			    $col = 5;
			    $total = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
					$jml = empty($arrTrx[$value['id_mutasi']][$th['id_bulan']]['jumlah']) ? 0 : $arrTrx[$value['id_mutasi']][$th['id_bulan']]['jumlah'];
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row, $jml);
					$col++;
					$total += $jml;
				}
				$persen = ($total / $value['total']) * 100;
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row, $total);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, number_format($persen,2));
			                             
			$i++;
		}

		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);


		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan rekapitulasi kegiatan d2d pegawai per upad_'.date('dMy').'.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
}
