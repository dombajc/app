<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Triwulan extends CI_Model{

	private $tabel='t_triwulan';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function optionSelect()
	{
		$opsi = '';
		foreach ( $this->getAllData()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_triwulan .'"> '. $row->triwulan .' </option>';
		}
		return $opsi;
	}

	public function getDataByID($ID)
	{
		$cmd = "select * from ". $this->tabel ." where id_triwulan='". $ID ."'";
		return $this->db->query($cmd);
	}

	public function opsiOrderByBulanSekarang()
	{
		$cmd = "SELECT
			t_triwulan.`id_triwulan`, triwulan,
			(SELECT
			IF(char_bulan=MONTH(NOW()),1,0) AS sts
			FROM
			t_bulan
			WHERE t_bulan.`id_triwulan`=t_triwulan.`id_triwulan`
			ORDER BY sts DESC LIMIT 1
			) AS triwulan_ini
			FROM
			t_triwulan
			ORDER BY triwulan_ini DESC";

		$query = $this->db->query($cmd);
		$opsi = '';
		foreach ( $query->result() as $row ) {
			$opsi .= '<option value="'. $row->id_triwulan .'"> '. $row->triwulan .' </option>';
		}
		return $opsi;
	}

}