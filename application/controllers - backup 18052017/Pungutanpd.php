<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pungutanpd extends CI_Controller {

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
        $this->load->Model('Trxpd');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Obyek Pungutan Pajak Daerah';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form rekam pungutan pajak daerah';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function json_detil_rekam_by_lokasi()
	{
		$this->load->Model('Pd');

		$json = array();
		$arrPd = array();
		$error = '';
		$postLokasi = $this->security->xss_clean($_POST['lokasi']);
		$postAnggaran = $this->security->xss_clean($_POST['ia']);
		$postBulan = $this->security->xss_clean($_POST['bulan']);

		$getRekam = $this->Trxpd->getTransaksiInput($postAnggaran, $postLokasi, $postBulan);

		$getPd = $this->Pd->getRekeningRekam($this->security->xss_clean($_POST['lokasi']));
		foreach ( $getPd->result() as $pd ) {
			$jumlah = empty($getRekam[$pd->id_rek_pd]['jumlah']) ? 0 : $getRekam[$pd->id_rek_pd]['jumlah'];
			$arrPd[] = array(
				'id_rek_pd' => $pd->id_rek_pd,
				'nama_rekening' => $pd->nama_rekening,
				'jumlah' => $jumlah,
				'nama_class' => $pd->nama_class,
				'disabled' => $pd->disabled
			);
		}

		$json['arrPd'] = $arrPd;
		$json['error'] = $error;

		echo json_encode($json);
	}

	public function act_crud()
	{
		$json = array();
		$error = '';
		$msg = '';
		$id = time();
		$postLokasi = $this->security->xss_clean($_POST['lokasi']);
		$postAnggaran = $this->security->xss_clean($_POST['ia']);
		$postBulan = $this->security->xss_clean($_POST['bulan']);

		$n = 0;
		foreach ($_POST['txtjumlah'] as $r) {
			$arr[$n][2] = $r;
			$n++;
		}
		$n = 0;
		foreach ($_POST['txtpd'] as $r) {
			$arr[$n][1] = $r;
			$n++;
		}

		$this->db->trans_begin();

		$i = 0;
		foreach ( $arr as $post ) {
			// Hapus Transaksi
			$cmd = "delete from t_pungut_pd where id_lokasi='". $postLokasi ."' and id_anggaran='". $postAnggaran ."' and id_bulan='". $postBulan ."' and id_rek_pd='". $post[1] ."'";
			$this->db->query($cmd);

			// Input Transaksi
			$arrPost = array(
				'id_pungut_pd' => $id.$i,
				'id_lokasi' => $postLokasi,
				'id_anggaran' => $postAnggaran,
				'id_bulan' => $postBulan,
				'id_rek_pd' => $post[1],
				'jumlah' => $post[2],
				'id_user' => $this->session->userdata('id_user'),
				'last_update' => date('Y-m-d H:i:s')
			);
			$this->db->insert('t_pungut_pd', $arrPost);
			$i++;
		}

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			$error = $this->db->_error_message();
		else:
			$this->db->trans_commit();
			$msg = 'Dokumen Obyek Pajak Daerah telah tersimpan !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	

}
