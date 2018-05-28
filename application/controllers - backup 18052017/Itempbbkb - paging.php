<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itempbbkb extends CI_Controller {

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
        $this->load->Model('Pbbkb');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'ITEM PAJAK BAHAN BAKAR KENDARAAN BERMOTOR (PBBKB)';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data item pbbkb';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function load_json_grid()
	{
		$page = $this->security->xss_clean($_POST['pagenum']);
		$countrow = $this->security->xss_clean($_POST['pagesize']);
		$offset = $page*$countrow;
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) ? 'id_item_pbbkb' : $_POST['sortdatafield'];

		$total_rows = $this->Pbbkb->TotalDataGrid($cond);
		$items = $this->Pbbkb->getGridData($offset, $countrow, $cond, $sort);

		$data[] = array(
	    	'TotalRows' => $total_rows,
		   	'Rows' => $items
		);
		echo json_encode($data);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postId = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		$postItem = empty($_POST['txtitem']) ? '' : $this->security->xss_clean($_POST['txtitem']);
		$getTime = date('Y-m-d H:i:s');

		switch ($postAksi) {
			case 'add':
				$arrPostData = array(
					'id_item_pbbkb' => $postId,
					'item_pbbkb' => $postItem,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Pbbkb->tambah($arrPostData);

				if ( $result == '' ) {
					$msg = 'Jenis Pajak Bahan Bakar berhasil ditambahkan !';
				} else {
					$error = $result;
				}
				break;
			case 'edit':
				$arrPostData = array(
					'item_pbbkb' => $postItem,
					'create_by' => $this->session->userdata('id_user'),
					'last_update' => $getTime
					);
				$result = $this->Pbbkb->ubah($arrPostData, $postId);

				if ( $result == '' ) {
					$msg = 'Jenis Pajak Bahan Bakar berhasil diperbaharui !';
				} else {
					$error = $result;
				}
				break;
			case 'delete':
				
				$result = $this->Pbbkb->hapus($postId);

				if ( $result == '' ) {
					$msg = 'Jenis Pajak Bahan Bakar berhasil di hapus !';
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

	public function item_detil()
	{
		$postId = $this->security->xss_clean($_POST['id']);
		$error = '';
		$item = '';

		$cek = $this->Pbbkb->getDataById($postId);
		if ( $cek->num_rows() == 1 ) {
			$item = $cek->row();
		} else {
			$error = 'Maaf data tidak ditemukan';
		}

		$json['error']=$error;
		$json['item']=$item;
		echo json_encode($json);
	}
}
