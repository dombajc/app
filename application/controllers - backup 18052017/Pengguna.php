<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends CI_Controller {

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
        $this->load->Model('Users');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Data Pengguna Terdaftar';
		$data['subtitle'] = '';
		if ( $this->Opsisite->getDataUser()['pusat'] == 1 ) {
			$data['dinamisContent'] = $this->folder.'data users';
		} else {
			$data['dinamisContent'] = $this->folder.'data users daerah';
		}
		
		
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
				
				if ( $this->Users->cekUsername( $this->security->xss_clean($_POST['txtuser'])) == 0 ) {
					$arrPost = array(
						'id_user' => $id,
						'username' => $this->security->xss_clean($_POST['txtuser']),
						'katakunci' => $this->security->xss_clean($_POST['txtkatasandi']),
						'sandi' => md5($this->security->xss_clean($_POST['txtkatasandi'])),
						'nama_user' => $this->security->xss_clean($_POST['txtnama']),
						'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
						'admin' => $this->security->xss_clean($_POST['slctakses']),
						'pusat' => $this->security->xss_clean($_POST['slctstatus']),
						'menuakses' => implode(',', $_POST['chkmenu'])
					);
					$result = $this->Users->tambah($arrPost);
					if ( $result == ''){
						$msg = 'Pengguna Baru berhasil di simpan !';
					}else{
						$error = $result;
					}
				} else {
					$error = "Maaf Username sudah pernah digunakan !";
				}
				
				break;
			case 'edit':
				$arrPost2 = array();
				
				$arrPost = array(
					'katakunci' => $this->security->xss_clean($_POST['txtkatasandi']),
					'sandi' => md5($this->security->xss_clean($_POST['txtkatasandi'])),
					'nama_user' => $this->security->xss_clean($_POST['txtnama']),
					'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
					'admin' => $this->security->xss_clean($_POST['slctakses']),
					'pusat' => $this->security->xss_clean($_POST['slctstatus']),
					'menuakses' => implode(',', $_POST['chkmenu'])
				);
				
				$result = $this->Users->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Data Pengguna berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'validasi':
				$arrPost = array(
					'aktif' => $this->security->xss_clean($_POST['value'])
				);
				$result = $this->Users->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Data Pengguna berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'delete':
				$this->load->Model('Pegawai');

				// -- Cek user apa sudah pernah melakukan input pegawai
				if ( $this->Pegawai->countPegawaiByIdUser($this->security->xss_clean($_POST['hID'])) == 0 ) {
					$result = $this->Users->delete($this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Data Pengguna berhasil di dihapus !';
					}else{
						$error = $result;
					}
				} else {
					$error = 'Maaf pengguna tidak dapat di hapus karena telah melakukan aksi buat/update pegawai !';
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

	public function json_users()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="id_user";
		$where = '1=1';

		if ( $this->Opsisite->getDataUser()['pusat'] == '1' ) {
			$where = '1=1';
		} else {
			$where = "id_lokasi='". $this->Opsisite->getDataUser()['id_lokasi'] ."' and admin=0";
		}

		
		$where .= $_POST['sByNama'] == "" ? "" : " and nama_user LIKE '%". $_POST['sByNama'] ."%'";
		$where .= $_POST['sByUser'] == "" ? "" : " and username LIKE '%". $_POST['sByUser'] ."%'";
		$where .= !empty($_POST['sByLokasi']) ? " and id_lokasi='". $_POST['sByLokasi'] ."'" : "";
		$where .= !empty($_POST['sByStatus']) ? " and pusat='". $_POST['sByStatus'] ."'" : "";
		$where .= !empty($_POST['sByAkses']) ? " and admin='". $_POST['sByAkses'] ."'" : "";
		
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
		
		$total = $this->Users->countDataAll($where);
		$query = $this->Users->getLoadAllDataWithLimit($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_user" : "'. $row->id_user .'",
				"username" : "'. $row->username .'",
				"nama_user" : "'. $row->nama_user .'",
				"lokasi" : "'. $row->lokasi .'",
				"pusat" : "'. $row->pusat .'",
				"admin" : "'. $row->admin .'",
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
		$username = '';
		$katakunci = '';
		$nama_user = '';
		$id_lokasi = '';
		$admin = '';
		$pusat = '';
		$menuakses = '';
		
		$getData = $this->Users->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();
			$username = $row->username;
			$katakunci = $row->katakunci;
			$nama_user = $row->nama_user;
			$id_lokasi = $row->id_lokasi;
			$admin = $row->admin;
			$pusat = $row->pusat;
			$menuakses = $row->menuakses;
		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['nama_user'] = $nama_user;
		$json['katakunci'] = $katakunci;
		$json['username'] = $username;
		$json['id_lokasi'] = $id_lokasi;
		$json['admin'] = $admin;
		$json['pusat'] = $pusat;
		$json['error'] = $error;
		$json['menuakses'] = $menuakses;
		
		echo json_encode($json);
	}
}
