<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtpegawai extends CI_Controller {

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
        $this->load->Model('Pegawai');
        $this->load->Model('Trxd2d');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DATA PEGAWAI UP3AD';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'management pegawai';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	private function pengecekanInputByNipJikaASN($sts, $nip)
	{
		$result = 0;
		if ( $sts == '33' ) {
			if ( $this->Pegawai->cekIDByNip($nip) == 0 ) {
				$result = 0;
			} else {
				$result = 1;
			}
		} else {
			$result = 0;
		}
		return $result;
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
				
				$arrPostASN = array();
				//Jika status ASN dan Cek NIP
				if ( $this->pengecekanInputByNipJikaASN( $this->security->xss_clean($_POST['slctstatus']), $this->security->xss_clean($_POST['txtnip'])) == 0 ) {
					//Jika status ASN
					if ( $_POST['slctstatus'] == '33' ) {
						
						$arrPostASN = array(
							'nip' => $this->security->xss_clean($_POST['txtnip']),
						);

					}

					$arrPost = array(
						'id_pegawai' => $id,
						'id_sts_pegawai' => $this->security->xss_clean($_POST['slctstatus']),
						'nama_pegawai' => $this->security->xss_clean($_POST['txtnama']),
						'id_pangkat' => $this->security->xss_clean($_POST['slctpangkat']),
						'id_jabatan' => $this->security->xss_clean($_POST['slctjabatan']),
						'id_user' => $this->session->userdata('id_user'),
						'history_buat' => $track
					);

					$arrPostRiwayat = array(
						'id_riwayat_lokasi' => $id,
						'id_pegawai' => $id,
						'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
						'id_user' => $this->session->userdata('id_user'),
						'riwayat_buat' => $track
					);

					$result = $this->Pegawai->tambah(array_merge($arrPost,$arrPostASN), $arrPostRiwayat);
					if ( $result == ''){
						$msg = 'Pegawai Baru berhasil di simpan !'. $this->pengecekanInputByNipJikaASN( $this->security->xss_clean($_POST['slctstatus']), $this->security->xss_clean($_POST['txtnip']));
					}else{
						$error = $result;
					}
				} else {
					$error = "Maaf NIP sudah pernah terdaftar !";
				}
				break;
			case 'edit':
				
				$arrPost = array(
					'nama_pegawai' => $this->security->xss_clean($_POST['txtnama']),
					'id_pangkat' => $this->security->xss_clean($_POST['slctpangkat']),
					'id_jabatan' => $this->security->xss_clean($_POST['slctjabatan']),
					'id_user' => $this->session->userdata('id_user'),
					'history_update' => $track
				);
				
				$result = $this->Pegawai->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Data Pegawai berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'validasi':
				$arrPost = array(
					'aktif' => $this->security->xss_clean($_POST['value'])
				);
				$result = $this->Pegawai->update($arrPost,$this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Data Pegawai berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
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

	public function json_grid()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="id_pegawai";
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
		
		$total = $this->Pegawai->countDataAll($where);
		$query = $this->Pegawai->getLoadAllDataWithLimit($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_pegawai" : "'. $row->id_pegawai .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"nip" : "'. $row->nip .'",
				"jabatan" : "'. $row->jabatan .'",
				"pangkat" : "'. $row->pangkat .'",
				"golongan" : "'. $row->golongan .'",
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

	public function json_detil()
	{
		$this->load->Model('Historilokasi');

		$json=array();
		$error="";
		$id_sts_pegawai = '';
		$nama_pegawai = '';
		$nip = '';
		$id_pangkat = '';
		$id_jabatan = '';
		$arrLokasiAktif = array();
		
		$getData = $this->Pegawai->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();
			$id_sts_pegawai = $row->id_sts_pegawai;
			$nama_pegawai = $row->nama_pegawai;
			$nip = $row->nip;
			$id_pangkat = $row->id_pangkat;
			$id_jabatan = $row->id_jabatan;

			$QueryLokasiAktif = $this->Historilokasi->getLokasiAktifByIdPegawai($this->security->xss_clean($_POST['hID']));
			foreach ( $QueryLokasiAktif->result() as $lok ) {
				$arrLokasiAktif[] = array(
					'id_riwayat_lokasi' => $lok->id_riwayat_lokasi,
					'lokasi' => $lok->lokasi
				);
			}

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['id_sts_pegawai'] = $id_sts_pegawai;
		$json['nama_pegawai'] = $nama_pegawai;
		$json['nip'] = $nip;
		$json['id_jabatan'] = $id_jabatan;
		$json['id_pangkat'] = $id_pangkat;
		$json['arrLokasiAktif'] = $arrLokasiAktif;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

	public function json_select_by_name()
	{
		$cmd = "select id_pegawai,nama_pegawai,nip from t_pegawai where nama_pegawai LIKE '%". $_GET['q'] ."%'";
		$query = $this->db->query($cmd);
		$count = $query->num_rows();
		echo '{"total_count": '. $count .',
			"incomplete_results": false,
			"items": [';
		$i = 1;
		foreach ( $query->result() as $row){
			echo '{
				"id": "'. $row->id_pegawai .'",
				"nama_pegawai": "'. $row->nama_pegawai .'",
				"nip": "'. $row->nip .'"
			}';
			
			if ( $i < $count ){
				echo ',';
			}
			$i++;
		}
			
		echo ']}';
	}

	public function json_homebase()
	{
		
		$json=array();
		$error = '';
		$arrHomebase = array();

		$cmd = "select * from v_homebase where id_lokasi='". $this->security->xss_clean($_POST['postLokasi']) ."'";
		$q = $this->db->query($cmd);
		if ( $q->num_rows() == 1 ) {
			$row = $q->row();
			if ( $row->d2d_pusat == 1 ) {
				$rowPusat = $this->Lokasi->getAllPusat()->row();
				$arrHomebase[] = array(
					'id_lokasi' => $rowPusat->id_lokasi,
					'lokasi' => $rowPusat->lokasi
				);
			}

			foreach ( $this->Lokasi->getInduknSamtu($row->id_lokasi) as $r ) {
				$arrHomebase[] = array(
					'id_lokasi' => $r['id_lokasi'],
					'lokasi' => $r['lokasi']
				);
			}
		} else {
			$error = 'Maaf Lokasi tidak ditemukan !';
		}
		
		$json['arrHomebase'] = $arrHomebase;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

}
