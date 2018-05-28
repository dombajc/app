<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayatnosts extends CI_Controller {

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
	private $folder = 'esamsat jateng/';
	
	public function __construct() {
        parent::__construct();
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
        $this->load->Model('Esamsatjateng');
        $this->load->Model( 'Rekeningpad' );
        $this->load->Model( 'Nosts' );
    }
	
	public function index()
	{

		$data['title'] = 'PENCARIAN No.STS';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'riwayat pencarian no sts';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}
	
}
