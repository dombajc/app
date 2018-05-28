<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Trxpd extends CI_Model{

	private $tabel='t_pungut_pd';

	public function getTransaksiInput($postAnggaran, $postLokasi, $postBulan)
	{
		$array = array();
		$cmd = "SELECT
			id_rek_pd,jumlah
			FROM
			t_pungut_pd
			where id_lokasi='". $postLokasi ."' and id_anggaran='". $postAnggaran ."' and id_bulan='". $postBulan ."'";

		foreach( $this->db->query($cmd)->result() as $row ) {
			$array[$row->id_rek_pd]['jumlah'] = $row->jumlah;
		}

		return $array;
	}

	public function getArrPungutan($postAnggaran)
	{
		$arr = array();

		$cmd = "SELECT
			id_anggaran,
			t_bulan.`id_triwulan`,
			t_rek_pd.`id_rek_pd`,
			SUM(t_pungut_pd.`jumlah`) AS realisasi
			FROM
			t_pungut_pd
			LEFT JOIN t_rek_pd ON
			LEFT(t_pungut_pd.`id_rek_pd`,CHAR_LENGTH(t_rek_pd.`id_rek_pd`))=t_rek_pd.`id_rek_pd`
			LEFT JOIN t_bulan ON
			t_bulan.`id_bulan`=t_pungut_pd.`id_bulan`
			WHERE id_anggaran='". $postAnggaran ."' and t_rek_pd.`sub_rek_pd`='00'
			GROUP BY id_anggaran,t_bulan.`id_triwulan`,t_rek_pd.`id_rek_pd`";

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_triwulan][$row->id_rek_pd]['realisasi'] = $row->realisasi;
		}

		return $arr;
	}

	public function getArrForLaporanObyekPerUpad($postTriwulan, $postAnggaran, $postJenisLaporan)
	{
		$arr = array();

		$cmd = "SELECT id_lokasi,id_bulan,SUM(realisasi) AS realisasi FROM v_list_pungut_pd WHERE id_triwulan='". $postTriwulan ."' and id_anggaran='". $postAnggaran ."' and id_rek_pd='". $postJenisLaporan ."'
		GROUP BY id_rek_pd,id_lokasi,id_bulan
		ORDER BY id_rek_pd";

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_lokasi][$row->id_bulan]['realisasi'] = $row->realisasi;
		}

		return $arr;
	}

	public function getArrForLaporanObyekPerUpadKhususBBNKB($postTriwulan, $postAnggaran)
	{
		$arr = array();

		$cmd = "select * from v_list_pungut_pd where id_triwulan='". $postTriwulan ."' and id_anggaran='". $postAnggaran ."' and id_rek_pd='02'";

		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_rek_pd_pungut][$row->id_lokasi][$row->id_bulan]['realisasi'] = $row->realisasi;
		}

		return $arr;
	}

}