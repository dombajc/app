<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updateprofile extends CI_Controller {

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
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'PERBAHARUI PROFILE';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form ubah profile';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_update()
	{
		$this->load->Model('Users');
		$json=array();
		$error="";
		$msg="";

		$arrPost = array(
			'nama_user' => $this->security->xss_clean($_POST['txtnama'])
		);
		$result = $this->Users->update($arrPost, $this->session->userdata('id_user'));
		if ( $result == ''){
			$msg = 'Data Profile berhasil di simpan !';
		}else{
			$error = $result;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}
}
