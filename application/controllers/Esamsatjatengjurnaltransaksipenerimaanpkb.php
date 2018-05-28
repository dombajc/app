<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Esamsatjatengjurnaltransaksipenerimaanpkb extends CI_Controller {

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
		$data['title'] = 'LAPORAN TRANSAKSI e-SAMSAT';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'laporan transaksi penerimaan pkb';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function cetak() {
		$paramtipe = $this->security->xss_clean($_GET['type']);
		$parampelaporan = $this->security->xss_clean($_GET['jnslaporan']);

		switch ($paramtipe) {
			case 'printout':
				if ( $parampelaporan == 0 ) :
					$this->printrekapview();
					else :
						$this->printjurnalview();
						endif;
				break;

			case 'pdf' :
				if ( $parampelaporan == 0 ) :
					$this->pdfrekapview();
					else :
						$this->pdfjurnalview();
						endif;
				break;

			case 'excel' :
				if ( $parampelaporan == 0 ) :
					$this->excelrekapview();
					else :
						$this->exceljurnalview();
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
		$parambank = $this->security->xss_clean($_GET['bank']);
		$paramopsi = $this->security->xss_clean($_GET['opsitgl']);
		$paramperiodeawal = $this->security->xss_clean($_GET['paw']);
		$paramperiodeakhir = $this->security->xss_clean($_GET['pak']);
		$parampelaporan = $this->security->xss_clean($_GET['jnslaporan']);

		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;
		$bulan = $this->Fungsi->getBulan( $parambulan );
		$nama_penerimaan = $this->Esamsatjateng->get_nama_penerimaan_by_id($paramjenispenerimaan);

		if ( $paramopsi == 0 ) {

			$set_periode_start = date( 'Y-01-01', 
				strtotime(
					date( $tahun .'-'. $parambulan .'-01' )
					));
			
			$set_tgl_awal = date( 'Y-m-d 00:00:00', 
				strtotime(
					date( $tahun .'-'. $parambulan .'-01' )
					));
			$set_tgl_akhir = date( 'Y-m-t 23:59:59', 
				strtotime(
					date( $tahun .'-'. $parambulan .'-01' )
					));

		} else {
			$format_tgl_awal = $this->Fungsi->getdatetimesql($paramperiodeawal);
			$format_tgl_akhir = $this->Fungsi->getdatetimesql($paramperiodeakhir);

			$set_periode_start = date( 'Y-01-01', 
				strtotime(
					date( $format_tgl_awal['getdate'] .' '. $format_tgl_awal['gettime'] )
					));

			$set_tgl_awal = empty( $paramperiodeawal ) || $paramperiodeawal == '' ? '-' : $format_tgl_awal['getdate'] .' '. $format_tgl_awal['gettime'];
			$set_tgl_akhir = empty( $paramperiodeakhir ) || $paramperiodeakhir == '' ? '-' : $format_tgl_akhir['getdate'] .' '. $format_tgl_akhir['gettime'];
		}
		
		
		$set_jenis_transaksi = empty($paramjenistransaksi) || $paramjenistransaksi == '' ? '' : $paramjenistransaksi;
		$set_bank = empty($parambank) || $parambank == '' ? '' : $parambank;

		 

		if ( $parampelaporan == 0 ) {
			$arr['periodelalu'] = $this->Esamsatjateng->arr_rekap_lalu_by($set_periode_start, $set_tgl_awal, $set_jenis_transaksi, $set_bank, $paramjenispenerimaan);
			$arr['rows'] = $this->Esamsatjateng->arr_rekap_by($set_tgl_awal, $set_tgl_akhir, $set_jenis_transaksi, $set_bank, $paramjenispenerimaan);
			$arr['header1'] = 'REKAP TRANSAKSI PENERIMAAN '. $nama_penerimaan;
		} else {
			$arr['rows'] = $this->Esamsatjateng->arr_jurnal_by($set_tgl_awal, $set_tgl_akhir, $set_jenis_transaksi, $set_bank)->result_array();
			$arr['header1'] = 'JURNAL TRANSAKSI PENERIMAAN '. $nama_penerimaan;
		}
		
		$arr['nama_penerimaan'] = $nama_penerimaan;
		$arr['set_id_penerimaan'] = $paramjenispenerimaan;
		
		$arr['header2'] = empty( $paramjenistransaksi ) || $paramjenistransaksi == '' ? '' : 'e-SAMSAT '. $this->Esamsatjateng->get_nama_transaksi_by_id($paramjenistransaksi);
		$arr['header201'] = empty( $parambank ) || $parambank == '' ? '' : ' / '. $this->Esamsatjateng->get_bank_by_id($parambank);
		$arr['header3'] = $paramopsi == 0 ? 'Bulan : '. $bulan . ' '. $tahun : 'Periode : '. $this->Fungsi->format_sql_to_indo($format_tgl_awal['getdate']) .' s.d '. $this->Fungsi->format_sql_to_indo($format_tgl_akhir['getdate']);
		$arr['header4'] = $paramopsi == 0 ? '' : 'Jam Transaksi : '. $format_tgl_awal['gettime'] .' s.d '. $format_tgl_akhir['gettime'];

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
		$html .= $getfunc['header2'] == '' ? '' : '<h2>'. $getfunc['header2'] . $getfunc['header201'] .'</h2>';
		$html .= '<h4>'. $getfunc['header3'] .'</h4>';
		$html .= $getfunc['header4'] == '' ? '' : '<h4>'. $getfunc['header4'] .'</h4>';
		$html .= '</center><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:9px;">
			<thead>
				<tr>
					<th rowspan="3">No</th>
					<th rowspan="2" colspan="3">Billing System</th>
					<th rowspan="3">Nomor Polisi</th>
					<th rowspan="3">Nama</th>
					<th rowspan="3">Alamat</th>
					<th rowspan="2" colspan="3">'. $getfunc['nama_penerimaan'] .'</th>
					<th colspan="6">Pencatatan Rekening Bendahara</th>
					<th rowspan="3">Bank Transaksi / Merchant</th>
					<th rowspan="3">Samsat Asal</th>
				</tr>
				<tr>
					<th rowspan="2">Reff.Bank</th>
					<th rowspan="2">Tanggal</th>
					<th rowspan="2">Jam</th>
					<th colspan="3">Jumlah '. $getfunc['nama_penerimaan'] .'</th>
				</tr>
				<tr>
					<th>Kode</th>
					<th>Tanggal</th>
					<th>Jam</th>
					<th>Pokok</th>
					<th>Denda</th>
					<th>Jumlah</th>
					<th>Pokok</th>
					<th>Denda</th>
					<th>Jumlah</th>
				</tr>
			</thead>
			<tbody>';
		foreach ( $getfunc['rows'] as $r ) {
			$html .= '<tr>
				<td align="right">'. $no .'</td>
				<td align="center">'. $r['kode_billing'] .'</td>
				<td align="center">'. $r['tgl_billing'] .'</td>
				<td align="center">'. $r['jam_billing'] .'</td>
				<td align="center">'. $r['no_polisi'] .'</td>
				<td>'. $r['nama_pemilik'] .'</td>
				<td>'. $r['alamat'] .'</td>
				<td align="right">'. number_format($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok'],0) .'</td>
				<td align="right">'. number_format($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda'],0) .'</td>
				<td align="right">'. number_format(($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda']),0) .'</td>
				<td align="center">'. $r['kodebayar'] .'</td>
				<td align="center">'. $r['tgl_bayar'] .'</td>
				<td align="center">'. $r['jam_bayar'] .'</td>
				<td align="right">'. number_format($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'],0) .'</td>
				<td align="right">'. number_format($r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda'],0) .'</td>
				<td align="right">'. number_format(($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']),0) .'</td>
				<td align="center">'. $r['nama_bank'] .'</td>
				<td align="center">'. $r['lokasi'] .'</td>
			</tr>';
			$no++;
		}
		$html .= '</tbody>
		</table>';

		$html .= '<i style="font-size:9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br><br>';

		$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$html .= '<div style="width:100%;"><div style="width:70%; float:left;"></div>';
			$html .= '<div style="width:30%; text-align : center; float:right; font-size:9px;">
			Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'<br>
			'. $rowparaf->nama_paraf .'
			<br><br><br><br><br><br>
			<span style="text-decoration:underline;">'. $rowparaf->nama_pegawai .'</span><br>
			NIP. '. $rowparaf->nip .'
			</div></div>';
		}

		echo $html;
	}

	private function printrekapview() {
		$getfunc = $this->get_arr_data();
		$no = 1;

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<center>';
		$html .= '<h2>'. $getfunc['header1'] .'</h2>';
		$html .= $getfunc['header2'] == '' ? '' : '<h2>'. $getfunc['header2'] . $getfunc['header201'] .'</h2>';
		$html .= '<h4>'. $getfunc['header3'] .'</h4>';
		$html .= $getfunc['header4'] == '' ? '' : '<h4>'. $getfunc['header4'] .'</h4>';
		$html .= '</center><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:12px;">
			<thead>
				<tr>
					<th rowspan="3">No</th>
					<th rowspan="3">JENIS PUNGUTAN</th>
					<th colspan="3">PENETAPAN '. $getfunc['nama_penerimaan'] .'</th>
					<th rowspan="2">JUMLAH</th>
				</tr>
				<tr>
					<th rowspan="2">OBYEK</th>
					<th>POKOK</th>
					<th>DENDA</th>
				</tr>
				<tr>
					<th>Rp</th>
					<th>Rp</th>
					<th>Rp</th>
				</tr>
			</thead>
			<tbody>';

		$value = $getfunc['rows'];
		$tot_oby = 0;
		$tot_pokok = 0;
		$tot_denda = 0;
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $r ) {
			$oby = empty($value[$r['id_jenis']]['oby']) ? 0 : $value[$r['id_jenis']]['oby'];
			$pokok = empty($value[$r['id_jenis']]['pokok']) ? 0 : $value[$r['id_jenis']]['pokok'];
			$denda = empty($value[$r['id_jenis']]['denda']) ? 0 : $value[$r['id_jenis']]['denda'];

			$html .= '<tr>
			<td align="right" width="5%">'. $no .' .</td>
			<td align="center">'. $r['kode_jenis'] .'</td>
			<td align="center">'. number_format($oby, 0) .'</td>
			<td align="right">'. number_format($pokok, 0) .'</td>
			<td align="right">'. number_format($denda, 0) .'</td>
			<td align="right">'. number_format( ($pokok + $denda), 0) .'</td>
			</tr>';
			$no++;
			$tot_oby += $oby;
			$tot_pokok += $pokok;
			$tot_denda += $denda;
		}

		$periodelalu = $getfunc['periodelalu'];

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td colspan="2"> JUMLAH BULAN / PERIODE INI </td>
				<td align="center">'. number_format($tot_oby, 0) .'</td>
				<td align="right">'. number_format($tot_pokok, 0) .'</td>
				<td align="right">'. number_format($tot_denda, 0) .'</td>
				<td align="right">'. number_format( ($tot_pokok + $tot_denda), 0) .'</td>
			</tr>
			<tr>
				<td colspan="2"> JUMLAH S/D BULAN / PERIODE LALU </td>
				<td align="center">'. number_format($periodelalu->oby, 0) .'</td>
				<td align="right">'. number_format($periodelalu->pokok, 0) .'</td>
				<td align="right">'. number_format($periodelalu->denda, 0) .'</td>
				<td align="right">'. number_format( ($periodelalu->pokok + $periodelalu->denda), 0) .'</td>
			</tr>
			<tr>
				<td colspan="2"> JUMLAH S/D BULAN / PERIODE INI </td>
				<td align="center">'. number_format($periodelalu->oby + $tot_oby, 0) .'</td>
				<td align="right">'. number_format($periodelalu->pokok + $tot_pokok, 0) .'</td>
				<td align="right">'. number_format($periodelalu->denda + $tot_denda, 0) .'</td>
				<td align="right">'. number_format( ($periodelalu->pokok + $periodelalu->denda + ($tot_pokok + $tot_denda)), 0) .'</td>
			</tr>
		</tfoot>
		</table>';

		$html .= '<i>dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br><br>';

		$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$html .= '<div style="width:100%;"><div style="width:50%; float:left;"></div>';
			$html .= '<div style="width:50%; text-align : center; float:right;">
			Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'<br>
			'. $rowparaf->nama_paraf .'
			<br><br><br><br><br><br>
			<span style="text-decoration:underline;">'. $rowparaf->nama_pegawai .'</span><br>
			NIP. '. $rowparaf->nip .'
			</div></div>';
		}
		
		echo $html;
	}

	private function pdfrekapview() {
		ini_set('max_execution_time', 0);
		$this->load->library('Pdf');

		$getfunc = $this->get_arr_data();
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
		$pdf->AddPage('P', 'A4');

		$html = '';
		$html .= $this->Fungsi->stylecetakhtml();
		$html .= '<div style="text-align:center;">';
		$html .= '<h2>'. $getfunc['header1'];
		$html .= $getfunc['header2'] == '' ? '' : '<br>'. $getfunc['header2'] . $getfunc['header201'];
		$html .= '</h2>';
		$html .= '<h4>'. $getfunc['header3'];
		$html .= $getfunc['header4'] == '' ? '' : '<br>'. $getfunc['header4'];
		$html .= '</h4>';
		$html .= '</div><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:9px;">
			<thead>
				<tr>
					<th rowspan="3" width="5%" align="center"><b>No</b></th>
					<th rowspan="3" align="center"><b>JENIS PUNGUTAN</b></th>
					<th colspan="3" width="55%" align="center"><b>PENETAPAN '. $getfunc['nama_penerimaan'] .'</b></th>
					<th rowspan="2" width="20%" align="center"><b>JUMLAH</b></th>
				</tr>
				<tr>
					<th rowspan="2" width="15%" align="center"><b>OBYEK</b></th>
					<th width="20%" align="center"><b>POKOK</b></th>
					<th width="20%" align="center"><b>DENDA</b></th>
				</tr>
				<tr>
					<th width="20%" align="center"><b>Rp</b></th>
					<th width="20%" align="center"><b>Rp</b></th>
					<th width="20%" align="center"><b>Rp</b></th>
				</tr>
			</thead>
			<tbody>';

		$value = $getfunc['rows'];
		$tot_oby = 0;
		$tot_pokok = 0;
		$tot_denda = 0;
		foreach ( $this->Fungsi->arrKodeJenisPajak() as $r ) {
			$oby = empty($value[$r['id_jenis']]['oby']) ? 0 : $value[$r['id_jenis']]['oby'];
			$pokok = empty($value[$r['id_jenis']]['pokok']) ? 0 : $value[$r['id_jenis']]['pokok'];
			$denda = empty($value[$r['id_jenis']]['denda']) ? 0 : $value[$r['id_jenis']]['denda'];

			$html .= '<tr>
			<td align="right" width="5%">'. $no .' .</td>
			<td align="center">'. $r['kode_jenis'] .'</td>
			<td align="center" width="15%">'. number_format($oby, 0) .'</td>
			<td align="right" width="20%">'. number_format($pokok, 0) .'</td>
			<td align="right" width="20%">'. number_format($denda, 0) .'</td>
			<td align="right" width="20%">'. number_format( ($pokok + $denda), 0) .'</td>
			</tr>';
			$no++;
			$tot_oby += $oby;
			$tot_pokok += $pokok;
			$tot_denda += $denda;
		}

		$periodelalu = $getfunc['periodelalu'];

		$html .= '</tbody>
		<tfoot>
			<tr>
				<td colspan="2"><b> JUMLAH BULAN / PERIODE INI</b> </td>
				<td align="center"><b>'. number_format($tot_oby, 0) .'</b></td>
				<td align="right"><b>'. number_format($tot_pokok, 0) .'</b></td>
				<td align="right"><b>'. number_format($tot_denda, 0) .'</b></td>
				<td align="right"><b>'. number_format( ($tot_pokok + $tot_denda), 0) .'</b></td>
			</tr>
			<tr>
				<td colspan="2"><b> JUMLAH S/D BULAN / PERIODE LALU</b> </td>
				<td align="center"><b>'. number_format($periodelalu->oby, 0) .'</b></td>
				<td align="right"><b>'. number_format($periodelalu->pokok, 0) .'</b></td>
				<td align="right"><b>'. number_format($periodelalu->denda, 0) .'</b></td>
				<td align="right"><b>'. number_format( ($periodelalu->pokok + $periodelalu->denda), 0) .'</b></td>
			</tr>
			<tr>
				<td colspan="2"><b> JUMLAH S/D BULAN / PERIODE INI</b> </td>
				<td align="center"><b>'. number_format($periodelalu->oby + $tot_oby, 0) .'</b></td>
				<td align="right"><b>'. number_format($periodelalu->pokok + $tot_pokok, 0) .'</b></td>
				<td align="right"><b>'. number_format($periodelalu->denda + $tot_denda, 0) .'</b></td>
				<td align="right"><b>'. number_format( ($periodelalu->pokok + $periodelalu->denda + ($tot_pokok + $tot_denda)), 0) .'</b></td>
			</tr>
		</tfoot>
		</table>';

		$html .= '<i style="font-size: 9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br>';

		$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$html .= '<div style="width:100%; text-align : center; font-size: 9px;">
			<table>
			<tr>
			<td width="60%"></td><td align="center" width="40%">Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'</td>
			</tr>
			<tr>
			<td width="60%"></td><td align="center">'. $rowparaf->nama_paraf .'</td>
			</tr>
			<tr>
			<td width="60%"></td><td align="center"><br><br><br><br><br><br></td>
			</tr>
			<tr>
			<td width="60%"></td><td align="center"><span style="text-decoration:underline;">'. $rowparaf->nama_pegawai .'</span></td>
			</tr>
			<tr>
			<td width="60%"></td><td align="center">NIP. '. $rowparaf->nip .'</td>
			</tr>
			</table>
			</div>';
		}
		
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( time() . $getfunc['header1'] .'.pdf', 'I');
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
		$html .= $getfunc['header2'] == '' ? '' : '<br>'. $getfunc['header2'] . $getfunc['header201'];
		$html .= '</h2>';
		$html .= '<h4>'. $getfunc['header3'];
		$html .= $getfunc['header4'] == '' ? '' : '<br>'. $getfunc['header4'];
		$html .= '</h4>';
		$html .= '</div><br><br>';
		$html .= '<table border="1" cellpadding="5px" id="tabel" style="font-size:8px;">
			<thead>
				<tr>
					<th rowspan="3" width="3%" align="center"><b>No</b></th>
					<th rowspan="2" colspan="3" width="18%" align="center"><b>Billing System</b></th>
					<th rowspan="3" width="5%" align="center"><b>Nomor Polisi</b></th>
					<th rowspan="3" width="5%" align="center"><b>Nama</b></th>
					<th rowspan="3" width="10%" align="center"><b>Alamat</b></th>
					<th rowspan="2" colspan="3" align="center" width="15%"><b>'. $getfunc['nama_penerimaan'] .'</b></th>
					<th colspan="5" width="30%" align="center"><b>Pencatatan Rekening Bendahara</b></th>
					<th rowspan="3" width="6%" align="center"><b>Bank Transaksi / Merchant</b></th>
					<th rowspan="3" width="7%" align="center"><b>Samsat Asal</b></th>
				</tr>
				<tr>
					<th rowspan="2" width="5%" align="center"><b>Reff. Bank</b></th>
					<th rowspan="2" width="5%" align="center"><b>Tanggal</b></th>
					<th rowspan="2" width="5%" align="center"><b>Jam</b></th>
					<th colspan="3" width="15%" align="center"><b>Jumlah '. $getfunc['nama_penerimaan'] .'</b></th>
				</tr>
				<tr>
					<th width="8%" align="center"><b>Kode</b></th>
					<th width="5%" align="center"><b>Tanggal</b></th>
					<th width="5%" align="center"><b>Jam</b></th>
					<th width="5%" align="center"><b>Pokok</b></th>
					<th width="5%" align="center"><b>Denda</b></th>
					<th width="5%" align="center"><b>Jumlah</b></th>
					<th width="5%" align="center"><b>Pokok</b></th>
					<th width="5%" align="center"><b>Denda</b></th>
					<th width="5%" align="center"><b>Jumlah</b></th>
				</tr>
			</thead>
			<tbody>';
		foreach ( $getfunc['rows'] as $r ) {
			$html .= '<tr>
				<td align="right" width="3%">'. $no .'</td>
				<td align="center" width="8%">'. $r['kode_billing'] .'</td>
				<td align="center" width="5%">'. $r['tgl_billing'] .'</td>
				<td align="center" width="5%">'. $r['jam_billing'] .'</td>
				<td align="center" width="5%">'. $r['no_polisi'] .'</td>
				<td width="5%">'. $r['nama_pemilik'] .'</td>
				<td width="10%">'. $r['alamat'] .'</td>
				<td align="right" width="5%">'. number_format($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok'],0) .'</td>
				<td align="right" width="5%">'. number_format($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda'],0) .'</td>
				<td align="right" width="5%">'. number_format(($r['penetapan_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['penetapan_'. $getfunc['set_id_penerimaan'] .'_denda']),0) .'</td>
				<td align="center" width="5%">'. $r['kodebayar'] .'</td>
				<td align="center" width="5%">'. $r['tgl_bayar'] .'</td>
				<td align="center" width="5%">'. $r['jam_bayar'] .'</td>
				<td align="right" width="5%">'. number_format($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'],0) .'</td>
				<td align="right" width="5%">'. number_format($r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda'],0) .'</td>
				<td align="right" width="5%">'. number_format(($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']),0) .'</td>
				<td align="center" width="6%">'. $r['nama_bank'] .'</td>
				<td align="center" width="7%">'. $r['lokasi'] .'</td>
			</tr>';
			$no++;
		}
		$html .= '</tbody>
		</table>';

		$html .= '<i style="font-size: 9px;">dicetak : '. element('username', $this->Opsisite->getDataUser()) .' pada '. date('d-m-Y H:i:s') .'</i><br>';

		$getparaf = $this->Paraf->get_paraf_tr_esamsat();
		if ( $getparaf->num_rows() == 1 ) {
			$rowparaf = $getparaf->row();
			$html .= '<div style="width:100%; text-align : center; font-size: 9px;">
			<table>
			<tr>
			<td width="70%"></td><td align="center" width="30%">Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'</td>
			</tr>
			<tr>
			<td width="70%"></td><td align="center">'. $rowparaf->nama_paraf .'</td>
			</tr>
			<tr>
			<td width="70%"></td><td align="center"><br><br><br><br><br><br></td>
			</tr>
			<tr>
			<td width="70%"></td><td align="center"><span style="text-decoration:underline;">'. $rowparaf->nama_pegawai .'</span></td>
			</tr>
			<tr>
			<td width="70%"></td><td align="center">NIP. '. $rowparaf->nip .'</td>
			</tr>
			</table>
			</div>';
		}

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( time() . $getfunc['header1'] .'.pdf', 'I');
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
		$objPHPExcel->getActiveSheet()->setTitle('jurnal');
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);


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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 10, 'Reff.Bank');
	    $objget->mergeCells('K10:K11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 10, 'TANGGAL');
	    $objget->mergeCells('L10:L11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 10, 'JAM');
	    $objget->mergeCells('M10:M11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 10, 'JUMLAH '. $getfunc['nama_penerimaan']);
	    $objget->mergeCells('N10:O10');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 11, 'POKOK');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 11, 'DENDA');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 11, 'JUMLAH');
	    
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 9, 'Bank Transaksi');
	    $objget->mergeCells('Q9:Q11');
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 9, 'Samsat Asal');
	    $objget->mergeCells('R9:R11');

	    $objPHPExcel->getActiveSheet()
			->getStyle('A9:R11')
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
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 10, $row_td, trim($r['kodebayar']));
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 11, $row_td, $r['tgl_bayar']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 12, $row_td, $r['jam_bayar']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 13, $row_td, $r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 14, $row_td, $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 15, $row_td, ($r['bayar_'. $getfunc['set_id_penerimaan'] .'_pokok'] + $r['bayar_'. $getfunc['set_id_penerimaan'] .'_denda']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 16, $row_td, $r['nama_bank']);
	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_td + 17, $row_td, $r['lokasi'] );
			$no++;
			
		}


		$objPHPExcel->getActiveSheet()->getStyle('H12:J'. $row_td)->getNumberFormat()->setFormatCode('#,##0');
		$objPHPExcel->getActiveSheet()->getStyle('N12:P'. $row_td)->getNumberFormat()->setFormatCode('#,##0');

		$styleCenter = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        )
	    );
	    $objget->getStyle("B12:E". $row_td)->applyFromArray($styleCenter);
	    $objget->getStyle("K12:M". $row_td)->applyFromArray($styleCenter);
	    $objget->getStyle("Q12:R". $row_td)->applyFromArray($styleCenter);

		$objPHPExcel->getActiveSheet()
			->getStyle('A9:R'. $row_td)
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
