<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Target extends CI_Model{

	private $tabel='t_target_d2d';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function tambah($arrPost, $target)
	{
		
		$this->db->trans_begin();
		
		$this->db->query("delete from ". $this->tabel ." where id_anggaran='". $target ."'");
		$this->db->insert_batch($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function delete($ID){
		
		$this->db->trans_begin();
		$this->db->where('id_anggaran',$ID);
		$this->db->delete($this->tabel);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function getDataById($ID)
	{
		$cmd = "select * from ". $this->tabel ." where id_anggaran='". $ID ."'";
		return $this->db->query($cmd);
	}

}