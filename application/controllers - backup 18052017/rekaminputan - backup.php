<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekaminputan extends CI_Controller {

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
	private $folder = 'inputan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->Model('Trxd2d');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKAM INPUT D2D';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form rekam inputan d2d';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_detil_rekam_by_pegawai()
	{
		$this->load->Model('Pegawai');

		$json=array();
		$error="";
		$arrBulan = array();
		
		$getData = $this->Pegawai->getDataByID($this->security->xss_clean($_POST['ip']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();

			$QueryRiwayatInput = $this->Trxd2d->getTransaksiInput($this->security->xss_clean($_POST['idt']), $this->security->xss_clean($_POST['irl']), $this->security->xss_clean($_POST['ia']));
			foreach ( $QueryRiwayatInput->result() as $in ) {
				$arrBulan[] = array(
					'id_bulan' => $in->id_bulan,
					'bulan' => $in->bulan,
					'jumlah' => $in->jumlah
				);
			}

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['arrBulan'] = $arrBulan;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();

		switch ($_POST['haksi']) {
			
			case 'add':
				
				$arrPost = array();

				$n = 0;
				foreach ($_POST['txtbulan'] as $r) {
					$arr[$n][1] = $r;
					$n++;
				}

				$n = 0;
				foreach ($_POST['txtjumlah'] as $r) {
					$arr[$n][2] = $r;
					$n++;
				}

				$i = 0;
				foreach( $arr as $r ) {
					if ( $r[2] > 0 ){
						$arrPost[] = array(
							'id_trx' => $id.$i,
							'id_riwayat_lokasi' => $this->security->xss_clean($_POST['irl']),
							'id_bulan' => $r[1],
							'id_anggaran' => $this->security->xss_clean($_POST['ia']),
							'jumlah' => $r[2],
							'id_user' => $this->session->userdata('id_user')
						);
						$i++;
					}
				}

				$result = $this->Trxd2d->tambah($arrPost, $arr, $this->security->xss_clean($_POST['irl']), $this->security->xss_clean($_POST['ia']));
				if ( $result == ''){
					$msg = 'Rekam D2D berhasil di simpan !';
				}else{
					$error = $result;
				}
				
				break;
			
			case 'delete':

				if ( $this->Trxd2d->countTransaksiByIdPegawai($this->security->xss_clean($_POST['hID'])) == 0 ) {
					$result = $this->Pegawai->delete($this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Data Pegawai berhasil di dihapus !';
					}else{
						$error = $result;
					}
				} else {
					$error = 'Maaf pegawai tidak dapat dihapus karena sudah pernah melakukan input transaksi !';
				}

				break;
			default:
				$error = 'Aksi tidak ditemukan !';
				break;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

}
