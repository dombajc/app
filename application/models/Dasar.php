<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dasar extends CI_Model{

	private $tabel='t_dasar_transaksi_pbbkb';

	public function getAllData()
	{
		//$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function getEntryOnlyBadanUsaha()
	{
		$radio = '';
		$this->db->where('entrybu', 1);
		$q = $this->db->get( $this->tabel );

		foreach ( $q->result() as $row ) {
			$radio .= '<label class="radio-inline"><input type="radio" name="radDasar" id="radDasar_'. $row->id_dasar_trx_pbbkb .'" value="'. $row->id_dasar_trx_pbbkb .'" class="filter" '. $row->default_check .'> '. $row->dasar_trx_pbbkb .'</label>';
		}
		return $radio;
	}

	public function printRadioButton()
	{
		$radio = '';
		foreach ( $this->getAllData()->result() as $row ) {
			$radio .= '<label class="radio-inline"><input type="radio" name="radDasar" id="radDasar_'. $row->id_dasar_trx_pbbkb .'" value="'. $row->id_dasar_trx_pbbkb .'" class="filter" '. $row->default_check .'> '. $row->dasar_trx_pbbkb .'</label>';
		}
		return $radio;
	}

	public function getDataById($getId)
	{
		$cmd = "select * from ". $this->tabel ." where id_dasar_trx_pbbkb='". $getId ."'";
		return $this->db->query($cmd)->row();
	}

}