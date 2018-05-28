<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rekeningskpdlain extends CI_Model{

	private $tabel='n_rekening_skpd_lain';
	private $view = 'v_rekening_skpd_lain';
	private $keyId = 'id_rekening';

	public function tambah($arrPost)
	{
		$this->db->trans_begin();
		
		$this->db->insert($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function ubah($arrPost, $postId)
	{
		$this->db->trans_begin();
		
		$this->db->where($this->keyId, $postId);
		$this->db->update($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function hapus($postId)
	{
		if ( $this->count_sub_rekening($postId) == 0 ) {
			$this->db->trans_begin();
			
			$this->db->where($this->keyId, $postId);
			$this->db->delete($this->tabel);
					
			if ($this->db->trans_status() === FALSE):
				$this->db->trans_rollback();
				return $this->db->_error_message();
			else:
				$this->db->trans_commit();
			endif;
		} else {
			return 'MAAF DATA REKENING MEMPUNYAI SUB YANG AKTIF !';
		}
	}

	public function arr_tabel($cond, $sort)
	{
		$cmd = "select * from ". $this->view ." where ". $cond ." order by ". $sort;
		return $this->db->query($cmd)->result_array();
	}

	public function get_data_by_id($getId)
	{
		$cmd = "select * from ". $this->tabel ." where ". $this->keyId ."='". $getId ."'";
		return $this->db->query($cmd);
	}
	
	public function get_rekening_by_lokasi($get_lokasi)
	{
		$cmd = "select * from ". $this->tabel ." where id_skpd='". $get_lokasi ."'";
		return $this->db->query($cmd);
	}
	
	public function get_detil_by_id($get_id)
	{
		$cmd = "select * from ". $this->view ." where id_rekening='". $get_id ."'";
		return $this->db->query($cmd);
	}
	
	private function count_sub_rekening($id)
	{
		$cmd = "select count(*) as total from ". $this->tabel ." where sub_rekening='". $id ."'";
		return $this->db->query($cmd)->row()->total;
	}
	
	private function get_data_by_lokasi($get_lokasi)
	{
		$cmd = "SELECT 
  id_rekening,id_skpd,no_rekening2,nama_rekening,a.`sub_rekening`,
  (
  SELECT COUNT(id_rekening) FROM n_rekening_skpd_lain b WHERE b.sub_rekening=a.`id_rekening`
  ) AS anakan
FROM
  v_rekening_skpd_lain a WHERE id_lokasi='". $get_lokasi ."' AND aktif=1";
		return $this->db->query($cmd);
	}
	
	public function arr_data_by_id_skpd($get_lokasi)
	{
		$arr = array();
		foreach ( $this->get_data_by_lokasi($get_lokasi)->result() as $row )
		{
			$arr[$row->id_skpd][] = $row;
		}
		return $arr;
	}
	
	public function arr_data_by_id_parent($get_skpd)
	{
		$data = array();
		$cmd = "SELECT 
		  id_rekening,no_rekening,no_rekening2,nama_rekening,sub_rekening,
		  (
		  SELECT COUNT(id_rekening) FROM n_rekening_skpd_lain b WHERE b.sub_rekening=a.`id_rekening`
		  ) AS anakan
		FROM
		  v_rekening_skpd_lain a WHERE id_skpd='". $get_skpd ."' AND aktif=1 order by no_rekening2";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$data[$row->sub_rekening][] = $row;
		}
		return $data;
	}
	
	public function arr_total_entrian($get_lokasi, $b)
	{
		$arr = array();
		foreach ( $this->get_data_by_lokasi($get_lokasi)->result() as $row )
		{
			$val = 0;
			foreach ( $b as $row_b )
			{
				if ( stripos($row_b->no_rekening2, $row->no_rekening2) !== false )
				{
					$val += $row_b->tot_jumlah;
				}
			}
			$arr[$row->id_rekening] = array('jml'=>$val);
		}
		return $arr;
	}
	
	public function laporan_rd02($param_th, $param_bln, $param_lok, $param_target)
	{
		$print = '';
		$rows_skpd_lain = $this->Skpdlain->get_data_by_cond("id_skpd,nama_skpd", " where id_lokasi='". $param_lok ."'");
		$get_entrian = $this->Rekamretribusiskpdlain->get_entrian($param_th, $param_bln, $param_lok)->result();
		$arr_entrian = $this->arr_total_entrian($param_lok, $get_entrian);
		$get_entrian_sd_bln_lalu = $this->Rekamretribusiskpdlain->get_entrian_sd_bln_lalu($param_th, $param_bln, $param_lok)->result();
		$arr_entrian_sd_bln_lalu = $this->arr_total_entrian($param_lok, $get_entrian_sd_bln_lalu);
		$get_target = $this->Targetretribusiskpdlain->get_target($param_th, $param_target, $param_lok)->result();
		$arr_target = $this->arr_total_entrian($param_lok, $get_target);
		
		foreach ( $rows_skpd_lain->result() as $skpd )
		{
			$get_rekening_by_tahun = $this->arr_data_by_id_parent($skpd->id_skpd);

			$print .= '<tr>';
			$print .= '<td width="20%"></td>';
			$print .= '<td width="40%"><b>'. $skpd->nama_skpd .'</b></td>';
			$print .= '<td width="8%"></td>';
			$print .= '<td width="8%"></td>';
			$print .= '<td width="8%"></td>';
			$print .= '<td width="8%"></td>';
			$print .= '<td width="5%"></td>';
			$print .= '</tr>';
			if( count($get_rekening_by_tahun) > 0 ) {
				$print .= $this->tabel_laporan($get_rekening_by_tahun,  0, $arr_entrian, $arr_entrian_sd_bln_lalu, $arr_target);
			}
		}
		return $print;
	}
	
	private function tabel_laporan($data, $parent = 0, $arr_entrian, $arr_entrian_sd_bln_lalu,$arr_target)
	{
		static $i = 1;
		if ($data[$parent]) {
			$i++;
			
			foreach ($data[$parent] as $v) {
				
				$tot_sd_bln_ini = $arr_entrian_sd_bln_lalu[$v->id_rekening]['jml'] + $arr_entrian[$v->id_rekening]['jml'];
				$persen_sd_bln_ini = $tot_sd_bln_ini == 0 || $arr_target[$v->id_rekening]['jml'] == 0 ? 0 : ( $tot_sd_bln_ini / $arr_target[$v->id_rekening]['jml'] ) * 100;
				$html2 .= '<tr>
					<td width="20%">'. $v->no_rekening2 .'</td>
					<td width="40%">'. $v->nama_rekening .'</td>
					<td align="right" width="8%">'. number_format($arr_target[$v->id_rekening]['jml'],0) .'</td>
					<td align="right" width="8%">'. number_format($arr_entrian_sd_bln_lalu[$v->id_rekening]['jml'],0) .'</td>
					<td align="right" width="8%">'. number_format($arr_entrian[$v->id_rekening]['jml'],0) .'</td>
					<td align="right" width="8%">'. number_format($tot_sd_bln_ini,0) .'</td>
					<td align="center" width="5%">'. number_format($persen_sd_bln_ini,2) .'</td>
				</tr>';
				$child = $this->tabel_laporan($data, $v->id_rekening, $arr_entrian, $arr_entrian_sd_bln_lalu,$arr_target);
				if ($child) {
					$i--;
					$html2 .= $child;
				}
			}
			return $html2;
		} else {
			return false;
		}
	}
}