<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanbukubantumonitoringtunggakan extends CI_Controller {

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
	private $folder = 'laporan/retribusi/buku bantu monitoring/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'LAPORAN BUKU BANTU MONITORING TUNGGAKAN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'view';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function report()
	{
		$this->load->model('Setkoderetribusi');
		$this->load->model('Rekeningpad');
		$this->load->model('Rekamretribusi');
		
		$param = $this->uri->uri_to_assoc(2);
		$get_th = $param['th'];
		$get_bln = $param['bln'];
		$get_lokasi = $param['lok'];
		$row_lokasi = $this->Lokasi->getDataById($get_lokasi)->row();
		
		$data['title'] = 'LAPORAN BUKU BANTU MONITORING TUNGGAKAN';
		$data['tahun'] = $get_th;
		$data['bulan'] = $get_bln;
		$data['lokasi'] = $get_lokasi;
		$data['tgl_cetak'] = $row_lokasi->kota .', '. $this->Fungsi->format_tgl_indo(date('d-m-Y'));
		$data['get_bulan_tahun'] = $this->Fungsi->getBulan($get_bln) .' '. $this->Thanggaran->getDataByID($get_th)->row()->th_anggaran;
		$data['get_lokasi'] = $row_lokasi->lokasi;
		$data['namafile'] = 'laporan buku bantu monitoring tunggakan';
		$data['dinamisContent'] = $this->folder.'cetak/cetak';

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
		$tgl_cetak = $row_lokasi->kota .', '. $this->Fungsi->format_tgl_indo(date('d-m-Y'));

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('BUKU BANTU MONITORING TUNGGAKAN');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'F4');

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
<table style="font-size:12pt;border-collapse:collapse; width:100%; font-weight:bold;" cellpadding="1">
<tr><td colspan="9" align="center">BUKU BANTU MONITORING TUNGGAKAN</td></tr>
<tr><td colspan="9" align="center">RETRIBUSI KEKAYAAN DAERAH (RPKD)</td></tr>
<tr><td colspan="9" align="center">'. $get_upad .'</td></tr>
<tr><td colspan="9" align="center">BAGIAN BULAN : '. $get_bulan_tahun .'</td></tr>
</table>
<br><br>
<table id="tabel" style="font-size:10pt;border-collapse:collapse;" cellpadding="5" border="1">
	<thead>
		<tr>
			<th rowspan="2" width="5%" align="center"><b>NO.</b></th>
			<th rowspan="2" width="30%" align="center"><b>PERUNTUKAN</b></th>
			<th colspan="2" width="18%" align="center"><b>KETETAPAN</b></th>
			<th colspan="2" width="18%" align="center"><b>PEMBAYARAN</b></th>
			<th colspan="2" width="18%" align="center"><b>TUNGGAKAN</b></th>
			<th rowspan="2" align="center"><b>KETERANGAN</b></th>
		</tr>
		<tr>
			<th width="8%" align="center"><b>OBY</b></th>
			<th width="10%" align="center"><b>RP</b></th>
			<th width="8%" align="center"><b>OBY</b></th>
			<th width="10%" align="center"><b>RP</b></th>
			<th width="8%" align="center"><b>OBY</b></th>
			<th width="10%" align="center"><b>RP</b></th>
		</tr>
	</thead>
	<tbody>';
		$html .= $this->Rekeningpad->laporan_buku_bantu_monitoring_tunggakan($tahun, $bulan, $lokasi);
	$html .= '</tbody>
	<tfoot>';
		$html .= $this->Rekeningpad->footer_laporan_buku_bantu_monitoring_tunggakan($tahun, $bulan, $lokasi);
	$html .= '</tfoot>
</table>
<small>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</small>
<br>
<table style="width:100%;">
<tr>
<td colspan="5" style="width:50%; text-align:center; font-size:9pt;">
</td>
<td colspan="4" style="width:50%; text-align:center; font-size:9pt;">
'. $tgl_cetak .'<br>
'.	$this->Paraf->getParafLaporan('rd2d', $lokasi, 1 ) .'
</td>
</tr>
</table>';

		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('buku bantu monitoring tunggakan.pdf', 'I');
	}
}
