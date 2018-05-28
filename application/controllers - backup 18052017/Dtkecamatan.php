<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtkecamatan extends CI_Controller {

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
        $this->load->Model('Kecamatan');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DATA KECAMATAN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data kecamatan';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function load_json_grid()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_kecamatan asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariNama']) || $_POST['cariNama'] == '' ? '' : " and kecamatan like '%". $_POST['cariNama'] ."%'";
		$cond .= empty($_POST['cariLokasi']) || $_POST['cariLokasi'] == '' ? '' : " and id_lokasi='". $_POST['cariLokasi'] ."'";

		$items = $this->Kecamatan->getDataByCond($cond, $sort);

		echo json_encode($items);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		$postKecamatan = empty($_POST['txtkecamatan']) ? '' : $this->security->xss_clean($_POST['txtkecamatan']);
		$postLokasi = empty($_POST['slctlokasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi']);
		$getTime = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				$arrPostData = array(
					'id_kecamatan' => $postId,
					'kecamatan' => $postKecamatan,
					'id_lokasi' => $postLokasi,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Kecamatan->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'Nama Kecamatan berhasil ditambahkan !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':
				$arrPostData = array(
					'kecamatan' => $postKecamatan,
					'id_lokasi' => $postLokasi,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Kecamatan->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Nama Kecamatan berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif']
					);
				$result = $this->Kecamatan->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Nama Kecamatan berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Kecamatan->hapus($postId);

				if ( $result == '' ) {
					$msg = 'Nama Kecamatan berhasil di hapus !';
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

		$cek = $this->Kecamatan->getDataById($postId);
		if ( $cek->num_rows() == 1 ) {
			$item = $cek->row();
		} else {
			$error = 'Maaf data tidak ditemukan';
		}

		$json['error']=$error;
		$json['item']=$item;
		echo json_encode($json);
	}

	public function json_kecamatan_by_lokasi()
	{
		$json = array();
		$postLokasi = $this->security->xss_clean($_POST['postIdLokasi']);
		$opsiKecamatan = $this->Kecamatan->getDataByLokasi($postLokasi);

		$json['opsi'] = $opsiKecamatan;
		echo json_encode($json);
	}
}
