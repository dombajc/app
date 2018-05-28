<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportpd extends CI_Controller {

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
        $this->load->model('Targetpelypd');
		$this->load->model('Trxpd');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKAP OBYEK PEMUNGUTAN PAJAK';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form obyek pajak daerah';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_report()
	{
		$param = $this->uri->uri_to_assoc(2);
		$postTriwulan = $param['tw'];
		$postAnggaran = $param['ta'];
		$postPd = $param['lapjenis'];

		$data['title'] = 'REKAP OBYEK PEMUNGUTAN PAJAK DAERAH';
		$data['subtitle'] = '';

		if ( $postPd == '02' ) {
			$data['dinamisContent'] = $this->folder.'cetak laporan obyek pajak daerah khusus bbnkb';
			$data['arrTransaksi'] = $this->Trxpd->getArrForLaporanObyekPerUpadKhususBBNKB( $postTriwulan, $postAnggaran);
		} else {
			$data['dinamisContent'] = $this->folder.'cetak laporan obyek pajak daerah';
			$data['arrTransaksi'] = $this->Trxpd->getArrForLaporanObyekPerUpad( $postTriwulan, $postAnggaran, $postPd);
		}
		
		$data['postTriwulan'] = $postTriwulan;
		$data['getPd'] = $this->Pd->getDetilById($postPd, $postTriwulan, $postAnggaran);
		
		$data['arrLokasi'] = $this->Lokasi->getListUpad();
		$data['getTahun'] = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$data['getTriwulan'] = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;
		$data['namafile'] = 'REKAP OBYEK PEMUNGUTAN PAJAK DAERAH';

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
		$postPd = $param['lapjenis'];
		
		$getTahun = $this->Thanggaran->getDataByID($postAnggaran)->row()->th_anggaran;
		$getTriwulan = $this->Triwulan->getDataByID($postTriwulan)->row()->triwulan;

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'F4', true, 'UTF-8', false);
		$pdf->SetTitle('REKAP OBYEK PEMUNGUTAN PAJAK DAERAH');
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
		

		$arrLokasi = $this->Lokasi->getListUpad();
		$getPd = $this->Pd->getDetilById($postPd, $postTriwulan, $postAnggaran);

		if ( $postPd == '02' ) {
			$pdf->SetFont('helvetica', '', 9);
			$pdf->AddPage('L', 'F4');

			$arrTransaksi = $this->Trxpd->getArrForLaporanObyekPerUpadKhususBBNKB( $postTriwulan, $postAnggaran);
			$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:10pt;">
REKAP OBYEK PEMUNGUTAN PAJAK DAERAH<br>
BEA BALIK NAMA KENDARAAN BERMOTOR<br>
TRIWULAN '. $getTriwulan .' - TAHUN '. $getTahun .'
</td>
</tr>
</table>
<br><br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
	<tr>
		<th rowspan="3" width="5%" align="center"><b>No</b></th>
		<th rowspan="3" align="center" width="18%"><b>UP3AD</b></th>
		<th colspan="6" align="center"><b>Jumlah Obyek</b></th>
		<th rowspan="2" colspan="2" align="center"><b>Jumlah</b></th>
	</tr>
	<tr>';
			foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
				$html .= '<th align="center" colspan="2" align="center"><b>'. $th['bulan'] .'</b></th>';
			}
	$html .= "</tr>
	<tr>";
		foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
			foreach ( $this->Pd->getArrayBBNKB() as $thbbnkb ) {
				$html .= "<th align=\"center\"><b>". $thbbnkb['nama_rekening'] ."</b></th>";
			}
		}
		foreach ( $this->Pd->getArrayBBNKB() as $thbbnkb ) {
			$html .= "<th align=\"center\"><b>". $thbbnkb['nama_rekening'] ."</b></th>";
	}
	$html .= "</tr>
</thead>
<tbody>";
		
		$arrJumlahPerBulan = array();
		$total_keseluruhan = array();
		
		$no = 1;
		foreach( $arrLokasi as $lokasi ) {
			$jmlperup3ad = array();
			$html .= '<tr nobr="true">
				<td align="right" width="5%">'. $no .'</td>
				<td width="18%">'. $lokasi['lokasi'] .'</td>';
			
			foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
				foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkb ) {
					$jml = empty($arrTransaksi[$tdbbnkb['id_rek_pd']][$lokasi['id_lokasi']][$td['id_bulan']]['realisasi']) ? 0 : $arrTransaksi[$tdbbnkb['id_rek_pd']][$lokasi['id_lokasi']][$td['id_bulan']]['realisasi'];
					$html .= '<td align="right">'. number_format($jml, 0) .'</td>';
					$jmlperup3ad[$tdbbnkb['id_rek_pd']] += $jml;
					$arrJumlahPerBulan[$tdbbnkb['id_rek_pd']][$td['id_bulan']] += $jml;
				}
			}
			foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkb2 ) {
				$jml_per_bbnkb = empty($jmlperup3ad[$tdbbnkb2['id_rek_pd']]) ? 0 : $jmlperup3ad[$tdbbnkb2['id_rek_pd']];
				$html .= '<td align="right">'. number_format($jml_per_bbnkb, 0) .'</td>';
				$total_keseluruhan[$tdbbnkb2['id_rek_pd']] += $jml_per_bbnkb;
			}
			
			$html .= '</tr>';
			$no++;
			
		}

$html .= '</tbody>
<tfoot>
	<tr>
		<td colspan="2"><b>TOTAL</b></td>';
		foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
			foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkbfooter ) {
				$html .= '<td align="right"><b>'. number_format($arrJumlahPerBulan[$tdbbnkbfooter['id_rek_pd']][$tdfooter['id_bulan']], 0) .'</b></td>';
			}
		}

		$total = 0;
		foreach ( $this->Pd->getArrayBBNKB() as $tdbbnkbfooter2 ) {
			$html .= '<td align="right"><b>'. number_format($total_keseluruhan[$tdbbnkbfooter2['id_rek_pd']], 0) .'</b></td>';
			$total += $total_keseluruhan[$tdbbnkbfooter2['id_rek_pd']];
		}
		$persentase = $getPd->total_target <= 0 ? 0 : ($total/$getPd->total_target)*100;
		$html .= '</tr>
	<tr>
		<td colspan="8"><b>JUMLAH BBNKB I + BBNKB II</b></td>
		<td align="right" colspan="2"><b>'. number_format($total, 0) .'</b></td>
	</tr>
	<tr>
		<td colspan="8"><b>TARGET TRIWULAN'. $getTriwulan .'</b></td>
		<td align="right" colspan="2"><b>'. number_format($getPd->total_target, 0) .'</b></td>
	</tr>
	<tr>
		<td colspan="8"><b>PROSENTASE CAPAIAN (%)</b></td>
		<td align="right" colspan="2"><b>'. number_format($persentase, 0) .'</b></td>
	</tr>
</tfoot>
</table>';

		} else {
			$pdf->SetFont('helvetica', '', 8);
			$pdf->AddPage('P', 'F4');

			$arrTransaksi = $this->Trxpd->getArrForLaporanObyekPerUpad( $postTriwulan, $postAnggaran, $postPd);

			$html = '<style type="text/css" media="print,screen">
#tabel { width: 100%; page-break-before: always;}
#tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:0.5px solid #000; padding:0.5em 0.3em; }
h1,h2,h3,h4,h5,h6 { margin:0; text-align:center;}
#judul { width: 100%; text-align:center; }
</style>
<table style="width:100%;">
<tr>
<td style="text-align:center; font-size:10pt;">
REKAP OBYEK PEMUNGUTAN PAJAK DAERAH<br>
PAJAK AIR PERMUKAAN<br>
TRIWULAN '. $getTriwulan .' - TAHUN '. $getTahun .'
</td>
</tr>
</table>
<br><br><br>
<table id="tabel" border="1" cellspacing="0" cellpadding="4" style="border-collapse: collapse;">
<thead>
	<tr>
		<th rowspan="2" align="center" width="5%"><b>No</b></th>
		<th rowspan="2" align="center" width="35%"><b>UP3AD</b></th>
		<th colspan="3" align="center"><b>Jumlah Obyek</b></th>
		<th rowspan="2" align="center"><b>Jumlah</b></th>
	</tr>
	<tr>';
		foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $th ) {
			$html .= '<th align="center" align="center"><b>'. $th['bulan'] .'</b></th>';
		}
	$html .= "</tr>
</thead>
<tbody>";
		
		$arrJumlahPerBulan = array();
			$total_keseluruhan = 0;
			$no = 1;
			foreach( $arrLokasi as $lokasi ) {

				$html .= '<tr nobr="true">
					<td align="right" width="5%">'. $no .'</td>
					<td width="35%">'. $lokasi['lokasi'] .'</td>';
				
				$jmlperup3ad = 0;
				foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $td ) {
					$jml = empty($arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['realisasi']) ? 0 : $arrTransaksi[$lokasi['id_lokasi']][$td['id_bulan']]['realisasi'];
					$html .= '<td align="right">'. number_format($jml, 0) .'</td>';
					$jmlperup3ad += $jml;
					$arrJumlahPerBulan[$td['id_bulan']] += $jml;
				}

				$html .= '<td align="right">'. number_format($jmlperup3ad, 0) .'</td>';
				$html .= '</tr>';
				$no++;
				$total_keseluruhan += $jmlperup3ad;
			}

			$persentase = $getPd->total_target <= 0 ? 0 : ($total_keseluruhan/$getPd->total_target)*100;

$html .= '</tbody>
<tfoot>
	<tr>
		<td colspan="2"><b>JUMLAH</b></td>';
		foreach ( $this->Bulan->getAllByIdTriwulan($postTriwulan) as $tdfooter ) {
			$html .= '<td align="right"><b>'. number_format($arrJumlahPerBulan[$tdfooter['id_bulan']], 0) .'</b></td>';
		}
	$html .= '<td align="right"><b>'. number_format($total_keseluruhan, 0) .'</b></td>
	</tr>
	<tr>
		<td colspan="5"><b>TARGET TRIWULAN '. $getTriwulan .'</b></td>
		<td align="right"><b>'. number_format($getPd->total_target, 0) .'</b></td>
	</tr>
	<tr>
		<td colspan="5"><b>PROSENTASE CAPAIAN (%)</b></td>
		<td align="right"><b>'. number_format($persentase, 0) .'</b></td>
	</tr>
</tfoot>
</table>';
		}

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('REKAP OBYEK PEMUNGUTAN PAJAK DAERAH.pdf', 'I');
	}
}
