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
			id_pegawai,nm_plh,nip_plh,pangkat_plh,jabatan_plh
			FROM
			t_data_setting_paraf
			LEFT JOIN t_paraf ON
			t_paraf.`id_paraf`=t_data_setting_paraf.`id_paraf`
			WHERE id_lokasi='". $postLokasi ."'";
			// AND t_paraf.`nama_laporan`='rd2d'

		return $this->db->query($cmd)->result_array();
	}

	public function getParafLaporan($postLaporan, $postLokasi, $postPosisi)
	{
		if ( $postLokasi == '05' || $postLokasi == '' || $postLokasi == '99' ) {
			return '';
		} else {
			$cmd = "SELECT if(t_data_setting_paraf.id_pegawai='99',concat('Plt. ',t_paraf.nama_paraf),t_paraf.`nama_paraf`) as nama_paraf,if(t_data_setting_paraf.id_pegawai='99',nm_plh,v_pegawai_v2.`nama_pegawai`) as nama_pegawai,if(t_data_setting_paraf.id_pegawai='99',nip_plh,v_pegawai_v2.`nip`) as nip,if(t_data_setting_paraf.id_pegawai='99',pangkat_plh,v_pegawai_v2.`pangkat`) as pangkat,if(t_data_setting_paraf.id_pegawai='99',jabatan_plh,'') as jbtn_plt
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
				return $row->nama_paraf .'<br>'. $row->jbtn_plt .'<br><br><br><br><br><br><span style="text-decoration: underline;">'. $row->nama_pegawai .'</span><br>'. $row->pangkat .'<br>NIP. '. $row->nip;
			} else {
				return 'Pejabat Penanda tangan belum di set !';
			}
		}
		
	}

	public function getParafonlyKaUpad($getlokasi)
	{
		$cmd = "SELECT
if(t_data_setting_paraf.id_pegawai='99','Plt. ','') as paraf_plt,
if(t_data_setting_paraf.id_pegawai='99',nm_plh,v_pegawai_v2.`nama_pegawai`) as nama_pegawai,if(t_data_setting_paraf.id_pegawai='99',nip_plh,v_pegawai_v2.`nip`) as nip,if(t_data_setting_paraf.id_pegawai='99',pangkat_plh,v_pegawai_v2.`pangkat`) as pangkat,if(t_data_setting_paraf.id_pegawai='99',jabatan_plh,'') as jbtn_plt
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
				if(t_data_setting_paraf.id_pegawai='99',concat('Plt ',t_paraf.nama_paraf),t_paraf.`nama_paraf`) as nama_paraf,if(t_data_setting_paraf.id_pegawai='99',nm_plh,v_pegawai_v2.`nama_pegawai`) as nama_pegawai,if(t_data_setting_paraf.id_pegawai='99',nip_plh,v_pegawai_v2.`nip`) as nip,if(t_data_setting_paraf.id_pegawai='99',pangkat_plh,v_pegawai_v2.`pangkat`) as pangkat,if(t_data_setting_paraf.id_pegawai='99',jabatan_plh,'') as jbtn_plt
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
				if(t_data_setting_paraf.id_pegawai='99',concat('Plt ',t_paraf.nama_paraf_pd),t_paraf.`nama_paraf_pd`) as nama_paraf_pd,if(t_data_setting_paraf.id_pegawai='99',nm_plh,v_pegawai_v2.`nama_pegawai`) as nama_pegawai,if(t_data_setting_paraf.id_pegawai='99',nip_plh,v_pegawai_v2.`nip`) as nip,if(t_data_setting_paraf.id_pegawai='99',pangkat_plh,v_pegawai_v2.`pangkat`) as pangkat,if(t_data_setting_paraf.id_pegawai='99',jabatan_plh,'') as jbtn_plt
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
				$detil_jabatan = $row->nama_paraf_pd .'<br>'. $row->jbtn_plt;
				$detil_nama = '<span style="text-decoration: underline;">'. $row->nama_pegawai .'</span><br>NIP. '. $row->nip;
			} else {
				$detil_nama = 'Pejabat Penanda tangan belum di set !';
			}
			$arr['detil_jabatan'] = $detil_jabatan;
			$arr['detil_nama'] = $detil_nama;
			return $arr;
		}
	}
	
	public function get_json_paraf($postLaporan, $postLokasi, $postPosisi)
	{
		$arr = array();
		$detil_nip = '';
		$detil_nama = '';

		if ( $postLokasi == '05' || $postLokasi == '' || $postLokasi == '99' ) {
			//return '';
		} else {
			$cmd = "SELECT
				if(t_data_setting_paraf.id_pegawai='99',concat('Plt. ',t_paraf.nama_paraf_pd),t_paraf.`nama_paraf_pd`) as nama_paraf_pd,if(t_data_setting_paraf.id_pegawai='99',nm_plh,v_pegawai_v2.`nama_pegawai`) as nama_pegawai,if(t_data_setting_paraf.id_pegawai='99',nip_plh,v_pegawai_v2.`nip`) as nip,if(t_data_setting_paraf.id_pegawai='99',pangkat_plh,v_pegawai_v2.`pangkat`) as pangkat,if(t_data_setting_paraf.id_pegawai='99',jabatan_plh,'') as jbtn_plt
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
				$detil_nip = $row->nip;
				$detil_nama = $row->nama_pegawai;
			} else {
				$detil_nama = 'Pejabat Penanda tangan belum di set !';
			}
			$arr['nip'] = $detil_nip;
			$arr['nama'] = $detil_nama;
			return $arr;
		}
	}

	// Update 24 07 2017
	public function get_form_by_location( $getlocation ) {
		if ( $getlocation == '05' ) {
			$this->db->where('klasifikasi','pusat');
		} else {
			$this->db->where('klasifikasi','daerah');
		}
		
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function get_paraf_tr_esamsat() {
		$cmd = "SELECT
if(b.id_pegawai='99',concat('Plt. ',a.nama_paraf),a.`nama_paraf`) as nama_paraf,if(b.id_pegawai='99',nm_plh,c.`nama_pegawai`) as nama_pegawai,if(b.id_pegawai='99',nip_plh,c.`nip`) as nip,if(b.id_pegawai='99',jabatan_plh,'') as jbtn_plt
FROM t_data_setting_paraf b
LEFT JOIN t_paraf a ON a.`id_paraf`=b.`id_paraf`
LEFT JOIN v_pegawai_v2 c ON c.`id_pegawai`=b.`id_pegawai`
WHERE a.`klasifikasi`='pusat' AND b.`id_lokasi`='05' AND a.`id_paraf`='01esam'";
		return $this->db->query( $cmd );
	}

	public function get_paraf_sts() {
		$cmd = "SELECT
if(b.id_pegawai='99',concat('Plt. ',a.nama_paraf),a.`nama_paraf`) as nama_paraf,if(b.id_pegawai='99',nm_plh,c.`nama_pegawai`) as nama_pegawai,if(b.id_pegawai='99',nip_plh,c.`nip`) as nip,if(b.id_pegawai='99',jabatan_plh,'') as jbtn_plt
FROM t_data_setting_paraf b
LEFT JOIN t_paraf a ON a.`id_paraf`=b.`id_paraf`
LEFT JOIN v_pegawai_v2 c ON c.`id_pegawai`=b.`id_pegawai`
WHERE a.`klasifikasi`='pusat' AND b.`id_lokasi`='05' AND a.`id_paraf` IN ('01esam','01sts')
ORDER BY a.top_sort DESC";
		return $this->db->query( $cmd );
	}
}