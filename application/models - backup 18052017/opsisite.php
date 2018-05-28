<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Opsisite extends CI_Model{

	private $tabel = 't_master_aplikasi';
	
	public function getDataSite(){
		$cmd = "select * from ". $this->tabel ." where id_master=1";
		$row = $this->db->query($cmd)->row();
		$arr_site = array(
			'nama_site' => $row->site,
			'site_singkat' => $row->site_singkat,
			'second_site' => 'KLINIK',
			'mobile_site' => 'APP',
			'default_template'=> 'template/main',
			'blank_template'=> 'template/blank',
			'display_template'=> 'template/display',
			'cetak_template'=> 'template/cetak',
			'excel_template'=> 'template/excel',
			'excel_pure_template'=> 'template/excel pure',
			'nama_perusahaan' => $row->nama_perusahaan,
			'alamat_perusahaan' => $row->alamat_perusahaan,
			'kota' => $row->kota,
			'no_telp' => $row->no_telp
		);
		return $arr_site;
	}
	
	public function getDataUser(){
		$q = $this->db->query("select * from v_users where id_user='". $this->session->userdata('id_user') ."' and aktif=1 limit 1");
			$row = $q->row();
			$row_user = array(
				'nama_user' => $row->nama_user,
				'lokasi' => $row->lokasi,
				'id_lokasi' => $row->id_lokasi,
				'username' => $row->username,
				'menuakses' => $row->menuakses,
				'admin' => $row->admin,
				'pusat' => $row->pusat
			);
			return $row_user;
		
	}

	public function getPegawaiUltah()
	{
		$hari_ini = date('md');
		$cmd = "SELECT
			nama_pegawai,
			th_lahir,
			YEAR(NOW())-th_lahir AS umur,
			jabatan,
			nama_homebase,
			tgl_ultah,
			IF(jns_kelamin=1,'Bapak','Ibu') AS jns_kelamin
			FROM
			v_pegawai_v2
			WHERE id_sts_pegawai='33' and id_jenis_mutasi='333333' AND tgl_ultah='". $hari_ini ."'";
		$q = $this->db->query($cmd);
		$count = $q->num_rows();
		$no = 1;
		$data_ultah = '';
		foreach ( $q->result() as $row ) {
			$data_ultah .= '<span class="badge">'. $no .'. '. $row->jns_kelamin .' '. $row->nama_pegawai .' ('. $row->umur .' th) - '. $row->nama_homebase .'</span>';
			$data_ultah .= $no < $count ? ' | ' : '';
			$no++;
		}

		$arr = array(
			'count' => $count,
			'data_ultah' => $data_ultah
		);

		return $arr;
	}
	
	public function getInformationApplication(){
		$cmd = "select * from ". $this->tabel ." where id_master=1 and aktif=1";
		return $this->db->query($cmd)->row();
	}
	
	public function updateBasicApplication($arrData)
	{
		$this->db->trans_begin();
		$this->db->where( 'id_master' , 1 );
		$this->db->update($this->tabel, $arrData);
			
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

}