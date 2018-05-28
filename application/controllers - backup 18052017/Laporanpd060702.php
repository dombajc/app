<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanpd060702 extends CI_Controller {

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
		$data['title'] = 'LAPORAN PD 02 / PD 03 / PD 06 / PD 07';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan pd 06 07 02';
		
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
		$postinduk = $param['getinduk'];
		$postlokasi = $param['getlokasi'];

		$data['title'] = 'LAPORAN PD 02 / PD 03 / PD 06 / PD 07';
		$data['subtitle'] = '';
		$data['paraf_1'] = $this->Paraf->getParafPD('rd2d', $postinduk, 1 );
		$data['paraf_2'] = $this->Paraf->getParafPD('rd2d', $postinduk, 3 );

		if ( $postbentuk == '02' ) {
			$data['header_periode'] = 'BULAN / TAHUN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$data['header_lokasi'] = $postinduk == $postlokasi ? 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postlokasi)->row()->lokasi) : 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postinduk)->row()->lokasi) .'('. $this->Lokasi->getDataById($postlokasi)->row()->lokasi .')';
			$data['header_lokasi2'] = $postinduk == $postlokasi ? 'SAMSAT INDUK + SAMKEL + PATEN + GERAI' : 'SAMSAT PEMBANTU';
			$data['postinduk'] = $postinduk;
			$data['arrData'] = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi);
			$data['arrDataLalu'] = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi);
			$data['dinamisContent'] = $this->folder.'pd 06 pd 07 pd 02/cetak pd 02';
			
			$data['namafile'] = 'pd02';
		} elseif ( $postbentuk == '03' ) {
			$data['header_periode'] = 'BULAN / TAHUN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$data['header_lokasi'] = $postinduk == $postlokasi ? 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postlokasi)->row()->lokasi) : 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postinduk)->row()->lokasi) .'('. $this->Lokasi->getDataById($postlokasi)->row()->lokasi .')';
			$data['header_lokasi2'] = $postinduk == $postlokasi ? 'SAMSAT INDUK + SAMKEL + PATEN + GERAI' : 'SAMSAT PEMBANTU';
			$data['postinduk'] = $postinduk;
			$data['arrData'] = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi, '012');
			$data['arrDataLalu'] = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi, '012');
			$data['dinamisContent'] = $this->folder.'pd 06 pd 07 pd 02/cetak pd 03';
			$data['namafile'] = 'pd03';
		} elseif ( $postbentuk == '06') {
			$data['header'] = 'LAPORAN PENETAPAN, PENGURANGAN, PENERIMAAN, DAN TUNGGAKAN BEA BALIK NAMA KENDARAAN BERMOTOR I<br>BULAN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran .'<br>'. $this->Lokasi->getDataById($postlokasi)->row()->lokasi;
			$data['postinduk'] = $postinduk;

			$data['arrData'] = $this->Pungutanbbnkb->getarrpd($postth, $postbulan, $postlokasi, '021');
			$data['arrDataLalu'] = $this->Pungutanbbnkb->getarrpdLalu($postth, $postbulan, $postlokasi, '021');
			$data['dinamisContent'] = $this->folder.'pd 06 pd 07 pd 02/cetak pd 06';
			$data['namafile'] = 'pd06';
		} elseif ( $postbentuk == '07') {
			$data['header'] = 'LAPORAN PENETAPAN, PENGURANGAN, PENERIMAAN, DAN TUNGGAKAN BEA BALIK NAMA KENDARAAN BERMOTOR II<br>BULAN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran .'<br>'. $this->Lokasi->getDataById($postlokasi)->row()->lokasi;
			$data['postinduk'] = $postinduk;

			$data['arrData'] = $this->Pungutanbbnkb->getarrpd($postth, $postbulan, $postlokasi, '022');
			$data['arrDataLalu'] = $this->Pungutanbbnkb->getarrpdLalu($postth, $postbulan, $postlokasi, '022');
			$data['dinamisContent'] = $this->folder.'pd 06 pd 07 pd 02/cetak pd 07';
			$data['namafile'] = 'pd07';
		}
		$data['dtlokasi'] = $this->Lokasi->getDataById($postlokasi)->row();
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

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 12);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT-10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT-10);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'A2');

		$param = $this->uri->uri_to_assoc(2);
		$postbentuk = $param['getbentuk'];
		$postbulan = $param['getbulan'];
		$postth = $param['gettahun'];
		$postinduk = $param['getinduk'];
		$postlokasi = $param['getlokasi'];
		$dtlokasi = $this->Lokasi->getDataById($postlokasi)->row();
		$paraf_1 = $this->Paraf->getParafPD('rd2d', $postinduk, 1 );
		$paraf_2 = $this->Paraf->getParafPD('rd2d', $postinduk, 3 );

		if ( $postbentuk == '02' ) {
			$header_periode = 'BULAN / TAHUN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$header_lokasi = $postinduk == $postlokasi ? 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postlokasi)->row()->lokasi) : 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postinduk)->row()->lokasi) .'('. $this->Lokasi->getDataById($postlokasi)->row()->lokasi .')';
			$header_lokasi2 = $postinduk == $postlokasi ? 'SAMSAT INDUK + SAMKEL + PATEN + GERAI' : 'SAMSAT PEMBANTU';
			$postinduk = $postinduk;
			$arrData = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi);
			$arrDataLalu = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi);
			$namafile = 'pd 02';

			$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:14pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<table style="font-size: 12pt; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td rowspan="4" valign="top" width="5%">LAPORAN : </td>
<td colspan="13">PENETAPAN, PENGURANGAN, TAGIHAN, PENERIMAAN DAN TUNGGAKAN PKB</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">( BERDASARKAN POTENSI OBYEK YANG TERDAFTAR / TER-REGISTRASI DI SAMSAT )</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">'. $header_periode .'</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">'. $header_lokasi .'</td>
<td align="center" colspan="7">'. $header_lokasi2 .'</td>
</tr>
</table>
<br><br>
<table style="font-size: 12pt; border-collapse: collapse;" width="100%">
<tr>
<td colspan="19">PENERIMAAN YANG DITERIMA CASH KASIR</td>
<td align="right" colspan="2">MODEL PD 02</td>
</tr>
</table>
<table style=" font-size: 12pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="4" align="center" width="3%"><b>NO.</b></th>
<th rowspan="4" align="center" width="7%"><b>JENIS PUNGUTAN</b></th>
<th colspan="3" rowspan="2" align="center" width="12%"><b>PENETAPAN S/D BULAN LALU</b></th>
<th colspan="6" align="center" width="30%"><b>PENETAPAN BULAN INI</b></th>
<th colspan="3" align="center" rowspan="2" width="12%"><b>PENGURANGAN</b></th>
<th colspan="4" align="center" width="23%"><b>PENERIMAAN</b></th>
<th colspan="3" rowspan="2" align="center" width="12%"><b>SISA PENETAPAN S/D BULAN INI</b></th>
</tr>
<tr>
<th colspan="3" align="center" width="14%"><b>TAHUN LALU</b></th>
<th colspan="3" align="center" width="16%"><b>TAHUN JALAN</b></th>
<th colspan="4" align="center"><b>LOKAL + MEMPROSES</b></th>
</tr>
<tr>
<th rowspan="2" align="center" width="4%"><b>OBYEK</b></th>
<th align="center" width="4%"><b>POKOK</b></th>
<th align="center" width="4%"><b>SANKSI</b></th>
<th align="center" rowspan="2" width="4%"><b>OBYEK</b></th>
<th align="center" width="5%"><b>POKOK</b></th>
<th align="center" width="5%"><b>SANKSI</b></th>
<th align="center" rowspan="2" width="4%"><b>OBYEK</b></th>
<th align="center" width="6%"><b>POKOK</b></th>
<th align="center" width="6%"><b>SANKSI</b></th>
<th align="center" rowspan="2"><b>OBYEK</b></th>
<th align="center"><b>POKOK</b></th>
<th align="center"><b>SANKSI</b></th>
<th align="center" rowspan="2" width="4%"><b>OBYEK</b></th>
<th align="center" width="6%"><b>POKOK</b></th>
<th align="center" width="6%"><b>SANKSI</b></th>
<th align="center" width="7%"><b>JUMLAH</b></th>
<th align="center" rowspan="2"><b>OBYEK</b></th>
<th align="center"><b>POKOK</b></th>
<th align="center"><b>SANKSI</b></th>
</tr>
<tr>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>TOTAL</b></th>
<th align="center"><b>Rp</b></th>
<th align="center"><b>Rp</b></th>
</tr>
<tr>';
 
for($i_th = 1;$i_th <= 21; $i_th++)
{
	$html .= '<th align="center"><b>'. $i_th .'</b></th>';
}

$html .= '</tr>
<tr>
<th align="center" width="3%">A</th>
<th align="center" width="7%"><small>PENETAPAN PKB THN JALAN</small></th>';
 
for($i_th = 1;$i_th <= 19; $i_th++)
{
	$html .= '<th></th>';
}

$html .= '</tr>
</thead>
<tbody>';

		$no = 1;
		$arr = array();
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_tl = empty($arrData[$jenis['id_jenis']][0]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_obyek;
			$pokok_tl = empty($arrData[$jenis['id_jenis']][0]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_pokok;
			$sanksi_tl = empty($arrData[$jenis['id_jenis']][0]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_sanksi;
			$oby_tj = empty($arrData[$jenis['id_jenis']][1]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_obyek;
			$pokok_tj = empty($arrData[$jenis['id_jenis']][1]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_pokok;
			$sanksi_tj = empty($arrData[$jenis['id_jenis']][1]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_sanksi;
			$html .= '<tr>
			<td align="center" width="3%">'. $no .'</td>
			<td align="center" width="7%">'. $jenis['kode_jenis'] .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tl) .'</td>
			<td align="right" width="5%">'. $this->Fungsi->formatangkalaporan($pokok_tl) .'</td>
			<td align="right" width="5%">'. $this->Fungsi->formatangkalaporan($sanksi_tl) .'</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($pokok_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($sanksi_tj) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($pokok_tl+$pokok_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($sanksi_tl+$sanksi_tj) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan(($pokok_tl+$pokok_tj)+($sanksi_tl+$sanksi_tj)) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			</tr>';
			$arr['oby_tl'][] = $oby_tl;
			$arr['pokok_tl'][] = $pokok_tl;
			$arr['sanksi_tl'][] = $sanksi_tl;
			$arr['oby_tj'][] = $oby_tj;
			$arr['pokok_tj'][] = $pokok_tj;
			$arr['sanksi_tj'][] = $sanksi_tj;
			$no++;
		}

		$tot_oby_tl_bs = empty($arrDataLalu[0]->tot_obyek) ? 0 : $arrDataLalu[0]->tot_obyek;
		$tot_pokok_tl_bs = empty($arrDataLalu[0]->tot_pokok) ? 0 : $arrDataLalu[0]->tot_pokok;
		$tot_sanksi_tl_bs = empty($arrDataLalu[0]->tot_sanksi) ? 0 : $arrDataLalu[0]->tot_sanksi;
		$tot_oby_tj_bs = empty($arrDataLalu[1]->tot_obyek) ? 0 : $arrDataLalu[1]->tot_obyek;
		$tot_pokok_tj_bs = empty($arrDataLalu[1]->tot_pokok) ? 0 : $arrDataLalu[1]->tot_pokok;
		$tot_sanksi_tj_bs = empty($arrDataLalu[1]->tot_sanksi) ? 0 : $arrDataLalu[1]->tot_sanksi;

$html .= '</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH BULAN INI</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj'])) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH S/D BULAN LALU</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs + $tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs + $tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH S/D BULAN INI</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl']) + $tot_oby_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + $tot_pokok_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + $tot_sanksi_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj']) + $tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj']) + $tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) + ($tot_pokok_tl_bs + $tot_pokok_tj_bs)) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) + ($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) + (($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs))) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
</tfoot>
</table>
<br><br>
<table style="font-size: 14px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="6" valign="top">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $dtlokasi->kota .', '. date('d') .' '. $this->Fungsi->getBulan(date('n')) .' '. date('Y') .'</td>
</tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_jabatan'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_jabatan'] .'</td>
</tr>
<tr>
<td align="center" colspan="6"></td>
</tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_nama'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_nama'] .'</td>
</tr>
</table>';

		} elseif ( $postbentuk == '03' ) {
			$header_periode = 'BULAN / TAHUN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran;
			$header_lokasi = $postinduk == $postlokasi ? 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postlokasi)->row()->lokasi) : 'UPPD : '. str_replace('UPPD ', '', $this->Lokasi->getDataByIdLokasi($postinduk)->row()->lokasi) .'('. $this->Lokasi->getDataById($postlokasi)->row()->lokasi .')';
			$header_lokasi2 = $postinduk == $postlokasi ? 'SAMSAT INDUK + SAMKEL + PATEN + GERAI' : 'SAMSAT PEMBANTU';
			$postinduk = $postinduk;
			$arrData = $this->Pungutanpkb->getarrpd($postth, $postbulan, $postlokasi, '012');
			$arrDataLalu = $this->Pungutanpkb->getarrpdLalu($postth, $postbulan, $postlokasi, '012');
			$namafile = 'pd 03';

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
<table style="font-size: 12pt; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td rowspan="4" valign="top">LAPORAN : </td>
<td colspan="13">PENETAPAN, PENGURANGAN, TAGIHAN, PENERIMAAN DAN TUNGGAKAN PKB</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">TRANSAKSI MEMPROSES</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">'. $header_periode .'</td>
<td align="center" colspan="7"></td>
</tr>
<tr>
<td colspan="13">'. $header_lokasi .'</td>
<td align="center" colspan="7">'. $header_lokasi2 .'</td>
</tr>
</table>
<br><br>
<table style="font-size: 9pt; border-collapse: collapse;" width="100%">
<tr>
<td colspan="19">PENERIMAAN YANG DITERIMA CASH KASIR</td>
<td align="right" colspan="2">MODEL PD 03</td>
</tr>
</table>
<table style=" font-size: 12pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="4" align="center" width="3%"><b>NO.</b></th>
<th rowspan="4" align="center" width="7%"><b>JENIS PUNGUTAN</b></th>
<th colspan="3" align="center" rowspan="2" width="12%">PENETAPAN S/D BULAN LALU</th>
<th colspan="6" align="center" width="32%">PENETAPAN BULAN INI</th>
<th colspan="3" align="center" rowspan="2" width="12%">PENGURANGAN</th>
<th colspan="4" align="center" width="22%">PENERIMAAN</th>
<th colspan="3" align="center" rowspan="2" width="12%">SISA PENETAPAN S/D BULAN INI</th>
</tr>
<tr>
<th colspan="3" align="center">TAHUN LALU</th>
<th colspan="3" align="center">TAHUN JALAN</th>
<th colspan="4" align="center">LOKAL + MEMPROSES</th>
</tr>
<tr>
<th rowspan="2" align="center" width="4%">OBYEK</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
<th rowspan="2" align="center" width="4%">OBYEK</th>
<th align="center" width="6%">POKOK</th>
<th align="center" width="6%">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBYEK</th>
<th align="center" width="6%">POKOK</th>
<th align="center" width="6%">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBYEK</th>
<th align="center" width="4%">POKOK</th>
<th align="center" width="4%">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBYEK</th>
<th align="center" width="6%">POKOK</th>
<th align="center" width="6%">SANKSI</th>
<th align="center" width="6%">JUMLAH</th>
<th align="center" rowspan="2" width="4%">OBYEK</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
</tr>
<tr>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">TOTAL</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
</tr>
<tr>';
 
for($i_th = 1;$i_th <= 21; $i_th++)
{
	$html .= '<th align="center"><b>'. $i_th .'</b></th>';
}

$html .= '</tr>
<tr>
<th align="center" width="3%">A</th>
<th align="center" width="7%"><small>PENETAPAN PKB THN JALAN</small></th>';
 
for($i_th = 1;$i_th <= 19; $i_th++)
{
	$html .= '<th></th>';
}

$html .= '</tr>
</thead>
<tbody>';

		$no = 1;
		$arr = array();
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_tl = empty($arrData[$jenis['id_jenis']][0]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_obyek;
			$pokok_tl = empty($arrData[$jenis['id_jenis']][0]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_pokok;
			$sanksi_tl = empty($arrData[$jenis['id_jenis']][0]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][0]->tot_sanksi;
			$oby_tj = empty($arrData[$jenis['id_jenis']][1]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_obyek;
			$pokok_tj = empty($arrData[$jenis['id_jenis']][1]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_pokok;
			$sanksi_tj = empty($arrData[$jenis['id_jenis']][1]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']][1]->tot_sanksi;
			$html .= '<tr>
			<td align="center" width="3%">'. $no .'</td>
			<td align="center" width="7%">'. $jenis['kode_jenis'] .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tl) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($pokok_tl) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($sanksi_tl) .'</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($pokok_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($sanksi_tj) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($pokok_tl+$pokok_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan($sanksi_tl+$sanksi_tj) .'</td>
			<td align="right" width="6%">'. $this->Fungsi->formatangkalaporan(($pokok_tl+$pokok_tj)+($sanksi_tl+$sanksi_tj)) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			</tr>';
			$arr['oby_tl'][] = $oby_tl;
			$arr['pokok_tl'][] = $pokok_tl;
			$arr['sanksi_tl'][] = $sanksi_tl;
			$arr['oby_tj'][] = $oby_tj;
			$arr['pokok_tj'][] = $pokok_tj;
			$arr['sanksi_tj'][] = $sanksi_tj;
			$no++;
		}

		$tot_oby_tl_bs = empty($arrDataLalu[0]->tot_obyek) ? 0 : $arrDataLalu[0]->tot_obyek;
		$tot_pokok_tl_bs = empty($arrDataLalu[0]->tot_pokok) ? 0 : $arrDataLalu[0]->tot_pokok;
		$tot_sanksi_tl_bs = empty($arrDataLalu[0]->tot_sanksi) ? 0 : $arrDataLalu[0]->tot_sanksi;
		$tot_oby_tj_bs = empty($arrDataLalu[1]->tot_obyek) ? 0 : $arrDataLalu[1]->tot_obyek;
		$tot_pokok_tj_bs = empty($arrDataLalu[1]->tot_pokok) ? 0 : $arrDataLalu[1]->tot_pokok;
		$tot_sanksi_tj_bs = empty($arrDataLalu[1]->tot_sanksi) ? 0 : $arrDataLalu[1]->tot_sanksi;

$html .= '</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH BULAN INI</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj'])) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH S/D BULAN LALU</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_tl_bs + $tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_tl_bs + $tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="2"><b>JUMLAH S/D BULAN INI</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tl']) + $tot_oby_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tl']) + $tot_pokok_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tl']) + $tot_sanksi_tl_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['pokok_tj']) + $tot_pokok_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['sanksi_tj']) + $tot_sanksi_tj_bs) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(array_sum($arr['oby_tj']) + $tot_oby_tj_bs) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj'])) + ($tot_pokok_tl_bs + $tot_pokok_tj_bs)) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan((array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj'])) + ($tot_sanksi_tl_bs + $tot_sanksi_tj_bs)) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan(((array_sum($arr['pokok_tl']) + array_sum($arr['pokok_tj']))+(array_sum($arr['sanksi_tl']) + array_sum($arr['sanksi_tj']))) + (($tot_pokok_tl_bs + $tot_pokok_tj_bs)+($tot_sanksi_tl_bs + $tot_sanksi_tj_bs))) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
</tfoot>
</table>
<br><br>
<table style="font-size: 12px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="6" valign="top">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $dtlokasi->kota .', '. date('d') .' '. $this->Fungsi->getBulan(date('n')) .' '. date('Y') .'</td>
</tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_jabatan'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_jabatan'] .'</td>
</tr>
<tr>
<td align="center" colspan="6"></td>
</tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_nama'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_nama'] .'</td>
</tr>
</table>';

		} elseif ( $postbentuk == '06') {
			$header = 'LAPORAN PENETAPAN, PENGURANGAN, PENERIMAAN, DAN TUNGGAKAN BEA BALIK NAMA KENDARAAN BERMOTOR I<br>BULAN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran .'<br>'. $this->Lokasi->getDataById($postlokasi)->row()->lokasi;
			$postinduk = $postinduk;

			$arrData = $this->Pungutanbbnkb->getarrpd($postth, $postbulan, $postlokasi, '021');
			$arrDataLalu = $this->Pungutanbbnkb->getarrpdLalu($postth, $postbulan, $postlokasi, '021');
			$namafile = 'pd 06';

$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:14pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<table style="font-size: 14pt; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td colspan="19" align="center">'. $header .'</td>
</tr>
</table>
<br><br>
<table style="font-size: 12pt; border-collapse: collapse;" width="100%">
<tr>
<td colspan="17">PENERIMAAN YANG DITERIMA CASH KASIR</td>
<td align="right" colspan="2">MODEL PD 06</td>
</tr>
</table>
<table style=" font-size: 12pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="3" align="center" width="3%"><b>NO.</b></th>
<th rowspan="3" align="center" width="13%"><b>JENIS PUNGUTAN</b></th>
<th align="center" colspan="3" width="12%">PENETAPAN S/D BULAN LALU</th>
<th align="center" colspan="3" width="18%">PENETAPAN BULAN INI</th>
<th align="center" colspan="3" width="12%">PENGURANGAN</th>
<th align="center" colspan="5" width="30%">PENERIMAAN</th>
<th align="center" colspan="3" width="12%">SISA KETETAPAN S/D BULAN</th>
</tr>
<tr>
<th align="center" rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBY</th>
<th align="center" width="7%">POKOK</th>
<th align="center" width="7%">SANKSI</th>
<th align="center" rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBY</th>
<th align="center" width="5%">ANGSURAN</th>
<th align="center" width="7%">POKOK</th>
<th align="center" width="7%">SANKSI</th>
<th align="center" width="7%">JUMLAH</th>
<th rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
</tr>
<tr>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
</tr>
<tr>';
 
for($i_th = 1;$i_th <= 19; $i_th++)
{
	$html .= '<th align="center"><b>'. $i_th .'</b></th>';
}

$html .= '</tr>
<tr>
<th align="center" width="3%">A</th>
<th align="center" width="13%"><small>BBNKB I</small></th>';
 
for($i_th = 1;$i_th <= 17; $i_th++)
{
	$html .= '<th></th>';
}

$html .= '</tr>
</thead>
<tbody>';

		$no = 1;
		$arr = array();
		$tot_oby_bi = 0;
		$tot_pokok_bi = 0;
		$tot_sanksi_bi = 0;
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_bi = empty($arrData[$jenis['id_jenis']]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']]->tot_obyek;
			$pokok_bi = empty($arrData[$jenis['id_jenis']]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']]->tot_pokok;
			$sanksi_bi = empty($arrData[$jenis['id_jenis']]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']]->tot_sanksi;
			$html .= '<tr>
			<td align="center" width="3%">'. $no .'</td>
			<td align="center" width="13%">'. $jenis['kode_jenis'] .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right" width="5%">-</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi + $pokok_bi) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			</tr>';
			$tot_oby_bi += $oby_bi;
			$tot_pokok_bi += $pokok_bi;
			$tot_sanksi_bi += $sanksi_bi;
			$no++;
		}

$html .= '</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH BULAN INI</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH S/D BULAN LALU</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH S/D BULAN INI</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi + $tot_pokok_bi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
</tfoot>
</table>
<br><br>
<table style="font-size: 12px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="6" valign="top">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $dtlokasi->kota .', '. date('d') .' '. $this->Fungsi->getBulan(date('n')) .' '. date('Y') .'</td>
</tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_jabatan'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_jabatan'] .'</td>
</tr>
<tr>
<td align="center" colspan="6"></td>
</tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_nama'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_nama'] .'</td>
</tr>
</table>';

		} elseif ( $postbentuk == '07') {
			$header = 'LAPORAN PENETAPAN, PENGURANGAN, PENERIMAAN, DAN TUNGGAKAN BEA BALIK NAMA KENDARAAN BERMOTOR II<br>BULAN : '. $this->Fungsi->getBulan($postbulan) .' '. $this->Thanggaran->getDataByID($postth)->row()->th_anggaran .'<br>'. $this->Lokasi->getDataById($postlokasi)->row()->lokasi;
			$postinduk = $postinduk;

			$arrData = $this->Pungutanbbnkb->getarrpd($postth, $postbulan, $postlokasi, '022');
			$arrDataLalu = $this->Pungutanbbnkb->getarrpdLalu($postth, $postbulan, $postlokasi, '022');
			$dinamisContent = $this->folder.'pd 06 pd 07 pd 02/cetak pd 07';
			$namafile = 'pd 07';

$html = '<style type="text/css" media="print,screen">
    h3 {
        margin:0;
        font-weight:bold;
        font-size:14pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0;}
</style>
<table style="font-size: 14pt; border-collapse: collapse; font-weight: bold;" width="100%">
<tr>
<td colspan="19" align="center">'. $header .'</td>
</tr>
</table>
<br><br>
<table style="font-size: 12pt; border-collapse: collapse;" width="100%">
<tr>
<td colspan="17">PENERIMAAN YANG DITERIMA CASH KASIR</td>
<td align="right" colspan="2">MODEL PD 07</td>
</tr>
</table>
<table style=" font-size: 12pt; border-collapse: collapse;" border="1" cellpadding="3" width="100%">
<thead style="font-weight: bold;">
<tr>
<th rowspan="3" align="center" width="3%"><b>NO.</b></th>
<th rowspan="3" align="center" width="13%"><b>JENIS PUNGUTAN</b></th>
<th align="center" colspan="3" width="12%">PENETAPAN S/D BULAN LALU</th>
<th align="center" colspan="3" width="18%">PENETAPAN BULAN INI</th>
<th align="center" colspan="3" width="12%">PENGURANGAN</th>
<th align="center" colspan="5" width="30%">PENERIMAAN</th>
<th align="center" colspan="3" width="12%">SISA KETETAPAN S/D BULAN</th>
</tr>
<tr>
<th align="center" rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBY</th>
<th align="center" width="7%">POKOK</th>
<th align="center" width="7%">SANKSI</th>
<th align="center" rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
<th align="center" rowspan="2" width="4%">OBY</th>
<th align="center" width="5%">ANGSURAN</th>
<th align="center" width="7%">POKOK</th>
<th align="center" width="7%">SANKSI</th>
<th align="center" width="7%">JUMLAH</th>
<th align="center" rowspan="2">OBY</th>
<th align="center">POKOK</th>
<th align="center">SANKSI</th>
</tr>
<tr>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
<th align="center">Rp</th>
</tr>
<tr>';
 
for($i_th = 1;$i_th <= 19; $i_th++)
{
	$html .= '<th align="center"><b>'. $i_th .'</b></th>';
}

$html .= '</tr>
<tr>
<th align="center" width="3%">A</th>
<th align="center" width="13%"><small>BBNKB II</small></th>';
 
for($i_th = 1;$i_th <= 17; $i_th++)
{
	$html .= '<th></th>';
}

$html .= '</tr>
</thead>
<tbody>';

		$no = 1;
		$arr = array();
		$tot_oby_bi = 0;
		$tot_pokok_bi = 0;
		$tot_sanksi_bi = 0;
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $jenis )
		{
			$oby_bi = empty($arrData[$jenis['id_jenis']]->tot_obyek) ? '0' : $arrData[$jenis['id_jenis']]->tot_obyek;
			$pokok_bi = empty($arrData[$jenis['id_jenis']]->tot_pokok) ? '0' : $arrData[$jenis['id_jenis']]->tot_pokok;
			$sanksi_bi = empty($arrData[$jenis['id_jenis']]->tot_sanksi) ? '0' : $arrData[$jenis['id_jenis']]->tot_sanksi;
			$html .= '<tr>
			<td align="center" width="3%">'. $no .'</td>
			<td align="center" width="13%">'. $jenis['kode_jenis'] .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">'. $this->Fungsi->formatangkalaporan($oby_bi) .'</td>
			<td align="right" width="5%">-</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($pokok_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi) .'</td>
			<td align="right" width="7%">'. $this->Fungsi->formatangkalaporan($sanksi_bi + $pokok_bi) .'</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			<td align="right" width="4%">-</td>
			</tr>';
			$tot_oby_bi += $oby_bi;
			$tot_pokok_bi += $pokok_bi;
			$tot_sanksi_bi += $sanksi_bi;
			$no++;
		}

$html .= '</tbody>
<tfoot>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH BULAN INI</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_oby_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_sanksi_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($tot_pokok_bi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH S/D BULAN LALU</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
<tr style="font-weight: bold;">
<td colspan="5"><b>JUMLAH S/D BULAN INI</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->oby + $tot_oby_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $tot_pokok_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->sanksi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>'. $this->Fungsi->formatangkalaporan($arrDataLalu->pokok + $arrDataLalu->sanksi + $tot_pokok_bi + $tot_sanksi_bi) .'</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
<td align="right"><b>-</b></td>
</tr>
</tfoot>
</table>
<br><br>
<table style="font-size: 12px; border-collapse: collapse;" width="100%">
<tr>
<td colspan="6" valign="top">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $dtlokasi->kota .', '. date('d') .' '. $this->Fungsi->getBulan(date('n')) .' '. date('Y') .'</td>
</tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_jabatan'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_jabatan'] .'</td>
</tr>
<tr>
<td align="center" colspan="6"></td>
</tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr><td><br></td><td><br></td><td><br></td></tr>
<tr>
<td align="center" colspan="6">'. $paraf_1['detil_nama'] .'</td>
<td colspan="9" width="40%"></td>
<td align="center" colspan="6">'. $paraf_2['detil_nama'] .'</td>
</tr>
</table>';


		}

			
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output($namafile .'.pdf', 'I');
	}
}
