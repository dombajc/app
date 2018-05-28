<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aturparaf extends CI_Controller {

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
	private $folder = 'pengaturan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
        $this->load->model('Mutasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'PENGATURAN TANDA TANGAN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'pengaturan tanda tangan laporan';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_detil()
	{

		$json=array();
		$error="";
		$arrPegawai = $this->Mutasi->getPegawaiAktifByLokasi($_POST['lokasi'])->result_array();
		$arrParaf = $this->Paraf->getDataSettingParaf($_POST['lokasi']);

		$json['error'] = $error;
		$json['arrPegawai'] = $arrPegawai;
		$json['arrParaf'] = $arrParaf;
		
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
				foreach ($_POST['txtparaf'] as $r) {
					$arrInput[$n][1] = $r;
					$n++;
				}

				$n = 0;
				foreach ($_POST['slctpegawai'] as $r) {
					$arrInput[$n][2] = $r;
					$n++;
				}

				$i = 1;
				foreach ( $arrInput as $row ) {
					$arrPost[] = array(
						'id_data_setting_paraf' => $id.$i,
						'id_lokasi' => $this->security->xss_clean($_POST['postLokasi']),
						'id_pegawai' => $row[2],
						'id_paraf' => $row[1]
					);
					$i++;
				}

				$result = $this->Paraf->tambah($arrPost, $this->security->xss_clean($_POST['postLokasi']));
				if ( $result == ''){
					$msg = 'Pengaturan tanda tangan berhasil di simpan !';
				}else{
					$error = $result;
				}
				
				break;
			
			case 'delete':
				$result = $this->Paraf->delete($this->security->xss_clean($_POST['lokasi']));
				if ( $result == ''){
					$msg = 'Pengaturan pejabat berhasil di dihapus !';
				}else{
					$error = $result;
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
