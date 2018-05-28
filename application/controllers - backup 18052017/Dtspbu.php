<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtspbu extends CI_Controller {

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
        $this->load->Model('Spbu');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DATA SPBU / Badan Usaha';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data spbu';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function load_json_grid()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_kecamatan asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariKelompok']) || $_POST['cariKelompok'] == '' ? '' : " and id_jenis_penyalur_pbbkb='". $_POST['cariKelompok'] ."'";
		$cond .= empty($_POST['cariNo']) || $_POST['cariNo'] == '' ? '' : " and no_spbu like '%". $_POST['cariNo'] ."%'";
		$cond .= empty($_POST['cariPerusahaan']) || $_POST['cariPerusahaan'] == '' ? '' : " and nama_spbu='". $_POST['cariPerusahaan'] ."'";
		$cond .= empty($_POST['cariLokasi']) || $_POST['cariLokasi'] == '' ? '' : " and id_lokasi='". $_POST['cariLokasi'] ."'";
		$cond .= empty($_POST['cariKecamatan']) || $_POST['cariKecamatan'] == '' ? '' : " and id_kecamatan='". $_POST['cariKecamatan'] ."'";

		$items = $this->Spbu->getDataByCond($cond, $sort);

		echo json_encode($items);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		

		switch ($postAksi) {
			case 'add':
				$postJenis = $this->security->xss_clean($_POST['radJenis']);
				$postNoSpbu = empty($_POST['txtnospbu']) ? '' : $this->security->xss_clean($_POST['txtnospbu']);
				$postNama = empty($_POST['txtnama']) ? '' : $this->security->xss_clean($_POST['txtnama']);
				$postAlamat = empty($_POST['txtalamat']) ? '' : $this->security->xss_clean($_POST['txtalamat']);
				$postLokasi = empty($_POST['slctlokasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi']);
				$postKecamatan = empty($_POST['slctkecamatan']) ? '' : $this->security->xss_clean($_POST['slctkecamatan']);
				$postTelp = empty($_POST['txttelp']) ? '' : $this->security->xss_clean($_POST['txttelp']);
				$getTime = date('Y-m-d H:i:s');
				
				$arrPostData = array(
					'id_jenis_penyalur_pbbkb' => $postJenis,
					'id_lokasi_pbbkb' => $postId,
					'no_spbu' => $postNoSpbu,
					'nama_spbu' => $postNama,
					'alamat_spbu' => $postAlamat,
					'id_lokasi' => $postLokasi,
					'id_kecamatan' => $postKecamatan,
					'telp' => $postTelp,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Spbu->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'Perusahaan berhasil ditambahkan !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':
				$postJenis = $this->security->xss_clean($_POST['radJenis']);
				$postNoSpbu = empty($_POST['txtnospbu']) ? '' : $this->security->xss_clean($_POST['txtnospbu']);
				$postNama = empty($_POST['txtnama']) ? '' : $this->security->xss_clean($_POST['txtnama']);
				$postAlamat = empty($_POST['txtalamat']) ? '' : $this->security->xss_clean($_POST['txtalamat']);
				$postLokasi = empty($_POST['slctlokasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi']);
				$postKecamatan = empty($_POST['slctkecamatan']) ? '' : $this->security->xss_clean($_POST['slctkecamatan']);
				$postTelp = empty($_POST['txttelp']) ? '' : $this->security->xss_clean($_POST['txttelp']);
				$getTime = date('Y-m-d H:i:s');

				$arrPostData = array(
					'no_spbu' => $postNoSpbu,
					'nama_spbu' => $postNama,
					'alamat_spbu' => $postAlamat,
					'id_lokasi' => $postLokasi,
					'id_kecamatan' => $postKecamatan,
					'telp' => $postTelp,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Spbu->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Perusahaan berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif']
					);
				$result = $this->Spbu->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Perusahaan berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Spbu->hapus($postId);

				if ( $result == '' ) {
					$msg = 'Perusahaan berhasil di hapus !';
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

		$cek = $this->Spbu->getDataById($postId);
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
