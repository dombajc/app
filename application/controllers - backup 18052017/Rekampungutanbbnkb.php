<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekampungutanbbnkb extends CI_Controller {

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
        $this->load->Model('Pungutanbbnkb');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Rekam Pemungutan Obyek dan Realisasi BBNKB';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form rekam pungutan bbnkb';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function arrinputan()
	{
		$json = array();
		$error = '';
		$postLokasi = $this->security->xss_clean($_POST['lokasi']);
		$postAnggaran = $this->security->xss_clean($_POST['ia']);
		$postBulan = $this->security->xss_clean($_POST['bulan']);
		$postJenis = $this->security->xss_clean($_POST['jenis']);

		$cekRekam = $this->Pungutanbbnkb->cekTransaksi($postAnggaran, $postBulan, $postLokasi, $postJenis);
		$rekam = $cekRekam->num_rows() == 0 ? array() : $cekRekam->row();

		$json['arrPkb'] = $this->Pungutanbbnkb->arrListInput($postAnggaran, $postBulan, $postLokasi, $postJenis);
		$json['cek'] = $cekRekam->num_rows();
		$json['rekam'] = $rekam;
		$json['error'] = $error;

		echo json_encode($json);
	}

	public function act()
	{
		$json = array();
		$error = '';
		$msg = '';
		$id = time();
		$postLokasi = $this->security->xss_clean($_POST['hlokasi']);
		$postAnggaran = $this->security->xss_clean($_POST['hth']);
		$postBulan = $this->security->xss_clean($_POST['hbulan']);
		$postJenis = $this->security->xss_clean($_POST['hjenis']);

		$n = 0;
		foreach ($_POST['postidjenis'] as $r) {
			$arr[$n][1] = $r;
			$n++;
		}
		$n = 0;
		foreach ($_POST['postoby'] as $r) {
			$arr[$n][2] = str_replace(',','',$r);
			$n++;
		}
		$n = 0;
		foreach ($_POST['postpokok'] as $r) {
			$arr[$n][3] = str_replace(',','',$r);
			$n++;
		}
		$n = 0;
		foreach ($_POST['postsanksi'] as $r) {
			$arr[$n][4] = str_replace(',','',$r);
			$n++;
		}

		$this->db->trans_begin();

		// Hapus Transaksi
		$cmd = "delete from n_rekam_bbnkb where id_lokasi='". $postLokasi ."' and id_anggaran='". $postAnggaran ."' and id_bulan='". $postBulan ."' and id_rek_pd='". $postJenis ."'";
		$this->db->query($cmd);

		$arrPost = array(
			'id_rekam_bbnkb' => $id,
			'id_anggaran' => $postAnggaran,
			'id_lokasi' => $postLokasi,
			'id_bulan' => $postBulan,
			'id_rek_pd' => $postJenis,
			'id_user' => $this->session->userdata('id_user')
			);
		$this->db->insert('n_rekam_bbnkb', $arrPost);

		$i = 0;
		foreach ( $arr as $post ) {
			if ( $post[2] > 0 || $post[3] >0 || $post[4] > 0 ) {
				// Input Transaksi
				$arrItem = array(
					'id_item' => $id.$i,
					'id_rekam_bbnkb' => $id,
					'id_jenis' => $post[1],
					'obyek' => $post[2],
					'pokok' => $post[3],
					'sanksi' => $post[4]
				);
				$this->db->insert('n_item_rekam_bbnkb', $arrItem);
			}
			
			$i++;
		}

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			$error = $this->db->_error_message();
		else:
			$this->db->trans_commit();
			$msg = 'Dokumen Rekam BBNKB telah tersimpan !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function hapus()
	{
		$json = array();
		$error = '';
		$msg = '';
		$postid = $this->security->xss_clean($_POST['id']);

		$this->db->trans_begin();

		$this->db->where('id_rekam_bbnkb', $postid);
		$this->db->delete('n_rekam_bbnkb');

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			$error = $this->db->_error_message();
		else:
			$this->db->trans_commit();
			$msg = 'Dokumen Rekam BBNKB telah dihapus !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function arrLokasi()
	{
		$postinduk = $this->security->xss_clean($_POST['induk']);
		$cmd = "SELECT * FROM v_data_lokasi where id_induk='". $postinduk ."'";
		$data = $this->db->query($cmd)->result_array();
		echo json_encode(array('data'=>$data));
	}



}