<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Penyalurbbm extends CI_Model{

	private $tabel = 't_penyalur_bbm';
	private $KeyId = 'id_penyalur';
	private $view = 'v_data_penyalur';

	public function getAll()
	{
		$cmd = "select * from ". $this->tabel ." order by default_pilihan desc, provinsi";
		return $this->db->query($cmd);
	}

	public function getViewAll($select, $cond, $sort)
	{
		$cmd = "select ". $select ." from ". $this->view ." where ". $cond ." order by ". $sort;
		return $this->db->query($cmd);
	}

	public function get_array_grid($cond, $sort)
	{
		return $this->getViewAll('*',$cond, $sort)->result_array();
	}

	public function getById($getId)
	{
		$this->db->where($this->KeyId, $getId);
		return $this->db->get($this->view);
	}

	public function printOpsi()
	{
		$opsi = '';

		foreach ($this->getAll()->result() as $row) {
			$opsi .= '<option value="'. $row->id_provinsi .'"> '. $row->provinsi .' </option>';
		}

		return $opsi;
	}

	public function tambah($arrPost)
	{
		$this->db->trans_begin();
		
		$this->db->insert($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function ubah($arrPost, $postId)
	{
		$this->db->trans_begin();
		
		$this->db->where($this->KeyId, $postId);
		$this->db->update($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	private function countTransaksiById($getId)
	{
		$cmd = "select count(*) as jml from t_entry_distribusi_bbm where id_penyalur='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($postId)
	{
		if ( $this->countTransaksiById($postId) == 0 ) {
			$this->db->trans_begin();
		
			$this->db->where($this->KeyId, $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'Maaf data perusahaan penyalur bbm tidak dapat dihapus karena sudah pernah melakukan transaksi !';
		}
	}

	public function getDataByKota($getIdKota)
	{
		$cmd = "select id_penyalur,nama_penyalur from ". $this->tabel ." where id_kota_asal='". $getIdKota ."'";
		return $this->db->query($cmd)->result_array();
	}

}