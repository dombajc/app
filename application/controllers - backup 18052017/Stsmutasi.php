<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stsmutasi extends CI_Controller {

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
	private $folder = 'pegawai/';
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Mutasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{

		$data['title'] = 'DATA PEMBERIAN STATUS MUTASI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data status mutasi';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_grid()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="tgl_sk desc";
		$where = "id_jenis_mutasi='333333'";
		
		$where .= $_POST['postLokasi'] == "99" ? "" : " and id_homebase='". $_POST['postLokasi'] ."'";
		$where .= $_POST['postNoSK'] == "" ? "" : " and no_sk LIKE '%". $_POST['postNIP'] ."%'";
		$where .= $_POST['postNama'] == "" ? "" : " and nama_pegawai LIKE '%". $_POST['postNama'] ."%'";
		
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
		
		$total = $this->Mutasi->countDataAll($where);
		$query = $this->Mutasi->getLoadAllDataWithLimit($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_mutasi" : "'. $row->id_mutasi .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"lokasi_asal" : "'. $row->lokasi_asal .'",
				"jenis_mutasi" : "'. $row->jenis_mutasi .'",
				"nama_homebase" : "'. $row->nama_homebase .'",
				"no_sk" : "'. $row->no_sk .'",
				"tgl_sk" : "'. $this->Fungsi->formatsqltodate($row->tgl_sk) .'",
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

	public function activate()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();

		switch ($_POST['haksi']) {			
			case 'validasi':
				$value = $this->security->xss_clean($_POST['value']);

				$arrPost = array(
					'aktif' => $value
				);
				$result = $this->Mutasi->stsaktif($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					if ( $value == 1 ) :
						$msg = 'Status Mutasi berhasil diaktifkan !';
					else :
						$msg = 'Status Mutasi berhasil dibatalkan !';
					endif;
					
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
}
