<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulan extends CI_Model{

	private $tabel='t_bulan';

	public function getAllData()
	{
		$this->db->order_by('urutan');
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function opsiSemuaBulan()
	{
		$opsi = '';
		foreach($this->getAllData()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_bulan .'"> '. $row->bulan .' </option>';
		}
		return $opsi;
	}

	public function getAllByIdTriwulan($id_triwulan)
	{
		$cmd = "select * from ". $this->tabel ." where id_triwulan='". $id_triwulan ."'";
		return $this->db->query($cmd)->result_array();
	}

	public function getBulanPerTriwulan($postTriwulan)
	{
		$cmd = "select * from ". $this->tabel ." where id_triwulan='". $postTriwulan ."'";
		return $this->db->query($cmd)->result();
	}

	public function getBulanPertama($ID)
	{
		$cmd = "select id_bulan from ". $this->tabel ." where id_triwulan='". $ID ."' limit 1";
		return $this->db->query($cmd)->row()->id_bulan;
	}

	public function getDataById($getId)
	{
		$cmd = "select * from ". $this->tabel ." where id_bulan='". $getId ."'";
		return $this->db->query($cmd)->row();
	}

	public function getGrupBulan($ID)
	{
		$cmd = "select min(id_bulan) as min,max(id_bulan) as max from ". $this->tabel ." where id_triwulan='". $ID ."' limit 1";
		return $this->db->query($cmd)->row();	
	}

	public function getBulanOrderByBulanAktif()
	{
		$cmd = "SELECT id_bulan,char_bulan,bulan,IF(char_bulan=MONTH(NOW()),1,0) AS skrg FROM t_bulan
ORDER BY skrg DESC, urutan";
		$opsi = '';
		foreach($this->db->query($cmd)->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_bulan .'"> '. $row->bulan .' </option>';
		}
		return $opsi;
	}

	public function inputbuland2d($getIdTriwulan, $getIdMutasi)
	{
		$getBulanAkhirMutasi = $this->Mutasi->getDataMutasiSebelumnya($getIdMutasi);
		$addFilter = $getBulanAkhirMutasi->num_rows() == 1 ? " and t_bulan.id_bulan < ". $getBulanAkhirMutasi->row()->bulan : "";

		$cmd = "SELECT
			t_bulan.id_bulan,
			t_bulan.bulan
			FROM
			t_bulan
			LEFT JOIN v_mutasi ON
			t_bulan.`id_bulan`>=MONTH(tgl_sk)". $addFilter ."
			WHERE id_triwulan='". $getIdTriwulan ."' AND id_mutasi='". $getIdMutasi ."'";

		return $this->db->query($cmd)->result();
	}

	// update 17 09 2016
	public function arr_per_semester($get_semester)
	{
		$cmd = "select * from ". $this->tabel ." where id_semester='". $get_semester ."'";
		return $this->db->query($cmd);
	}

}