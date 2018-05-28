<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtmutasi extends CI_Controller {

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
		$data['title'] = 'DATA MUTASI PEGAWAI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'daftar riwayat mutasi pegawai';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function aksi()
	{
		$this->load->model('Trxd2d');
		$json=array();
		$error="";
		$msg="";
		
		$postAksi = $this->security->xss_clean($_POST['haksi']);
		

		switch ( $postAksi ) {
			case 'add':
				$getID = time();
				$postIdPegawai = $this->security->xss_clean($_POST['hIdPegawai']);
				$postIdJabatan = empty($_POST['slctjabatan']) ? '' : $this->security->xss_clean($_POST['slctjabatan']);
				$postJenisMutasi = empty($_POST['slctjenis_mutasi']) ? '' : $this->security->xss_clean($_POST['slctjenis_mutasi']);
				$postLokasiMutasi = empty($_POST['slctlokasi_mutasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi_mutasi']);
				$postMutasiSebelumnya = empty($_POST['hIdMutasiSebelumnya']) ? '' : $this->security->xss_clean($_POST['hIdMutasiSebelumnya']);
				$postSkpdLain = empty($_POST['txtlokasi_lain']) ? '' : $this->security->xss_clean($_POST['txtlokasi_lain']);
				$postSurat = empty($_POST['txtdasarsurat']) ? '' : $this->security->xss_clean($_POST['txtdasarsurat']);
				$postTgl = empty($_POST['txttgl']) ? '' : $this->Fungsi->formatdatetosql($this->security->xss_clean($_POST['txttgl']));
				$status = $postJenisMutasi == '333333' ? 0 : 1;
				
				

				$this->db->trans_begin();

				if ( $postJenisMutasi != '333333' ) {
					$arrnonaktif = array(
						'aktif' => 0
					);
					$this->db->where('id_pegawai', $postIdPegawai);
					$this->db->update('t_mutasi', $arrnonaktif);
				}

				$arrData = array(
					'id_mutasi' => $getID,
					'id_jenis_mutasi' => $postJenisMutasi,
					'id_pegawai' => $postIdPegawai,
					'id_jabatan' => $postIdJabatan,
					'id_tujuan_mutasi' => $postLokasiMutasi,
					'lokasi_lain' => $postSkpdLain,
					'no_sk' => $postSurat,
					'tgl_sk' => $postTgl,
					'tgl_mulai' => $postTgl,
					'id_mutasi_sebelumnya' => $postMutasiSebelumnya,
					'aktif' => $status,
					'id_user' => $this->session->userdata('id_user'),
					'update_akhir' => date('Y-m-d H:i:s')
					);
				
				$this->db->insert('t_mutasi', $arrData);		
				if ($this->db->trans_status() === FALSE):
					$this->db->trans_rollback();
					$error = $this->db->_error_message();
				else:
					$this->db->trans_commit();
					$msg = 'Mutasi berhasil di simpan !';
				endif;
				
				//---------------------------------------------------------------
				break;
			case 'edit':
				$getID = $this->security->xss_clean($_POST['hID']);
				$postIdPegawai = $this->security->xss_clean($_POST['hIdPegawai']);
				$postIdJabatan = empty($_POST['slctjabatan']) ? '' : $this->security->xss_clean($_POST['slctjabatan']);
				$postJenisMutasi = empty($_POST['slctjenis_mutasi']) ? '' : $this->security->xss_clean($_POST['slctjenis_mutasi']);
				$postLokasiMutasi = empty($_POST['slctlokasi_mutasi']) ? '' : $this->security->xss_clean($_POST['slctlokasi_mutasi']);
				$postMutasiSebelumnya = empty($_POST['hIdMutasiSebelumnya']) ? '' : $this->security->xss_clean($_POST['hIdMutasiSebelumnya']);
				$postSkpdLain = empty($_POST['txtlokasi_lain']) ? '' : $this->security->xss_clean($_POST['txtlokasi_lain']);
				$postSurat = empty($_POST['txtdasarsurat']) ? '' : $this->security->xss_clean($_POST['txtdasarsurat']);
				$postTgl = empty($_POST['txttgl']) ? '' : $this->Fungsi->formatdatetosql($this->security->xss_clean($_POST['txttgl']));
				$jml = $this->Trxd2d->jml_transaksi_yg_telah_dilakukan($getID);
				if ( $jml == 0 ) {
					$arrData = array(
						'id_jenis_mutasi' => $postJenisMutasi,
						'id_jabatan' => $postIdJabatan,
						'id_tujuan_mutasi' => $postLokasiMutasi,
						'lokasi_lain' => $postSkpdLain,
						'no_sk' => $postSurat,
						'tgl_sk' => $postTgl,
						'tgl_mulai' => $postTgl,
						'id_user' => $this->session->userdata('id_user'),
						'update_akhir' => date('Y-m-d H:i:s')
						);
				} else {
					$arrData = array(
						'id_jabatan' => $postIdJabatan,
						'no_sk' => $postSurat,
						'id_user' => $this->session->userdata('id_user'),
						'update_akhir' => date('Y-m-d H:i:s')
						);
				}
				//---------------------------------------------------------------
					
				$result = $this->Mutasi->ubah($arrData, $getID);
				if ( $result == '' ) {
					$msg = 'Mutasi berhasil di perbaharui !';
				} else {
					$error = $result;
				}
				//---------------------------------------------------------------
				break;
			case 'delete':
				$getID = $this->security->xss_clean($_POST['hID']);
				$result = $this->Mutasi->hapus($getID);
				if ( $result == '' ) {
					$msg = 'Mutasi Pegawai berhasil di hapus !';
				} else {
					$error = $result;
				}
				break;
			default:
				$error = 'Terjadi kesalahan dalam perintah !';
				break;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	
}
