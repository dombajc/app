<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
	private $folder = '';
	
	public function __construct() {
        parent::__construct();
		if($this->session->userdata('status_login')==TRUE){
            redirect('/');
        }
    }
	
	public function index()
	{
        $this->load->view($this->folder.'form login');
	}

	public function validasi_login()
	{
		$json=array();
        $error="";
        $msg = "";
		
        $cmd = "select id_user from v_users where username='". $this->security->xss_clean($_POST['loguser']) ."' and sandi='". md5($this->security->xss_clean($_POST['logpass'])) ."' and aktif=1";
        $q = $this->db->query($cmd);
        if($q->num_rows() == 1){
            $row=$q->row();
            $session=array(
                'id_user'=>$row->id_user,
                'status_login'=>true
            );
            $this->session->set_userdata($session);
            $msg = "Terima Kasih anda menggunakan aplikasi kami. Tunggu sebentar halaman akan reload otomatis !";
        }else{
            $error = "Maaf nama akses dan kata sandi tidak cocok. <br>Silahkan hubungi administrator !";
        }
		
		$json['error']=$error;
        $json['msg']=$msg;
        echo json_encode($json);
	}
}
