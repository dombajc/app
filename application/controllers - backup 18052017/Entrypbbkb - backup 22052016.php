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
		$data['title'] = 'ENTRY DATA PBBKB';
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
		$postTgl = $_POST['radJenis'] == '11' ? '' : $this->security->xss_clean($_POST['slcttgl']);

		$arr_jenis_pbbkb = $this->Pbbkb->getAllData();

		$json['arr_jenis_pbbkb'] = $arr_jenis_pbbkb;
		$json['arr_trx'] = $this->getAllTransactionByFilter($postSpbu, $postTh, $postBulan, $postDasar, $_POST['radJenis'], $postTgl);
		$json['getLokasi'] = $postLokasi;
		$json['getSpbu'] = $postSpbu;
		$json['getTahun'] = $postTh;
		$json['getBulan'] = $postBulan;
		$json['getDasar'] = $postDasar;
		$json['getTgl'] = $postTgl;
		echo json_encode($json);
	}

	private function getAllTransactionByFilter($getSpbu, $getTh, $getBulan, $getDasar, $getJenis, $getTgl='')
	{
		$arr = array();
		$cond = $getJenis == '11' ? "" : " and tgl='". $getTgl ."'";
		$cmd = "select * from t_transaksi_pbbkb where id_lokasi_pbbkb='". $getSpbu ."' and  id_anggaran='". $getTh ."' and id_bulan='". $getBulan ."' and id_dasar_trx_pbbkb='". $getDasar ."'". $cond;
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
		$postTgl = empty($_POST['gettgl']) ? '' : $this->security->xss_clean($_POST['gettgl']);
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

		$i = 0;

		//Hapus Transaksi

		$cond = $postTgl == '' ? "" : " and tgl='". $postTgl ."'";
		$cmd = "delete from t_transaksi_pbbkb where id_lokasi_pbbkb='". $postSpbu ."' and  id_anggaran='". $postTh ."' and id_bulan='". $postBulan ."' and id_dasar_trx_pbbkb='". $postDasar ."'". $cond;
		$this->db->query($cmd);

		foreach( $arr as $p ) {

			$arrPost = array(
				'id_transaksi_pbbkb' => $id.$i,
				'id_lokasi_pbbkb' => $postSpbu,
				'id_anggaran' => $postTh,
				'id_bulan' => $postBulan,
				'tgl' => $postTgl,
				'id_dasar_trx_pbbkb' => $postDasar,
				'id_item_pbbkb' => $p[1],
				'jumlah' => $p[2],
				'create_by' => $this->session->userdata('id_user'),
				'last_update' => $getTime
			);

			$this->db->insert('t_transaksi_pbbkb', $arrPost);
			
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
