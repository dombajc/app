<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Historilokasi extends CI_Model{

	private $tabel = 't_riwayat_lokasi_pegawai';
	private $view = 'v_riwayat_lokasi_pegawai';

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

	public function getLoadAllDataWithLimit($dari,$sampai,$filter,$sort){
		$cmd =  "select * from ". $this->view ." where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}
	
	public function countDataAll($filter){
		$cmd =  "select count(*) as total from ". $this->tabel ." where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

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
		$this->db->where('id_riwayat_lokasi',$ID);
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
		$this->db->where('id_riwayat_lokasi',$ID);
		$this->db->delete($this->tabel);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function getDataByID($ID)
	{
		$cmd = "select * from ". $this->tabel ." where id_riwayat_lokasi='". $ID ."'";
		return $this->db->query($cmd);
	}

	public function getLokasiAktifByIdPegawai($ID)
	{
		$cmd = "select id_riwayat_lokasi,lokasi from v_riwayat_lokasi_pegawai where id_pegawai='". $ID ."' and aktif=1";
		return $this->db->query($cmd);
	}
}