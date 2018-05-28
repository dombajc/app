<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtasalkotabbm extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Kota Asal Penyalur BBM';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data kota penyalur bbm';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function load_json_grid()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_kota_asal asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariKota']) || $_POST['cariKota'] == '' ? '' : " and kota_asal like '%". $_POST['cariKota'] ."%'";
		$cond .= empty($_POST['cariProv']) || $_POST['cariProv'] == '' ? '' : " and id_provinsi='". $_POST['cariProv'] ."'";

		$items = $this->Kotaasal->get_array_grid($cond, $sort);

		echo json_encode($items);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		$postKota = empty($_POST['txtkota']) ? '' : trim($this->security->xss_clean($_POST['txtkota']));
		$postProvinsi = empty($_POST['slctprov']) ? '' : $this->security->xss_clean($_POST['slctprov']);
		$getTime = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				$arrPostData = array(
					'id_kota_asal' => $postId,
					'kota_asal' => $postKota,
					'id_provinsi' => $postProvinsi,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
					
				if ( $this->Kotaasal->cek_sudah_pernah_input_belum($postKota, $postProvinsi) == 0 ) {
					$result = $this->Kotaasal->tambah($arrPostData);
					if ( $result == '' ) {
						$msg = 'Nama Kota berhasil ditambahkan !';
					} else {
						$error = $result;
					}
				} else {
					$error = 'Maaf Kota sudah pernah di tambahkan !';
				}
				
				break;
			case 'edit':
				
				if ( $this->Kotaasal->cek_edit_sudah_pernah_input_belum($postId, $postKota, $postProvinsi) == 0 ) {
					$error = 'Maaf data sudah pernah di tambahkan !';
				} else {
					$arrPostData = array(
						'kota_asal' => $postKota,
						'id_provinsi' => $postProvinsi,
						'create_by' => $this->session->userdata('id_user'),
						'last_update' => $getTime
						);
					$result = $this->Kotaasal->ubah($arrPostData, $postId);

					if ( $result == '' ) {
						$msg = 'Nama Kota berhasil diperbaharui !';
					} else {
						$error = $result;
					}
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif']
					);
				$result = $this->Kotaasal->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Nama Kota berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Kotaasal->hapus($postId);

				if ( $result == '' ) {
					$msg = 'Nama Kota berhasil di hapus !';
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

	public function view_detil()
	{
		$postId = $this->security->xss_clean($_POST['id']);
		$error = '';
		$item = '';

		$cek = $this->Kotaasal->getById($postId);
		if ( $cek->num_rows() == 1 ) {
			$item = $cek->row();
		} else {
			$error = 'Maaf data tidak ditemukan';
		}

		$json['error']=$error;
		$json['item']=$item;
		echo json_encode($json);
	}

	public function json_kota_by_provinsi()
	{
		$json = array();
		$postProv = $this->security->xss_clean($_POST['postIdProvinsi']);
		$opsiKota = $this->Kotaasal->arr_data_by_prov($postProv);

		$json['opsi'] = $opsiKota;
		echo json_encode($json);
	}
}
