<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pilihkoderekening extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'SETTING KODE REKENING RETRIBUSI';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'setting kode rekening';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}
	
	public function result_search_user()
	{
		$this->load->Model('Users');
		$cond = $_POST['cariNama'] == '' || empty($_POST['cariNama']) ? " and nama_user='". $_POST['cariNama'] ."'" : " and nama_user like '%". $_POST['cariNama'] ."%'";
		$sort = ' order by nama_user';
		
		$items = $this->Users->result_find_by_name($cond, $sort)->result_array();

		echo json_encode($items);
	}
	
	public function arr_result_setting()
	{
		$postthn = $this->security->xss_clean($_POST['post_thn']);
		
		echo $this->Rekeningpad->show_pilihan_kode_by_tahun($postthn);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();
		$last_action = date('Y-m-d H:i:s');
		
		$arrpostdata = array();
		
		$arr_post_kode_rekening_retribusi = $_POST['postkoderetribusi'];
		$postidthn = $this->security->xss_clean($_POST['post_thn']);
		//$postiduser = $this->security->xss_clean($_POST['post_user']);
		$postiduser = $this->session->userdata('id_user');
		
		if ( count($arr_post_kode_rekening_retribusi) > 0 )
		{
			$arrpostdata = array(
				'arrkoderekening' => $arr_post_kode_rekening_retribusi,
				'getid' => $id,
				'idthn' => $postidthn,
				'iduser' => $postiduser
			);
			
			$result = $this->Setkoderetribusi->save_form($arrpostdata);
			
			if ( $result == '' )
			{
				$msg = 'Pengaturan berhasil di simpan !';
			} else {
				$error = $result;
			}
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}
	
}
