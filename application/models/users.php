<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Model{

	private $tabel='t_users';

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
		$this->db->where('id_user',$ID);
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
		$this->db->where('id_user',$ID);
		$this->db->delete($this->tabel);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}
	
	public function getLoadAllData($filter){
		$cmd =  "select * from ". $this->tabel ." where ". $filter;
		return $this->db->query($cmd);
	}
	
	public function getLoadAllDataWithLimit($dari,$sampai,$filter,$sort){
		$cmd =  "select * from v_users where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}
	
	public function countDataAll($filter){
		$cmd =  "select count(*) as total from ". $this->tabel ." where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}
	
	public function getDataByID($ID)
	{
		$cmd = "select * from ". $this->tabel ." where id_user='". $ID ."'";
		return $this->db->query($cmd);
	}
	
	public function selectoptionvalue()
	{
		$opsi = '';
		$cmd = "select id_dokter,nama_dokter from  ". $this->tabel ." where aktif=1";
		foreach($this->db->query($cmd)->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_dokter .'"> '. $row->nama_dokter .' </option>';
		}
		return $opsi;
	}

	public function cekUsername( $username )
	{
		$cmd = "select * from ". $this->tabel ." where username='". $username ."'";
		return $this->db->query($cmd)->num_rows();
	}

	public function cekKataSandiLama( $katasandi )
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where id_user='". $this->session->userdata('id_user') ."' and sandi='". md5($katasandi) ."'";
		return $this->db->query($cmd)->row()->total;
	}

}