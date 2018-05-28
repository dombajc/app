<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanrekapobyekspbu extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
        
    }
	
	public function index()
	{
		
		$this->load->model('Targetpelypd');
		$this->load->model('Trxpd');

		$data['title'] = 'Rekap Penataan PBBKB';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekap obyek spbu';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postOby = $this->security->xss_clean($param['getoby']);
		$postAnggaran = $this->security->xss_clean($param['getta']);
		$postDasar = $this->security->xss_clean($param['getdasar']);

		switch($postOby):
			case '0':
				$this->load->Model('Spbu');
				
				$header = "<h3>Dinas Pendapatan Dan Pengelolaan Aset Daerah Provinsi Jawa Tengah</h3>";
				$header .= "<h3>Rekapitulasi Obyek Pelaporan SPBU</h3>";
				$header .= "<h4>Berdasar : ". $this->Dasar->getDataById($postDasar)->dasar_trx_pbbkb ."</h4>";
				$header .= "<h4>Tahun Anggaran : ". $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran ."</h4><br>";

				$cond = " where id_dasar_trx_pbbkb='".$postDasar."' and id_anggaran='".$postAnggaran."'";


				$data['title'] = 'REKAP OBYEK LAP. SPBU';
				$data['subtitle'] = '';
				$data['header'] = $header;
				$data['arrJmlOby'] = $this->Spbu->arrJumlahSPBUperUpad();
				$data['arrTr'] = $this->Spbu->arrTransaksiSpbu($cond);
				$data['dinamisContent'] = $this->folder.'cetak laporan rekap obyek spbu';
				$data['namafile'] = 'LAPORAN REKAP OBYEK SPBU';
				break;
			case '1':
				$this->load->model('Entriandistribusi');
			
				$header = "<h3>Dinas Pendapatan Dan Pengelolaan Aset Daerah Provinsi Jawa Tengah</h3>";
				$header .= "<h3>Rekapitulasi Kinerja UP3AD - Pendataan Distribusi BBM Di Provinsi Jawa Tengah</h3>";
				$header .= "<h4>Tahun Anggaran : ". $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran ."</h4><br>";

				$cond = " where id_anggaran='".$postAnggaran."'";

				$data['title'] = 'Rekap Distribusi BBM Di Provinsi Jawa Tengah';
				$data['subtitle'] = '';
				$data['header'] = $header;
				$data['arrTr'] = $this->Entriandistribusi->arrGroupTransaksi($cond);
				$data['arr_per_lokasi'] = $this->Entriandistribusi->arrGrupByLokasi($cond);
				$data['dinamisContent'] = $this->folder.'cetak laporan rekap obyek badan usaha';
				$data['namafile'] = 'Rekap Distribusi BBM Di Provinsi Jawa Tengah';
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

	public function pdf()
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		$param = $this->uri->uri_to_assoc(2);
		$postOby = $this->security->xss_clean($param['getoby']);
		$postAnggaran = $this->security->xss_clean($param['getta']);
		$postDasar = $this->security->xss_clean($param['getdasar']);
		
		$this->load->library('Pdf');
		$pdf = new Pdf('L', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('REKAP OBYEK SPBU');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(5, 10, PDF_MARGIN_RIGHT); 
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		$pdf->SetFont('helvetica', '', 8);
		$pdf->AddPage('L', 'F4');

		
		switch($postOby):
			case '0':
				$this->load->Model('Spbu');
				
				$header = "<h3>Dinas Pendapatan Dan Pengelolaan Aset Daerah Provinsi Jawa Tengah";
				$header .= "<br>Rekapitulasi Obyek Pelaporan SPBU</h3>";
				$header .= "<h4>Berdasar : ". $this->Dasar->getDataById($postDasar)->dasar_trx_pbbkb;
				$header .= "<br>Tahun Anggaran : ". $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran ."</h4><br>";

				$cond = " where id_dasar_trx_pbbkb='".$postDasar."' and id_anggaran='".$postAnggaran."'";


				$header = $header;
				$arrJmlOby = $this->Spbu->arrJumlahSPBUperUpad();
				$arrTr = $this->Spbu->arrTransaksiSpbu($cond);
				$namafile = 'LAPORAN REKAP OBYEK SPBU.pdf';
				
				$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
'. $header .'
<br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
<tr>
<th rowspan="2" width="3%" align="center"><b>No.</b></th>
<th rowspan="2" align="center" width="19%"><b>UP3AD</b></th>
<th rowspan="2" width="6%" align="center"><b>Jml Oby SPBU</b></th>
<th colspan="12" width="72%" align="center"><b>Obyek Pelaporan Bulanan</b></th>
</tr>
<tr>';
		foreach( $this->Bulan->getAllData()->result() as $thbulan )
		{
			$html .= '<th width="6%" align="center"><b>'. $thbulan->bulan .'</b></th>';
		}
$html .= '</tr>
</thead>
<tbody>';

$no = 1;
$totaloby = 0;
$arrJml = array();
foreach( $this->Lokasi->showAllUpad()->result() as $lokasi )
{
$jmlOby = empty($arrJmlOby[$lokasi->id_lokasi]) ? 0 : $arrJmlOby[$lokasi->id_lokasi];

$html .= '<tr nobr="true">
<td width="3%" align="right">'. $no .'.</td>
<td width="19%" align="left">'. $lokasi->lokasi .'</td>
<td align="center" width="6%">'. $jmlOby .'</td>';

foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$jmlTr = empty($arrTr[$lokasi->id_lokasi][$bulan->id_bulan]) ? 0 : $arrTr[$lokasi->id_lokasi][$bulan->id_bulan];
	$html .= '<td align="right" width="6%">'. $jmlTr .'</td>';
	$arrJml[$bulan->id_bulan] += $jmlTr;
}

$html .= '</tr>';
	$no++;
	$totaloby += $jmlOby;
}

$html .= '</tbody>
<tfoot>
<tr>
<td colspan="2" align="center"><b>JUMLAH</b></td>
<td align="center" width="6%">'. number_format($totaloby) .'</td>';
	foreach( $this->Bulan->getAllData()->result() as $footbulan )
	{
		$html .= '<td align="right" width="6%">'. $arrJml[$footbulan->id_bulan] .'</td>';
	}
		
$html .= '</tr>
</tfoot>
</table>';

$html .= '<i style="text-align:left;>dicetak : '. element('username', $this->Opsisite->getDataUser()).' ; '. date('d-m-Y H:i:s') .'</i>';
				
				break;
			case '1':
				$this->load->model('Entriandistribusi');
			
				$header = "<h3>Dinas Pendapatan Dan Pengelolaan Aset Daerah Provinsi Jawa Tengah";
				$header .= "<br>Rekapitulasi Kinerja UP3AD - Pendataan Distribusi BBM Di Provinsi Jawa Tengah</h3>";
				$header .= "<h4>Tahun Anggaran : ". $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran ."</h4><br>";

				$cond = " where id_anggaran='".$postAnggaran."'";

				$arrTr = $this->Entriandistribusi->arrGroupTransaksi($cond);
				$arr_per_lokasi = $this->Entriandistribusi->arrGrupByLokasi($cond);
				$dinamisContent = $this->folder.'cetak laporan rekap obyek badan usaha';
				$namafile = 'Rekap Distribusi BBM Di Provinsi Jawa Tengah.pdf';
				
				$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
'. $header .'
<br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
<tr>
<th rowspan="2" width="3%" align="center"><b>No.</b></th>
<th rowspan="2" align="center" width="19%"><b>UP3AD</b></th>
<th rowspan="2" width="6%" align="center"><b>Kinerja Pendataan</b></th>
<th colspan="12" width="72%" align="center"><b>Obyek Pendataan Bulanan</b></th>
</tr>
<tr>';
		foreach( $this->Bulan->getAllData()->result() as $thbulan )
		{
			$html .= '<th width="6%" align="center"><b>'. $thbulan->bulan .'</b></th>';
		}
$html .= '</tr>
</thead>
<tbody>';

$no = 1;
$totaloby = 0;
$arrJml = array();
foreach( $this->Lokasi->showAllUpad()->result() as $lokasi )
{
$jmlOby = empty($arr_per_lokasi[$lokasi->id_lokasi]) ? 0 : $arr_per_lokasi[$lokasi->id_lokasi];

$html .= '<tr nobr="true">
<td width="3%" align="right">'. $no .'.</td>
<td width="19%" align="left">'. $lokasi->lokasi .'</td>
<td align="center" width="6%">'. $jmlOby .'</td>';

foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$jmlTr = empty($arrTr[$lokasi->id_lokasi][$bulan->id_bulan]) ? 0 : $arrTr[$lokasi->id_lokasi][$bulan->id_bulan];
	$html .= '<td align="right" width="6%">'. $jmlTr .'</td>';
	$arrJml[$bulan->id_bulan] += $jmlTr;
}

$html .= '</tr>';
	$no++;
	$totaloby += $jmlOby;
}

$html .= '</tbody>
<tfoot>
<tr>
<td colspan="2" align="center"><b>JUMLAH</b></td>
<td align="center" width="6%">'. number_format($totaloby) .'</td>';
	foreach( $this->Bulan->getAllData()->result() as $footbulan )
	{
		$html .= '<td align="right" width="6%">'. $arrJml[$footbulan->id_bulan] .'</td>';
	}
		
$html .= '</tr>
</tfoot>
</table>';

$html .= '<i style="text-align:left;">dicetak : '. element('username', $this->Opsisite->getDataUser()).' ; '. date('d-m-Y H:i:s') .'</i>';
				break;
		endswitch;
		
		
		


		$pdf->writeHTML($html, true, false, true, false, 'C');

		$pdf->lastPage();
		$pdf->Output($namafile, 'I');
	}

}
