<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Targetretribusiskpdlain extends CI_Model{

	private $tabel = 'e_target_retribusi_skpd_lain';
	private $item_tabel = 'i_e_target_retribusi_skpd_lain';
	private $view = 'v_i_e_target_retribusi_skpd_lain';
	
	public function find_entrian($getth, $gettarget, $getlokasi)
	{
		$cmd = "SELECT
a.*,b.`nama_user`
FROM ". $this->tabel ." a
LEFT JOIN t_users b ON b.`id_user`=a.`id_user` WHERE id_anggaran='". $getth ."' AND a.id_lokasi='". $getlokasi ."' AND id_target='". $gettarget ."'";
		return $this->db->query($cmd);
	}
	
	public function arr_item_value($id)
	{
		$arr = array();
		$cmd = "SELECT * FROM r_item_rekam_retribusi WHERE id_rekam='". $id ."'";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->kd_rekening] = $row;
		}
		return $arr;
	}
	
	public function save($arrEntry, $arrItem)
	{
		$this->db->trans_begin();
		
		$this->hapus($arrEntry['id_anggaran'], $arrEntry['id_target'], $arrEntry['id_lokasi']);

		$this->db->insert($this->tabel, $arrEntry);
		
		$i = 0;
		foreach ( $arrItem as $r )
		{
			if ( $r[2] > 0 )
			{
				$dataitem = array(
					'id_item' => $arrEntry['id_entri'].$i,
					'id_entri' => $arrEntry['id_entri'],
					'id_rekening' => $r[1],
					'jumlah' => $r[2]
				);
				$this->db->insert($this->item_tabel, $dataitem);
				$i++;
			}
		}
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			return $this->db->trans_commit();
		endif;
	}
	
	public function hapus($getth, $gettarget, $getlokasi)
	{
		$this->db->trans_begin();
		
		$this->db->where("id_anggaran='". $getth ."' AND id_lokasi='". $getlokasi ."' AND id_target='". $gettarget ."'");
		$this->db->delete($this->tabel);
		
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			return $this->db->trans_commit();
		endif;
	}
	
	public function arr_entrian_by_rekening($get_anggaran, $get_target, $get_lokasi)
	{
		$arr = array();
		foreach ( $this->get_target($get_anggaran, $get_target, $get_lokasi)->result() as $row )
		{
			$arr[$row->id_rekening] = $row->tot_jumlah;
		};
		return $arr;
	}
	
	public function get_target($get_anggaran, $get_target, $get_lokasi)
	{
		$cmd = "SELECT a.id_rekening,no_rekening2,SUM(jumlah) AS tot_jumlah FROM ". $this->view ." a WHERE id_anggaran='". $get_anggaran ."' AND id_target=". $get_target ." AND a.id_lokasi='". $get_lokasi ."' group by a.id_rekening";
		return $this->db->query($cmd);
	}

}
