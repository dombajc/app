<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pbbkbperbulan extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan rekap pbbkb per bulan';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	private function arrEntrian($getDasar, $getTh, $getBulan)
	{
		$cmd = "SELECT
			v_penyetor_pbbkb.`id_lokasi`,
			t_item_transaksi_pbbkb.`id_item_pbbkb`,
			SUM(t_item_transaksi_pbbkb.`jumlah`) total
			FROM
			t_item_transaksi_pbbkb
			LEFT JOIN t_transaksi_pbbkb2 ON
			t_transaksi_pbbkb2.`id_transaksi_pbbkb`=t_item_transaksi_pbbkb.`id_transaksi_pbbkb`
			LEFT JOIN v_penyetor_pbbkb ON
			v_penyetor_pbbkb.`id_lokasi_pbbkb`=t_transaksi_pbbkb2.`id_lokasi_pbbkb`
			WHERE id_anggaran='". $getTh ."' and id_bulan=". $getBulan ." and id_dasar_trx_pbbkb='". $getDasar ."'
			GROUP BY v_penyetor_pbbkb.`id_lokasi`,t_item_transaksi_pbbkb.`id_item_pbbkb`
			ORDER BY v_penyetor_pbbkb.`id_lokasi`";
		$arr = array();
		foreach ($this->db->query($cmd)->result() as $row) {
			$arr[$row->id_lokasi][$row->id_item_pbbkb] = $row->total;
		}
		return $arr;
	}

	private function arrSPBU($getDasar, $getTh, $getBulan)
	{
		$cmd = "SELECT
			v_penyetor_pbbkb.`id_lokasi`,
			COUNT(*) AS jml
			FROM
			t_transaksi_pbbkb2
			LEFT JOIN v_penyetor_pbbkb ON
			v_penyetor_pbbkb.`id_lokasi_pbbkb`=t_transaksi_pbbkb2.`id_lokasi_pbbkb`
			where id_anggaran='". $getTh ."' and id_bulan=". $getBulan ." and id_dasar_trx_pbbkb='". $getDasar ."'
			GROUP BY v_penyetor_pbbkb.`id_lokasi`";
		$arr = array();
		foreach ($this->db->query($cmd)->result() as $row) {
			$arr[$row->id_lokasi] = $row->jml;
		}
		return $arr;
	}

	private function arrEntrianBulanLalu($getDasar, $getTh, $getBulan)
	{
		$cmd = "SELECT
			t_item_transaksi_pbbkb.`id_item_pbbkb`,SUM(t_item_transaksi_pbbkb.`jumlah`) as total
			FROM
			t_item_transaksi_pbbkb
			LEFT JOIN t_transaksi_pbbkb2 ON
			t_transaksi_pbbkb2.`id_transaksi_pbbkb`=t_item_transaksi_pbbkb.`id_transaksi_pbbkb`
			WHERE t_transaksi_pbbkb2.`id_anggaran`='". $getTh ."'
			AND t_transaksi_pbbkb2.`id_bulan`<". $getBulan ."
			AND t_transaksi_pbbkb2.`id_dasar_trx_pbbkb`='". $getDasar ."'
			GROUP BY id_item_pbbkb";
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
		$postDasar = $param['getdasar'];
		$postSatuan = $param['getsatuan'];

		$data['title'] = 'REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD';
		$data['subtitle'] = '';

		$data['dinamisContent'] = $this->folder.'cetak laporan rekap pbbkb per bulan';

		$data['arrJenisPbbkb'] = $this->Pbbkb->getAllData();
		$data['getDasar'] = $this->Dasar->getDataById($postDasar)->keterangan;
		$data['getEntrian'] = $this->arrEntrian($postDasar, $postAnggaran, $postBulan);
		$data['getJmlSPBU'] = $this->arrSPBU($postDasar, $postAnggaran, $postBulan);
		$data['getEntrianLalu'] = $this->arrEntrianBulanLalu($postDasar, $postAnggaran, $postBulan);
		$data['arrLokasi'] = $this->Lokasi->getListUpad();
		$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$data['getBulan'] = $this->Bulan->getDataById($postBulan)->bulan;
		$data['getSatuan'] = $postSatuan;
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
		$postDasar = $param['getdasar'];
		$getSatuan = $param['getsatuan'];
		
		$getTahun = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$getBulan = $this->Bulan->getDataById($postBulan)->bulan;
		$getDasar = $this->Dasar->getDataById($postDasar)->keterangan;
		$getJmlSPBU = $this->arrSPBU($postDasar, $postAnggaran, $postBulan);
		$getEntrian = $this->arrEntrian($postDasar, $postAnggaran, $postBulan);
		$getEntrianLalu = $this->arrEntrianBulanLalu($postDasar, $postAnggaran, $postBulan);
		$arrJenisPbbkb = $this->Pbbkb->getAllData();
		$arrLokasi = $this->Lokasi->getListUpad();

		$this->load->library('Pdf');
		$pdf = new Pdf('L', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT-10, 10, PDF_MARGIN_RIGHT); 
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		

		$arrLokasi = $this->Lokasi->getListUpad();

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
REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD<br>
BERDASARKAN '. $getDasar .' SPBU<br>
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
	<th align="center" width="15%"><b>UP3AD</b></th>
	<th align="center" width="5%"><b>Jumlah SPBU</b></th>';
	foreach ( $arrJenisPbbkb as $th ) {
		$html .= '<th align="center" align="center"><b>'. $th['item_pbbkb'] .'</b></th>';
	}
	$html .= '<th align="center"><b>Jumlah</b></th>
</tr>
</thead>
<tbody>';
	
		$arrJumlahPerJenis = array();
		$total_keseluruhan = 0;
		$no = 1;
		$jmlspbu = 0;
		foreach( $arrLokasi as $lokasi ) {
			$countSpbu = empty($getJmlSPBU[$lokasi['id_lokasi']]) ? 0 : $getJmlSPBU[$lokasi['id_lokasi']];
			$html .= '<tr nobr="true">
				<td align="right" width="5%">'. $no .'</td>
				<td width="15%">'. $lokasi['lokasi'] .'</td>
				<td width="5%" align="right">'. $countSpbu .'</td>';
			
			$jmlperjenis = 0;
			foreach ( $arrJenisPbbkb as $td ) {
				$jml = empty($getEntrian[$lokasi['id_lokasi']][$td['id_item_pbbkb']]) ? '0' : $getEntrian[$lokasi['id_lokasi']][$td['id_item_pbbkb']];
				$html .= '<td align="right">'. $this->Satuan->printValue($jml, $getSatuan) .'</td>';
				$jmlperjenis += $jml;
				$arrJumlahPerJenis[$td['id_item_pbbkb']] += $jml;
			}

			$html .= '<td align="right">'. $this->Satuan->printValue($jmlperjenis, $getSatuan) .'</td>';
			$html .= '</tr>';
			$no++;
			$total_keseluruhan += $jmlperjenis;
			$jmlspbu += $countSpbu;
		}

$html .= '</tbody>
<tfoot>
<tr>
	<td colspan="2"><b>Jumlah Bulan ini</b></td>
	<td align="center"><b>'. number_format($jmlspbu) .'</b></td>';
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
	}
$html .= '<td align="right"><b>'. $this->Satuan->printValue($total_keseluruhan, $getSatuan) .'</b></td>
</tr>
<tr>
<td colspan="3"><b>Jumlah s.d Bulan Lalu</b></td>';
	$total_bulan_lalu = 0;
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($getEntrianLalu[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
		$total_bulan_lalu += $getEntrianLalu[$tdfooter['id_item_pbbkb']];
	}
$html .= '<td align="right"><b>'. $this->Satuan->printValue($total_bulan_lalu, $getSatuan) .'</b></td>
</tr>
<tr>
<td colspan="3"><b>Jumlah s.d Bulan Ini</b></td>';
	$total_sd_bulan_ini = 0;
	foreach ( $arrJenisPbbkb as $tdfooter ) {
		$html .= '<td align="right"><b>'. $this->Satuan->printValue($getEntrianLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']], $getSatuan) .'</b></td>';
		$total_sd_bulan_ini += $getEntrianLalu[$tdfooter['id_item_pbbkb']]+$arrJumlahPerJenis[$tdfooter['id_item_pbbkb']];
	}
$html .= '<td align="right"><b>'. $this->Satuan->printValue($total_sd_bulan_ini, $getSatuan) .'</b></td>
</tr>
</tfoot>
</table>
<div style="float:right;"><i>dicetak : '. element('username', $this->Opsisite->getDataUser()).' ; '. date('d-m-Y H:i:s') .'</i></div>';


		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('REKAP BULANAN DISTRIBUSI BBM DI WILAYAH KERJA UP3AD.pdf', 'I');
	}
}
