<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Provinsi extends CI_Model{

	private $tabel = 't_provinsi';
	private $KeyId = '';

	public function getAll()
	{
		$cmd = "select * from ". $this->tabel ." order by default_pilihan desc, provinsi";
		return $this->db->query($cmd);
	}

	public function printOpsi()
	{
		$opsi = '';

		foreach ($this->getAll()->result() as $row) {
			$opsi .= '<option value="'. $row->id_provinsi .'"> '. $row->provinsi .' </option>';
		}

		return $opsi;
	}

}