<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entriretribusiskpdlain extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
		$this->load->model('Rekamretribusiskpdlain');
		$this->load->model('Rekeningskpdlain');
    }
	
	public function index()
	{
		

		$data['title'] = 'ENTRY PENERIMAAN RETRIBUSI SKPD LAIN';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form entri penerimaan retribusi skpd lain';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}
	
	public function json_view_rekening_skpd_lain()
	{
		$this->load->model('Skpdlain');
		
		$post_thn = $this->security->xss_clean($_POST['post_thn']);
		$post_bln = $this->security->xss_clean($_POST['post_bln']);
		$post_lokasi = $this->security->xss_clean($_POST['post_lokasi']);
		
		$json = array();
		$error = '';
		$arr = array();
		$sts_entri = 0;
		$row_entri = array();
		
		$arrSkpdLain = $this->Skpdlain->get_data_by_cond("id_skpd,nama_skpd", " where id_lokasi='". $post_lokasi ."'")->result();
		$arrRekening = $this->Rekeningskpdlain->arr_data_by_id_skpd($post_lokasi);
		$arrEntrian = $this->Rekamretribusiskpdlain->arr_entrian_by_rekening($post_thn, $post_bln, $post_lokasi);
		$rowSearchEntrian = $this->Rekamretribusiskpdlain->find_entrian($post_thn, $post_bln, $post_lokasi);
		
		if ( $rowSearchEntrian->num_rows() == 1 )
		{
			$sts_entri = 1;
			$row_entri = $rowSearchEntrian->row();
		}
		
		if ( count($arrRekening) > 0 )
		{
			foreach ( $arrSkpdLain as $row )
			{
				if ( !empty($arrRekening[$row->id_skpd]) )
				{
					$arr[$row->id_skpd][] = $row->nama_skpd;
					foreach ( $arrRekening[$row->id_skpd] as $list )
					{
						$total = empty($arrEntrian[$list->id_rekening]) ? 0 : $arrEntrian[$list->id_rekening];
						$arr[$row->id_skpd]['node'][] = array('id_rekening' => $list->id_rekening, 'no_rekening2' => $list->no_rekening2, 'nama_rekening' => $list->nama_rekening, 'anakan' => $list->anakan, 'jumlah' => $total);
					}
				}
				
			}
		}
		else
		{
			$error = 'Tidak ada no rekening yang akan di input !';
		}
		
		$json['arr'] = $arr;
		$json['error'] = $error;
		$json['sts_entri'] = $sts_entri;
		$json['row_entri'] = $row_entri;
		echo json_encode($json);
	}
	
	public function act()
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
		foreach ($_POST['postjumlah'] as $r) {
			$arrItem[$n][2] = empty($r) || $r == '' ? 0 : $r;
			$n++;
		}
		
		$datarekam = array(
			'id_entri' => $get_id,
			'id_anggaran' => $post_thn,
			'id_lokasi' => $post_lokasi,
			'id_bulan' => $post_bln,
			'id_user' => $get_user,
			'last_update' => $get_last_update
		);
		
		$result = $this->Rekamretribusiskpdlain->save($datarekam, $arrItem);
		
		if ( empty($result) || $result == '' ):
			$error = $result;
		else:
			$msg = 'Data berhasil di simpan !';
		endif;
		
		$json['msg'] = $msg;
		$json['error'] = $error;
		echo json_encode($json);
	}
	
	public function hapus()
	{
		$json = array();
		$error = '';
		$msg = '';
		$post_thn = $this->security->xss_clean($_POST['post_thn']);
		$post_bln = $this->security->xss_clean($_POST['postbulan']);
		$post_lokasi = $this->security->xss_clean($_POST['postlokasi']);
		
		$result = $this->Rekamretribusiskpdlain->hapus($post_thn, $post_bln, $post_lokasi);
		
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
