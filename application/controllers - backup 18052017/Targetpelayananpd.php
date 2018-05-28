<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Targetpelayananpd extends CI_Controller {

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
        $this->load->Model('Thanggaran');
        $this->load->Model('Targetpelypd');
		if($this->session->userdata('status_login') == FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Pengisian Target Minimal Pelayanan Pajak Daerah';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'atur target pelayanan pajak daerah';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();

		switch ($_POST['haksi']) {
			
			case 'add':
				$arrPost = array();

				$n = 0;
				foreach ($_POST['hIdRekPD'] as $r) {
					$arrInput[$n][1] = $r;
					$n++;
				}

				$n = 0;
				foreach ($_POST['hIdtriwulan'] as $r) {
					$arrInput[$n][2] = $r;
					$n++;
				}
				$n = 0;
				foreach ($_POST['txtjml'] as $r) {
					$arrInput[$n][3] = $r;
					$n++;
				}

				$i = 1;
				foreach ( $arrInput as $row ) {
					$arrPost[] = array(
						'id_target' => $id.$i,
						'id_anggaran' => $this->security->xss_clean($_POST['slctth']),
						'id_triwulan' => $row[2],
						'id_rek_pd' => $row[1],
						'total' => $row[3]
					);
					$i++;
				}

				$result = $this->Targetpelypd->tambah($arrPost, $this->security->xss_clean($_POST['slctth']));
				if ( $result == ''){
					$msg = 'Tahun Anggaran berhasil di simpan !';
				}else{
					$error = $result;
				}
				
				break;
			
			case 'delete':
				$result = $this->Targetpelypd->delete($this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Tahun Anggaran berhasil di dihapus !';
				}else{
					$error = $result;
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

	public function json_detil()
	{
		$json=array();
		$error="";
		$th_anggaran = '';
		$resultArr = array();
		
		$getData = $this->Thanggaran->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			
			$row = $getData->row();
			$th_anggaran = $row->th_anggaran;

			foreach ( $this->Targetpelypd->getDataById($row->id_anggaran)->result() as $row2 ) {
				$resultArr[$row2->id_rek_pd .'_'. $row2->id_triwulan]['forID'] = $row2->id_rek_pd .'_'. $row2->id_triwulan;
				$resultArr[$row2->id_rek_pd .'_'. $row2->id_triwulan]['total'] = $row2->total;
			};

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['th_anggaran'] = $th_anggaran;
		$json['resultArr'] = $resultArr;
		$json['error'] = $error;
		
		echo json_encode($json);
	}
}
