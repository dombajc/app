<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pbbkb extends CI_Model{

	private $tabel='t_item_pbbkb';

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
		
		$this->db->where('id_item_pbbkb', $postId);
		$this->db->update($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	private function countTransaksi($getId)
	{
		$cmd = "select count(*) as jml from t_item_distribusi_bbm where id_item_pbbkb='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($postId)
	{
		$count = $this->countTransaksi($postId);

		if ( $count == 0 ) {
			$this->db->trans_begin();
		
			$this->db->where('id_item_pbbkb', $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'Data Jenis Bahan Bakar tidak dapat dihapus karena sudah pernah melakukan transaksi sebelumnya !';
		}
	}

	public function TotalDataGrid($cond)
	{
		$cmd = "select count(*) as jml from ". $this->tabel ." where ". $cond;
		return $this->db->query($cmd)->row()->jml;
	}

	public function getGridData($offset, $countrow, $cond, $sort)
	{
		$cmd = "select id_item_pbbkb,item_pbbkb,aktif from ". $this->tabel ." where ". $cond ." order by ". $sort ." limit ". $offset .",". $countrow;
		return $this->db->query($cmd)->result_array();
	}

	public function getDataByCond($cond, $sort)
	{
		$cmd = "select id_item_pbbkb,item_pbbkb,aktif from ". $this->tabel ." where ". $cond ." order by ". $sort;
		return $this->db->query($cmd)->result_array();
	}

	public function getDataById($getId)
	{
		$cmd = "select * from ". $this->tabel ." where id_item_pbbkb='". $getId ."'";
		return $this->db->query($cmd);
	}

	public function getAllData()
	{
		$cmd = "select * from ". $this->tabel ." where aktif=1";
		return $this->db->query($cmd)->result_array();
	}

}