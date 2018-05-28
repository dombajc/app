<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Statuspegawai extends CI_Model{

	private $tabel='t_sts_pegawai';

	public function getAllData()
	{
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function printRadioButton()
	{
		$radio = '';
		foreach ( $this->getAllData()->result() as $row ) {
			$radio .= '<label class="radio-inline">
			  		<input type="radio" class="stspegawai" name="slctstatus" id="slctstatus_'. $row->id_sts_pegawai .'" value="'. $row->id_sts_pegawai .'" onchange="actGetStatus(this.value)"> '. $row->status_pegawai .'
			  	</label>';
		}
		return $radio;
	}

	public function selectoption()
	{
		$opsi = '<option value=""> Keseluruhan </option>';
		foreach ( $this->getAllData()->result() as $row ) {
			$opsi .= '<option value="'. $row->id_sts_pegawai .'">'. $row->status_pegawai .'</option>';
		}
		return $opsi;		
		
	}

}
