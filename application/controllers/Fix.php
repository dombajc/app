<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fix extends CI_Controller {

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
		
    }
	
	public function index()
	{
		$this->db->trans_begin();

		$cmd = "select * from t_mutasi";
		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arrData = array(
				'id_tujuan_mutasi' => $this->Lokasi->getDataById($row->id_homebase)->row()->id_induk,
				'id_jenis_mutasi' => '333333'
			);

			$this->db->where('id_mutasi', $row->id_mutasi);
			$this->db->update('t_mutasi', $arrData);
		}

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			echo $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}
}
