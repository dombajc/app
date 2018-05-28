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

	public function getTransaksiInput($postAnggaran, $postIdPegawai)
	{
		$array = array();
		$cmd = "SELECT 
			t_trx_d2d.`id_bulan`,
			t_trx_d2d.`jumlah`,
			t_mutasi.`id_mutasi`,
			t_mutasi.`id_jabatan`
			FROM
			t_trx_d2d
			LEFT JOIN t_mutasi ON
			t_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE
			t_trx_d2d.`id_anggaran`='". $postAnggaran ."' and t_mutasi.id_pegawai='". $postIdPegawai ."'";

		foreach( $this->db->query($cmd)->result() as $row ) {
			$array[$row->id_bulan]['id_mutasi'] = $row->id_mutasi;
			//$array[$row->id_bulan]['id_jabatan'] = $row->id_jabatan;
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

	public function getPersentaseDashboardUpadTertinggi( $postAnggaran, $postTriwulan)
	{
		$cmd = "SELECT
			v_lokasi_upad.`lokasi`,
			IFNULL((
			SELECT SUM(total) FROM v_list_target
			WHERE v_list_target.`id_lokasi`=v_lokasi_upad.`id_lokasi`
			AND `id_anggaran`='1458627189' AND `id_triwulan`='01'
			),0) AS total_target,
			IFNULL((
			SELECT SUM(jumlah) FROM v_list_transaksi
			WHERE v_list_transaksi.`id_lokasi`=v_lokasi_upad.`id_lokasi`
			AND `id_anggaran`='". $postAnggaran ."' AND `id_triwulan`='". $postTriwulan ."'
			),0) AS total_input
			FROM
			v_lokasi_upad
			ORDER BY total_input DESC,total_target DESC";

		return $this->db->query($cmd)->row();
	}

	public function getPersentaseDashboardUpadTerendah( $postAnggaran, $postTriwulan)
	{
		$cmd = "SELECT
			v_lokasi_upad.`lokasi`,
			IFNULL((
			SELECT SUM(total) FROM v_list_target
			WHERE v_list_target.`id_lokasi`=v_lokasi_upad.`id_lokasi`
			AND `id_anggaran`='1458627189' AND `id_triwulan`='01'
			),0) AS total_target,
			IFNULL((
			SELECT SUM(jumlah) FROM v_list_transaksi
			WHERE v_list_transaksi.`id_lokasi`=v_lokasi_upad.`id_lokasi`
			AND `id_anggaran`='". $postAnggaran ."' AND `id_triwulan`='". $postTriwulan ."'
			),0) AS total_input
			FROM
			v_lokasi_upad
			ORDER BY total_input,total_target";

		return $this->db->query($cmd)->row();
	}

	public function getRata( $postAnggaran, $postTriwulan, $postStatus='')
	{
		$getWhere = $postStatus == '' ? '' : " AND t_pegawai.`id_sts_pegawai`='". $postStatus ."'";

		$cmd = "SELECT
			IFNULL(AVG(t_trx_d2d.`jumlah`),0) AS ratarata
			FROM
			t_trx_d2d
			LEFT JOIN v_mutasi ON
			v_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			LEFT JOIN t_pegawai ON
			t_pegawai.`id_pegawai`=v_mutasi.`id_pegawai`
			LEFT JOIN v_bulan ON
			v_bulan.`id_bulan`=t_trx_d2d.`id_bulan`
			WHERE t_trx_d2d.`id_anggaran`='". $postAnggaran ."' AND v_bulan.`id_triwulan`='". $postTriwulan ."'". $getWhere;
	
		return $this->db->query($cmd)->row()->ratarata;
	}

	public function countTransaksiD2DPerMutasi($postId)
	{
		$cmd = "select count(*) as jml from ". $this->tabel ." where id_mutasi='". $postId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($getIdMutasi, $getBulan, $getAnggaran)
	{
		$this->db->trans_begin();
		$cmd = "delete from t_trx_d2d where id_mutasi='". $getIdMutasi ."' and id_bulan='". $getBulan ."' and id_anggaran='". $getAnggaran ."'";
		$this->db->query($cmd);
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function jml_transaksi_yg_telah_dilakukan($getIdMutasi)
	{
		$cmd = "select count(*) as jml from t_trx_d2d where id_mutasi='". $getIdMutasi ."'";
		return $this->db->query($cmd)->row()->jml;
	}

}