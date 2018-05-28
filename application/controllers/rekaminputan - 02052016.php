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
		
		$getData = $this->Pegawai->getDataByID($this->security->xss_clean($_POST['ip']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();

			$id_sts_pegawai = $row->id_sts_pegawai;
			$nama_pegawai = $row->nama_pegawai;
			$nip = $row->nip;

			//$QueryRiwayatInput = $this->Trxd2d->getTransaksiInput($this->security->xss_clean($_POST['idt']), $this->security->xss_clean($_POST['irl']), $this->security->xss_clean($_POST['ia']));
			$arrTransaksi = $this->Trxd2d->getTransaksiInput($this->security->xss_clean($_POST['ia']), $this->security->xss_clean($_POST['ip']));

			foreach ( $this->Bulan->getBulanPerTriwulan($this->security->xss_clean($_POST['idt'])) as $in ) {
				$jumlah = empty($arrTransaksi[$in->id_bulan]['jumlah']) ? 0 : $arrTransaksi[$in->id_bulan]['jumlah'];
				$id_lokasi = empty($arrTransaksi[$in->id_bulan]['id_lokasi']) ? '' : $arrTransaksi[$in->id_bulan]['id_lokasi'];
				$id_jabatan = empty($arrTransaksi[$in->id_bulan]['id_jabatan']) ? '' : $arrTransaksi[$in->id_bulan]['id_jabatan'];

				$arrBulan[] = array(
					'id_bulan' => $in->id_bulan,
					'bulan' => $in->bulan,
					'jumlah' => $jumlah,
					'id_lokasi' => $id_lokasi,
					'id_jabatan' => $id_jabatan
				);
			}

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['nip'] = $nip;
		$json['nama_pegawai'] = $nama_pegawai;
		$json['id_sts_pegawai'] = $id_sts_pegawai;
		$json['arrBulan'] = $arrBulan;
		$json['error'] = $error;

		echo json_encode($json);
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
				$n = 0;
				foreach ($_POST['postLokasi'] as $r) {
					$arr[$n][3] = $r;
					$n++;
				}
				$n = 0;
				foreach ($_POST['postJabatan'] as $r) {
					$arr[$n][4] = $r;
					$n++;
				}

				//Hapus semua mutasi kecuali yang aktif
				$cmd = "delete from t_mutasi where id_pegawai='". $this->security->xss_clean($_POST['ip']) ."' and aktif=0";
				$this->db->query($cmd);

				// Input lokasi mutasi
				$i = 0;
				foreach( $arr as $p ) {
					//-- Mencari hari terakhir dari bulan
					$tgl_awal = date('Y-'. $p[1] .'-01');
					$tgl_mutasi_keluar = date('Y-m-t', strtotime($tgl_awal));
					$tgl_mutasi_masuk = date('Y-m-d', strtotime( '+1 day', strtotime($tgl_mutasi_keluar)));

					$query = $this->Mutasi->getByIdLokasi($p[3], $this->security->xss_clean($_POST['ip']));
					$count = $query->num_rows();

					if ( $count == 0 ) {

						//Insert mutasi keluar
						$arrInsertMutasiKeluar = array(
							'id_mutasi' => $id.'3'.$i,
							'id_pegawai' => $this->security->xss_clean($_POST['ip']),
							'id_lokasi' => $p[3],
							'id_homebase' => $p[3],
							'id_jabatan' => $p[4],
							'tgl_sk' => $tgl_mutasi_keluar,
							'tgl_mulai' => $tgl_mutasi_keluar,
							'id_user' => $this->session->userdata('id_user'),
							'update_akhir' => date('Y-m-d H:i:s')
						);
						$this->db->insert('t_mutasi', $arrInsertMutasiKeluar);

						//Update mutasi masuk
						$arrInsertMutasiMasuk = array(
							'id_jabatan' => $p[4],
							'tgl_sk' => $tgl_mutasi_masuk,
							'tgl_mulai' => $tgl_mutasi_masuk,
							'id_user' => $this->session->userdata('id_user'),
							'update_akhir' => date('Y-m-d H:i:s')
						);

						$this->db->where('id_pegawai', $this->security->xss_clean($_POST['ip']));
						$this->db->where('aktif', 1);
						$this->db->update('t_mutasi', $arrInsertMutasiMasuk);

						$cmd = "SELECT
							id_trx
							FROM
							t_trx_d2d
							LEFT JOIN t_mutasi ON
							t_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
							WHERE
							t_trx_d2d.`id_anggaran`='". $this->security->xss_clean($_POST['ia']) ."' AND t_trx_d2d.`id_bulan`='". $p[1] ."' AND t_mutasi.`id_pegawai`='". $this->security->xss_clean($_POST['ip']) ."'";

						$cek2 = $this->db->query($cmd);

						if ( $cek2->num_rows() == 0 ) {
							
							if ( $p[2] > 0 ) {
								$arrPost = array(
									'id_trx' => $id.'7'.$i,
									'id_mutasi' => $id.'3'.$i,
									'id_bulan' => $p[1],
									'id_anggaran' => $this->security->xss_clean($_POST['ia']),
									'jumlah' => $p[2],
									'id_user' => $this->session->userdata('id_user'),
									'last_update' => date('Y-m-d H:i:s')
								);

								$this->db->insert('t_trx_d2d', $arrPost);
							}
							
						} else {
							$this->db->where('id_trx', $cek2->row()->id_trx);
							$this->db->delete('t_trx_d2d');

							if ( $p[2] > 0 ) {
								$arrPost = array(
									'id_trx' => $id.'5'.$i,
									'id_mutasi' => $id.'3'.$i,
									'id_bulan' => $p[1],
									'id_anggaran' => $this->security->xss_clean($_POST['ia']),
									'jumlah' => $p[2],
									'id_user' => $this->session->userdata('id_user'),
									'last_update' => date('Y-m-d H:i:s')
								);
								$this->db->insert('t_trx_d2d', $arrPost);
							}

						}

						

						
					} else {
						$row = $query->row();

						if ( $row->aktif == 0 ) {
							//Update mutasi Keluar
							$arrInsertMutasiKeluar = array(
								'id_jabatan' => $p[4],
								'tgl_sk' => $tgl_mutasi_keluar,
								'tgl_mulai' => $tgl_mutasi_keluar,
								'id_user' => $this->session->userdata('id_user'),
								'update_akhir' => date('Y-m-d H:i:s')
							);

							$this->db->where('id_lokasi', $p[3]);
							$this->db->where('id_pegawai', $this->security->xss_clean($_POST['ip']));
							$this->db->where('aktif', 0);
							$this->db->update('t_mutasi', $arrInsertMutasiKeluar);

							//Update mutasi masuk
							$arrInsertMutasiMasuk = array(
								'id_jabatan' => $p[4],
								'tgl_sk' => $tgl_mutasi_masuk,
								'tgl_mulai' => $tgl_mutasi_masuk,
								'id_user' => $this->session->userdata('id_user'),
								'update_akhir' => date('Y-m-d H:i:s')
							);

							$this->db->where('id_pegawai', $this->security->xss_clean($_POST['ip']));
							$this->db->where('aktif', 1);
							$this->db->update('t_mutasi', $arrInsertMutasiMasuk);
						}

						//$get_id_mutasi = $this->Mutasi->getDataIdMutasi($p[3], $this->security->xss_clean($_POST['ip']), $p[1]);
						
						//Cek dulu ada data transaksinya ngga 
						$this->db->where('id_mutasi', $row->id_mutasi);
						$this->db->where('id_bulan', $p[1]);
						$this->db->where('id_anggaran', $this->security->xss_clean($_POST['ia']));
						$query2 = $this->db->get('t_trx_d2d');

						if ( $query2->num_rows() == 0 ) {
							if ( $p[2] > 0 ) {
								$arrPost = array(
									'id_trx' => $id.'8'.$i,
									'id_mutasi' => $row->id_mutasi,
									'id_bulan' => $p[1],
									'id_anggaran' => $this->security->xss_clean($_POST['ia']),
									'jumlah' => $p[2],
									'id_user' => $this->session->userdata('id_user'),
									'last_update' => date('Y-m-d H:i:s')
								);

								$this->db->insert('t_trx_d2d', $arrPost);
							}
							
						} else {
							$trx = $query2->row();
							$arrPost = array(
								'jumlah' => $p[2],
								'id_user' => $this->session->userdata('id_user')
							);
							$this->db->where('id_trx', $trx->id_trx);
							$this->db->update('t_trx_d2d', $arrPost);
						}

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
		$urutan="kepalaupad DESC,id_jabatan,id_homebase DESC,id_sts_pegawai";
		$where = "id_jenis_mutasi='333333'";
		
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
		$query = $this->Pegawai->getSortPegawai($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_pegawai" : "'. $row->id_pegawai .'",
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
