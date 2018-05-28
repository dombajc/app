<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setkoderetribusi extends CI_Model{
	
	private $tabel = 't_set_kode_retribusi_per_user_per_tahun';
	
	public function get_by_user_and_thn($id_user, $id_tahun)
	{
		$cmd = "select kd_rekening from ". $this->tabel ." where id_user='". $id_user ."' and id_anggaran='". $id_tahun ."'";
		return $this->db->query($cmd);
	}
	
	public function get_result($id_user, $id_tahun)
	{
		$arr = array();
		
		foreach ( $this->get_by_user_and_thn($id_user, $id_tahun)->result() as $row )
		{
			$arr[$row->kd_rekening] = 0 ;
		};
		return $arr;
	}
	
	public function get_arr_kode_rekening_by_tahun($get_th)
	{
		$arr = array();
		$cmd = "select kd_rekening from ". $this->tabel ." where id_user='". $this->session->userdata('id_user') ."' and id_anggaran='". $get_th ."'";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[] = $row->kd_rekening;
		}
		return $arr;
	}
	
	public function save_form($arrpost)
	{
		$getid = $arrpost['getid'];
		$getthn = $arrpost['idthn'];
		$getuser = $arrpost['iduser'];
		$getkoderekening = $arrpost['arrkoderekening'];
		
		$this->db->trans_begin();
		
		$cmd_hapus = "DELETE FROM ". $this->tabel ." WHERE id_user='". $getuser ."' AND id_anggaran='". $getthn ."'";
		$this->db->query($cmd_hapus);
		
		$i = 0;
		foreach ( $getkoderekening as $kode )
		{
			$data = array(
				'id_set' => $getid.$i,
				'id_user' => $getuser,
				'id_anggaran' => $getthn,
				'kd_rekening' => $kode
			);
			
			$this->db->insert($this->tabel, $data);
			$i++;
		}
		
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

}