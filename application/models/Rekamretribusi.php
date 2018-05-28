<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rekamretribusi extends CI_Model{

	public function find_transaction($getth, $getbulan, $getlokasi)
	{
		$cmd = "SELECT * FROM n_entry_retribusi WHERE id_anggaran='". $getth ."' AND id_lokasi='". $getlokasi ."' AND id_bulan='". $getbulan ."'";
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
	
	public function act_create($arrEntry, $arrItem)
	{
		$this->db->trans_begin();
		
		$this->db->insert('n_entry_retribusi', $arrEntry);
		
		$i = 0;
		foreach ( $arrItem as $r )
		{
			if ( $r[2] > 0 || $r[3] > 0 )
			{
				$dataitem = array(
					'id_item' => $arrEntry['id_rekam'].$i,
					'id_rekam' => $arrEntry['id_rekam'],
					'kd_rekening' => $r[1],
					'oby' => $r[2],
					'jumlah' => $r[3]
				);
				$this->db->insert('r_item_rekam_retribusi', $dataitem);
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
	
	public function act_update($id_update, $arrEntryUpdate, $arrItem)
	{
		$arr_kode_rekening = $this->arr_kode_item_by_id_rekam($id_update);
		
		$this->db->trans_begin();
		$this->db->where('id_rekam', $id_update);
		$this->db->update('n_entry_retribusi', $arrEntryUpdate);
		$i = 0;
		foreach ( $arrItem as $r )
		{
			if ( in_array($r[1], $arr_kode_rekening) )
			{
				$dataitem = array(
					'oby' => $r[2],
					'jumlah' => $r[3]
				);
				$this->db->where("id_rekam = '". $id_update ."' and kd_rekening = '". $r[1] ."'");
				$this->db->update('r_item_rekam_retribusi', $dataitem);
			}
			else
			{
				if ( $r[2] > 0 || $r[3] > 0 )
				{
					$dataitem = array(
						'id_item' => time().$i,
						'id_rekam' => $id_update,
						'kd_rekening' => $r[1],
						'oby' => $r[2],
						'jumlah' => $r[3]
					);
					$this->db->insert('r_item_rekam_retribusi', $dataitem);
				}
			}
			
			$i++;
		}
		$this->db->where("id_rekam = '". $id_update ."' and (jumlah<=0 or oby<=0)");
		$this->db->delete('r_item_rekam_retribusi');
		
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			return $this->db->trans_commit();
		endif;
	}
	
	public function delete_act($getth, $getbulan, $getlokasi)
	{
		
		$cek = $this->find_transaction($getth, $getbulan, $getlokasi)->num_rows();
		if ( $cek == 1 ) {
			$this->db->trans_begin();
			
			$this->db->where("id_anggaran='". $getth ."' AND id_lokasi='". $getlokasi ."' AND id_bulan='". $getbulan ."'");
			$this->db->delete('n_entry_retribusi');
			
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				return $this->db->trans_commit();
			endif;
		} else {
			return 'Data entrian tidak dapat ditemukan !';
		}
	}
	
	private function arr_kode_item_by_id_rekam($id_rekam)
	{
		$arr = array();
		$cmd = "select kd_rekening from r_item_rekam_retribusi where id_rekam='". $id_rekam ."'";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[] = $row->kd_rekening;
		};
		return $arr;
	}
	
	public function get_entrian($get_anggaran, $get_bln, $get_lokasi)
	{
		$cmd = "SELECT kd_rekening,SUM(oby) AS tot_oby,SUM(jumlah) AS tot_jumlah FROM v_entrian_retribusi 
WHERE id_anggaran='". $get_anggaran ."' AND id_bulan=". $get_bln ." AND id_lokasi='". $get_lokasi ."' group by kd_rekening";
		return $this->db->query($cmd);
	}
	
	public function get_entrian_sd_bln_lalu($get_anggaran, $get_bln, $get_lokasi)
	{
		$cmd = "SELECT kd_rekening,SUM(oby) AS tot_oby,SUM(jumlah) AS tot_jumlah FROM v_entrian_retribusi 
WHERE id_anggaran='". $get_anggaran ."' AND id_bulan<". $get_bln ." AND id_lokasi='". $get_lokasi ."' group by kd_rekening";
		return $this->db->query($cmd);
	}

}
