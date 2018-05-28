<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtpenyalurbbm extends CI_Controller {

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
        $this->load->Model('Penyalurbbm');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Data Penyalur/Penyedia Bahan Bakar';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data penyalur bahan bakar';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function load_json_grid()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_penyalur asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariNama']) || $_POST['cariNama'] == '' ? '' : " and nama_penyalur like '%". $_POST['cariNama'] ."%'";
		$cond .= empty($_POST['cariProv']) || $_POST['cariProv'] == '' ? '' : " and id_provinsi='". $_POST['cariProv'] ."'";

		$items = $this->Penyalurbbm->get_array_grid($cond, $sort);

		echo json_encode($items);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		$postNama = empty($_POST['txtnama']) ? '' : $this->security->xss_clean($_POST['txtnama']);
		$postAlamat = empty($_POST['txtalamat']) ? '' : $this->security->xss_clean($_POST['txtalamat']);
		$postTelp = empty($_POST['txttelp']) ? '' : $this->security->xss_clean($_POST['txttelp']);
		$postKota = empty($_POST['slctkota']) ? '' : $this->security->xss_clean($_POST['slctkota']);
		$getTime = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				$arrPostData = array(
					'id_penyalur' => $postId,
					'id_kota_asal' => $postKota,
					'nama_penyalur' => $postNama,
					'alamat' => $postAlamat,
					'telp' => $postTelp,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Penyalurbbm->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'Data Penyalur berhasil ditambahkan !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':
				$arrPostData = array(
					'id_kota_asal' => $postKota,
					'nama_penyalur' => $postNama,
					'alamat' => $postAlamat,
					'telp' => $postTelp,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Penyalurbbm->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Data Penyalur berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif']
					);
				$result = $this->Penyalurbbm->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Data Penyalur berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Penyalurbbm->hapus($postId);

				if ( $result == '' ) {
					$msg = 'Data Penyalur berhasil di hapus !';
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

		$cek = $this->Penyalurbbm->getById($postId);
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

	public function get_data_by_id_kota()
	{
		$json = array();
		$postKota = $this->security->xss_clean($_POST['postIdKotaAsal']);
		$json['opsi'] = $this->Penyalurbbm->getDataByKota($postKota);
		echo json_encode($json);
	}
}
