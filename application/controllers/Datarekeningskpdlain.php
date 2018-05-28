<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datarekeningskpdlain extends CI_Controller {

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
        $this->load->Model('Rekeningskpdlain');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DAFTAR REKENING SKPD LAIN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'daftar rekening skpd lain';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_tabel()
	{
		$cond = empty($_POST['postskpd']) || $_POST['postskpd'] == '' ? '1=1' : "id_skpd='". $_POST['postskpd'] ."'";
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_rekening asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		//$cond .= empty($_POST['cariKelompok']) || $_POST['cariKelompok'] == '' ? '' : " and id_jenis_penyalur_pbbkb='". $_POST['cariKelompok'] ."'";
		//$cond .= empty($_POST['cariNo']) || $_POST['cariNo'] == '' ? '' : " and no_spbu like '%". $_POST['cariNo'] ."%'";
		//$cond .= empty($_POST['cariPerusahaan']) || $_POST['cariPerusahaan'] == '' ? '' : " and nama_spbu='". $_POST['cariPerusahaan'] ."'";
		//$cond .= empty($_POST['cariLokasi']) || $_POST['cariLokasi'] == '' ? '' : " and id_lokasi='". $_POST['cariLokasi'] ."'";
		//$cond .= empty($_POST['cariKecamatan']) || $_POST['cariKecamatan'] == '' ? '' : " and id_kecamatan='". $_POST['cariKecamatan'] ."'";

		$items = $this->Rekeningskpdlain->arr_tabel($cond, $sort);

		echo json_encode($items);
	}

	public function act()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postid = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hid']);
		$postSkpd = empty($_POST['slctskpd']) ? '' : $this->security->xss_clean($_POST['slctskpd']);
		$postNoRekening = empty($_POST['txtnorekening']) ? '' : $this->security->xss_clean($_POST['txtnorekening']);
		$postNamaRekening = empty($_POST['txtnamarekening']) ? '' : $this->security->xss_clean($_POST['txtnamarekening']);
		$postSub = empty($_POST['slctsub']) ? '0' : $this->security->xss_clean($_POST['slctsub']);
		$postMurni = empty($_POST['txtmurni']) ? '' : $this->security->xss_clean($_POST['txtmurni']);
		$postPerubahan = empty($_POST['txtperubahan']) ? '' : $this->security->xss_clean($_POST['txtperubahan']);
		$get_user = $this->session->userdata('id_user');
		$last_update = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				
				$arrPostData = array(
					'id_rekening' => $postid,
					'id_skpd' => $postSkpd,
					'no_rekening' => $postNoRekening,
					'nama_rekening' => $postNamaRekening,
					'sub_rekening' => $postSub,
					'murni' => $postMurni,
					'perubahan' => $postPerubahan,
					'create_by' => $get_user,
					'last_update' => $last_update,
					'last_act' => 'C'
					);
				$result = $this->Rekeningskpdlain->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI TAMBAHKAN !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':

				$arrPostData = array(
					'id_skpd' => $postSkpd,
					'no_rekening' => $postNoRekening,
					'nama_rekening' => $postNamaRekening,
					'sub_rekening' => $postSub,
					'murni' => $postMurni,
					'perubahan' => $postPerubahan,
					'create_by' => $get_user,
					'last_update' => $last_update,
					'last_act' => 'U'
					);
				$result = $this->Rekeningskpdlain->ubah($arrPostData, $postid);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI PERBAHARUI !';
				} else {
					$error = $result;
				}
				break;
			case 'valid':
				$arrPostData = array(
					'aktif' => $_POST['aktif'],
					'create_by' => $get_user,
					'last_update' => $last_update,
					'last_act' => 'V'
					);
				$result = $this->Rekeningskpdlain->ubah($arrPostData, $postid);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI PERBAHARUI !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Rekeningskpdlain->hapus($postid);

				if ( $result == '' ) {
					$msg = 'DATA BERHASIL DI HAPUS !';
				} else {
					$error = $result;
				}
				break;
			default:
				$error = 'PERINTAH TIDAK DAPAT DI TERIMA !';
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
	
	public function sub_rekening_option()
	{
		$post_skpd = $this->security->xss_clean($_POST['postskpd']);
		$result = $this->Rekeningskpdlain->get_rekening_by_lokasi($post_skpd)->result();
		echo json_encode($result);
	}
	
	public function get_no_rekening()
	{
		$json = array();
		$error = '';
		$data = '';
		
		$post_id_rekening = $this->security->xss_clean($_POST['postid']);
		$result = $this->Rekeningskpdlain->get_detil_by_id($post_id_rekening);
		if ( $result->num_rows() == 1 ) {
			$data = $result->row();
		} else {
			$error = 'Data tidak ditemukan !';
		}
		$json['error'] = $error;
		$json['data'] = $data;
		echo json_encode($json);
	}
	
	public function get_opsi_skpd_lain()
	{
		$this->load->model('Skpdlain');
		
		$post_lokasi = $this->security->xss_clean($_POST['postlokasi']);
		$select = "id_skpd,nama_skpd";
		$cond = " where id_lokasi='". $post_lokasi ."'";
		$result = $this->Skpdlain->get_data_by_cond($select, $cond)->result();
		echo json_encode($result);
	}
}
