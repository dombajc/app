<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kotaasal extends CI_Model{

	private $tabel = 't_kota_asal';
	private $KeyId = 'id_kota_asal';
	private $view = 'v_kota_asal_penyalur_bbm';

	public function getAll()
	{
		$cmd = "SELECT id_kota_asal,kota_asal FROM ". $this->tabel ." WHERE id_provinsi='13' ORDER BY kota_asal";
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
		return $this->db->get($this->tabel);
	}

	public function arr_data_by_prov($getProv)
	{
		$this->db->select('id_kota_asal,kota_asal');
		$this->db->where('id_provinsi', $getProv);
		return $this->db->get($this->tabel)->result_array();
	}

	public function printOpsi()
	{
		$opsi = '';

		foreach ($this->getAll()->result() as $row) {
			$opsi .= '<option value="'. $row->id_kota_asal .'"> '. $row->kota_asal .' </option>';
		}

		return $opsi;
	}
	
	public function cek_sudah_pernah_input_belum($getKota, $getProv)
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where id_provinsi='". $getProv ."' and kota_asal='". $getKota ."'";
		return $this->db->query($cmd)->row()->total;
	}
	
	public function cek_edit_sudah_pernah_input_belum($getId, $getKota, $getProv)
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where id_kota_asal='". $getId ."' and id_provinsi='". $getProv ."' and kota_asal='". $getKota ."'";
		return $this->db->query($cmd)->row()->total;
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

	private function countPenyalurById($getId)
	{
		$cmd = "select count(*) as jml from t_penyalur_bbm where id_kota_asal='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($postId)
	{
		$count = $this->countPenyalurById($postId);
		if ( $count == 0 ) {
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
			return 'Maaf data kota tidak dapat dihapus karena sudah pernah melakukan penyimpanan di perusahaan penyalur !';
		}

	}

}