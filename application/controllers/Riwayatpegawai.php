<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayatpegawai extends CI_Controller {

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
        $this->load->Model('Historilokasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'RIWAYAT LOKASI PEGAWAI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'daftar riwayat lokasi pegawai';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_grid()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="id_riwayat_lokasi";
		$where = "id_pegawai='". $_POST['sIDPegawai'] ."'";
		
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
		
		$total = $this->Historilokasi->countDataAll($where);
		$query = $this->Historilokasi->getLoadAllDataWithLimit($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_riwayat_lokasi" : "'. $row->id_riwayat_lokasi .'",
				"lokasi" : "'. $row->lokasi .'",
				"nama_user" : "'. $row->nama_user .'",
				"riwayat_buat" : "'. $row->riwayat_buat .'",
				"riwayat_ubah" : "'. $row->riwayat_ubah .'",
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

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();
		$track = date('Y-m-d H:i:s');

		switch ($_POST['haksi']) {
			
			case 'add':

				$arrPost = array(
					'id_riwayat_lokasi' => $id,
					'id_pegawai' => $this->security->xss_clean($_POST['slctpegawai']),
					'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
					'id_user' => $this->session->userdata('id_user'),
					'riwayat_buat' => $track
				);

				$result = $this->Historilokasi->tambah($arrPost);
				if ( $result == ''){
					$msg = 'Lokasi Pegawai Baru berhasil di simpan !';
				}else{
					$error = $result;
				}
				
				break;
			case 'edit':
				
				$arrPost = array(
					'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
					'id_user' => $this->session->userdata('id_user'),
					'riwayat_ubah' => $track
				);
				
				$result = $this->Historilokasi->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Lokasi Pegawai berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'validasi':
				$arrPost = array(
					'aktif' => $this->security->xss_clean($_POST['value'])
				);
				$result = $this->Historilokasi->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Lokasi Pegawai berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'delete':

				//if ( $this->Trxd2d->countTransaksiByIdPegawai($this->security->xss_clean($_POST['hID'])) == 0 ) {
					$result = $this->Historilokasi->delete($this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Lokasi Pegawai berhasil di dihapus !';
					}else{
						$error = $result;
					}
				//} else {
					//$error = 'Maaf pegawai tidak dapat dihapus karena sudah pernah melakukan input transaksi !';
				//}

				break;
			default:
				$error = 'Aksi tidak ditemukan !';
				break;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function json_detil()
	{
		$json=array();
		$error="";
		$id_lokasi = '';
		
		$getData = $this->Historilokasi->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();
			$id_lokasi = $row->id_lokasi;
		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['id_lokasi'] = $id_lokasi;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

}
