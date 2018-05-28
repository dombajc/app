<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trxd2d extends CI_Model{

	private $tabel='t_trx_d2d';

	public function countTransaksiByIdPegawai($id_pegawai)
	{
		$cmd = "SELECT COUNT(*) AS total FROM t_trx_d2d
			LEFT JOIN t_mutasi ON
			t_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE id_pegawai='". $id_pegawai ."'";
		return $this->db->query($cmd)->row()->total;
	}

	public function getTransaksiInput($postAnggaran, $postPegawai)
	{
		$array = array();
		$cmd = "SELECT 
			t_trx_d2d.`id_bulan`,
			t_trx_d2d.`jumlah`,
			t_mutasi.`id_lokasi`,
			t_mutasi.`id_jabatan`
			FROM
			t_trx_d2d
			LEFT JOIN t_mutasi ON
			t_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE
			t_trx_d2d.`id_anggaran`='". $postAnggaran ."' and t_mutasi.id_pegawai='". $postPegawai ."'";

		foreach( $this->db->query($cmd)->result() as $row ) {
			$array[$row->id_bulan]['id_lokasi'] = $row->id_lokasi;
			$array[$row->id_bulan]['id_jabatan'] = $row->id_jabatan;
			$array[$row->id_bulan]['jumlah'] = $row->jumlah;
		}

		return $array;
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