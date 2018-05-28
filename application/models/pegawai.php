<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pegawai extends CI_Model{

	private $tabel='t_pegawai';
	private $view = 'v_pegawai_v2';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function cekIDByNip( $nip )
	{
		$cmd = "select * from ". $this->tabel ." where nip='". $nip ."'";
		return $this->db->query($cmd)->num_rows();
	}

	public function tambah($arrPost, $arrMutasi)
	{
		
		$this->db->trans_begin();
		
		$this->db->insert($this->tabel, $arrPost);
		$this->db->insert('t_mutasi', $arrMutasi);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function update($arrData, $ID){
		
		$this->db->trans_begin();
		$this->db->where('id_pegawai',$ID);
		$this->db->update($this->tabel, $arrData);

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function validasi($arrData, $ID) {
		
		$this->db->trans_begin();
		$this->db->where('id_pegawai',$ID);
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
		$this->db->where('id_pegawai',$ID);
		$this->db->delete($this->tabel);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function getLoadAllDataWithLimit($dari,$sampai,$filter,$sort){
		$cmd =  "select * from ". $this->view ." where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}
	
	public function countDataAll($filter){
		$cmd =  "select count(*) as total from v_sort_pegawai_update where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

	public function getDataByID($ID)
	{
		$cmd = "select * from t_pegawai where id_pegawai='". $ID ."'";
		return $this->db->query($cmd);
	}

	public function countPegawaiByIdUser($id_user)
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where id_user='". $id_user ."'";
		return $this->db->query($cmd)->row()->total;
	}

	public function cekEditNIP( $nip, $id_user)
	{
		if ( $nip == '' ) {
			return 0;
		} else {
			$cmd = "select * from ". $this->tabel ." where nip='". $nip ."' and id_pegawai!='". $id_user ."'";
			return $this->db->query($cmd)->num_rows();
		}
	}

	public function getSortPegawai($filter, $sort)
	{
		$cmd =  "SELECT
			id_pegawai,nama_pegawai,nip,status_pegawai,jabatan,nama_lokasi,nama_homebase
			FROM
			v_sort_pegawai_update where ". $filter ." order by ". $sort;
		return $this->db->query($cmd);
	}
	
	public function getSortPegawaiOld($dari,$sampai,$filter,$sort)
	{
		$cmd =  "select * from v_sort_pegawai_update where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		//$cmd =  "select * from v_pegawai_update28042016 where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}

}
