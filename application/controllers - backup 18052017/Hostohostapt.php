<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hostohostapt extends CI_Controller {

	
	
	public function __construct() {
        parent::__construct();
        $this->load->model('Paraf');
    }

	public function getparafrekapjurnal($paramlokasi)
	{	
		$getlokasi = $this->security->xss_clean($paramlokasi);
		$json = array();
		$json['paraf1'] = $this->Paraf->get_json_paraf('rd2d', $getlokasi, 1 );
		$json['paraf2'] = $this->Paraf->get_json_paraf('rd2d', $getlokasi, 2 );
		echo json_encode($json);
	}
}
