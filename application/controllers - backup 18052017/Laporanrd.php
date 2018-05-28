<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanrd extends CI_Controller {

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
	private $folder = 'laporan/retribusi/rd/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'LAPORAN RD-01';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'view';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$get_laporan = $param['lap'];
		$get_target = $param['target'];
		$get_th = $param['th'];
		$get_bln = $param['bln'];
		$get_lokasi = $param['lok'];
		$row_lokasi = $this->Lokasi->getDataById($get_lokasi)->row();
		
		switch ($get_laporan):
			case '01':
				$this->load->model('Setkoderetribusi');
				$this->load->model('Rekeningpad');
				$this->load->model('Rekamretribusi');
			
				$data['title'] = 'LAPORAN RD-01';
				$data['subtitle'] = '';
				$data['get_bulan_tahun'] = $this->Fungsi->getBulan($get_bln) .' '. $this->Thanggaran->getDataByID($get_th)->row()->th_anggaran;
				$data['get_lokasi'] = $row_lokasi->lokasi;
				$data['tahun'] = $get_th;
				$data['bulan'] = $get_bln;
				$data['lokasi'] = $get_lokasi;
				$data['target'] = $get_target;
				$data['tgl_cetak'] = $row_lokasi->kota .', '. $this->Fungsi->format_tgl_indo(date('d-m-Y'));
				$data['namafile'] = 'laporan rd-01';
				$data['dinamisContent'] = $this->folder.'cetak/rd01';
				
				break;
			case '02':
				$this->load->model('Rekamretribusiskpdlain');
				$this->load->model('Rekeningskpdlain');
				$this->load->model('Skpdlain');
				$this->load->model('Targetretribusiskpdlain');

				$data['title'] = 'LAPORAN RD-02';
				$data['subtitle'] = '';
				$data['tahun'] = $get_th;
				$data['bulan'] = $get_bln;
				$data['lokasi'] = $get_lokasi;
				$data['target'] = $get_target;
				$data['get_bulan_tahun'] = $this->Fungsi->getBulan($get_bln) .' '. $this->Thanggaran->getDataByID($get_th)->row()->th_anggaran;
				$data['get_lokasi'] = $row_lokasi->lokasi;
				$data['tgl_cetak'] = $row_lokasi->kota .', '. $this->Fungsi->format_tgl_indo(date('d-m-Y'));
				$data['namafile'] = 'laporan rd-02';
				$data['dinamisContent'] = $this->folder.'cetak/rd02';
				
				break;
		
		endswitch;

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
		$get_laporan = $param['lap'];
		$get_target = $param['target'];
		$get_th = $param['th'];
		$get_bln = $param['bln'];
		$get_lokasi = $param['lok'];
		$row_lokasi = $this->Lokasi->getDataById($get_lokasi)->row();
		$get_bulan_tahun = $this->Fungsi->getBulan($get_bln) .' '. $this->Thanggaran->getDataByID($get_th)->row()->th_anggaran;
		$get_upad = $row_lokasi->lokasi;
		$tahun = $get_th;
		$bulan = $get_bln;
		$lokasi = $get_lokasi;
		$target = $get_target;
		$data['tgl_cetak'] = $row_lokasi->kota .', '. $this->Fungsi->format_tgl_indo(date('d-m-Y'));

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('LAPORAN RD-'. $get_laporan);
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'F4');

		if ( $get_laporan == '01' ) {
			$this->load->model('Setkoderetribusi');
			$this->load->model('Rekeningpad');
			$this->load->model('Rekamretribusi');
		
			$getNama = 'RD-01';
			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000 !important; padding:0.5em 0.3em; }
	#tabel thead tr th { font-weight:bold;}
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<table style="font-size:8pt;border-collapse:collapse; width:100%; font-weight:bold;" cellpadding="1">
<tr><td rowspan="4" colspan="2" width="20%" valign="top" align="right">LAPORAN :</td><td colspan="8">TARGET, PENETAPAN, PENERIMAAN DAN TUNGGKAN</td></tr>
<tr><td colspan="8">RETRIBUSI DAERAH YANG DIKELOLA LANGSUNG UP3AD</td></tr>
<tr><td colspan="2" width="15%">BAGIAN BULAN</td><td colspan="5">: '. $get_bulan_tahun .'</td><td align="center" colspan="2">Model : RD.01</td></tr>
<tr><td colspan="2" width="15%">UP3AD KABUPATEN</td><td colspan="7">: '. str_replace('UP3AD', '', $get_upad) .'</td></tr>
</table>
<br><br>
<table id="tabel" style="font-size:7pt;border-collapse:collapse;" cellpadding="5" border="1">
	<thead>
		<tr>
			<th width="20%" align="center"><b>No. REKENING</b></th>
			<th width="20%" align="center"><b>NAMA REKENING</b></th>
			<th width="8%" align="center"><b>TARGET</b></th>
			<th width="5%" align="center"><b>TUNGGAKAN S/D BLN LALU</b></th>
			<th width="8%" align="center"><b>PENETAPAN BULAN INI</b></th>
			<th width="8%" align="center"><b>S/D BLN LALU</b></th>
			<th width="8%" align="center"><b>BULAN INI</b></th>
			<th width="5%" align="center"><b>DENDA BLN INI</b></th>
			<th width="8%" align="center"><b>S/D BULAN INI</b></th>
			<th width="5%" align="center"><b>%</b></th>
		</tr>
	</thead>
	<tbody>';
		$html .= $this->Rekeningpad->laporan_rd01($tahun, $bulan, $lokasi, $target);
	$html .= '</tbody>
</table>
<small>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</small>
<br>
<table style="width:100%;">
<tr>
<td colspan="6" style="width:50%; text-align:center; font-size:9pt;">
</td>
<td colspan="4" style="width:50%; text-align:center; font-size:9pt;">
'. $tgl_cetak .'<br>
'.	$this->Paraf->getParafLaporan('rd2d', $lokasi, 1 ) .'
</td>
</tr>
</table>';

		} elseif ( $get_laporan == '02' ) {
			$this->load->model('Rekamretribusiskpdlain');
			$this->load->model('Rekeningskpdlain');
			$this->load->model('Skpdlain');
			$this->load->model('Targetretribusiskpdlain');
			$getNama = 'RD-02';
			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000 !important; padding:0.5em 0.3em; }
	#tabel thead tr th { font-weight:bold;}
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<table style="font-size:8pt;border-collapse:collapse; width:100%; font-weight:bold;" cellpadding="1">
<tr><td rowspan="4" colspan="2" width="20%" valign="top" align="right">LAPORAN :</td><td colspan="8">TARGET, PENETAPAN, PENERIMAAN DAN TUNGGKAN</td></tr>
<tr><td colspan="8">RETRIBUSI DAERAH YANG TIDAK DIKELOLA LANGSUNG UP3AD</td></tr>
<tr><td colspan="2" width="15%">BAGIAN BULAN</td><td colspan="5">: '. $get_bulan_tahun .'</td><td align="center" colspan="2">Model : RD.02</td></tr>
<tr><td colspan="2" width="15%">UP3AD KABUPATEN</td><td colspan="7">: '. str_replace('UP3AD', '', $get_upad) .'</td></tr>
</table>
<br><br>
<table id="tabel" style="font-size:7pt;border-collapse:collapse;" cellpadding="5" border="1">
	<thead>
		<tr>
			<th width="20%" align="center"><b>No. REKENING</b></th>
			<th width="40%" align="center"><b>NAMA REKENING</b></th>
			<th width="8%" align="center"><b>TARGET</b></th>
			<th width="8%" align="center"><b>S/D BLN LALU</b></th>
			<th width="8%" align="center"><b>BULAN INI</b></th>
			<th width="8%" align="center"><b>S/D BULAN INI</b></th>
			<th width="5%" align="center"><b>%</b></th>
		</tr>
	</thead>
	<tbody>';
		$html .= $this->Rekeningskpdlain->laporan_rd02($tahun, $bulan, $lokasi, $target);
	$html .= '</tbody>
</table>
<small>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</small>
<br>
<table style="width:100%;">
<tr>
<td colspan="6" style="width:50%; text-align:center; font-size:9pt;">
</td>
<td colspan="4" style="width:50%; text-align:center; font-size:9pt;">
'. $tgl_cetak .'<br>
'.	$this->Paraf->getParafLaporan('rd2d', $lokasi, 1 ) .'
</td>
</tr>
</table>';
		}

		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('pdfexample.pdf', 'I');
	}
}
