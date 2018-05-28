<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jabatan extends CI_Model{

	private $tabel='t_jabatan';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function optionSelect()
	{
		$opsi = '';
		foreach ( $this->getAllData()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_jabatan .'">'. $row->jabatan .'</option>';
		}
		return $opsi;
	}

	public function opsiASN()
	{
		$opsi = '';

		$this->db->where('non_asn', 0);
		$this->db->where('aktif', 1);
		$this->db->order_by('non_asn desc');
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_jabatan .'">'. $row->jabatan .'</option>';
		}
		return $opsi;
	}
	
	public function opsiNonASN()
	{
		$opsi = '';

		$this->db->where('non_asn', 1);
		$this->db->where('aktif', 1);
		$this->db->order_by('non_asn desc');
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_jabatan .'">'. $row->jabatan .'</option>';
		}
		return $opsi;
	}

	public function opsiPolisi()
	{
		$opsi = '';

		$this->db->where('non_asn', 2);
		$this->db->where('aktif', 1);
		$this->db->order_by('non_asn desc');
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ){
			$opsi .= '<option value="'. $row->id_jabatan .'">'. $row->jabatan .'</option>';
		}
		return $opsi;
	}


}