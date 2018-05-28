<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekaminputan extends CI_Controller {

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
	private $folder = 'inputan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->Model('Trxd2d');
        $this->load->Model('Pegawai');
        $this->load->Model('Mutasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'REKAM INPUT D2D';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form rekam inputan d2d grid';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_detil_rekam_by_pegawai()
	{

		$json=array();
		$error="";
		$arrBulan = array();
		$id_sts_pegawai = '';
		$nama_pegawai = '';
		$nip = '';
		$lokasi_d2d = '';
		$jabatan = '';
		$disabled = '';

		//Mendapatkan Tahun anggaran yang aktif
		$postThAnggaran = $this->security->xss_clean($_POST['ia']);
		$getTahunPilihan = $this->Thanggaran->getDataByID($postThAnggaran)->row()->th_anggaran;
		
		$postIdMutasi = $this->security->xss_clean($_POST['ip']);
		$getData = $this->Mutasi->getDataById($postIdMutasi);

		$postIdTriwulan = $this->security->xss_clean($_POST['idt']);
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();

			$id_sts_pegawai = $row->id_sts_pegawai;
			$nama_pegawai = $row->nama_pegawai;
			$nip = $row->nip;
			$lokasi_d2d = $row->nama_lokasi;
			$jabatan = $row->jabatan;

			$getDate = $row->tgl_selesai_mutasi;
			$getSK = $row->tgl_sk;

			$arrTransaksi = $this->Trxd2d->getTransaksiInput($this->security->xss_clean($_POST['ia']), $row->id_pegawai);

			foreach ( $this->Bulan->getBulanPerTriwulan($postIdTriwulan) as $in ) {
				
				$expire = date($getTahunPilihan.'-'.$in->char_bulan.'-01');
				$lastDate = date('Y-m-t', strtotime($expire));
				$minEnableDate = date('Y-m-20', strtotime($getDate));
				$jumlah = empty($arrTransaksi[$in->id_bulan]['jumlah']) ? 0 : $arrTransaksi[$in->id_bulan]['jumlah'];
				if ( $getDate == 0 ) {
					if ( strtotime($getSK) > strtotime($expire) ) {
						$disabled = 'disabled';
					} else {
						$disabled = '';
					}
				} else {
					if ( strtotime($getDate) >= strtotime($lastDate) ) {
						$disabled = '';
					} else {
						if ( strtotime($getDate) >= strtotime($lastDate) ) {
							$disabled = '';
						} else {
							if ( strtotime($getDate) <= strtotime($minEnableDate) ) {
								$disabled = '';
							} else {
								$disabled = 'disabled';
							}
						}
					}
					
				}

				$arrBulan[] = array(
					'id_bulan' => $in->id_bulan,
					'bulan' => $in->bulan,
					'jumlah' => $jumlah,
					'disabled' => $disabled
				);
			}

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['nip'] = $nip;
		$json['nama_pegawai'] = $nama_pegawai;
		$json['arrBulan'] = $arrBulan;
		$json['lokasi_d2d'] = $lokasi_d2d;
		$json['jabatan'] = $jabatan;
		$json['error'] = $error;

		echo json_encode($json);
	}

  	private function compare_two_date($date_1,$date_2) {
  		if ( $date_1 == 0 ) {
  			return '';
  		} else {
  			
		  	$new_date_1 = sprintf('%04d%02d%02d', $year, $month, $day);
		  	list($year, $month, $day) = explode('-', $date_2);
		  	$new_date_2 = sprintf('%04d%02d%02d', $year, $month, $day);
		    if ($date_2 < $date_1) {
		    	return 'disabled';
		    } else {
		    	return '';
		    }
  		}
  	}

	public function act_crud()
	{
		$this->load->model('Mutasi');

		$json=array();
		$error="";
		$msg="";
		$id = time();

		switch ($_POST['haksi']) {
			
			case 'add':

				$postIdMutasi = $this->security->xss_clean($_POST['hID']);
				
				$this->db->trans_begin();

				$n = 0;
				foreach ($_POST['txtbulan'] as $r) {
					$arr[$n][1] = $r;
					$n++;
				}

				$n = 0;
				foreach ($_POST['txtjumlah'] as $r) {
					$arr[$n][2] = $r;
					$n++;
				}


				// Input lokasi mutasi
				$i = 0;
				foreach( $arr as $p ) {
					//Hapus Transaksi
					$cmd = "delete from t_trx_d2d where id_mutasi='". $postIdMutasi ."' and id_bulan='". $p[1] ."' and id_anggaran='". $this->security->xss_clean($_POST['ia']) ."'";
					$this->db->query($cmd);

					if ( $p[2] > 0 ) {
						$arrPost = array(
							'id_trx' => $id.$i,
							'id_mutasi' => $postIdMutasi,
							'id_bulan' => $p[1],
							'id_anggaran' => $this->security->xss_clean($_POST['ia']),
							'jumlah' => $p[2],
							'id_user' => $this->session->userdata('id_user'),
							'last_update' => date('Y-m-d H:i:s')
						);

						$this->db->insert('t_trx_d2d', $arrPost);
					}		
					
					$i++;
				}

				if ($this->db->trans_status() === FALSE):
					$this->db->trans_rollback();
					$error = $this->db->_error_message();
				else:
					$this->db->trans_commit();
					$msg = 'Rekam Kegiatan Door To Door Per Bulan berhasil di simpan !';
				endif;
				
				break;
			
			case 'delete':

				if ( $this->Trxd2d->countTransaksiByIdPegawai($this->security->xss_clean($_POST['hID'])) == 0 ) {
					$result = $this->Pegawai->delete($this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Data Pegawai berhasil di dihapus !';
					}else{
						$error = $result;
					}
				} else {
					$error = 'Maaf pegawai tidak dapat dihapus karena sudah pernah melakukan input transaksi !';
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

	public function json_grid_pegawai()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$postTahun = $this->Thanggaran->getDataByID($this->security->xss_clean($_POST['postTahun']))->row()->th_anggaran;
		$postTriwulan = $this->security->xss_clean($_POST['postTriwulan']);

		$getGrupBulan = $this->Bulan->getGrupBulan($postTriwulan);
		$getMinTgl = date('Y-m-01', strtotime(date($postTahun.'-'.$getGrupBulan->min.'-01')));
		$getMakTgl = date('Y-m-t', strtotime(date($postTahun.'-'.$getGrupBulan->max.'-01')));

		$urutan="kepalaupad DESC,id_jabatan,id_homebase DESC,id_sts_pegawai";
		$where = "IF(tgl_selesai_mutasi=0,tgl_sk<='". $getMakTgl ."',tgl_selesai_mutasi>='". $getMinTgl ."') and id_jenis_mutasi='333333'";
		
		$where .= $_POST['postLokasi'] == "" ? "" : " and id_lokasi='". $_POST['postLokasi'] ."'";
		$where .= $_POST['postNama'] == "" ? "" : " and nama_pegawai LIKE '%". $_POST['postNama'] ."%'";
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
		
		$total = $this->Pegawai->countDataAll($where);
		$query = $this->Pegawai->getSortPegawaiOld($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_mutasi" : "'. $row->id_mutasi .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"nip" : "'. $row->nip .'",
				"id_jabatan" : "'. $row->id_jabatan .'",
				"jabatan" : "'. $row->jabatan .'",
				"id_lokasi" : "'. $row->id_lokasi .'",
				"nama_lokasi" : "'. $row->nama_lokasi .'",
				"nama_homebase" : "'. $row->nama_homebase .'",
				"status_pegawai" : "'. $row->status_pegawai .'",
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

}
