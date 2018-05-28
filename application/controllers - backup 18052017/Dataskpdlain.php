<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataskpdlain extends CI_Controller {

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
	private $folder = 'retribusi/';
	
	public function __construct() {
        parent::__construct();
        $this->load->Model('Skpdlain');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DATA SKPD LAIN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data skpd lain';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_tabel()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_skpd asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariNama']) || $_POST['cariNama'] == '' ? '' : " and nama_skpd like '%". $_POST['cariNama'] ."%'";
		$cond .= empty($_POST['cariLokasi']) || $_POST['cariLokasi'] == '' ? '' : " and id_lokasi='". $_POST['cariLokasi'] ."'";

		$items = $this->Skpdlain->arr_tabel($cond, $sort);

		echo json_encode($items);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hid']);
		$postNama = empty($_POST['txtnamaskpd']) ? '' : $this->security->xss_clean($_POST['txtnamaskpd']);
		$postAlamat = empty($_POST['txtalamat']) ? '' : $this->security->xss_clean($_POST['txtalamat']);
		$postLokasi = empty($_POST['slctlokasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi']);
		$postTelp = empty($_POST['txttelp']) ? '' : $this->security->xss_clean($_POST['txttelp']);
		$getTime = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				
				$arrPostData = array(
					'id_skpd' => $postId,
					'id_lokasi' => $postLokasi,
					'nama_skpd' => $postNama,
					'alamat_skpd' => $postAlamat,
					'no_telp' => $postTelp,
					'id_user' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Skpdlain->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI TAMBAHKAN !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':

				$arrPostData = array(
					'id_lokasi' => $postLokasi,
					'nama_skpd' => $postNama,
					'alamat_skpd' => $postAlamat,
					'no_telp' => $postTelp,
					'id_user' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Skpdlain->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI UBAH !';
				} else {
					$error = $result;
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif']
					);
				$result = $this->Skpdlain->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI UBAH !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Skpdlain->hapus($postId);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI HAPUS !';
				} else {
					$error = $result;
				}
				break;
			default:
				$error = 'Perintah yang diterima salah !';
				break;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function json_row()
	{
		$postId = $this->security->xss_clean($_POST['id']);
		$error = '';
		$item = '';

		$cek = $this->Skpdlain->get_data_by_id($postId);
		if ( $cek->num_rows() == 1 ) {
			$item = $cek->row();
		} else {
			$error = 'Maaf data tidak ditemukan';
		}

		$json['error']=$error;
		$json['item']=$item;
		echo json_encode($json);
	}

	public function get_json_spbu()
	{
		$json = array();
		$postIdLokasi = $this->security->xss_clean($_POST['postIdLokasi']);
		$postJenis = $this->security->xss_clean($_POST['postJenis']);
		
		$item = $this->Spbu->getDataByIdLokasi($postIdLokasi, $postJenis);

		$json['opsi'] = $item;
		echo json_encode($json);
	}
}
