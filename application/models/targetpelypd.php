<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Targetpelypd extends CI_Model{

	private $tabel='t_target_pely_pd';

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

	public function getArrTarget($postAnggaran)
	{
		$arr = array();

		$cmd = "SELECT
			id_anggaran,
			id_triwulan,
			id_rek_pd,
			SUM(total) AS total_target
			FROM
			t_target_pely_pd
			where id_anggaran='". $postAnggaran ."'
			GROUP BY id_anggaran,id_triwulan,id_rek_pd";

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_triwulan][$row->id_rek_pd]['target'] = $row->total_target;
		}

		return $arr;
	}

}