<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Thanggaran extends CI_Model{

	private $tabel='t_th_anggaran';

	public function tambah($arrData){
		
		$this->db->trans_begin();
		$this->db->insert($this->tabel, $arrData);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}
	
	public function update($arrData,$ID){
		
		$this->db->trans_begin();
		$this->db->where('id_anggaran',$ID);
		$this->db->update($this->tabel, $arrData);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function aktif($arrData,$ID){
		
		$this->db->trans_begin();
		
		$this->db->query("update t_th_anggaran set aktif=0");
		
		$this->db->where('id_anggaran',$ID);
		$this->db->update($this->tabel, $arrData);
				
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

	private function getAll()
	{
		$cmd = "SELECT
			*
			FROM
			t_th_anggaran";
		return $this->db->query($cmd);
	}

	private function getAllOrderByAktif()
	{
		$cmd = "SELECT
			*
			FROM
			t_th_anggaran order by aktif desc";
		return $this->db->query($cmd);
	}

	private function arrThAnggaran()
	{
		$arr = array();
		foreach ( $this->getAll()->result() as $row ) {
			$arr[] = $row->th_anggaran;
		}
		return $arr;
	}

	public function optionAll()
	{
		$opsi = '';
		$arrTh = $this->arrThAnggaran();
		$th = 2010;
		for ( $y=date('Y'); $y>= $th; $y-- ) {
			if ( !in_array($y, $arrTh) ) {
				$opsi .= '<option value="'. $y .'"> '. $y .' </option>';
			}
		}
		
		return $opsi;
	}

	public function opsiByTahunAktif()
	{
		$opsi = '';
		foreach( $this->getAllOrderByAktif()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_anggaran .'"> '. $row->th_anggaran .' </option>';
		}
		return $opsi;
	}

	public function getLoadAllDataWithLimit($dari,$sampai,$filter,$sort){
		$cmd =  "select * from ". $this->tabel ." where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}
	
	public function countDataAll($filter){
		$cmd =  "select count(*) as total from ". $this->tabel ." where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

	public function getDataByID($ID)
	{
		$cmd = "select * from ". $this->tabel ." where id_anggaran='". $ID ."'";
		return $this->db->query($cmd);
	}

	public function getIdThAktif()
	{
		$id = '';
		$cmd = "select * from t_th_anggaran where aktif=1";

		$count = $this->db->query($cmd)->num_rows();
		if ( $count == 1 ) {
			$row = $this->db->query($cmd)->row();
			$id = $row->id_anggaran;
		}

		return $id;

	}
}