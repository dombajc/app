<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updatepassword extends CI_Controller {

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
		$data['title'] = 'DASHBOARD';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'form ubah kata sandi';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_change_password()
	{
		$this->load->Model('Users');
		$json=array();
		$error="";
		$msg="";

		//-- Cek apakah kata sandi lama sudah sama !
		if ( $this->Users->cekKataSandiLama($this->security->xss_clean($_POST['txtkatasandilama'])) == 1 ) {
			$arrPost = array(
				'katakunci' => $this->security->xss_clean($_POST['txtkatasandi']),
				'sandi' => md5($this->security->xss_clean($_POST['txtkatasandi']))
			);
			$result = $this->Users->update($arrPost, $this->session->userdata('id_user'));
			if ( $result == ''){
				$msg = 'Pengguna Baru berhasil di simpan !';
			}else{
				$error = $result;
			}
		} else {
			$error = 'Maaf kata sandi lama tidak cocok !';
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}
}
