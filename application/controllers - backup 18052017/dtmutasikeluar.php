<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtmutasikeluar extends CI_Controller {

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
		$data['title'] = 'RIWAYAT MUTASI KELUAR PEGAWAI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'riwayat mutasi keluar';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_grid()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="tgl_sk desc";
		$where = 'id_mutasi_sebelumnya IS NOT NULL';
		
		$where .= $_POST['postLokasi'] == "99" ? "" : " and lokasi_asal='". $_POST['postLokasi'] ."'";
		$where .= $_POST['postNoSK'] == "" ? "" : " and no_sk LIKE '%". $_POST['postNoSK'] ."%'";
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
		
		$total = $this->Mutasi->countTotalDataMutasiKeluar($where);
		$query = $this->Mutasi->getDataMutasiKeluar($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_mutasi" : "'. $row->id_mutasi .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"lokasi_sebelumnya" : "'. $row->lokasi_sebelumnya .'",
				"jenis_mutasi" : "'. $row->jenis_mutasi .'",
				"lokasi_tujuan_mutasi" : "'. $row->lokasi_tujuan_mutasi .'",
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

	public function json_detil()
	{
		$this->load->Model('Trxd2d');

		$json=array();
		$error="";
		$msg="";
		$data = '';
		$opsi = '';
		$tgl = '';
		$postId = $this->security->xss_clean($_POST['postID']);
		// Cek Data Mutasi
		$get = $this->Mutasi->getDataById($postId);
		$count = $get->num_rows();

		// Jumlah data transaksi d2d yang di rekam
		$jml = $this->Trxd2d->jml_transaksi_yg_telah_dilakukan($postId);

		if ( $count == 1 ) {
			$data = $get->row();
			$tgl = $this->Fungsi->formatsqltodate($data->tgl_sk);
			if ( $data->id_sts_pegawai == '33' ) {
				$opsi = $this->Jabatan->opsiASN();
			} else {
				$opsi = $this->Jabatan->opsiNonASN();
			}
		} else {
			$error = 'Maaf data tidak diketemukan !';
		}

		$json['error']=$error;
		$json['data'] = $data;
		$json['opsi'] = $opsi;
		$json['tgl'] = $tgl;
		$json['msg']=$msg;
		$json['jml_transaksi'] = $jml;
		echo json_encode($json);
	}

	public function json_detil_untuk_penambahan_mutasi()
	{
		$json=array();
		$error="";
		$msg="";
		$data = '';
		$opsi = '';
		$postId = $this->security->xss_clean($_POST['postID']);
		$get = $this->Mutasi->getDataById($this->security->xss_clean($postId));

		if ( $get->num_rows() == 0 ) {
			$error = 'Maaf Data tidak diketemukan !';
		} elseif ($this->Mutasi->getJumlahMutasiSebelumnya($postId) > 0) {
			$error = 'Maaf Pegawai telah melakukan mutasi sebelumnya !';
		} else {
			$data = $get->row();
			if ( $data->id_sts_pegawai == '33' ) {
				$opsi = $this->Jabatan->opsiASN();
			} else {
				$opsi = $this->Jabatan->opsiNonASN();
			}
		}

		$json['error']=$error;
		$json['data'] = $data;
		$json['opsi'] = $opsi;
		$json['msg']=$msg;
		echo json_encode($json);
		
	}
}
