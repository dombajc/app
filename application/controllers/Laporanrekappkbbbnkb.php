<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanrekappkbbbnkb extends CI_Controller {

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
        $this->load->Model('Pungutanpkb');
        $this->load->Model('Pungutanbbnkb');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Rekap Realisasi PKB/BBNKB Se-Jateng';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekap pkb bbnkb se jateng';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function tiperekening()
	{
		$posttipe = $this->security->xss_clean($_POST['tipe']);
		echo json_encode(array('data'=>$this->Pd->arrbysub($posttipe)->result_array()));
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postbentuk = $param['getbentuk'];
		$postbulan = $param['getbulan'];
		$postth = $param['gettahun'];
		$postmultiyears = $param['getmultiyears'];
		$postjenis = $param['getjenis'];
		$postpd = $param['getpd'];
		$posttipe = $param['gettipe'];

		$data['title'] = 'REKAP LAPORAN PKB/BBNKB Se-JATENG';
		$data['subtitle'] = '';

		$getJenisPelaporan = $this->Fungsi->getDetilByJenisPelaporan($postjenis);
		$getMainRekening = $this->Pd->getdetilrekeningbyid($postpd);
		$getSecondRekening = $posttipe == '99' ? '' : ' ('. $this->Pd->getdetilrekeningbyid($posttipe)->rekening .') ';

		if ( $postbentuk == 'bln' ) {

			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanpkb->arrTransaksi($postth, $postbulan, $idrekening, $postjenis);
				$data['arrTrxLalu'] = $this->Pungutanpkb->arrTransaksiLalu($postth, $postbulan, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanbbnkb->arrTransaksi($postth, $postbulan, $idrekening, $postjenis);
				$data['arrTrxLalu'] = $this->Pungutanbbnkb->arrTransaksiLalu($postth, $postbulan, $idrekening, $postjenis);
			}

			$data['header'] = 'LAPORAN REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening .'<br>Bulan : '. $this->Fungsi->getBulan($postbulan) .' Tahun : '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$data['th'] = 'REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening;
			$data['dinamisContent'] = $this->folder.'rekap pkb bbnkb se jateng/cetak laporan bulanan';
			$data['dinamisContentExcel'] = $this->folder.'rekap pkb bbnkb se jateng/excel laporan bulanan';
			$data['namafile'] = 'rekap bulanan pkb bbnkb se jateng';
		} elseif ( $postbentuk == 'thn') {

			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanpkb->arrTahunanPerJenis($postth, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanbbnkb->arrTahunanPerJenis($postth, $idrekening, $postjenis);
			}

			$data['header'] = 'TAHUNAN DATA '. $getJenisPelaporan->jenis_pelaporan .'<br>'. $getMainRekening->rekening . $getSecondRekening .'<br>BERDASAR JENIS KENDARAAN BERMOTOR<br>TAHUN ANGGARAN '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$data['th'] = $getJenisPelaporan->jenis_pelaporan;
			$data['dinamisContent'] = $this->folder.'rekap pkb bbnkb se jateng/cetak laporan tahunan';
			$data['dinamisContentExcel'] = $this->folder.'rekap pkb bbnkb se jateng/excel laporan tahunan';
			$data['namafile'] = 'rekap tahunan pkb bbnkb se jateng';
		} else {
			$tahun = $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$multitahun = $tahun + ($postmultiyears - 1);

			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanpkb->arrMultiTahun($tahun, $multitahun, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$data['arrTrx'] = $this->Pungutanbbnkb->arrMultiTahun($tahun, $multitahun, $idrekening, $postjenis);
			}

			$data['header'] = 'DATA PERBANDINGAN REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening .'<br>TAHUN '. $tahun .' s.d '. $multitahun;
			$data['th'] = 'REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening;
			$data['gettahunawal'] = intval($tahun);
			$data['gettahunakhir'] = intval($multitahun);
			$data['dinamisContent'] = $this->folder.'rekap pkb bbnkb se jateng/cetak laporan multi tahunan';
			$data['dinamisContentExcel'] = $this->folder.'rekap pkb bbnkb se jateng/excel laporan multi tahunan';
			$data['namafile'] = 'rekap tahunan pkb bbnkb se jateng';
		}

		if ( $param['type'] == 'frame'){
            $this->load->view($this->Opsisite->getDataSite() ['cetak_template'], $data);    
        } elseif ( $param['type'] == 'cetak'){
            //$this->load->view($this->dir_cetak . 'fullbasic', $data);
        } elseif ( $param['type'] == 'excel'){
            $this->load->view($this->Opsisite->getDataSite() ['excel_template'], $data);
        }
	}

	public function show_pdf()
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		ini_set('max_execution_time', 0);
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
		

		$param = $this->uri->uri_to_assoc(2);
		$postbentuk = $param['getbentuk'];
		$postbulan = $param['getbulan'];
		$postth = $param['gettahun'];
		$postmultiyears = $param['getmultiyears'];
		$postjenis = $param['getjenis'];
		$postpd = $param['getpd'];
		$posttipe = $param['gettipe'];

		$getJenisPelaporan = $this->Fungsi->getDetilByJenisPelaporan($postjenis);
		$getMainRekening = $this->Pd->getdetilrekeningbyid($postpd);
		$getSecondRekening = $posttipe == '99' ? '' : ' ('. $this->Pd->getdetilrekeningbyid($posttipe)->rekening .') ';

		$jmlcolspan = count($this->Fungsi->arrKodeJenisPajak());

		if ( $postbentuk == 'bln' ) {
			$pdf->AddPage('L', 'A2');
			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanpkb->arrTransaksi($postth, $postbulan, $idrekening, $postjenis);
				$arrTrxLalu = $this->Pungutanpkb->arrTransaksiLalu($postth, $postbulan, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanbbnkb->arrTransaksi($postth, $postbulan, $idrekening, $postjenis);
				$arrTrxLalu = $this->Pungutanbbnkb->arrTransaksiLalu($postth, $postbulan, $idrekening, $postjenis);
			}

			$header = 'LAPORAN REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening .'<br>Bulan : '. $this->Fungsi->getBulan($postbulan) .' Tahun : '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$th = 'REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening;
			$namafile = 'rekap bulanan pkb bbnkb se jateng';

			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
	#tabel tfoot tr td { font-weight : bold;}
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>';

$html .= '<center><h2 style="text-align:center;">LAPORAN '. $header .'</h2></center>
<br><br>
<table style="font-size: 9pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead>
<tr>
<th rowspan="2" width="3%" align="center"><b>No.</b></th>
<th rowspan="2" width="10%" align="center"><b>Lokasi</b></th>
<th colspan="'. $jmlcolspan .'" align="center"><b>'. $th .'</b></th>
<th rowspan="2" align="center"><b>JUMLAH</b></th>
</tr>
<tr>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$html .= '<th align="center"><b>'. $kode['kode_jenis'] .'</b></th>';
	}
$html .= '</tr>
</thead>
<tbody>';

$arrTotalperColumn = array();
$no = 1;
foreach( $this->Lokasi->arrLokasiIndukDanSamtu() as $lokasi )
{
	$TotalPerRowLokasi = 0;
	$html .= '<tr>
	<td align="right" width="3%">'. $no .'.</td>
	<td width="10%">'. $lokasi['lokasi'] .'</td>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$total = empty($arrTrx[$lokasi['id_lokasi']][$kode['id_jenis']]) ? 0 : $arrTrx[$lokasi['id_lokasi']][$kode['id_jenis']] ;
		$html .= '<td align="right">'. $this->Fungsi->formatangkalaporan($total) .'</td>';
		$arrTotalperColumn[$kode['id_jenis']][] = $total;
		$TotalPerRowLokasi += $total;
	}
	$html .= '<td align="right">'. $this->Fungsi->formatangkalaporan($TotalPerRowLokasi) .'</td>
	</tr>';
	$no++;
}

$html .= '</tbody>
<tfoot>
<tr>
<td colspan="2"><b>JUMLAH BULAN INI</b></td>';

$arrTotal = array();

foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalPerColumn = 0;
	foreach ( $arrTotalperColumn[$kode['id_jenis']] as $r )
	{
		$TotalPerColumn += $r;
	}
	//Only PHP 5.6 dan seterusnya
	//$TotalPerColumn = empty(array_sum($arrTotalperColumn[$kode['id_jenis']])) ? 0 : array_sum($arrTotalperColumn[$kode['id_jenis']]);
	//$TotalPerColumn = 0;
	$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan($TotalPerColumn) .'</b></td>';
	$arrTotal[$kode['id_jenis']] = $TotalPerColumn;
}

$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arrTotal)) .'</b></td>
</tr>
<tr>
<td colspan="2"><b>JUMLAH S.D. BULAN LALU</b></td>';

foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalPerColumnLalu = empty($arrTrxLalu[$kode['id_jenis']]) ? 0 : $arrTrxLalu[$kode['id_jenis']];
	$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan($TotalPerColumnLalu) .'</b></td>';
	$arrTotal[$kode['id_jenis']] += $TotalPerColumnLalu;
}

$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arrTrxLalu)) .'</b></td>
</tr>
<tr>
<td colspan="2"><b>JUMLAH S.D. BULAN INI</b></td>';

foreach( $this->Fungsi->arrKodeJenisPajak() as $kode )
{
	$TotalFinalColumn = empty($arrTotal[$kode['id_jenis']]) ? 0 : $arrTotal[$kode['id_jenis']];
	$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan($TotalFinalColumn) .'</b></td>';
}

$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arrTotal)) .'</b></td>
</tr>
</tfoot>
</table><div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</i></div>';

		} elseif ( $postbentuk == 'thn') {
			$pdf->AddPage('L', 'A2');
			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanpkb->arrTahunanPerJenis($postth, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanbbnkb->arrTahunanPerJenis($postth, $idrekening, $postjenis);
			}

			$header = 'TAHUNAN DATA '. $getJenisPelaporan->jenis_pelaporan .'<br>'. $getMainRekening->rekening . $getSecondRekening .'<br>BERDASAR JENIS KENDARAAN BERMOTOR<br>TAHUN ANGGARAN '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$th = $getJenisPelaporan->jenis_pelaporan;
			$namafile = 'rekap tahunan pkb bbnkb se jateng';

			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
	#tabel tfoot tr td { font-weight : bold;}
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>';

$html .= '<center><h2 style="text-align:center;">LAPORAN '. $header .'</h2></center>
<br><br>
<table style="font-size: 11pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead>
<tr>
<th rowspan="2" width="3%" align="center"><b>No.</b></th>
<th rowspan="2" width="5%" align="center"><b>BULAN</b></th>
<th colspan="'. $jmlcolspan .'" align="center"><b>'. $th .'</b></th>
<th rowspan="2" align="center"><b>JUMLAH</b></th>
</tr>
<tr>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$html .= '<th align="center"><b>'. $kode['kode_jenis'] .'</b></th>';
	}
$html .= '</tr>
</thead>
<tbody>';

$arrTotalperColumn = array();
$no = 1;
foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$TotalPerRowBulan = 0;
	$html .= '<tr>
	<td align="right" width="3%">'. $no .'.</td>
	<td width="5%">'. $bulan->bulan .'</td>';
	foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
		$total = empty($arrTrx[$bulan->id_bulan][$kode['id_jenis']]) ? 0 : $arrTrx[$bulan->id_bulan][$kode['id_jenis']] ;
		$html .= '<td align="right">'. $this->Fungsi->formatangkalaporan($total) .'</td>';
		$arrTotalperColumn[$kode['id_jenis']][] = $total;
		$TotalPerRowBulan += $total;
	}
	$html .= '<td align="right" class="str">'. $this->Fungsi->formatangkalaporan($TotalPerRowBulan) .'</td>
	</tr>';
	if( $no > 1 ) {
		$no ++;
		$subtotal2 = 0;
		$html .= '<tr style="color:red;">
		<td align="right"><b>'. $no .'.</b></td>
		<td><b>JUMLAH</b></td>';
		foreach( $this->Fungsi->arrKodeJenisPajak() as $kode ){
			
			$subtotal = 0;
			foreach ( $arrTotalperColumn[$kode['id_jenis']] as $r )
			{
				$subtotal += $r;
			}
			//Only PHP 5.6 dan seterusnya
			//$subtotal = empty(array_sum($arrTotalperColumn[$kode['id_jenis']])) ? 0 : array_sum($arrTotalperColumn[$kode['id_jenis']]) ;
			$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan($subtotal) .'</b></td>';
			$subtotal2 += $subtotal;
		}
		$html .= '<td align="right"><b>'. $this->Fungsi->formatangkalaporan($subtotal2) .'</b></td>
		</tr>';
	}
	$no ++;
}

$html .= '</tbody>
</table><div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</i></div>';

		} else {
			$pdf->AddPage('L', 'F4');
			$tahun = $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$multitahun = $tahun + ($postmultiyears - 1);

			if ( $postpd == '01' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanpkb->arrMultiTahun($tahun, $multitahun, $idrekening, $postjenis);
			}
			elseif ( $postpd == '02' )
			{
				$idrekening = $posttipe == '99' ? $postpd : $posttipe;
				$arrTrx = $this->Pungutanbbnkb->arrMultiTahun($tahun, $multitahun, $idrekening, $postjenis);
			}

			$header = 'DATA PERBANDINGAN REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening . $getSecondRekening .'<br>TAHUN '. $tahun .' s.d '. $multitahun;
			$th = 'REALISASI '. $getJenisPelaporan->jenis_pelaporan .' '. $getMainRekening->rekening;
			$gettahunawal = intval($tahun);
			$gettahunakhir = intval($multitahun);
			$namafile = 'rekap tahunan pkb bbnkb se jateng';

			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
	#tabel tfoot tr td { font-weight : bold;}
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>';

$html .= '<center><h2 style="text-align:center;">LAPORAN '. $header .'</h2></center>
<br><br>
<table style="font-size: 10pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead>
<tr>
<th rowspan="3" width="3%" align="center"><b>No.</b></th>
<th rowspan="3" width="7%" align="center"><b>BULAN</b></th>
<th colspan="'. (($gettahunakhir-$gettahunawal)+1)*2 .'" align="center"><b>'. $th .'</b></th>
</tr>
<tr>';
	$th = $gettahunawal;
	while( $th <= $gettahunakhir ){
		$html .= '<th colspan="2" align="center"><b>'. $th .'</b></th>';
		$th++;
	}
$html .= '</tr>
<tr>';
	$th2 = $gettahunawal;
	while( $th2 <= $gettahunakhir ){
		$html .= '<th align="center"><b>Jumlah</b></th>
		<th align="center"><b>%</b></th>';
		$th2++;
	}
$html .= '</tr>
</thead>
<tbody>';

$arrTotalperColumn = array();
$no = 1;
foreach( $this->Bulan->getAllData()->result() as $bulan )
{
	$td = $gettahunawal;
	$TotalPerRowBulan = 0;
	$html .= '<tr>
	<td align="right" width="3%">'. $no .'.</td>
	<td width="7%">'. $bulan->bulan .'</td>';
	while( $td <= $gettahunakhir ){
		$total = empty($arrTrx[$td][$bulan->id_bulan]) ? 0 : $arrTrx[$td][$bulan->id_bulan] ;
		$hasil_tren_bulan_lalu = $bulan->id_bulan == 1 ? 0 : $total - $arrTrx[$td][$bulan->id_bulan-1];
		$persentase_tren = $arrTrx[$td][$bulan->id_bulan-1] == 0 ? 0 : ($hasil_tren_bulan_lalu / $arrTrx[$td][$bulan->id_bulan-1]) * 100;
		$html .= '<td align="right">'. $this->Fungsi->formatangkalaporan($total) .'</td>';
		$html .= '<td align="center">'. number_format($persentase_tren, 2) .'</td>';
		$arrTotalperColumn[$td][] = $total;
		$td++;
	}
	$html .= '</tr>';
	$no ++;
}

$html .= '</tbody>
<tfoot>
<tr>
<td colspan="2"><b>TOTAL</b></td>';

$td = $gettahunawal;
while( $td <= $gettahunakhir ){
	
	$subtotal = 0;
	foreach ( $arrTotalperColumn[$td] as $r )
	{
		$subtotal += $r;
	}
	
	$html .= '<td align="right" class="str"><b>'. $this->Fungsi->formatangkalaporan($subtotal) .'</b></td>';
	$html .= '<td align="center" class="str"><b>'. $this->Fungsi->formatangkalaporan(0) .'</b></td>';
	$td++;
}

$html .= '</tr>
</tfoot>
</table><div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' ; '. date('d-m-Y H:i:s') .'</i></div>';
		}
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output($namafile .'.pdf', 'I');
	}

}
