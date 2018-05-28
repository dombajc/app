<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entrypbbkb extends CI_Controller {

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
        $this->load->Model('Pbbkb');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Input Data Distribusi BBM di SPBU';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form entry data pbbkb';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_entry()
	{
		$json = array();
		$postTh = $this->security->xss_clean($_POST['slcttahun']);
		$postLokasi = $this->security->xss_clean($_POST['slctlokasi']);
		$postSpbu = $this->security->xss_clean($_POST['slctspbu']);
		$postBulan = $this->security->xss_clean($_POST['slctbulan']);
		$postDasar = $this->security->xss_clean($_POST['radDasar']);

		$arr_jenis_pbbkb = $this->Pbbkb->getAllData();
		$cek_transaksi = 0;
		$arr_transaksi = array();
		$get_transaksi = $this->getDataTransaksiByFilter($postSpbu, $postTh, $postBulan, $postDasar);

		if ( $get_transaksi->num_rows() == 1 ) {
			$getId = $get_transaksi->row()->id_transaksi_pbbkb;
			$arr_transaksi = $this->getValueById($getId);
			$cek_transaksi = 1;
		}

		$json['arr_jenis_pbbkb'] = $arr_jenis_pbbkb;
		$json['arr_trx'] = $arr_transaksi;
		$json['cek_transaksi'] = $cek_transaksi;

		$json['getLokasi'] = $postLokasi;
		$json['getSpbu'] = $postSpbu;
		$json['getTahun'] = $postTh;
		$json['getBulan'] = $postBulan;
		$json['getDasar'] = $postDasar;
		echo json_encode($json);
	}

	private function getDataTransaksiByFilter($getSpbu, $getTh, $getBulan, $getDasar)
	{
		$cmd = "SELECT * FROM t_transaksi_pbbkb2
			WHERE
			t_transaksi_pbbkb2.`id_anggaran`='". $getTh ."'
			AND t_transaksi_pbbkb2.`id_bulan`='". $getBulan ."'
			AND t_transaksi_pbbkb2.`id_dasar_trx_pbbkb`='". $getDasar ."'
			AND t_transaksi_pbbkb2.`id_lokasi_pbbkb`='". $getSpbu ."'";
		return $this->db->query($cmd);
	}

	private function getValueById($getId)
	{
		$arr = array();
		$cmd = "SELECT
			t_item_transaksi_pbbkb.`id_item_pbbkb`,t_item_transaksi_pbbkb.`jumlah`
			FROM
			t_item_transaksi_pbbkb
			WHERE t_item_transaksi_pbbkb.`id_transaksi_pbbkb`='". $getId ."'";
		foreach( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_item_pbbkb] = $row->jumlah;
		}
		return $arr;
	}

	public function act_crud()
	{
		$postTh = $this->security->xss_clean($_POST['gettahun']);
		$postLokasi = $this->security->xss_clean($_POST['getlokasi']);
		$postSpbu = $this->security->xss_clean($_POST['getspbu']);
		$postBulan = $this->security->xss_clean($_POST['getbulan']);
		$postDasar = $this->security->xss_clean($_POST['getdasar']);
		$getTime = date('Y-m-d H:i:s');

		$json=array();
		$error="";
		$msg="";
		$id = time();

		$n = 0;
		foreach ($_POST['idpbbkb'] as $r) {
			$arr[$n][1] = $r;
			$n++;
		}

		$n = 0;
		foreach ($_POST['txtliter'] as $r) {
			$arr[$n][2] = $r;
			$n++;
		}

		$this->db->trans_begin();
		//Hapus Transaksi

		$cmd = "delete from t_transaksi_pbbkb2 where id_lokasi_pbbkb='". $postSpbu ."' and  id_anggaran='". $postTh ."' and id_bulan='". $postBulan ."' and id_dasar_trx_pbbkb='". $postDasar ."'";
		$this->db->query($cmd);

		$arrPost = array(
				'id_transaksi_pbbkb' => $id,
				'id_lokasi_pbbkb' => $postSpbu,
				'id_anggaran' => $postTh,
				'id_bulan' => $postBulan,
				'id_dasar_trx_pbbkb' => $postDasar,
				'create_by' => $this->session->userdata('id_user'),
				'last_update' => $getTime
			);
		$this->db->insert('t_transaksi_pbbkb2', $arrPost);
		$i = 0;
		foreach( $arr as $p ) {

			$arrItem = array(
				'id_item_transaksi_pbbkb' => $id.$i,
				'id_transaksi_pbbkb' => $id,
				'id_item_pbbkb' => $p[1],
				'jumlah' => $p[2]
			);

			$this->db->insert('t_item_transaksi_pbbkb', $arrItem);
			
			$i++;
		}

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			$error = $this->db->_error_message();
		else:
			$this->db->trans_commit();
			$msg = 'Entry PBBKB Per Bulan berhasil di simpan !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function delete_rekam()
	{
		$postTh = $this->security->xss_clean($_POST['gettahun']);
		$postLokasi = $this->security->xss_clean($_POST['getlokasi']);
		$postSpbu = $this->security->xss_clean($_POST['getspbu']);
		$postBulan = $this->security->xss_clean($_POST['getbulan']);
		$postDasar = $this->security->xss_clean($_POST['getdasar']);

		$json=array();
		$error="";
		$msg="";
		$this->db->trans_begin();
		$cmd = "delete from t_transaksi_pbbkb2 where id_lokasi_pbbkb='". $postSpbu ."' and  id_anggaran='". $postTh ."' and id_bulan='". $postBulan ."' and id_dasar_trx_pbbkb='". $postDasar ."'";
		$this->db->query($cmd);

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			$error = $this->db->_error_message();
		else:
			$this->db->trans_commit();
			$msg = 'Entry PBBKB Per Bulan berhasil di hapus !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function get_opsi_tgl()
	{
		$json = array();

		$postBulan = $_POST['postBulan'];
		$postTahun = $this->Thanggaran->getDataByID($_POST['postTahun'])->row()->th_anggaran;
		$tgl = array();
		$tgl_akhir = date('t', strtotime($postTahun .'-'. $postBulan .'-01'));
		
		for ( $d=1;$d<=$tgl_akhir;$d++ ) {
			$tgl[] = $d;
		}

		$json['tgl'] = $tgl;
		echo json_encode($json);
	}

}
