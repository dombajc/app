<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setthanggaran extends CI_Controller {

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
        $this->load->Model('Thanggaran');
		if($this->session->userdata('status_login') == FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Pengaturan Tahun Anggaran';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'atur th anggaran';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();

		switch ($_POST['haksi']) {
			
			case 'add':
				
				$arrPost = array(
					'id_anggaran' => $id,
					'th_anggaran' => $this->security->xss_clean($_POST['slctth']),
					'keterangan' => $this->security->xss_clean($_POST['txtketerangan'])
				);
				$result = $this->Thanggaran->tambah($arrPost);
				if ( $result == ''){
					$msg = 'Tahun Anggaran Baru berhasil di simpan !';
				}else{
					$error = $result;
				}

				break;
			case 'edit':
				
				$arrPost = array(
					'th_anggaran' => $this->security->xss_clean($_POST['slctth']),
					'keterangan' => $this->security->xss_clean($_POST['txtketerangan'])
				);
				
				$result = $this->Thanggaran->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Tahun Anggaran berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'validasi':
				$arrPost = array(
					'aktif' => $this->security->xss_clean($_POST['value'])
				);
				$result = $this->Thanggaran->aktif($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Tahun Anggaran berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'delete':
				$result = $this->Thanggaran->delete($this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Tahun Anggaran berhasil di dihapus !';
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

	public function json_data_grid()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="id_anggaran";
		$where = '1=1';
		
		//$where .= $_POST['sNama'] == "" ? "" : " and nama_obat LIKE '%". $_POST['sNama'] ."%'";
		//$where .= $_POST['sKode'] == "" ? "" : " and kode_obat LIKE '%". $_POST['sKode'] ."%'";
		//$where .= $_POST['sKategori'] == "" ? "" : " and t_obat.id_kategori='". $_POST['sKategori'] ."'";
		
		//Handles Sort querystring sent from Bootgrid
		if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort']) )
		{
			$urutan = "";
			$jmlSort = count($_REQUEST['sort']);
			$i = 1;
			foreach($_REQUEST['sort'] as $key=> $value)
			{
				$urutan.="$key $value";
				if ( $i < $jmlSort ){
					$urutan.=",";
				}else{
					$urutan.="";
				}
				$i++;
			}
		}
		
		//Handles search querystring sent from Bootgrid
		//if (isset($_REQUEST['searchPhrase']) )
		//{
		//$search=trim($_REQUEST['searchPhrase']);
		//$where.= " AND ( movie LIKE '".$search."%' OR year LIKE '".$search."%' OR genre LIKE '".$search."%' ) ";
		//}
		
		$total = $this->Thanggaran->countDataAll($where);
		$query = $this->Thanggaran->getLoadAllDataWithLimit($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_anggaran" : "'. $row->id_anggaran .'",
				"th_anggaran" : "'. $row->th_anggaran .'",
				"keterangan" : "'. $row->keterangan .'",
				"aktif" : "'. $row->aktif .'"}';
				$i++;
				if ( $i > $cek ){
					$data .= '';
				}else{
					$data .= ',';
				}
				
			}
		}
		
		$json = '{"current": '. $page .',
		  "rowCount": '. $countrow .',
		  "rows": [';
			$json .= $data;
		$json .='],
		"total": '. $total .'}';
		
		echo $json;
	}

	public function json_detil()
	{
		$json=array();
		$error="";
		$th_anggaran = '';
		$keterangan = '';
		
		$getData = $this->Thanggaran->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();
			$th_anggaran = $row->th_anggaran;
			$keterangan = $row->keterangan;
		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['th_anggaran'] = $th_anggaran;
		$json['keterangan'] = $keterangan;
		$json['error'] = $error;
		
		echo json_encode($json);
	}
}
