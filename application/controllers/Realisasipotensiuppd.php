<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Realisasipotensiuppd extends CI_Controller {

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
	private $folder = 'esamsat jateng/';
	
	public function __construct() {
        parent::__construct();
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
        $this->load->Model('Esamsatjateng');
        $this->load->Model('Paraf');
    }
	
	public function index()
	{
		$data['title'] = 'REALISASI POTENSI UPPD';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'laporan realisasi potensi uppd';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function cetak() {
		$paramtipe = $this->security->xss_clean($_GET['type']);
		$parampelaporan = $this->security->xss_clean($_GET['jnslaporan']);

		switch ($paramtipe) {
			case 'printout':
				if ( $parampelaporan == 0 ) :
					if ( $this->security->xss_clean($_GET['lok']) == '99' ) {
						$this->printrekapview();
					} else {
						$this->printrekapviewperlokasi();
					}
				else :
					$this->printjurnalview();
				endif;
				break;

			case 'pdf' :
				if ( $parampelaporan == 0 ) :
					if ( $this->security->xss_clean($_GET['lok']) == '99' ) {
						$this->pdfrekapview();
					} else {
						$this->pdfrekapviewperlokasi();
					}
					else :
						$this->pdfjurnalview();
						endif;
				break;

			case 'excel' :
				echo '<style type="text/css" media="print,screen">
					.str2{ mso-number-format:\@; }
				</style>';
				header("Content-type: application/vnd-ms-excel");
				
				if ( $parampelaporan == 0 ) :
					if ( $this->security->xss_clean($_GET['lok']) == '99' ) {
						header("Content-Disposition: attachment; filename=rekam_potensi_uppd.xls");
						$this->printrekapview();
					} else {
						header("Content-Disposition: attachment; filename=jurnal_potensi_uppd.xls");
						$this->printrekapviewperlokasi();
					}
				else :
					$this->printjurnalview();
				endif;
				
				break;
			
			default:
				# code...
				break;
		}

	}

	private function get_jenis_pelaporan( $value ) {
		if ( $value == 0 ) {
			return "REKAP";
		} else {
			return "JURNAL";
		}
	}

	private function get_arr_data() {
		$arr = array();

		$parambulan = $this->security->xss_clean($_GET['m']);
		$paramtahun = $this->security->xss_clean($_GET['y']);
		$paramjenistransaksi = $this->security->xss_clean($_GET['jnstrx']);
		$paramjenispenerimaan = $this->security->xss_clean($_GET['jnsbyr']);
		$parampelaporan = $this->security->xss_clean($_GET['jnslaporan']);
		$parambank = $this->security->xss_clean($_GET['b']);

		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;
		$bulan = $this->Fungsi->getBulan( $parambulan );
		$nama_penerimaan = $this->Esamsatjateng->get_nama_penerimaan_by_id($paramjenispenerimaan);

		
		
		
		$set_jenis_transaksi = empty($paramjenistransaksi) || $paramjenistransaksi == '' ? '' : $paramjenistransaksi;
		$set_type = $this->security->xss_clean($_GET['jnstipe']) == '00' ? 'Obyek Transaksi' : 'Rupiah Penerimaan';
		
		if ( $parampelaporan == 0 ) {
			
			if ( $this->security->xss_clean($_GET['lok']) == '99' ) {
				$arr['rows'] = $this->Esamsatjateng->get_rekap_potensi_all();
				$arr['header1'] = 'SISTEM ADMINISTRASI KENDARAAN DAN PAJAK ONLINE (SAKPOLE)<br>REKAP REALISASI '. $nama_penerimaan .' BERDASARKAN POTENSI UPPD<br>TAHUN ANGGARAN '. $tahun .'<br>Berdasarkan : '. $set_type;
			} else {
				$lokasi = $this->Lokasi->getNamaLokasiById($this->security->xss_clean($_GET['lok']));
				$arr['rows'] = $this->Esamsatjateng->get_rekap_potensi_by_lokasi();
				$arr['header1'] = 'SISTEM ADMINISTRASI KENDARAAN DAN PAJAK ONLINE (SAKPOLE)<br>REKAP REALISASI '. $nama_penerimaan .' BERDASARKAN POTENSI UPPD<br>TAHUN ANGGARAN '. $tahun .'<br>'. $lokasi .'<br>Berdasarkan : '. $set_type;
			}
			
		} else {
			if ( $this->security->xss_clean($_GET['lok']) == '99' ) {
				$arr['rows'] = $this->Esamsatjateng->get_jurnal_potensi_all()->result_array();
				$arr['header1'] = 'SISTEM ADMINISTRASI KENDARAAN DAN PAJAK ONLINE (SAKPOLE)<br>JURNAL REALISASI Se-JAWA TENGAH';
			} else {
				$lokasi = $this->Lokasi->getNamaLokasiById($this->security->xss_clean($_GET['lok']));
				$arr['rows'] = $this->Esamsatjateng->get_jurnal_potensi_by_lokasi()->result_array();
				$arr['header1'] = 'SISTEM ADMINISTRASI KENDARAAN DAN PAJAK ONLINE (SAKPOLE)<br>JURNAL REALISASI '. $nama_penerimaan .' BERDASARKAN POTENSI UPPD<br>TAHUN ANGGARAN '. $tahun .' Bulan : '. $bulan .'<br>'. $lokasi;
			}
			
		}
		
		$arr['nama_penerimaan'] = $nama_penerimaan;
		//$arr['set_id_penerimaan'] = $paramjenispenerimaan;
		
		//$arr['header2'] = empty( $paramjenistransaksi ) || $paramjenistransaksi == '' ? '' : 'e-SAMSAT '. $this->Esamsatjateng->get_nama_transaksi_by_id($paramjenistransaksi);
		//$arr['header201'] = empty( $parambank ) || $parambank == '' ? '' : ' / '. //$this->Esamsatjateng->get_bank_by_id($parambank);
		//$arr['header3'] = $paramopsi == 0 ? 'Bulan : '. $bulan . ' '. $tahun : 'Periode : '. //$this->Fungsi->format_sql_to_indo($format_tgl_awal['getdate']) .' s.d '. //$this->Fungsi->format_sql_to_indo($format_tgl_akhir['getdate']);
		//$arr['header4'] = $paramopsi == 0 ? '' : 'Jam Transaksi : '. $format_tgl_awal['gettime'] .' s.d '. $format_tgl_akhir['gettime'];

		return $arr;
	}

	private function printjurnalview()
	{

		$getfunc = $this->get_arr_data();

		$no = 1;
		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<center>';
		$html .= '<h2>'. $getfunc['header1'] .'</h2>';
		//$html .= $getfunc['header2'] == '' ? '' : '<h2>'. $getfunc['header2'] . $getfunc['header201'] .'</h2>';
		//$html .= '<h4>'. $getfunc['header3'] .'</h4>';
		//$html .= $getfunc['header4'] == '' ? '' : '<h4>'. $getfunc['header4'] .'</h4>';
		$html .= '</center><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:9px;">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nomor Polisi</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Alamat</th>
					<th colspan="3">'. $getfunc['nama_penerimaan'] .'</th>
					<th colspan="3">Transaksi</th>
				</tr>
				<tr>
					<th>Pokok</th>
					<th>Denda</th>
					<th>Jumlah</th>
					<th>Tanggal</th>
					<th>Bank</th>
					<th>Kode Billing</th>
				</tr>
			</thead>
			<tbody>';
		foreach ( $getfunc['rows'] as $r ) {
			$html .= '<tr>
				<td align="right">'. $no .'</td>
				<td align="center">'. $r['no_polisi'] .'</td>
				<td>'. $r['nama_pemilik'] .'</td>
				<td>'. $r['alamat'] .'</td>
				<td align="right" class="str2">'. number_format($r['pokok'],0) .'</td>
				<td align="right" class="str2">'. number_format($r['denda'],0) .'</td>
				<td align="right" class="str2">'. number_format(($r['pokok'] + $r['denda']),0) .'</td>
				<td align="center">'. $r['tgl_bayar'] .'</td>
				<td align="center">'. $r['nama_bank'] .'</td>
				<td align="center">'. $r['kode_billing'] .'</td>
			</tr>';
			$no++;
		}
		$html .= '</tbody>
		</table>';

		$html .= '<i style="font-size:9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br><br>';

		echo $html;
	}

	private function printrekapview() {
		$getfunc = $this->get_arr_data();
		$arrBulan = $this->Bulan->getAllData()->result();
		$rows = $getfunc['rows'];
		$no = 1;

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<center>';
		$html .= '<h2>'. $getfunc['header1'] .'</h2>';
		//$html .= $getfunc['header2'] == '' ? '' : '<h2>'. $getfunc['header2'] . $getfunc['header201'] .'</h2>';
		//$html .= '<h4>'. $getfunc['header3'] .'</h4>';
		//$html .= $getfunc['header4'] == '' ? '' : '<h4>'. $getfunc['header4'] .'</h4>';
		$html .= '</center><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:12px;">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">SAMSAT</th>
					<th colspan="12">BULAN</th>
					<th rowspan="2">JUMLAH</th>
				</tr>
				<tr>';
				foreach ( $arrBulan as $th ) {
					$html .= '<th>'. $th->bulan .'</th>';
				}
		$html .= '</tr>
			</thead>
			<tbody>';
		
		$i = 1;
		$arr_jml_per_bulan = array();
		$total = 0;
		foreach( $this->Lokasi->arrUppd()->result() as $td ) {
			$jml_per_lokasi = 0;
			$html .= '<tr>
				<td align="right">'. $i .'</td>
				<td>'. $td->id_lokasi .' '. $td->lokasi .'</td>';
			foreach( $arrBulan as $b ) {
				$val = empty($rows[$td->id_lokasi][$b->id_bulan]) ? 0 : $rows[$td->id_lokasi][$b->id_bulan];
				$html .= '<td align="right">'. number_format($val) .'</td>';
				$jml_per_lokasi += $val;
				$arr_jml_per_bulan[$b->id_bulan][] = $val;
				$total += $val;
			}
			$html .= '<td align="right">'. number_format($jml_per_lokasi) .'</td>
			</tr>';
			$i++;
		}
		

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td align="right" colspan="2"><b>TOTAL</b></td>';
				foreach( $arrBulan as $f ) {
					$html .= '<td align="right" class="str2"><b>'. number_format(array_sum($arr_jml_per_bulan[$f->id_bulan])).'</b></td>';
				}
		$html .= '<td align="right" class="str2"><b>'. number_format($total) .'</b></td>
			</tr>
		</tfoot>
		</table>';
		
		echo $html;
	}

	private function printrekapviewperlokasi() {
		$getfunc = $this->get_arr_data();
		$arrBulan = $this->Bulan->getAllData()->result();
		$rows = $getfunc['rows'];
		$no = 1;

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<center>';
		$html .= '<h2>'. $getfunc['header1'] .'</h2>';
		//$html .= $getfunc['header2'] == '' ? '' : '<h2>'. $getfunc['header2'] . $getfunc['header201'] .'</h2>';
		//$html .= '<h4>'. $getfunc['header3'] .'</h4>';
		//$html .= $getfunc['header4'] == '' ? '' : '<h4>'. $getfunc['header4'] .'</h4>';
		$html .= '</center><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:12px;">
			<thead>
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2">SAMSAT</th>
					<th colspan="12">BULAN</th>
					<th rowspan="2">JUMLAH</th>
				</tr>
				<tr>';
				foreach ( $arrBulan as $th ) {
					$html .= '<th>'. $th->bulan .'</th>';
				}
		$html .= '</tr>
			</thead>
			<tbody>';
		
		$i = 1;
		$arr_jml_per_bulan = array();
		$total = 0;
		foreach( $this->Fungsi->arrKodeJenisPajak() as $td ) {
			$jml_per_lokasi = 0;
			$html .= '<tr>
				<td align="right">'. $i .'</td>
				<td>'. $td['kode_jenis'] .'</td>';
			foreach( $arrBulan as $b ) {
				$val = empty($rows[$td['id_jenis']][$b->id_bulan]) ? 0 : $rows[$td['id_jenis']][$b->id_bulan];
				$html .= '<td align="right">'. number_format($val) .'</td>';
				$jml_per_lokasi += $val;
				$arr_jml_per_bulan[$b->id_bulan][] = $val;
				$total += $val;
			}
			$html .= '<td align="right">'. number_format($jml_per_lokasi) .'</td>
			</tr>';
			$i++;
		}
		

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td align="right" colspan="2"><b>TOTAL</b></td>';
				foreach( $arrBulan as $f ) {
					$html .= '<td align="right"><b>'. number_format(array_sum($arr_jml_per_bulan[$f->id_bulan])).'</b></td>';
				}
		$html .= '<td align="right"><b>'. number_format($total) .'</b></td>
			</tr>
		</tfoot>
		</table>';
		
		echo $html;
	}

	private function pdfrekapview() {
		ini_set('max_execution_time', 0);
		$this->load->library('Pdf');

		$getfunc = $this->get_arr_data();
		$arrBulan = $this->Bulan->getAllData()->result();
		$rows = $getfunc['rows'];
		$no = 1;

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 12);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP-15, PDF_MARGIN_RIGHT-10);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'F4');

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<div style="text-align:center;">';
		$html .= '<h2>'. $getfunc['header1'];
		$html .= '</h2>';
		$html .= '</div><br><br>';
		$html .= '<table border="1" cellpadding="3px" id="tabel" style="font-size:8px;">
				<thead>
				<tr>
					<th rowspan="2" width="3%" align="center"><b>No</b></th>
					<th rowspan="2" width="12%" align="center"><b>SAMSAT</b></th>
					<th colspan="12" width="72%" align="center"><b>BULAN</b></th>
					<th rowspan="2" width="11%" align="center"><b>JUMLAH</b></th>
				</tr>
				<tr>';
				foreach ( $arrBulan as $th ) {
					$html .= '<th width="6%" align="center"><b>'. $th->bulan .'</b></th>';
				}
		$html .= '</tr>
			</thead>
			<tbody>';

			$i = 1;
			$arr_jml_per_bulan = array();
			$total = 0;
			foreach( $this->Lokasi->arrUppd()->result() as $td ) {
				$jml_per_lokasi = 0;
				$html .= '<tr>
					<td align="right" width="3%">'. $i .'</td>
					<td width="12%">'. $td->lokasi .'</td>';
				foreach( $arrBulan as $b ) {
					$val = empty($rows[$td->id_lokasi][$b->id_bulan]) ? 0 : $rows[$td->id_lokasi][$b->id_bulan];
					$html .= '<td align="right" width="6%">'. number_format($val) .'</td>';
					$jml_per_lokasi += $val;
					$arr_jml_per_bulan[$b->id_bulan][] = $val;
					$total += $val;
				}
				$html .= '<td align="right" width="11%">'. number_format($jml_per_lokasi) .'</td>
				</tr>';
				$i++;
			}

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td align="right" colspan="2"><b>TOTAL</b></td>';
				foreach( $arrBulan as $f ) {
					$html .= '<td align="right"><b>'. number_format(array_sum($arr_jml_per_bulan[$f->id_bulan])).'</b></td>';
				}
		$html .= '<td align="right"><b>'. number_format($total) .'</b></td>
			</tr>
		</tfoot>
		</table>';

		$html .= '<i style="font-size: 9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br>';
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( time() .'_rekap.pdf', 'I');
	}

	private function pdfrekapviewperlokasi() {
		ini_set('max_execution_time', 0);
		$this->load->library('Pdf');

		$getfunc = $this->get_arr_data();
		$arrBulan = $this->Bulan->getAllData()->result();
		$rows = $getfunc['rows'];
		$no = 1;

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 12);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP-15, PDF_MARGIN_RIGHT-10);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'F4');

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<div style="text-align:center;">';
		$html .= '<h2>'. $getfunc['header1'];
		$html .= '</h2>';
		$html .= '</div><br><br>';
		$html .= '<table border="1" cellpadding="3px" id="tabel" style="font-size:8px;">
				<thead>
				<tr>
					<th rowspan="2" width="3%" align="center"><b>No</b></th>
					<th rowspan="2" width="12%" align="center"><b>SAMSAT</b></th>
					<th colspan="12" width="72%" align="center"><b>BULAN</b></th>
					<th rowspan="2" width="11%" align="center"><b>JUMLAH</b></th>
				</tr>
				<tr>';
				foreach ( $arrBulan as $th ) {
					$html .= '<th width="6%" align="center"><b>'. $th->bulan .'</b></th>';
				}
		$html .= '</tr>
			</thead>
			<tbody>';

			$i = 1;
			$arr_jml_per_bulan = array();
			$total = 0;
			foreach( $this->Fungsi->arrKodeJenisPajak() as $td ) {
				$jml_per_lokasi = 0;
				$html .= '<tr>
					<td align="right" width="3%">'. $i .'</td>
					<td width="12%" align="center">'. $td['kode_jenis'] .'</td>';
				foreach( $arrBulan as $b ) {
					$val = empty($rows[$td['id_jenis']][$b->id_bulan]) ? 0 : $rows[$td['id_jenis']][$b->id_bulan];
					$html .= '<td align="right" width="6%">'. number_format($val) .'</td>';
					$jml_per_lokasi += $val;
					$arr_jml_per_bulan[$b->id_bulan][] = $val;
					$total += $val;
				}
				$html .= '<td align="right" width="11%">'. number_format($jml_per_lokasi) .'</td>
				</tr>';
				$i++;
			}

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td align="right" colspan="2"><b>TOTAL</b></td>';
				foreach( $arrBulan as $f ) {
					$html .= '<td align="right"><b>'. number_format(array_sum($arr_jml_per_bulan[$f->id_bulan])).'</b></td>';
				}
		$html .= '<td align="right"><b>'. number_format($total) .'</b></td>
			</tr>
		</tfoot>
		</table>';

		$html .= '<i style="font-size: 9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br>';
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( time() .'_rekap.pdf', 'I');
	}

	private function pdfjurnalview() {
		ini_set('max_execution_time', 0);
		$this->load->library('Pdf');

		$getfunc = $this->get_arr_data();
		$no = 1;

		$pdf = new Pdf('L', 'mm', 'A3', true, 'UTF-8', false);
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('helvetica', '', 12);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); 
		$pdf->SetMargins(PDF_MARGIN_LEFT-5, PDF_MARGIN_TOP-15, PDF_MARGIN_RIGHT-10);                                   
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);  
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);             
		$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);
		$pdf->AddPage('L', 'A3');

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<div style="text-align:center;">';
		$html .= '<h2>'. $getfunc['header1'];
		$html .= '</h2>';
		$html .= '</div><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:9px;">
			<thead>
				<tr>
					<th rowspan="2" width="3%" align="center"><b>No</b></th>
					<th rowspan="2" align="center" width="7%">Nomor Polisi</th>
					<th rowspan="2" align="center" width="16%">Nama</th>
					<th rowspan="2" align="center" width="30%">Alamat</th>
					<th colspan="3" align="center" width="18%">'. $getfunc['nama_penerimaan'] .'</th>
					<th colspan="3" align="center" width="24%">Transaksi</th>
				</tr>
				<tr>
					<th align="center" width="6%">Pokok</th>
					<th align="center" width="6%">Denda</th>
					<th align="center" width="6%">Jumlah</th>
					<th align="center" width="10%">Tanggal</th>
					<th align="center" width="6%">Bank</th>
					<th align="center" width="8%">Kode Billing</th>
				</tr>
			</thead>
			<tbody>';
			foreach ( $getfunc['rows'] as $r ) {
				$html .= '<tr>
					<td align="right" width="3%">'. $no .'</td>
					<td align="center" width="7%">'. $r['no_polisi'] .'</td>
					<td width="16%">'. $r['nama_pemilik'] .'</td>
					<td width="30%">'. $r['alamat'] .'</td>
					<td align="right" width="6%">'. number_format($r['pokok'],0) .'</td>
					<td align="right" width="6%">'. number_format($r['denda'],0) .'</td>
					<td align="right" width="6%">'. number_format(($r['pokok'] + $r['denda']),0) .'</td>
					<td align="center" width="10%">'. $r['tgl_bayar'] .'</td>
					<td align="center" width="6%">'. $r['nama_bank'] .'</td>
					<td align="center" width="8%">'. $r['kode_billing'] .'</td>
				</tr>';
				$no++;
			}
		$html .= '</tbody>
		</table>';

		$html .= '<i style="font-size: 9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br>';

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( time() . '_jurnal.pdf', 'I');
	}

	private function excelrekapview() {
		$getfunc = $this->get_arr_data();
		$no = 1;

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');

		/*$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'TCPDF';
		$rendererLibraryPath = dirname(__FILE__) . '/../libraries/' . $rendererLibrary;*/

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("eSAMSAT JATENG")
		                 ->setDescription("description");

		/*if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values at the top of this script as appropriate for your directory structure'
			);
		}*/

		// Assign cell values
		$objPHPExcel->getActiveSheet()->setTitle('REKAPITULASI');
 		$objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
        $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object

        $objget->setCellValueByColumnAndRow(0, 3, $getfunc['header1']);
    	$objget->mergeCells('A3:F3');
    	$objget->setCellValueByColumnAndRow(0, 4, $getfunc['header2'] . $getfunc['header201']);
    	$objget->mergeCells('A4:F4');
    	$objget->setCellValueByColumnAndRow(0, 5, $getfunc['header3']);
    	$objget->mergeCells('A5:F5');
    	$objget->setCellValueByColumnAndRow(0, 6, $getfunc['header4']);
    	$objget->mergeCells('A6:F6');

    	$styleheader = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
	        'font' => array(
	        	'bold' => true,
	        	//'color' => array( 'rgb' => '0B0A75'),
	        	'size' => 14
	        	)
	    );
	    $objget->getStyle("A1:F6")->applyFromArray($styleheader);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 9, 'No.');
	    $objget->mergeCells('A9:A11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 9, 'JENIS PUNGUTAN');
	    $objget->mergeCells('B9:B11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 9, 'PENETAPAN '. $getfunc['nama_penerimaan']);
	    $objget->mergeCells('C9:E9');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 9, 'JUMLAH');
	    $objget->mergeCells('F9:F10');

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 10, 'OBYEK');
	    $objget->mergeCells('C10:C11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 10, 'POKOK');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 10, 'SANKSI');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 11, 'Rp');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 11, 'Rp');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 11, 'Rp');

	    $objPHPExcel->getActiveSheet()
			->getStyle('A9:F11')
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'alignment' => array(
		                'horizontal' => 'center' ,
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'bold' => true
			        )
			    )
		);

        $total = 0;
	    $col_td = 0;
	    $row_td = 12;
	    $styleRed = array('font' => array('color' => array('rgb' => 'ff0000')));

	    $value = $getfunc['rows'];
		$tot_oby = 0;
		$tot_pokok = 0;
		$tot_denda = 0;

		foreach ( $this->Fungsi->arrKodeJenisPajak() as $r ) {
			$oby = empty($value[$r['id_jenis']]['oby']) ? 0 : $value[$r['id_jenis']]['oby'];
			$pokok = empty($value[$r['id_jenis']]['pokok']) ? 0 : $value[$r['id_jenis']]['pokok'];
			$denda = empty($value[$r['id_jenis']]['denda']) ? 0 : $value[$r['id_jenis']]['denda'];

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td, $row_td, $no);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 1, $row_td, $r['kode_jenis']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 2, $row_td, $oby);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 3, $row_td, $pokok);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 4, $row_td, $denda);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 5, $row_td, ($pokok + $denda));

			$no++;
			$tot_oby += $oby;
			$tot_pokok += $pokok;
			$tot_denda += $denda;
			$row_td ++;
		}

		$periodelalu = $getfunc['periodelalu'];

		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_td, ' JUMLAH BULAN / PERIODE INI ');
		$objget->mergeCells('A'. $row_td .':B'. $row_td);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row_td, $tot_oby);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_td, $tot_pokok);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_td, $tot_denda);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_td, ($tot_pokok + $tot_denda));

	    $row_td ++;

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_td, ' JUMLAH S/D BULAN / PERIODE LALU  ');
		$objget->mergeCells('A'. $row_td .':B'. $row_td);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row_td, $periodelalu->oby);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_td, $periodelalu->pokok);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_td, $periodelalu->denda);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_td, ($periodelalu->pokok + $periodelalu->denda));

	    $row_td ++;

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_td, ' JUMLAH S/D BULAN / PERIODE INI  ');
		$objget->mergeCells('A'. $row_td .':B'. $row_td);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row_td, $periodelalu->oby + $tot_oby);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row_td, $periodelalu->pokok + $tot_pokok);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row_td, $periodelalu->denda + $tot_denda);
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row_td, $periodelalu->pokok + $periodelalu->denda + ($tot_pokok + $tot_denda));


		$objPHPExcel->getActiveSheet()->getStyle('C12:F'. $row_td)->getNumberFormat()->setFormatCode('#,##0');

		$objPHPExcel->getActiveSheet()
			->getStyle('A'. ($row_td - 2) .':F'. $row_td)
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'alignment' => array(
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'bold' => true
			        )
			    )
		);

		$objPHPExcel->getActiveSheet()
			->getStyle('B12:C'. $row_td)
			->applyFromArray(
			    array(
			        'alignment' => array(
		                'horizontal' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            )
			    )
		);

		$objPHPExcel->getActiveSheet()
			->getStyle('A9:F'. $row_td)
			->applyFromArray(
			    array(
			        'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN
				          )
				    ),
			        'font' => array(
			        	'size' => 10
			        )
			    )
		);

		$row_td ++;

	    $objget->setCellValueByColumnAndRow(0, $row_td, 'dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s'));
    	$objget->mergeCells('A'. $row_td .':F'. $row_td);

    	$objPHPExcel->getActiveSheet()
			->getStyle('A'. $row_td .':F'. $row_td)
			->applyFromArray(
			    array(
			        'font' => array(
			        	'size' => 10,
			        	'italic' => true
			        )
			    )
		);

    	$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$row_td ++;
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(4, $row_td, 'Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ));
	    	$objget->mergeCells('E'. $row_td .':F'. $row_td);
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(4, $row_td, $rowparaf->nama_paraf);
	    	$objget->mergeCells('E'. $row_td .':F'. $row_td);
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(4, $row_td, $rowparaf->nama_pegawai);
	    	$objget->mergeCells('E'. $row_td .':F'. $row_td);
	    	$objPHPExcel->getActiveSheet()
				->getStyle('E'. $row_td .':F'. $row_td)
				->applyFromArray(
				    array(
				        'font' => array(
				        	'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
				        )
				    )
			);
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(4, $row_td, 'NIP. '. $rowparaf->nip);
	    	$objget->mergeCells('E'. $row_td .':F'. $row_td);
	    	$objPHPExcel->getActiveSheet()
				->getStyle('E'. ( $row_td - 8 ) .':F'. $row_td)
				->applyFromArray(
				    array(
				        'font' => array(
				        	'size' => 10
				        ),
					    'alignment' => array(
			                'horizontal' => 'center',
			                'wrap' => true,
			                'shrinkToFit' => true
			            )
				    )
			);
		}

		
    	
		// Save it as an excel 2003 file
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //Nama File
        header('Content-Disposition: attachment;filename="'. $getfunc['header1'] .'.xls"');

        //Download
        $objWriter->save("php://output");
	}

	private function exceljurnalview() {
		$getfunc = $this->get_arr_data();
		$no = 1;

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');

		/*$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
		$rendererLibrary = 'TCPDF';
		$rendererLibraryPath = dirname(__FILE__) . '/../libraries/' . $rendererLibrary;*/

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("eSAMSAT JATENG")
		                 ->setDescription("description");

		/*if (!PHPExcel_Settings::setPdfRenderer(
			$rendererName,
			$rendererLibraryPath
		)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values at the top of this script as appropriate for your directory structure'
			);
		}*/

		// Assign cell values
		$objPHPExcel->getActiveSheet()->setTitle($getfunc['header1']);
 		$objset = $objPHPExcel->setActiveSheetIndex(0); //inisiasi set object
        $objget = $objPHPExcel->getActiveSheet();  //inisiasi get object

        $objget->setCellValueByColumnAndRow(0, 3, $getfunc['header1']);
    	$objget->mergeCells('A3:Q3');
    	$objget->setCellValueByColumnAndRow(0, 4, $getfunc['header2'] . $getfunc['header201']);
    	$objget->mergeCells('A4:Q4');
    	$objget->setCellValueByColumnAndRow(0, 5, $getfunc['header3']);
    	$objget->mergeCells('A5:Q5');
    	$objget->setCellValueByColumnAndRow(0, 6, $getfunc['header4']);
    	$objget->mergeCells('A6:Q6');

    	$styleheader = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
	        'font' => array(
	        	'bold' => true,
	        	//'color' => array( 'rgb' => '0B0A75'),
	        	'size' => 14
	        	)
	    );
	    $objget->getStyle("A1:Q6")->applyFromArray($styleheader);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);


        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 9, 'No.');
	    $objget->mergeCells('A9:A11');

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 9, 'BILLING SYSTEM');
	    $objget->mergeCells('B9:D10');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 11, 'KODE');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 11, 'TANGGAL');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 11, 'JAM');


	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 9, 'Nomor Polisi');
	    $objget->mergeCells('E9:E11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 9, 'Nama');
	    $objget->mergeCells('F9:F11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 9, 'Alamat');
	    $objget->mergeCells('G9:G11');

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 9, $getfunc['nama_penerimaan']);
	    $objget->mergeCells('H9:J9');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 10, 'POKOK');
	    $objget->mergeCells('H10:H11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 10, 'DENDA');
	    $objget->mergeCells('I10:I11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 10, 'JUMLAH');
	    $objget->mergeCells('J10:J11');


	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 9, 'PENCATAT REKENING BENDAHARA');
	    $objget->mergeCells('K9:O9');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 10, 'TANGGAL');
	    $objget->mergeCells('K10:K11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 10, 'JAM');
	    $objget->mergeCells('L10:L11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 10, 'JUMLAH '. $getfunc['nama_penerimaan']);
	    $objget->mergeCells('M10:O10');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 11, 'POKOK');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 11, 'DENDA');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 11, 'JUMLAH');
	    

	    
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 9, 'Bank Transaksi');
	    $objget->mergeCells('P9:P11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 9, 'Samsat Asal');
	    $objget->mergeCells('Q9:Q11');

	    $objPHPExcel->getActiveSheet()
			->getStyle('A9:Q11')
			->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'DDDDDD')
			        ),
			        'alignment' => array(
		                'horizontal' => 'center' ,
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'bold' => true
			        )
			    )
		);

        $total = 0;
	    $col_td = 0;
	    $row_td = 11;
	    $styleRed = array('font' => array('color' => array('rgb' => 'ff0000')));

	    $value = $getfunc['rows'];

		foreach ( $getfunc['rows'] as $r ) {
			$row_td ++;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td, $row_td, $no);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 1, $row_td, $r['kode_billing']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 2, $row_td, $r['tgl_billing']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 3, $row_td, $r['jam_billing']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 4, $row_td, $r['no_polisi']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 5, $row_td, $r['nama_pemilik']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 6, $row_td, $r['alamat']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 7, $row_td, $r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 8, $row_td, $r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 9, $row_td, ($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda']));
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 10, $row_td, $r['tgl_bayar']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 11, $row_td, $r['jam_bayar']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 12, $row_td, $r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 13, $row_td, $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 14, $row_td, ($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 15, $row_td, $r['nama_bank']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 16, $row_td, $r['lokasi'] );
			$no++;
			
		}


		$objPHPExcel->getActiveSheet()->getStyle('H12:J'. $row_td)->getNumberFormat()->setFormatCode('#,##0');
		$objPHPExcel->getActiveSheet()->getStyle('M12:O'. $row_td)->getNumberFormat()->setFormatCode('#,##0');

		$styleCenter = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        )
	    );
	    $objget->getStyle("B12:E". $row_td)->applyFromArray($styleCenter);
	    $objget->getStyle("K12:L". $row_td)->applyFromArray($styleCenter);
	    $objget->getStyle("P12:Q". $row_td)->applyFromArray($styleCenter);

		$objPHPExcel->getActiveSheet()
			->getStyle('A9:Q'. $row_td)
			->applyFromArray(
			    array(
			        'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN
				          )
				    ),
				    'alignment' => array(
		                'vertical' => 'center',
		                'wrap' => true,
		                'shrinkToFit' => true
		            ),
			        'font' => array(
			        	'size' => 10
			        )
			    )
		);

		$row_td ++;

	    $objget->setCellValueByColumnAndRow(0, $row_td, 'dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s'));
    	$objget->mergeCells('A'. $row_td .':F'. $row_td);

    	$objPHPExcel->getActiveSheet()
			->getStyle('A'. $row_td .':F'. $row_td)
			->applyFromArray(
			    array(
			        'font' => array(
			        	'size' => 10,
			        	'italic' => true
			        )
			    )
		);

    	$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$row_td ++;
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(15, $row_td, 'Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ));
	    	$objget->mergeCells('P'. $row_td .':Q'. $row_td);
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(15, $row_td, $rowparaf->nama_paraf);
	    	$objget->mergeCells('P'. $row_td .':Q'. $row_td);
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(15, $row_td, $rowparaf->nama_pegawai);
	    	$objget->mergeCells('P'. $row_td .':Q'. $row_td);
	    	$objPHPExcel->getActiveSheet()
				->getStyle('P'. $row_td .':Q'. $row_td)
				->applyFromArray(
				    array(
				        'font' => array(
				        	'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
				        )
				    )
			);
	    	$row_td ++;
	    	$objget->setCellValueByColumnAndRow(15, $row_td, 'NIP. '. $rowparaf->nip);
	    	$objget->mergeCells('P'. $row_td .':Q'. $row_td);
	    	$objPHPExcel->getActiveSheet()
				->getStyle('P'. ( $row_td - 8 ) .':Q'. $row_td)
				->applyFromArray(
				    array(
				        'font' => array(
				        	'size' => 10
				        ),
					    'alignment' => array(
			                'horizontal' => 'center',
			                'wrap' => true,
			                'shrinkToFit' => true
			            )
				    )
			);
		}

		// Save it as an excel 2003 file
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //Nama File
        header('Content-Disposition: attachment;filename="'. $getfunc['header1'] .'.xls"');

        //Download
        $objWriter->save("php://output");
	}

}
