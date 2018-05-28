<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Spbu extends CI_Model{

	private $tabel='t_penyetor_pbbkb';
	private $view = 'v_penyetor_pbbkb';
	private $keyId = 'id_lokasi_pbbkb';

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

	private function countEntrianById($getId)
	{
		$cmd = "select count(*) as jml from t_entry_distribusi_bbm where id_lokasi_pbbkb='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	private function countTransaksiById($getId)
	{
		$cmd = "select count(*) as jml from t_transaksi_pbbkb2 where id_lokasi_pbbkb='". $getId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($postId)
	{
		if ( $this->countEntrianById($postId) == 0 && $this->countTransaksiById($postId) == 0 ) {
			$this->db->trans_begin();
			
			$this->db->where($this->keyId, $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'Maaf Data SPBU tidak dapat dihapus karena sudah pernah melakukan entrian data !';
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

	public function getDataByIdLokasi($getIdLokasi, $getJenis='')
	{
		$cond = " where 1=1";
		$cond .= $getJenis == '' ? "" : " and id_jenis_penyalur_pbbkb='". $getJenis ."'";
		$cond .= $getIdLokasi == '99' ? "" : " and id_lokasi='". $getIdLokasi ."'";
		
		$cmd = "select id_lokasi_pbbkb,no_spbu,nama_spbu from ". $this->tabel . $cond ." order by no_spbu";
		return $this->db->query($cmd)->result_array();
	}

	public function arrJumlahSPBUperUpad()
	{
		$cmd = "SELECT
id_lokasi,COUNT(*) as jml
FROM
t_penyetor_pbbkb
WHERE id_jenis_penyalur_pbbkb='11'
GROUP BY id_lokasi";
		$arr = array();
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi] = $row->jml;
		}
		return $arr;
	}

	public function arrTransaksiSpbu($cond)
	{
		$cmd = "SELECT
id_lokasi,id_bulan,COUNT(*) as jml
FROM
v_arr_transaksi_spbu". $cond ."
GROUP BY id_lokasi,id_bulan";
		$arr = array();
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi][$row->id_bulan] = $row->jml;
		}
		return $arr;
	}
}