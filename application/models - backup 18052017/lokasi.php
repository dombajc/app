<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lokasi extends CI_Model{

	private $tabel='lokasi';
	private $view = 'v_lokasi_upad';

	private function getAll()
	{
		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk AND LEFT(id_lokasi,CHAR_LENGTH('". $this->Opsisite->getDataUser()['id_lokasi'] ."'))='". $this->Opsisite->getDataUser()['id_lokasi'] ."'";
		return $this->db->query($cmd);
	}

	public function getAllPusat()
	{
		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk AND id_lokasi='05'";
		return $this->db->query($cmd);
	}

	public function showAllUpad()
	{
		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk AND id_lokasi!='05'";
		return $this->db->query($cmd);
	}

	private function getAllUpad()
	{
		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk AND id_lokasi!='05' AND LEFT(id_lokasi,CHAR_LENGTH('". $this->Opsisite->getDataUser()['id_lokasi'] ."'))='". $this->Opsisite->getDataUser()['id_lokasi'] ."'";
		return $this->db->query($cmd);
	}
	
	public function optionAllPusat()
	{
		$opsi = '';
		foreach($this->getAllPusat()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function optionAllUpad()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value=""> -- Pilih salah satu -- </option>' : '';
		foreach($this->getAllUpad()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function opsiAllUpadViewInputanRekamD2D()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value=""> -- Keseluruhan -- </option>' : '';
		foreach($this->getAllUpad()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function opsiLaporan()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value="99"> -- Keseluruhan -- </option>' : '';
		foreach($this->getAll()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function opsiLaporanOnlyDaerah()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value="99"> -- Keseluruhan -- </option>' : '';
		foreach($this->getAllUpad()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function opsiLaporanUpad()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value="99"> -- Pilih salah satu -- </option>' : '';
		foreach($this->getAllUpad()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function optionAll()
	{
		$opsi = '';
		foreach($this->getAll()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	//Opsi pilihan untuk mutasi masuk
	public function opsiLokasiMutasiMasuk()
	{
		$opsi = $this->Opsisite->getDataUser()['id_lokasi'] == '05' ? '<option value=""> -- Keseluruhan -- </option>' : '';
		foreach($this->getAll()->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}
	
	public function select_opotion_mutasi_keluar()
	{
		$opsi = '';
		
		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk";
		foreach($this->db->query($cmd)->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function getInduknSamtu($postLokasi)
	{
		$cmd = "select * from v_homebase where id_induk='". $postLokasi ."'";
		return  $this->db->query($cmd)->result_array();
	}

	public function pilihanLokasiHomebase()
	{
		$opsi = '';
		$cmd = "select id_lokasi,lokasi from v_homebase";
		$q = $this->db->query($cmd);
		foreach($q->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function opsiSementara()
	{
		$opsi = '';

		$cmd = "SELECT
			id_lokasi, lokasi
			FROM
			lokasi
			WHERE
			id_lokasi=id_induk AND id_lokasi!='05'";
		$q = $this->db->query($cmd);

		foreach($q->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_lokasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function getDataByIdLokasi($postLokasi)
	{
		$cmd = "select * from ". $this->tabel ." where id_lokasi='". $postLokasi ."'";
		return $this->db->query($cmd);
	}

	public function getListUpad()
	{
		$cmd = "select * from v_lokasi_upad order by id_lokasi";
		return $this->db->query($cmd)->result_array();
	}

	public function getLokasiD2D( $postHomebase )
	{
		if ( $postHomebase == '05' ) {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE d2d_pusat=1";
		} else {
			$cmd = "SELECT id_lokasi,lokasi FROM lokasi WHERE id_lokasi=left('". $postHomebase ."', 4)";
		}
		return $this->db->query($cmd);
	}

	public function pilihanBulanRekamD2D($getIdPegawai, $getIdTriwulan, $getbulan, $gettahun)
	{
		$opsi = '';

		//get Bulan minimal Triwulan
		$getMinBulan = $this->Bulan->getBulanPertama($getIdTriwulan);

		$cmd = "SELECT id_mutasi,lokasi.lokasi FROM v_mutasi_keluar 
LEFT JOIN lokasi ON
lokasi.id_lokasi=v_mutasi_keluar.id_lokasi
WHERE v_mutasi_keluar.`id_pegawai`='". $getIdPegawai ."' AND (MONTH(v_mutasi_keluar.`tgl_sk`) BETWEEN ". $getMinBulan ." AND ". $getbulan .") AND YEAR(v_mutasi_keluar.tgl_sk)>='". $gettahun ."'
ORDER BY tgl_sk DESC";
		$q = $this->db->query($cmd);

		foreach($q->result() as $row)
		{
			$opsi .= '<option value="'. $row->id_mutasi .'"> '. $row->lokasi .' </option>';
		}
		return $opsi;
	}

	public function getDataById($paramId)
	{
		$cmd = "select * from ". $this->tabel ." where id_lokasi='". $paramId ."'";
		return $this->db->query($cmd);
	}

	//script update 24 08 2016
	public function arrLokasiIndukDanSamtu()
	{
		$cmd = "SELECT * FROM v_data_lokasi ORDER BY sts DESC,id_lokasi";
		return $this->db->query($cmd)->result_array();
	}
}