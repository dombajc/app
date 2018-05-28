<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importphltosarjana extends CI_Controller {

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

		$cmd = "select id_pegawai from t_pegawai where id_sts_pegawai='99'";
		$query = $this->db->query($cmd);

		foreach ( $query->result() as $row )
		{
			$arrPost = array(
				'id_jabatan' => 'srjn'
			);
			$this->db->where('id_pegawai', $row->id_pegawai);
			$this->db->update('t_mutasi', $arrPost);
		}

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			echo $this->db->_error_message();
		else:
			$this->db->trans_commit();
			echo 'Berhasil';
		endif;
	}
}
