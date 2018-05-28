<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanrekondata extends CI_Controller {

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
	private $folder = 'laporan/rekonsiliasi app to pad/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKONSILIASI DATA PENERIMAAN (PAD Online Dengan APP)';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekonsiliasi data app to pad';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_report()
	{
		$this->load->Model('Pungutanpkb');
		$this->load->Model('Pungutanbbnkb');

		$param = $this->uri->uri_to_assoc(2);
		$postSemester = $param['smt'];
		$postAnggaran = $param['ta'];
		$postPajak = $param['pd'];

		$rek = $this->Pd->getdetilrekeningbyid($postPajak);
		$th = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$semester = $this->Fungsi->get_semester_by_id($postSemester);
		$Trx_pad_ol = $this->Fungsi->arr_transaksi_pad_ol($rek->rek_pad, $th, $postSemester);
		if ( $postPajak == '01' )
		{
			$Trx_app = $this->Pungutanpkb->arr_transaksi_rekon($postAnggaran, $postSemester);
		}
		else if ( $postPajak == '02' )
		{
			$Trx_app = $this->Pungutanbbnkb->arr_transaksi_rekon($postAnggaran, $postSemester);
		}
		
		$header = '<h2>REKONSILIASI DATA REALISASI PENERIMAAN '. $rek->nama_rekening;
		$header .= '<br>ANTARA APLIKASI PEMBUKUAN DAN PELAPORAN (APP) DENGAN APLIKASI PAD ONLINE (PAD OL)';
		$header .= '<br>SEMESTER '. $semester->nama_romawi .'('. $semester->nama_semester .') - TAHUN '. $th .'</h2><br>';

		$data['title'] = 'LAPORAN REKONSILIASI DATA';
		$data['subtitle'] = '';
		$data['get_semester'] = $this->Bulan->arr_per_semester($postSemester)->result();
		$data['trx_app'] = $Trx_app;
		$data['trx_padol'] = $Trx_pad_ol;
		$data['header'] = $header;
		$data['dinamisContent'] = $this->folder.'cetak laporan rekonsiliasi data app to pad';
		$data['namafile'] = 'LAPORAN REKONSILIASI DATA APP dengan PAD OL';

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

		$this->load->Model('Pungutanpkb');
		$this->load->Model('Pungutanbbnkb');

		$param = $this->uri->uri_to_assoc(2);
		$postSemester = $param['smt'];
		$postAnggaran = $param['ta'];
		$postPajak = $param['pd'];
		
		$rek = $this->Pd->getdetilrekeningbyid($postPajak);
		$th = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$semester = $this->Fungsi->get_semester_by_id($postSemester);
		$trx_padol = $this->Fungsi->arr_transaksi_pad_ol($rek->rek_pad, $th, $postSemester);
		$get_semester = $this->Bulan->arr_per_semester($postSemester)->result();
		if ( $postPajak == '01' )
		{
			$trx_app = $this->Pungutanpkb->arr_transaksi_rekon($postAnggaran, $postSemester);
		}
		else if ( $postPajak == '02' )
		{
			$trx_app = $this->Pungutanbbnkb->arr_transaksi_rekon($postAnggaran, $postSemester);
		}
		
		$header = '<h2>REKONSILIASI DATA REALISASI PENERIMAAN '. $rek->nama_rekening;
		$header .= '<br>ANTARA APLIKASI PEMBUKUAN DAN PELAPORAN (APP) DENGAN APLIKASI PAD ONLINE (PAD OL)';
		$header .= '<br>SEMESTER '. $semester->nama_romawi .'('. $semester->nama_semester .') - TAHUN '. $th .'</h2><br>';

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('LAPORAN REKONSILIASI APLIKASI APP DENGAN PAD ONLINE');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT); 
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		$pdf->SetFont('helvetica', '', 7);
		$pdf->AddPage('L', 'A2');

			$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:9pt;">'. $header .'</td>
</tr>
</table>
<br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
	<thead>
		<tr>
			<th rowspan="3" width="3%" align="center">No.</th>
			<th rowspan="3" width="10.6%" align="center" align="center">PUSAT / SAMSAT</th>
			<th colspan="24" width="86.4%" align="center">BULAN</th>
		</tr>
		<tr>';
			foreach( $get_semester as $th )
			{
				$html .= '<th colspan="3" align="center" width="14.4%">BULAN '. $this->Fungsi->getBulan($th->id_bulan) .'</th>';
			}
		$html .= '</tr>
		<tr>';
			foreach( $get_semester as $th2 )
			{
				$html .= '<th width="4.8%" align="center">APP</th><th width="4.8%" align="center">PAD OL</th><th width="4.8%" align="center">SELISIH</th>';
			}
		$html .= '</tr>
	</thead>
	<tbody>';
		$no = 1;
		$totaloby = 0;
		$arr_total_app_per_bulan = array();
		$arr_total_pad_per_bulan = array();
		foreach( $this->Lokasi->arrLokasiIndukDanSamtu() as $lokasi )
		{
			
			$html .= '<tr nobr="true">
			<td width="3%" align="right">'. $no .'</td>
			<td width="10.6%">'. $lokasi['lokasi'] .'</td>';
			foreach( $get_semester as $td )
			{
				$val_app = empty($trx_app[$lokasi['id_lokasi']][$td->id_bulan]) ? 0 : $trx_app[$lokasi['id_lokasi']][$td->id_bulan];
				$val_pad = empty($trx_padol[$lokasi['id_lokasi']][$td->id_bulan]) ? 0 : $trx_padol[$lokasi['id_lokasi']][$td->id_bulan];
				$selisih = $val_app - $val_pad;
				$html .= '<td width="4.8%" align="right">'. number_format($val_app,0) .'</td>
				<td width="4.8%" align="right">'. number_format($val_pad,0) .'</td>
				<td width="4.8%" align="right">'. number_format($selisih,0) .'</td>';
				$arr_total_app_per_bulan[$td->id_bulan] += $val_app;
				$arr_total_pad_per_bulan[$td->id_bulan] += $val_pad;
			}
			$html .= '</tr>';
			$no++;
		}
	$html .= '</tbody>
	<tfoot>
		<tr>
		<td width="13.6%" colspan="2" align="center"><b>JUMLAH</b></td>';
			foreach( $get_semester as $td2 )
			{
				$html .= '<td width="4.8%" align="right"><b>'. number_format($arr_total_app_per_bulan[$td2->id_bulan],0) .'</b></td>
					<td width="4.8%" align="right"><b>'. number_format($arr_total_pad_per_bulan[$td2->id_bulan],0) .'</b></td>
					<td width="4.8%" align="right"><b>'. number_format(($arr_total_app_per_bulan[$td2->id_bulan] - $arr_total_pad_per_bulan[$td2->id_bulan]),0) .'</b></td>';
			}
		$html .= '</tr>
	</tfoot>
</table>
<br>
<div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</i></div>';


		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('rekonsiliasi app padol.pdf', 'I');
	}
}
