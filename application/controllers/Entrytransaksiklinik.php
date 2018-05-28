<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entrytransaksiklinik extends CI_Controller {

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
	private $folder = 'new klinik/';
	
	public function __construct() {
        parent::__construct();
		$this->load->Model('Transaksiklinik');
		//if($this->session->userdata('status_login')==FALSE){
            //redirect('login');
        //}
    }
	
	public function index()
	{
		$data['title'] = 'ENTRY PEMBAYARAN KLINIK';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form pembayaran klinik baru';
		
        $this->load->view(element('themestruck_template',$this->Opsisite->getDataSite()), $data);
	}
	
	public function daftarpasientelahrekammedis()
	{
		$this->load->Model('Antrianpasien');
		$arr = $this->Antrianpasien->daftarpasienreservasiygtelahrekammedis();
		
		echo json_encode($arr);
	}
	
	public function viewdetilrekammedis()
	{
		$this->load->Model('Rekammedis');
		$postid = $this->security->xss_clean($_POST['postid']);
		
		$json=array();
		$error="";
		$msg="";
		$getdetil = '';
		
		$querydetil = $this->Rekammedis->getreservasiyangtelahmelakukanrekammedis($postid);
		
		if ( $querydetil->num_rows() == 1 )
		{
			$getdetil = $querydetil->row();
		} else {
			$error = 'Maaf reservasi pasien tidak di temukan !';
		}
		
		$json['error']=$error;
		$json['msg']=$msg;
		$json['getdetil'] = $getdetil;
		echo json_encode($json);
	}
	
	public function aksi()
	{
		$this->load->Model('Penjualanobat');
		
		$json=array();
		$error="";
		$msg="";
		
		$postaksi = $this->security->xss_clean($_POST['haksi']);
		//$postnofaktur = $this->security->xss_clean($_POST['txtnofaktur']);
		$postrekammedis = empty($_POST['txtidantrianrekammedis']) || $_POST['txtidantrianrekammedis']=='' ? '' : $this->security->xss_clean($_POST['txtidantrianrekammedis']);
		$posttgl = $this->Fungsi->formatdatetosql($this->security->xss_clean($_POST['txttgltrx']));
		$postbayar = $this->security->xss_clean($_POST['txtdibayar']);
		$postdiskon = $this->security->xss_clean($_POST['txtpotongan']);
		$postppn = $this->security->xss_clean($_POST['txtpajak']);
		$postcatatan = $this->security->xss_clean($_POST['txtcatatan']);
		$getiduser = $this->session->userdata('id_user');
		
		if (isset($_POST['hidmedis'])) {
			$n = 0;
			foreach ($_POST['hidmedis'] as $r) {
				$itemPostTindakan[$n][1] = $r;
				$n++;
			}
			$n = 0;
			foreach ($_POST['postharga2'] as $r) {
				$itemPostTindakan[$n][2] = $r;
				$n++;
			}
			$n = 0;
			foreach ($_POST['postdiskon2'] as $r) {
				$itemPostTindakan[$n][3] = $r;
				$n++;
			}
			$n = 0;
			foreach ($_POST['postppn2'] as $r) {
				$itemPostTindakan[$n][4] = $r;
				$n++;
			}
			$n = 0;
			foreach ($_POST['postfee'] as $r) {
				$itemPostTindakan[$n][5] = $r;
				$n++;
			}

		} else {
			$itemPostTindakan = array();
		}
		
		switch( $postaksi ) {
			case 'add' :
				$getid = time();
				
				$arrTrx = array(
					'id_trx_jual' => $getid,
					'jenis_transaksi' => 'KL',
					'no_faktur' => sprintf("%'.06d", $this->Transaksiklinik->getNoFaktur()+1),
					'tgl_jual' => $posttgl,
					'id_antrian_rekam_medis' => $postrekammedis,
					'diskon' => $postdiskon,
					'ppn' => $postppn,
					'jumlah_diterima' => $postbayar,
					'catatan' => $postcatatan,
					'id_user' => $getiduser
				);
				
				$result = $this->Penjualanobat->bayarklinik($getid, $arrTrx, array(), $itemPostTindakan, $postrekammedis);
				if ( $result == ''){
					$msg = 'Transaksi berhasil di simpan. Halaman akan membuka form transaksi baru.';
				}else{
					$error = $result;
				}
				
			break;
			
			case 'edit' :
				$getid = $this->security->xss_clean($_POST['Id']);
				
				$arrTrx = array(
					'tgl_jual' => $posttgl,
					'diskon' => $postdiskon,
					'ppn' => $postppn,
					'jumlah_diterima' => $postbayar,
					'catatan' => $postcatatan,
					'id_user' => $getiduser
				);
				
				$result = $this->Penjualanobat->ubah($getid, $arrTrx, array(), $itemPostTindakan);
				if ( $result == ''){
					$msg = 'Transaksi berhasil di perbaharui.';
				}else{
					$error = $result;
				}
			break;
			
			default :
				$error = "Gagal dalam menentukan aksi !";
			break;
		}
		
		$json['getid'] = $getid;
		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function cetak_struk()
	{
		$get_id = $this->security->xss_clean($_POST['id']);
		$row = $this->Transaksiklinik->get_date_by_id($get_id)->row();
		$getmedis = $this->Transaksiklinik->arrTransaksiMedisById($get_id);

		$html_struk = '<style> #print-struk{width:260px;font-size:8pt;font-family:"Courier New", Courier, monospace !important; line-height: 1.8; } #print-struk table{font-size:8pt;font-family:"Courier New", Courier, monospace !important;} #print-struk table tr td {border-collapse:collapse; vertical-align:top;} .border-top{ border-top:0.5px solid #000;} .border-bottom{ border-bottom:0.5px solid #000;}</style><div id="print-struk">';

		$html_struk .= '<center>'. element('nama_perusahaan',$this->Opsisite->getDataSite()) .'<br>';
		$html_struk .= element('alamat_perusahaan',$this->Opsisite->getDataSite()) .', '. element('kota',$this->Opsisite->getDataSite()) .'<br>';
		$html_struk .= element('no_telp',$this->Opsisite->getDataSite()) .'<br></center>';
		$html_struk .= '<center><strong>NOTA TINDAKAN KLINIK</strong></center>';
		$html_struk .= '<table cellpadding="2">';
		//$html_struk .= '<tr><td colspan="3">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td class="border-top">'. $this->Fungsi->formatsqltodate(date('Y-m-d')) .' '. date('H:i:s') .'</td><td align="right" colspan="2" class="border-top">'. $row->no_faktur .'</td></tr>';
		$html_struk .= '<tr><td>USER</td><td align="right" colspan="2">'. element('nama_user',$this->Opsisite->getDataUser()) .'</td></tr>';
		$html_struk .= '<tr><td>DOKTER</td><td align="right" colspan="2">'. $row->nama_dokter .'</td></tr>';
		$html_struk .= '<tr><td>SPESIALISASI</td><td align="right" colspan="2">'. $row->spesialisasi .'</td></tr>';
		$html_struk .= '<tr><td>PASIEN</td><td align="right" colspan="2">'. $row->nama_pasien .'</td></tr>';
		$html_struk .= '<tr><td>ALAMAT</td><td align="right" colspan="2">'. $row->alamat .'</td></tr>';
		//$html_struk .= '<tr><td colspan="3">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td align="center" class="border-top border-bottom">Nama</td><td align="center" width="5px" class="border-top border-bottom">Disk(%)</td><td align="center" class="border-top border-bottom">Total</td></tr>';
		//$html_struk .= '<tr><td colspan="3">'. $this->html_garis() .'</td></tr>';
		foreach ( $getmedis as $r )
		{
			$html_struk .= '<tr><td>'. $r->tindakan_medis .'</td><td align="center">'. number_format($r->diskon_item) .'</td><td align="right">'. number_format($r->total) .'</td></tr>';
		}
		//$html_struk .= '<tr><td colspan="3" >'. $this->html_garis() .'</td></tr>';
		
		//$html_struk .= '<tr><td>'. $item .' item</td><td>DISKON</td><td align="right" colspan="2">'. number_format($row->diskon) .' %</td></tr>';
		$html_struk .= '<tr><td colspan="2">TOTAL</td><td align="right" class="border-top">'. number_format($row->total) .'</td></tr>';
		//$html_struk .= '<tr><td></td><td>PPN</td><td align="right" colspan="2">'. number_format($row->ppn) .' %</td></tr>';
		//$html_struk .= '<tr><td></td><td>BIAYA RESEP</td><td align="right" colspan="2">'. number_format($row->biaya_resep) .'</td></tr>';
		//$html_struk .= '<tr><td></td><td>BIAYA RACIK</td><td align="right" colspan="2">'. number_format($row->biaya_racik) .'</td></tr>';
		//$html_struk .= '<tr><td></td><td>PEMBULATAN</td><td align="right" colspan="2">'. number_format($row->pembulatan) .'</td></tr>';
		//$html_struk .= '<tr><td></td><td>TOTAL</td><td align="right" colspan="2">'. number_format($row->total_dibayar) .'</td></tr>';
		//$html_struk .= '<tr><td colspan="3">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">TUNAI</td><td align="right" class="border-top">'. number_format($row->jumlah_diterima) .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">KEMBALIAN</td><td align="right" class="border-bottom">'. number_format($row->jumlah_diterima-$row->total) .'</td></tr>';
		$html_struk .= '</table>';
		//$html_struk .= '<tr><td colspan="3">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<center><b>TERIMA KASIH ATAS KUNJUNGAN ANDA</b></center>';

		$html_struk .= '</div>';
		echo $html_struk;

	}

	public function cetak_kwitansi()
	{
		$get_id = $this->security->xss_clean($_POST['id']);
		$row = $this->Transaksiklinik->get_date_by_id($get_id)->row();
		
		$item = 0;

		$html_struk = '<style> #print-kwitansi{width:260px;font-size:8pt; font-family:"Courier New", Courier, monospace !important; line-height: 1.8;}#print-kwitansi table{font-size:8pt; font-family:"Courier New", Courier, monospace !important; width:260px;}#print-kwitansi table tr td {border-collapse:collapse; vertical-align:top;}</style><div id="print-kwitansi">';

		$html_struk .= '<center>'. element('nama_perusahaan',$this->Opsisite->getDataSite()) .'<br>';
		$html_struk .= element('alamat_perusahaan',$this->Opsisite->getDataSite()) .', '. element('kota',$this->Opsisite->getDataSite()) .'<br>';
		$html_struk .= element('no_telp',$this->Opsisite->getDataSite()) .'<br></center>';
		$html_struk .= '<center><strong>K W I T A N S I</strong></center>';
		$html_struk .= '<table>';
		$html_struk .= '<tr><td colspan="2">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">Bukti Pembayaran</td></tr>';
		$html_struk .= '<tr><td width="50px">Invoice</td><td align="right">'. $row->no_faktur .'</td></tr>';
		$html_struk .= '<tr><td>Tanggal</td><td align="right">'. $this->Fungsi->formatsqltodate($row->tgl_jual) .'</td></tr>';
		$html_struk .= '<tr><td>Dokter</td><td align="right">'. $row->nama_dokter .'</td></tr>';
		$html_struk .= '<tr><td>Spesialisasi</td><td align="right">'. $row->spesialisasi .'</td></tr>';
		$html_struk .= '<tr><td valign="top">Pasien</td><td align="right" valign="top">'. $row->nama_pasien .'</td></tr>';
		$html_struk .= '<tr><td valign="top">Alamat</td><td align="right" valign="top">'. $row->alamat .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td><b>TOTAL</b></td><td align="right"><b>'. number_format($row->total) .'</b></td></tr>';
		$html_struk .= '<tr><td colspan="2">'. $this->html_garis() .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">'. $this->Fungsi->formatsqltodate(date('Y-m-d')) .' '. date('H:i:s') .' / '. element('nama_user',$this->Opsisite->getDataUser()) .'</td></tr>';
		$html_struk .= '<tr><td colspan="2">Terbilang :</td></tr>';
		$html_struk .= '<tr><td colspan="2" align="right">'. $this->Fungsi->terbilang($row->total) .' Rupiah</td></tr>';
		
		$html_struk .= '<tr><td colspan="2">'. $this->html_garis() .'</td></tr>';
		
		$html_struk .= '</table>';

		$html_struk .= '<center><b>TERIMA KASIH ATAS KUNJUNGAN ANDA</b><br>MELAYANI DENGAN SETULUS HATI</center>';

		$html_struk .= '</div>';
		echo $html_struk;

	}

	private function html_garis()
	{
		$txt = '';
		for( $garis=1;$garis<=40;$garis++ )
		{
			$txt .= ".";
		}
		return $txt .'<br>';
	}
}
