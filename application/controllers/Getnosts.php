<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getnosts extends CI_Controller {

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
        $this->load->Model( 'Rekeningpad' );
        $this->load->Model( 'Nosts' );
    }
	
	public function index() {

		$data['title'] = 'VALIDASI No. STS';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'konfirmasi create no sts';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function checknshowsetoran() {
		$error = '';
		$status = 0;

		$get_th = $this->security->xss_clean( $_POST['slctth'] );
		$get_jenis_transaksi = $this->security->xss_clean( $_POST['slctjenistransaksi'] );
		$get_bank = $this->security->xss_clean( $_POST['slctbank'] );
		$get_tgl_akhir = $this->security->xss_clean( $_POST['txttglawal'] );
		$get_penerimaan = $this->security->xss_clean( $_POST['slctjenispenerimaan'] );

		$checking = $this->Nosts->checking( $get_th, $get_jenis_transaksi, $get_bank, $get_tgl_akhir, $get_penerimaan );

		$arr_result = array();
		if ( $checking->num_rows() == 0 ) {

			$get_start_date = $this->Nosts->get_last( $get_th, $get_jenis_transaksi, $get_bank, $get_tgl_akhir, $get_penerimaan )->start_tgl;

			$row = $this->Esamsatjateng->get_jenis_penerimaan_by_id( $get_penerimaan );
			$get_jumlah_from_esamsat = $this->Esamsatjateng->view_resume_sts( $get_start_date, $this->Fungsi->getdatetimesql_string( $get_tgl_akhir ), $get_bank, $get_jenis_transaksi, $row->sync_field_api_esamsat );

			foreach( $this->Rekeningpad->get_list_by( $get_th, $row->sync_field_pad )->result() as $r ) {
				$arr_result[$r->kd_inisial]['no_rekening'] = $r->no_rekening;
				$arr_result[$r->kd_inisial]['nama_rekening'] = $r->nm_rekening;
				$arr_result[$r->kd_inisial]['jumlah'] = empty( $get_jumlah_from_esamsat[ $r->kd_inisial ] ) ? 0 : $get_jumlah_from_esamsat[ $r->kd_inisial ];
			}

			$status = 1;
		} else {
			$error = 'Maaf Tanggal Penyetoran terakhir sudah pernah digunakan !';
		}

		echo json_encode(
			array(
				'get_rekening_from_pad' => $arr_result,
				'Status' => $status,
				'Error' => $error
			));
	}

	public function getvalidationsts() {
		$get_th = $this->security->xss_clean( $_POST['slctth'] );
		$get_jenis_transaksi = $this->security->xss_clean( $_POST['slctjenistransaksi'] );
		$get_bank = $this->security->xss_clean( $_POST['slctbank'] );
		$get_tgl_akhir = $this->security->xss_clean( $_POST['txttglawal'] );
		$get_penerimaan = $this->security->xss_clean( $_POST['slctjenispenerimaan'] );

		$json = array();
		$error = '';
		$status = 0;
		$idsts = '';

		if ( $this->Nosts->checking( $get_th, $get_jenis_transaksi, $get_bank, $get_tgl_akhir, $get_penerimaan )->num_rows() == 0 ) {

			$last = $this->Nosts->get_last( $get_th, $get_jenis_transaksi, $get_bank, $get_tgl_akhir, $get_penerimaan );
			$getid = time();

			$post_data = array(
				'id_sts' => $getid,
				'kode_upt' => '00',
				'kode_gerai' => '09',
				'auto_no_sts' => sprintf("%'.03d", $last->no_sts),
				'id_jenis_tr_esamsat' => $get_penerimaan,
				'jenis_transaksi' => $get_jenis_transaksi,
				'tgl_mulai_penyetoran' => $last->start_tgl,
				'tgl_batas_penyetoran' => $this->Fungsi->getdatetimesql_string( $get_tgl_akhir ),
				'id_th_anggaran_app' => $get_th,
				'id_bank' => $get_bank,
				'last_input' => date( 'Y-m-d H:i:s' ),
				'id_session_app' => $this->session->userdata('id_user')
				);

			$simpan = $this->Nosts->simpan( $post_data );
			if ( $simpan == '' || empty( $simpan ) ) {
				$status = 1;
				$idsts = $getid;
			} else {
				$error = $simpan;
			}
		} else {
			$error = 'Maaf Tanggal Penyetoran terakhir sudah pernah digunakan !';
		}

		$json['Status'] = $status;
		$json['Error'] = $error;
		$json['IdSts'] = $idsts;
		echo json_encode($json);

	}

	public function pagedatanosts()
	{
		$data['title'] = 'PENCARIAN No. STS';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'riwayat pencarian no sts';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function get_arr_data() {
		echo json_encode( array( 'data' => $this->Nosts->getAllby() ) );
	}

	public function printpdf( $id ) {
		ini_set('max_execution_time', 0);
		$this->load->library('Pdf');
		$this->load->Model( 'Paraf' );

		$row = $this->Nosts->getById( $id )->row();
		$get_jumlah_from_esamsat = $this->Esamsatjateng->view_resume_sts( $row->tgl_mulai_penyetoran, $row->tgl_batas_penyetoran, $row->id_bank, $row->jenis_transaksi, $row->sync_field_api_esamsat );

		$no = 1;

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
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
		$pdf->AddPage('P', 'F4');

		$html = '';

		$html .= '<table style="font-size:9px;" cellpadding="5px"><tbody>';
		$html .= '<tr><td style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;">';
		$html .= '<table border="1" cellpadding="5px">';
			$html .= '<tr>
			<td align="center" width="45%"><br><br>PEMERINTAH PROVINSI JAWA TENGAH</td>
			<td width="25%">
			Lembar1 : Bendahara Penerima<br>
			Lembar2 : Kantor Kasda<br>
			Lembar3 : Biro Keuangan<br>
			Lembar4 : BPPD Provinsi
			</td>
			<td width="30%">
			Lembar5 : BANK JATENG Cabang<br>
			Lembar6 : BANK JATENG CB UTM<br>
			Lembar7 : UPPD Kota/Kab.<br>
			Lembar8 : Laporan
			</td>
			</tr>
			<tr>
			<td width="40%">
			<table>
			<tr>
			<td width="35%">Setoran</td><td width="5%">:</td><td width="60%"></td>
			</tr>
			<tr>
			<td>Dalam tahun</td><td>:</td><td>'. $this->Thanggaran->getDataByID( $row->id_th_anggaran_app )->row()->th_anggaran .'</td>
			</tr>
			<tr>
			<td>Jenis Setoran</td><td>:</td><td>E-SAMSAT '. $row->nama_transaksi .'</td>
			</tr>
			</table>
			</td>
			<td width="30%" align="center">
			<b>SURAT TANDA SETORAN</b><br><br>'. $row->format_no_sts .'
			</td>
			<td width="30%">
			Penyetoran Seperti ini dilakukan yang terakhir pada tanggal :<br>'. $row->tgl_batas_sts .'
			</td>
			</tr>
			</table>';
		$html .= '</td></tr>';
		$html .= '<tr><td style="border-left:1px solid #000; border-right:1px solid #000;">';
		$html .= '<br><br>';
		$html .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank : '. $row->nama_bank .'<br>';
		$html .= 'Harap menerima uang sebesar : Rp. '. number_format( $this->get_total( $get_jumlah_from_esamsat ), 2) .' { Total }<br>';
		$html .= 'Dengan huruf : <i># '. ucwords( $this->Fungsi->terbilang( $this->get_total( $get_jumlah_from_esamsat ) ) ) .' Rupiah #</i>';
		$html .= '<br><br>';
		$html .= '</td></tr>';
		$html .= '<tr><td style="border-left:1px solid #000; border-right:1px solid #000;">';
		$html .= '<table cellpadding="5px"><thead>';
		$html .= '<tr><th align="center" width="8%" style="border:1px solid #000;">No. Urut</th><th align="center" width="30%" style="border:1px solid #000;">Kode Rekening</th><th align="center" width="45%" style="border:1px solid #000;">URAIAN RINCIAN OBYEK</th><th align="center" width="17%" style="border:1px solid #000;">JUMLAH (Rp.)</th></tr></thead><tbody>';

		$rekening_induk = $this->Rekeningpad->get_induk_by( $row->id_th_anggaran_app, $row->sync_field_pad )->row();

		$html .= '<tr>
			<td align="center" width="8%" style="border-left:1px solid #000;"></td>
			<td align="center" width="30%" style="border-left:1px solid #000;"><b>'. $rekening_induk->no_rekening .'</b></td>
			<td align="center" width="45%" style="border-left:1px solid #000;"><b>'. $rekening_induk->nm_rekening .'</b></td>
			<td align="center" width="17%" style="border-left:1px solid #000; border-right:1px solid #000;"></td>
			</tr>';

		$no = 1;
		$total = 0;

		foreach( $this->Rekeningpad->get_list_by( $row->id_th_anggaran_app, $row->sync_field_pad )->result() as $r ) {
			//$arr_result[$r->kd_inisial]['jumlah'] = empty( $get_jumlah_from_esamsat[ $r->kd_inisial ] ) ? 0 : $get_jumlah_from_esamsat[ $r->kd_inisial ];
			$jumlah = empty( $get_jumlah_from_esamsat[ $r->kd_inisial ] ) ? 0 : $get_jumlah_from_esamsat[ $r->kd_inisial ];

			$html .= '<tr>
			<td style="border-left:1px solid #000;" align="center">'. $no .'</td>
			<td style="border-left:1px solid #000;">'. $r->no_rekening .'</td>
			<td style="border-left:1px solid #000;">'. $r->nm_rekening .'</td>
			<td style="border-left:1px solid #000; border-right:1px solid #000;" align="right">'. number_format($jumlah, 0) .'</td>
			</tr>';
			$no++;
			$total += $jumlah;
		}

		$html .= '<tr>
			<td style="border:1px solid #000;" align="center" colspan="3" width="83%" align="right"><b>T o t a l</b></td>
			<td style="border:1px solid #000;" align="center" width="17%" align="right"><b>'. number_format( $total, 0 ) .'</b></td>
			</tr>';

		$html .= '</tbody></table>';
		$html .= '</td></tr>';

		$paraf = $this->Paraf->get_paraf_sts();
		if ( $paraf->num_rows() > 0 ) {
			$rowp = $paraf->result_array();
			$html .= '<tr><td style="border-left:1px solid #000; border-right:1px solid #000;">';
			$html .= '<br><br><br><table>';
			$html .= '<tr>
			<td align="center" width="30%">
			Mengetahui,<br>
			'. $rowp[0]['nama_paraf'] .'
			<br><br><br><br><br>
			<span style="text-decoration:underline;">'. $rowp[0]['nama_pegawai'] .'</span>
			<br>
			'. $rowp[0]['nip'] .'
			</td>
			<td align="center" width="40%"></td>
			<td align="center" width="30%">
			Semarang, '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'<br>
			'. $rowp[1]['nama_paraf'] .'
			<br><br><br><br><br>
			<span style="text-decoration:underline;">'. $rowp[1]['nama_pegawai'] .'</span>
			<br>
			'. $rowp[1]['nip'] .'
			</td>
			</tr>
			<tr>
			<td align="center" width="30%"></td>
			<td align="center" width="40%">
			Uang tersebut diterima,<br>
			tanggal : '. $this->Fungsi->format_sql_to_indo( date('Y-m-d') ) .'
			<br><br><br><br><br>
			( --------------------------------------------- )
			</td>
			<td align="center" width="30%"></td>
			</tr>';
			$html .= '</table>';
			$html .= '</td></tr>';
		}

		$html .= '<tr><td style="border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">';
		$html .= '</td></tr>';
		$html .= '</tbody></table>';
		
		

		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output( 'test.pdf', 'I');
		//echo $html;
	}

	private function get_total( $arr ) {
		$jml = 0;
		foreach( $arr as $key=>$value ) {
			$jml += $value;
		}
		return $jml;
	}
}
