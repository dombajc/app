<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pangkat extends CI_Model{

	private $tabel='t_pangkat';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function optionSelect()
	{
		$opsi = '';
		foreach ( $this->getAllData()->result() as $row ){
			$opsi .= '<option value="'. $row->id_pangkat .'"> '. $row->pangkat .' [ '. $row->golongan .' ] </option>';
		}
		return $opsi;
	}

	public function opsiASN()
	{
		$opsi = '';

		$this->db->where('asn', 1);
		$this->db->where('aktif', 1);
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_pangkat .'"> '. $row->pangkat .' [ '. $row->golongan .' ] </option>';
		}
		return $opsi;
	}
	
	public function opsiNonASN()
	{
		$opsi = '';

		$this->db->where('asn', 0);
		$this->db->where('aktif', 1);
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_pangkat .'"> '. $row->pangkat .' [ '. $row->golongan .' ] </option>';
		}
		return $opsi;
	}

	public function opsiPolisi()
	{
		$opsi = '';

		$this->db->where('asn', 2);
		$this->db->where('aktif', 1);
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_pangkat .'"> '. $row->pangkat .' [ '. $row->golongan .' ] </option>';
		}
		return $opsi;
	}

}