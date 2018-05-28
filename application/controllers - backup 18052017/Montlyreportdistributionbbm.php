<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Montlyreportdistributionbbm extends CI_Controller {

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
        $this->load->model('Pbbkb');
        $this->load->model('Paraf');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'LAPORAN BULANAN PENYEDIA BBM';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekap bulanan distribusi bbm';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	private function arrDistribution($getTh, $getBulan, $getLokasi)
	{
		$where = $getLokasi == '99' ? '' : " and v_entry_distribusi.`id_lokasi`='". $getLokasi ."'";
		$cmd = "SELECT
			t_item_distribusi_bbm.*
			FROM
			t_item_distribusi_bbm
			LEFT JOIN v_entry_distribusi
			ON v_entry_distribusi.`id_distribusi_bbm`=t_item_distribusi_bbm.`id_distribusi_bbm`
			WHERE
			v_entry_distribusi.`id_anggaran`='". $getTh ."'
			AND v_entry_distribusi.`id_bulan`='". $getBulan ."'". $where ."";
		$arr = array();
		foreach ($this->db->query($cmd)->result() as $row) {
			$arr[$row->id_distribusi_bbm][$row->id_item_pbbkb] = $row->jumlah;
		}
		return $arr;
	}

	private function arrRowTransaction($getTh, $getBulan, $getLokasi)
	{
		$where = $getLokasi == '99' ? '' : " and id_lokasi='". $getLokasi ."'";
		$cmd = "SELECT 
			id_distribusi_bbm,
			tgl_input,
			nama_penyalur,
			kota_asal,
			nama_spbu
			FROM
			v_entry_distribusi
			where id_anggaran='". $getTh ."' and id_bulan='". $getBulan ."'". $where ."
			ORDER BY tgl_entry";
		return $this->db->query($cmd)->result_array();
	}

	private function arrTotalBulanLalu($getTh, $getBulan, $getLokasi)
	{
		$where = $getLokasi == '99' ? '' : " and v_entry_distribusi.`id_lokasi`='". $getLokasi ."'";
		$cmd = "SELECT
			t_item_distribusi_bbm.`id_item_pbbkb`,SUM(t_item_distribusi_bbm.`jumlah`) AS total
			FROM
			t_item_distribusi_bbm
			LEFT JOIN v_entry_distribusi
			ON v_entry_distribusi.`id_distribusi_bbm`=t_item_distribusi_bbm.`id_distribusi_bbm`
			WHERE
			v_entry_distribusi.`id_anggaran`='". $getTh ."'
			AND v_entry_distribusi.`id_bulan`<'". $getBulan ."'". $where ."
			GROUP BY t_item_distribusi_bbm.`id_item_pbbkb`";
		$arr = array();
		foreach ($this->db->query($cmd)->result() as $row) {
			$arr[$row->id_item_pbbkb] = $row->total;
		}
		return $arr;
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postBulan = $param['getbulan'];
		$postAnggaran = $param['getta'];
		$postSatuan = $param['getsatuan'];
		$postLokasi = $param['getlokasi'];
		$dataLokasi = $this->Lokasi->getDataByIdLokasi($postLokasi)->row();

		$data['title'] = 'REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD';
		$data['subtitle'] = '';

		$data['header'] = $postLokasi == '99' ? 'LAPORAN BULANAN KESELURUHAN' : 'LAPORAN BULANAN '. $dataLokasi->lokasi;
		$data['header2'] = $postLokasi == '99' ? '' : 'DI '. $dataLokasi->kota;

		$data['dinamisContent'] = $this->folder.'cetak laporan rekap distribusi bbm per bulan';

		$data['arrJenisPbbkb'] = $this->Pbbkb->getAllData();
		$data['getTransaction'] = $this->arrDistribution($postAnggaran, $postBulan, $postLokasi);
		$data['getRow'] = $this->arrRowTransaction($postAnggaran, $postBulan, $postLokasi);
		$data['getTotalLalu'] = $this->arrTotalBulanLalu($postAnggaran, $postBulan, $postLokasi);
		$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$data['getBulan'] = $this->Bulan->getDataById($postBulan)->bulan;
		$data['getSatuan'] = $postSatuan;
		$data['getLokasi'] = $dataLokasi;
		$data['getParaf'] = $this->Paraf->getParafonlyKaUpad($postLokasi);
		$data['namafile'] = 'REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD';

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
		$postBulan = $param['getbulan'];
		$postAnggaran = $param['getta'];
		$getSatuan = $param['getsatuan'];
		$postLokasi = $param['getlokasi'];
		$dataLokasi = $this->Lokasi->getDataByIdLokasi($postLokasi)->row();
		
		$header = $postLokasi == '99' ? 'LAPORAN BULANAN KESELURUHAN' : 'LAPORAN BULANAN '. $dataLokasi->lokasi;
		$header2 = $postLokasi == '99' ? '' : 'DI '. $dataLokasi->kota;
		$getTahun = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$getBulan = $this->Bulan->getDataById($postBulan)->bulan;
		$getRow = $this->arrRowTransaction($postAnggaran, $postBulan, $postLokasi);
		$getTransaction = $this->arrDistribution($postAnggaran, $postBulan, $postLokasi);
		$getTotalLalu = $this->arrTotalBulanLalu($postAnggaran, $postBulan, $postLokasi);
		$arrJenisPbbkb = $this->Pbbkb->getAllData();
		$getParaf = $this->Paraf->getParafonlyKaUpad($postLokasi);
		
		$this->load->library('Pdf');
		$pdf = new Pdf('L', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD');
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

		$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:10pt;">
'. $header .'<br>
DATA PENYEDIA BAHAN BAKAR MINYAK (BBM) YANG MELAKUKAN PENJUALAN BBM<br>
'. $header2 .'<br>
BULAN : '. $getBulan .' '. $getTahun .'
</td>
</tr>
</table>
<br><br>
<div style="text-align:right;">'. $this->Satuan->printSatuan($getSatuan) .'</div>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
<tr>
	<th align="center" width="5%"><b>No</b></th>
	<th align="center" width="8%"><b>Tanggal DO</b></th>
	<th align="center" width="10%"><b>Badan Usaha Penyedia BBM</b></th>
	<th align="center" width="8%"><b>Kota</b></th>';
	foreach ( $arrJenisPbbkb as $th ) {
		$html .= '<th align="center" align="center"><b>'. $th['item_pbbkb'] .'</b></th>';
	}
	$html .= '<th align="center" width="10%"><b>Perusahaan Penerima BBM</b></th>
</tr>
</thead>
<tbody>';
	
		$arrJumlahPerJenis = array();
		$total_keseluruhan = 0;
		$no = 1;
		foreach( $getRow as $tdrow ) {
			$html .= '<tr nobr="true">
				<td align="right" width="5%">'. $no .'</td>
				<td width="8%" align="center">'. $tdrow['tgl_input'] .'</td>
				<td width="10%">'. $tdrow['nama_penyalur'] .'</td>
				<td width="8%" align="center">'. $tdrow['kota_asal'] .'</td>';
			
			$jmlperjenis = 0;
			foreach ( $arrJenisPbbkb as $td ) {
				$jml = empty($getTransaction[$tdrow['id_distribusi_bbm']][$td['id_item_pbbkb']]) ? '0' : $getTransaction[$tdrow['id_distribusi_bbm']][$td['id_item_pbbkb']];
				$html .= '<td align="right">'. $this->Satuan->printValue($jml, $getSatuan) .'</td>';
				$jmlperjenis += $jml;
				$arrJumlahPerJenis[$td['id_item_pbbkb']] += $jml;
			}

			$html .= '<td width="10%">'. $tdrow['nama_spbu'] .'</td>';
			$html .= '</tr>';
			$no++;
			$total_keseluruhan += $jmlperjenis;
		}

$html .= '</tbody>
<tfoot>
<tr>
	<td colspan="4" width="31%"><b>Jumlah Bulan ini</b></td>';
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
	}
$html .= '<td align="right" width="10%"><b>'. $this->Satuan->printValue($total_keseluruhan, $getSatuan) .'</b></td>
</tr>
<tr>
<td colspan="4" width="31%"><b>Jumlah s.d Bulan Lalu</b></td>';
	$total_bulan_lalu = 0;
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($getTotalLalu[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
		$total_bulan_lalu += $getTotalLalu[$tdfooter['id_item_pbbkb']];
	}
$html .= '<td align="right" width="10%"><b>'. $this->Satuan->printValue($total_bulan_lalu, $getSatuan) .'</b></td>
</tr>
<tr>
<td colspan="4" width="31%"><b>Jumlah s.d Bulan Ini</b></td>';
	$total_sd_bulan_ini = 0;
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($getTotalLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
		$total_sd_bulan_ini += $getTotalLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']];
	}
$html .= '<td align="right" width="10%"><b>'. $this->Satuan->printValue($total_sd_bulan_ini, $getSatuan) .'</b></td>
</tr>
</tfoot>
</table>
<br>
<br>';

if ( $postLokasi != '99' ) {
	$html .= '<table style="width:100%;">
	<tr>
		<td width="30%" style="font-size:9pt;"></td>
		<td width="40%"></td>
		<td width="30%" style="font-size:8pt;" align="center">
		'. $dataLokasi->kota .', '. date('d-m-Y') .'
		<br>
		</td>
	</tr>
	<tr>
		<td width="30%" style="font-size:9pt;"></td>
		<td width="40%"></td>
		<td width="30%" style="font-size:9pt;" align="center">
				KEPALA '. $dataLokasi->lokasi .'
				<br>
				<br>
				<br>
				<br>
				<br>
				<span style="text-decoration:underline;">'. $getParaf->nama_pegawai .'</span>
				<br>
				'. $getParaf->pangkat .'
				<br>
				NIP. '. $getParaf->nip .'
		</td>
	</tr>
</table>';
}

$html .= '<div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()).' ; '. date('d-m-Y H:i:s') .'</i></div>';


		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD.pdf', 'I');
	}
}
