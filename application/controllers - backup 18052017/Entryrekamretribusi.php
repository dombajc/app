<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entryrekamretribusi extends CI_Controller {

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
	private $folder = 'retribusi/';
	
	public function __construct() {
        parent::__construct();
		$this->load->Model('Rekeningpad');
		$this->load->Model('Setkoderetribusi');
		$this->load->Model('Rekamretribusi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'ENTRY REKAM RETRIBUSI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'entry rekam retribusi';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}
	
	public function open_form()
	{
		$json = array();
		$error = '';
		
		$arrlist = array();
		$arr_last_rekam = array();
		
		$post_thn = $this->security->xss_clean($_POST['post_thn']);
		$post_bln = $this->security->xss_clean($_POST['postbulan']);
		$post_lokasi = $this->security->xss_clean($_POST['postlokasi']);
		$get_user = $this->session->userdata('id_user');
		
		$year = $this->Thanggaran->getDataByID($post_thn)->row()->th_anggaran;
		$tgl_start = $year.'-'.$post_bln.'-01';
		$tgl_end = date('Y-m-t', strtotime($tgl_start));
		
		$cond = "LEFT(kd_rekening,4)='.1.2' AND tgl_penerima>='". $tgl_start ."' AND tgl_penerima<='". $tgl_end ."' AND id_lokasi='". $post_lokasi ."'";
		
		$arr_kode_rekening_by_tahun = $this->Rekeningpad->arr_kode_rekening($post_thn);
		$transaksi_pad = $this->Rekeningpad->arr_transaksi($cond);
		$transaksi_app = $this->Rekamretribusi->find_transaction($post_thn, $post_bln, $post_lokasi);
		$sts_transaksi_app = $transaksi_app->num_rows();
		$row_transaksi_app = $transaksi_app->row();
		
		$get_kode_rekening_by_user = $this->Setkoderetribusi->get_arr_kode_rekening_by_tahun($post_thn);
		
		if ( $sts_transaksi_app == 1 )
		{
			$arr_last_rekam = $this->Rekamretribusi->arr_item_value($transaksi_app->row()->id_rekam);
		}
		
		foreach ( $arr_kode_rekening_by_tahun as $row )
		{
			// Kesampingkan kode rekening Induk
			if ( in_array($row->kd_rekening, $get_kode_rekening_by_user) == true ) {
				$value_pad = empty($transaksi_pad[$row->kd_rekening]) ? 0 : $transaksi_pad[$row->kd_rekening];
				$value_app_oby = empty($arr_last_rekam[$row->kd_rekening]) ? 0 : $arr_last_rekam[$row->kd_rekening]->oby;
				$value_app_penerimaan = empty($arr_last_rekam[$row->kd_rekening]) ? 0 : $arr_last_rekam[$row->kd_rekening]->jumlah;
				
				$arrlist[] = array(
					'kd_rekening' => $row->kd_rekening,
					'no_rekening' => $row->no_rekening,
					'nm_rekening' => $row->nm_rekening,
					'anakan' => $row->anakan,
					'getonline' => $value_pad,
					'getapp_oby' => $value_app_oby,
					'getapp_penerimaan' => $value_app_penerimaan
				);
			}
			
			
		}
		
		if ( $sts_transaksi_app == 1 )
		{
			$tgl_rekam = explode(' ', $row_transaksi_app->last_update);
			$str_rekam = $this->Fungsi->formatsqltodate($tgl_rekam[0]) .' '. $tgl_rekam[1];
		} else {
			$str_rekam = '';
		}
		
		
		$json['getrekening'] = $arrlist;
		$json['sts'] = $sts_transaksi_app;
		$json['tgl_rekam'] = $str_rekam;
		
		echo json_encode($json);
	}
	
	public function save_form()
	{
		$json = array();
		$arr = array();
		$error = '';
		$msg = '';
		$get_id = time();
		$get_last_update = date('Y-m-d H:i:s');
		
		$post_thn = $this->security->xss_clean($_POST['post_thn']);
		$post_bln = $this->security->xss_clean($_POST['postbulan']);
		$post_lokasi = $this->security->xss_clean($_POST['postlokasi']);
		$get_user = $this->session->userdata('id_user');

		$n = 0;
		foreach ($_POST['postkode'] as $r) {
			$arrItem[$n][1] = $r;
			$n++;
		}
		
		$n = 0;
		foreach ($_POST['txtoby'] as $r) {
			$arrItem[$n][2] = empty($r) || $r == '' ? 0 : $r;
			$n++;
		}

		$n = 0;
		foreach ($_POST['txtrealisasi'] as $r) {
			$arrItem[$n][3] = empty($r) || $r == '' ? 0 : $r;
			$n++;
		}
		
		$cek_status_entry = $this->Rekamretribusi->find_transaction($post_thn, $post_bln, $post_lokasi);
		
		if ( $cek_status_entry->num_rows() == 1 )
		{
			$row = $cek_status_entry->row();
			$datarekam = array(
				'id_user' => $get_user,
				'last_update' => $get_last_update
			);
			$result = $this->Rekamretribusi->act_update($row->id_rekam, $datarekam, $arrItem);
		}
		else
		{
			$datarekam = array(
				'id_rekam' => $get_id,
				'id_anggaran' => $post_thn,
				'id_lokasi' => $post_lokasi,
				'id_bulan' => $post_bln,
				'id_user' => $get_user,
				'last_update' => $get_last_update
			);
			$result = $this->Rekamretribusi->act_create($datarekam, $arrItem);
		}
		
		if ( empty($result) || $result == '' ):
			$error = $result;
		else:
			$msg = 'Data berhasil di simpan !';
		endif;
		
		$json['msg'] = $msg;
		$json['error'] = $error;
		echo json_encode($json);
	}
	
	public function delete_entry()
	{
		$json = array();
		$error = '';
		$msg = '';
		$post_thn = $this->security->xss_clean($_POST['post_thn']);
		$post_bln = $this->security->xss_clean($_POST['postbulan']);
		$post_lokasi = $this->security->xss_clean($_POST['postlokasi']);
		
		$result = $this->Rekamretribusi->delete_act($post_thn, $post_bln, $post_lokasi);
		
		if ( empty($result) || $result == '' ):
			$error = $result;
		else:
			$msg = 'Data berhasil di hapus !';
		endif;
		
		$json['msg'] = $msg;
		$json['error'] = $error;
		echo json_encode($json);
	}

}
