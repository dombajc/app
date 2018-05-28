<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entriandistribusi extends CI_Model{

	private $tabel = 't_entry_distribusi_bbm';
	private $item = 't_item_distribusi_bbm';
	private $view = 'v_entry_distribusi';
	private $keyId = 'id_distribusi_bbm';

	public function getDataById($getId)
	{
		$cmd = "SELECT
			id_distribusi_bbm,id_lokasi,id_lokasi_pbbkb,id_anggaran,id_bulan,tgl_entry,id_provinsi,id_kota_asal,id_penyalur
			FROM ". $this->view ." where ". $this->keyId ."='". $getId ."'";
		return $this->db->query($cmd);
	}

	public function getItemEntrianById($getId)
	{
		$cmd = "select id_item_pbbkb,jumlah from t_item_distribusi_bbm where ". $this->keyId ."='". $getId ."'";
		return $this->db->query($cmd)->result_array();
	}

	private function cekRow($getId)
	{
		return $this->getDataById($getId)->num_rows();
	}

	public function tambah($arrGetDistribusi, $arrGetItem)
	{
		$this->db->trans_begin();

		$this->db->insert($this->tabel, $arrGetDistribusi);
		$this->db->insert_batch($this->item, $arrGetItem);

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function ubah($arrGetDistribusi, $arrGetItem, $getId)
	{
		if ( $this->cekRow($getId) == 1 )
		{
			$this->db->trans_begin();

			$this->db->where($this->keyId, $getId);
			$this->db->update($this->tabel, $arrGetDistribusi);

			//Hapus Item Transaksi Lama
			$this->db->where($this->keyId, $getId);
			$this->db->delete($this->item);

			$this->db->insert_batch($this->item, $arrGetItem);

			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'Maaf Data tidak di ketemukan. Kemungkinan data telah di hapus. Silahkan ulangi cari tabel !';
		}
	}

	public function hapus($getId)
	{
		$this->db->trans_begin();

		$this->db->where($this->keyId, $getId);
		$this->db->delete($this->tabel);

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}
	
	public function arrGroupTransaksi($cond)
	{
		$arr = array();
		$cmd = "SELECT id_lokasi,id_bulan,COUNT(id_distribusi_bbm) AS jml FROM v_entry_distribusi ". $cond ." GROUP BY id_lokasi,id_bulan";
		foreach( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi][$row->id_bulan] = $row->jml;
		}
		return $arr;
	}
	
	public function arrGrupByLokasi($cond)
	{
		$arr = array();
		$cmd = "SELECT id_lokasi,COUNT(id_distribusi_bbm) AS jml FROM v_entry_distribusi ". $cond ." GROUP BY id_lokasi";
		foreach( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi] = $row->jml;
		}
		return $arr;
	}

}