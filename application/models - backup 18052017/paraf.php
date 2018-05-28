<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Paraf extends CI_Model{

	private $tabel='t_paraf';

	public function getAllByNamaLaporan()
	{
		$this->db->where('nama_laporan','rd2d');
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function tambah($arrPost, $postLokasi)
	{
		
		$this->db->trans_begin();
		
		$this->db->query("delete from t_data_setting_paraf where id_lokasi='". $postLokasi ."'");
		$this->db->insert_batch('t_data_setting_paraf', $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function delete($postLokasi){
		
		$this->db->trans_begin();
		$this->db->where('id_lokasi',$postLokasi);
		$this->db->delete('t_data_setting_paraf');
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;		
		
	}

	public function getDataSettingParaf($postLokasi)
	{
		$cmd = "SELECT
			t_data_setting_paraf.id_paraf,
			id_pegawai
			FROM
			t_data_setting_paraf
			LEFT JOIN t_paraf ON
			t_paraf.`id_paraf`=t_data_setting_paraf.`id_paraf`
			WHERE id_lokasi='". $postLokasi ."' AND t_paraf.`nama_laporan`='rd2d'";

		return $this->db->query($cmd)->result_array();
	}

	public function getParafLaporan($postLaporan, $postLokasi, $postPosisi)
	{
		if ( $postLokasi == '05' || $postLokasi == '' || $postLokasi == '99' ) {
			return '';
		} else {
			$cmd = "SELECT
				t_paraf.`nama_paraf`,v_pegawai_v2.`nama_pegawai`,v_pegawai_v2.`nip`,v_pegawai_v2.`pangkat`
				FROM
				t_paraf
				LEFT JOIN t_data_setting_paraf ON
				t_data_setting_paraf.`id_paraf`=t_paraf.`id_paraf`
				LEFT JOIN v_pegawai_v2 ON
				v_pegawai_v2.`id_pegawai`=t_data_setting_paraf.`id_pegawai`
				WHERE
				t_paraf.`nama_laporan`='". $postLaporan ."' AND t_data_setting_paraf.`id_lokasi`='". $postLokasi ."' and t_paraf.posisi='". $postPosisi ."'";

			$query = $this->db->query($cmd);
			if ( $query->num_rows() == 1 ) {
				$row = $query->row();
				return $row->nama_paraf .'<br><br><br><br><br><br><span style="text-decoration: underline;">'. $row->nama_pegawai .'</span><br>'. $row->pangkat .'<br>NIP. '. $row->nip;
			} else {
				return 'Pejabat Penanda tangan belum di set !';
			}
		}
		
	}

	public function getParafonlyKaUpad($getlokasi)
	{
		$cmd = "SELECT
v_pegawai_v2.`nama_pegawai`,v_pegawai_v2.`nip`,v_pegawai_v2.pangkat
FROM
t_data_setting_paraf
LEFT JOIN v_pegawai_v2 ON
v_pegawai_v2.`id_pegawai`=t_data_setting_paraf.`id_pegawai` where id_paraf='01rd2d' and t_data_setting_paraf.id_lokasi='". $getlokasi ."'";
		return $this->db->query($cmd)->row();
	}

	// update script 27082016

	public function getParafLaporan2($postLaporan, $postLokasi, $postPosisi)
	{
		if ( $postLokasi == '05' || $postLokasi == '' || $postLokasi == '99' ) {
			return '';
		} else {
			$cmd = "SELECT
				t_paraf.`nama_paraf`,v_pegawai_v2.`nama_pegawai`,v_pegawai_v2.`nip`,v_pegawai_v2.`pangkat`
				FROM
				t_paraf
				LEFT JOIN t_data_setting_paraf ON
				t_data_setting_paraf.`id_paraf`=t_paraf.`id_paraf`
				LEFT JOIN v_pegawai_v2 ON
				v_pegawai_v2.`id_pegawai`=t_data_setting_paraf.`id_pegawai`
				WHERE
				t_paraf.`nama_laporan`='". $postLaporan ."' AND t_data_setting_paraf.`id_lokasi`='". $postLokasi ."' and t_paraf.posisi='". $postPosisi ."'";

			$query = $this->db->query($cmd);
			if ( $query->num_rows() == 1 ) {
				$row = $query->row();
				return '<span style="text-decoration: underline;">'. $row->nama_pegawai .'</span><br>NIP. '. $row->nip;
			} else {
				return 'Pejabat Penanda tangan belum di set !';
			}
		}
		
	}

	public function getParafPD($postLaporan, $postLokasi, $postPosisi)
	{
		$arr = array();
		$detil_jabatan = '';
		$detil_nama = '';

		if ( $postLokasi == '05' || $postLokasi == '' || $postLokasi == '99' ) {
			//return '';
		} else {
			$cmd = "SELECT
				t_paraf.`nama_paraf_pd`,v_pegawai_v2.`nama_pegawai`,v_pegawai_v2.`nip`,v_pegawai_v2.`pangkat`
				FROM
				t_paraf
				LEFT JOIN t_data_setting_paraf ON
				t_data_setting_paraf.`id_paraf`=t_paraf.`id_paraf`
				LEFT JOIN v_pegawai_v2 ON
				v_pegawai_v2.`id_pegawai`=t_data_setting_paraf.`id_pegawai`
				WHERE
				t_paraf.`nama_laporan`='". $postLaporan ."' AND t_data_setting_paraf.`id_lokasi`='". $postLokasi ."' and t_paraf.posisi='". $postPosisi ."'";

			$query = $this->db->query($cmd);
			if ( $query->num_rows() == 1 ) {
				$row = $query->row();
				$detil_jabatan = $row->nama_paraf_pd;
				$detil_nama = '<span style="text-decoration: underline;">'. $row->nama_pegawai .'</span><br>NIP. '. $row->nip;
			} else {
				$detil_nama = 'Pejabat Penanda tangan belum di set !';
			}
			$arr['detil_jabatan'] = $detil_jabatan;
			$arr['detil_nama'] = $detil_nama;
			return $arr;
		}
	}

}