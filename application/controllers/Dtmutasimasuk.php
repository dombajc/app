<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtmutasimasuk extends CI_Controller {

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
        $this->load->Model('Mutasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'MUTASI MASUK PEGAWAI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data mutasi masuk pegawai';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_grid()
	{
		$cond = "tgl_sk>='". $this->Fungsi->formatdatetosql($_POST['cariTglAwal']) ."' and tgl_sk<='". $this->Fungsi->formatdatetosql($_POST['cariTglAkhir']) ."'";
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_mutasi asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariNama']) || $_POST['cariNama'] == '' ? '' : " and nama_pegawai like '%". $_POST['cariNama'] ."%'";
		$cond .= empty($_POST['cariLokasi']) || $_POST['cariLokasi'] == '' ? '' : " and id_induk='". $_POST['cariLokasi'] ."'";

		$items = $this->Mutasi->getGridMutasiMasuk($cond, $sort);

		echo json_encode($items);
	}

	public function json_lokasi_pegawai()
	{
		$json = array();
		$error="";
		$msg="";
		$opsiHomebase = '';
		$opsiD2D = '';
		$get = '';
		$id_homebase = '';
		$id_lokasi_d2d = '';

		$postId = $this->security->xss_clean($_POST['id']);

		$q = $this->Mutasi->getDataById($postId);
		$cek = $q->num_rows();

		//Cek data mutasi, jika ada
		if ( $cek == 1 ) {

			$get = $q->row();
			$id_homebase = empty($get->id_tujuan_mutasi) || $get->id_tujuan_mutasi == '' ? $get->id_homebase : $get->id_tujuan_mutasi;
			$id_lokasi_d2d = $get->id_lokasi;

			$opsiHomebase = $this->getOpsiLokasi($id_homebase)->result_array();
			$opsid2d = $this->getOpsiLokasiD2D($id_homebase)->result_array();

		} else {
			$error = 'Maaf data mutasi masuk tidak ditemukan !';
		}

		$json['error']=$error;
		$json['opsiHomebase'] = $opsiHomebase;
		$json['opsiD2D'] = $opsid2d;
		$json['id_mutasi'] = $postId;
		$json['id_homebase'] = $id_homebase;
		$json['id_lokasi_d2d'] = $id_lokasi_d2d;
		$json['msg']=$msg;
		echo json_encode($json);

	}

	private function getOpsiLokasiD2D($postLokasi)
	{
		if ( $postLokasi == '05' ) {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE d2d_pusat=1";
		} else {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE id_lokasi=id_induk and id_induk='". $postLokasi ."'";
		}
		return $this->db->query($cmd);
	}

	private function getOpsiLokasi($postLokasi)
	{
		if ( $postLokasi == '05' ) {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE id_lokasi='05'";
		} else {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE id_lokasi like '". $postLokasi ."%' and samsat in ('pusat','besar')";
		}
		return $this->db->query($cmd);
	}

	public function aksi_crud()
	{
		$this->load->Model('Trxd2d');

		$postId = $this->security->xss_clean($_POST['hId']);
		$postHomebase = $this->security->xss_clean($_POST['slctlokasi_mutasi']);
		$postd2d = $this->security->xss_clean($_POST['slctlokasi_d2d']);

		$get = $this->Mutasi->getDataById($postId);
		$count = $get->num_rows();
		$countTrx = $this->Trxd2d->jml_transaksi_yg_telah_dilakukan($postId);

		$json=array();
		$error="";
		$msg="";
		// Cek data mutasi
		if ( $count == 1 ) {
			// Cek jumlah transaksi rekam door to door, Jika belum pernah melakukan Door To Door maka dapat di simpan
			if ( $countTrx == 0 ) {
				$row = $get->row();

				$this->db->trans_begin();

				$arrNonAktif = array(
					'aktif' => 0
					);
				$this->db->where('id_pegawai', $row->id_pegawai);
				$this->db->update('t_mutasi', $arrNonAktif);

				$arrData = array(
					'id_lokasi' => $postd2d,
					'id_homebase' => $postHomebase,
					'aktif' => 1
					);

				$this->db->where('id_mutasi', $postId);
				$this->db->update('t_mutasi', $arrData);

				if ($this->db->trans_status() === FALSE):
					$this->db->trans_rollback();
					$error = $this->db->_error_message();
				else:
					$this->db->trans_commit();
					$msg = 'Data Pegawai berhasil di aktifkan dan diperbaharui !';
				endif;
			} else {
				$error = 'Data tidak dapat disimpan / diperbaharui karena telah melakukan Rekam Door To Door.<hr>Silahkan hubungi Administrator';
			}
			
		} else {
			$error = 'Data tidak ditemukan !';
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}
}
