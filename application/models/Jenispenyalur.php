<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jenispenyalur extends CI_Model{

	private $tabel='t_jenis_penyalur_pbbkb';
	private $IdKey = 'id_jenis_penyalur_pbbkb';

	public function getAllData()
	{
		//$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function printRadioButton()
	{
		$radio = '';
		foreach ( $this->getAllData()->result_array() as $row ) {
			$radio .= '<label class="radio-inline">
			  		<input type="radio" name="radJenis" id="radJenis_'. $row[$this->IdKey] .'" value="'. $row[$this->IdKey] .'" class="filter" '. $row['default_check'] .'> '. $row['jenis_penyalur_pbbkb'] .'
			  	</label>';
		}
		return $radio;
	}

	public function getDataById($getId)
	{
		$cmd = "select * from ". $this->tabel ." where id_dasar_trx_pbbkb='". $getId ."'";
		return $this->db->query($cmd)->row();
	}

	public function opsiPrint()
	{
		$opsi = '<option value=""> -- Keseluruhan -- </option>';

		foreach ( $this->getAllData()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_jenis_penyalur_pbbkb .'"> '. $row->jenis_penyalur_pbbkb .' </option>';
		}

		return $opsi;
	}

}