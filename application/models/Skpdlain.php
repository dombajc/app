<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Skpdlain extends CI_Model{

	private $tabel='n_data_skpd';
	private $view = 'v_data_skpd_lain';
	private $keyId = 'id_skpd';

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
		
		$this->db->where($this->keyId, $postId);
		$this->db->update($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function hapus($postId)
	{
		//if ( $this->countEntrianById($postId) == 0 && $this->countTransaksiById($postId) == 0 ) {
			$this->db->trans_begin();
			
			$this->db->where($this->keyId, $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		//} else {
			//return 'Maaf Data SPBU tidak dapat dihapus karena sudah pernah melakukan entrian data !';
		//}
	}

	public function arr_tabel($cond, $sort)
	{
		$cmd = "select * from ". $this->view ." where ". $cond ." order by ". $sort;
		return $this->db->query($cmd)->result_array();
	}

	public function get_data_by_id($getId)
	{
		$cmd = "select * from ". $this->tabel ." where ". $this->keyId ."='". $getId ."'";
		return $this->db->query($cmd);
	}
	
	public function get_data_by_cond($select='*', $cond='')
	{
		$cmd = "select ". $select ." from ". $this->tabel . $cond;
		return $this->db->query($cmd);
	}
}