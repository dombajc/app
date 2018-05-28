<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Statuspendidikan extends CI_Model{

	private $tabel='t_status_pendidikan';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function optionSelect()
	{
		$opsi = '';
		foreach ( $this->getAllData()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_status_pendidikan .'"> '. $row->pendidikan .' </option>';
		}
		return $opsi;
	}

}