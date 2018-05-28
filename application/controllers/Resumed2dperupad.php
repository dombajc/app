<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resumed2dperupad extends CI_Controller {

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
		$data['title'] = 'REKAP D2D Se-JATENG';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form laporan resume d2d per upad';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postTriwulan = $param['tw'];
		$postAnggaran = $param['ta'];

		$data['title'] = 'REKAP D2D Se-JATENG';
		$data['subtitle'] = '';

		$data['dinamisContent'] = $this->folder.'cetak laporan resume d2d per upad';
		$data['postTriwulan'] = $postTriwulan;
		$data['arrTransaksi'] = $this->getArrD2D( $postTriwulan, $postAnggaran);
		$data['arrTarget'] = $this->getArrTarget( $postTriwulan, $postAnggaran);
		$data['arrLokasi'] = $this->Lokasi->getListUpad();
		$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$data['getTriwulan'] = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;
		$data['namafile'] = 'REKAP D2D Se-JATENG';

		if ( $param['type'] == 'frame'){
            $this->load->view($this->Opsisite->getDataSite() ['cetak_template'], $data);    
        } elseif ( $param['type'] == 'cetak'){
            //$this->load->view($this->dir_cetak . 'fullbasic', $data);
        } elseif ( $param['type'] == 'excel'){
            $this->load->view($this->Opsisite->getDataSite() ['excel_template'], $data);
        }
	}

	public function getArrTarget( $postTriwulan, $postAnggaran)
	{
		$arr = array();

		$cmd = "SELECT
			v_mutasi.`id_lokasi`,
			t_target_d2d.`id_triwulan`,
			SUM(t_target_d2d.`total`) as total
			FROM
			v_mutasi
			LEFT JOIN t_target_d2d ON
			t_target_d2d.`id_jabatan`=v_mutasi.`id_jabatan`
			where id_triwulan='". $postTriwulan ."' and id_anggaran='". $postAnggaran ."'
			GROUP BY v_mutasi.`id_lokasi`,t_target_d2d.`id_triwulan`";
		foreach ( $this->db->query($cmd)->result() as $val ) {
			$arr[$val->id_lokasi] = $val->total; 
		};

		return $arr;
	}

	public function getArrD2D( $postTriwulan, $postAnggaran)
	{
		$arr = array();

		$cmd = "SELECT
			v_mutasi.`id_lokasi`,
			t_trx_d2d.`id_bulan`,
			SUM(t_trx_d2d.`jumlah`) AS jumlah
			FROM
			t_trx_d2d
			LEFT JOIN v_mutasi ON
			v_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			LEFT JOIN v_bulan ON
			v_bulan.`id_bulan`=t_trx_d2d.`id_bulan`
			WHERE
			v_bulan.`id_triwulan`='". $postTriwulan ."' AND t_trx_d2d.`id_anggaran`='". $postAnggaran ."'
			GROUP BY v_mutasi.`id_lokasi`,t_trx_d2d.`id_bulan`";

		foreach ( $this->db->query($cmd)->result() as $val ) {
			$arr[$val->id_lokasi][$val->id_bulan]['jumlah'] = $val->jumlah;
		}

		return $arr;
	}

	public function get_pdf()
	{
		error_reporting(E_ERROR | E_WARNING | E_PARSE);

		$param = $this->uri->uri_to_assoc(2);
		$postTriwulan = $param['tw'];
		$postAnggaran = $param['ta'];
		
		$getTahun = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$getTriwulan = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('REKAP D2D Se-JATENG');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		//$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT); 
		$pdf->SetMargins(PDF_MARGIN_LEFT, 10, -5);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('P', 'F4');

		$arrTransaksi = $this->getArrD2D( $postTriwulan, $postAnggaran);
		$arrTarget = $this->getArrTarget( $postTriwulan, $postAnggaran);
		$arrLokasi = $this->Lokasi->getListUpad();

		$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:10pt;">
LAPORAN RESUME DOOR TO DOOR PER UPAD<br>
TRIWULAN '. $getTriwulan .' - TAHUN '. $getTahun .'
</td>
</tr>
</table>
<br><br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
	<tr>
		<th rowspan="2" align="center" width="5%">No</th>
		<th rowspan="2" align="center" width="30%">Lokasi UP3AD</th>
		<th rowspan="2" align="center" width="8%">Target Minimal</th>
		<th colspan="3" align="center">Jumlah Obyek</th>
		<th rowspan="2" align="center" width="8%">Jumlah</th>
		<th rowspan="2" align="center" width="8%">%</th>
	</tr>
	<tr>';
			foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
				$html .= "<th align=\"center\">". $th['bulan'] ."</th>";
			}
	$html .= "</tr>
</thead>
<tbody>";
		
		$arrJumlahPerBulan = array();
		$total_target = 0;
		$total_keseluruhan = 0;
		$no = 1;
		foreach( $arrLokasi as $lokasi ) {
			$val_target = empty($arrTarget[$lokasi['id_lokasi']]) ? 0 : $arrTarget[$lokasi['id_lokasi']];
			$html .='<tr nobr="true">
				<td align="right" width="5%">'. $no .'</td>
				<td width="30%">'. $lokasi['lokasi'] .'</td>
				<td align="right" width="8%">'. number_format($val_target, 0) .'</td>';
			
			$jmlperpegawai = 0;
			foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
				$jml = empty($arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['jumlah']) ? 0 : $arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['jumlah'];
				$html .= '<td align="right">'. $jml .'</td>';
				$jmlperpegawai += $jml;
				$arrJumlahPerBulan[$td['id_bulan']] += $jml;
			}

			$persen = $jmlperpegawai > 0 && $val_target > 0 ? ($jmlperpegawai / $val_target) * 100 : 0;
			$html .='<td align="right" width="8%">'. number_format($jmlperpegawai, 0) .'</td>';
			$html .='<td align="right" width="8%">'. number_format( $persen , 2 ) .'</td>';
			$html .='</tr>';
			$no++;
			$total_target += $val_target;
			$total_keseluruhan += $jmlperpegawai;
		}
$persen_total = $total_keseluruhan > 0 && $total_target > 0 ? ($total_keseluruhan / $total_target) * 100 : 0;
$html .= '</tbody>
<tfoot>
	<tr>
		<td colspan="2" width="35%">Total Keseluruhan</td>
		<td align="right" width="8%">'. number_format($total_target, 0) .'</td>';
		foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
			$html .= '<td align="right">'. number_format($arrJumlahPerBulan[$tdfooter['id_bulan']], 0) .'</td>';
		}
		$html .= '<td align="right" width="8%">'. number_format($total_keseluruhan, 0) .'</td>
		<td align="right" width="8%">'. number_format( $persen_total , 2 ) .'</td>
	</tr>
</tfoot>
</table>';
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('REKAP D2D Se-JATENG.pdf', 'I');
	}
}
