<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kecamatan extends CI_Model{

	private $tabel='t_kecamatan';
	private $view = 'v_kecamatan';
	private $keyId = 'id_kecamatan';

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
		
		$this->db->where('id_kecamatan', $postId);
		$this->db->update($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	private function countSpbuById($getId)
	{
		$cmd = "select count(*) as jml from t_penyetor_pbbkb where id_kecamatan='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($postId)
	{
		$count = $this->countSpbuById($postId);
		if ( $count == 0 ) {
			$this->db->trans_begin();
		
			$this->db->where('id_kecamatan', $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'Maaf data kecamatan tidak dapat dihapus karena sudah pernah melakukan penyimpanan di SPBU !';
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
		$cmd = "select * from ". $this->view ." where ". $cond ." order by ". $sort;
		return $this->db->query($cmd)->result_array();
	}

	public function getDataById($getId)
	{
		$cmd = "select * from ". $this->tabel ." where ". $this->keyId ."='". $getId ."'";
		return $this->db->query($cmd);
	}

	public function getDataByLokasi($getIdLokasi)
	{
		$cond = $getIdLokasi == '' ? '' : " and id_lokasi='". $getIdLokasi ."'";
		$cmd = "select * from ". $this->tabel ." where aktif=1". $cond;
		return $this->db->query($cmd)->result_array();
	}

}