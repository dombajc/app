<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trxd2d extends CI_Model{

	private $tabel='t_trx_d2d';

	public function countTransaksiByIdPegawai($id_pegawai)
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where id_riwayat_lokasi='". $id_pegawai ."'";
		return $this->db->query($cmd)->row()->total;
	}

	public function getTransaksiInput($id_triwulan, $id_riwayat_lokasi, $id_anggaran)
	{
		$cmd = "SELECT
			t_bulan.*,
			(
			SELECT IFNULL(SUM(jumlah),0) FROM t_trx_d2d
			WHERE t_trx_d2d.`id_anggaran`='". $id_anggaran ."' AND t_trx_d2d.`id_bulan`=t_bulan.`id_bulan`
			AND t_trx_d2d.`id_riwayat_lokasi`='". $id_riwayat_lokasi ."'
			) AS jumlah
			FROM
			t_bulan
			LEFT JOIN t_triwulan ON
			t_triwulan.`id_triwulan`=t_bulan.`id_triwulan`
			WHERE
			t_triwulan.`id_triwulan`='". $id_triwulan ."'";

		return $this->db->query($cmd);
	}

	public function tambah($arrData, $array, $id_riwayat_lokasi, $id_anggaran){
		
		$this->db->trans_begin();

		foreach ( $array as $del ) {
			$this->db->query("DELETE FROM t_trx_d2d	WHERE id_bulan ='". $del[1] ."'
			AND t_trx_d2d.`id_riwayat_lokasi`='". $id_riwayat_lokasi ."' 
			AND t_trx_d2d.`id_anggaran`='". $id_anggaran ."'");
		}

		if (count($arrData) > 0) {
			$this->db->insert_batch($this->tabel, $arrData);
		}
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	

}