<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mutasi extends CI_Model{

	private $tabel='t_mutasi';
	private $view = 'v_mutasi';

	public function getByIdLokasi($postLokasi, $postPegawai)
	{
		$cmd = "select * from ". $this->tabel ." where id_lokasi='". $postLokasi ."' and id_pegawai='". $postPegawai ."'";
		return $this->db->query($cmd);
	}

	public function getDataById($postId)
	{
		$cmd = "SELECT *,
IF((`v_mutasi`.`aktif` = 1),0,(SELECT (`t_mutasi`.`tgl_sk` - INTERVAL 1 DAY) FROM `t_mutasi` WHERE (`t_mutasi`.`id_mutasi_sebelumnya` = `v_mutasi`.`id_mutasi`))) AS `tgl_selesai_mutasi`
FROM
v_mutasi where id_mutasi='". $postId ."'";
		return $this->db->query($cmd);
	}

	public function getJumlahMutasiSebelumnya($postId)
	{
		$cmd = "select count(*) as jml from t_mutasi where id_mutasi_sebelumnya='". $postId ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function getDataIdMutasi($postLokasi, $postPegawai, $postBulan)
	{
		$cmd = "select id_mutasi from ". $this->tabel ." where id_lokasi='". $postLokasi ."' and id_pegawai='". $postPegawai ."' and month(tgl_sk)<=". $postBulan;
		return $this->db->query($cmd)->row()->id_mutasi;
	}

	public function getPegawaiAktifByLokasi($postLokasi)
	{
		$cmd = "SELECT
			t_pegawai.`id_pegawai`,
			t_pegawai.`nama_pegawai`
			FROM
			v_mutasi
			LEFT JOIN t_pegawai ON
			t_pegawai.`id_pegawai`=v_mutasi.`id_pegawai`
			WHERE
			v_mutasi.`aktif`=1 AND t_pegawai.`aktif`=1 AND v_mutasi.id_sts_pegawai='33' AND v_mutasi.`id_jabatan` IN ('e3','e4') and v_mutasi.id_lokasi='". $postLokasi ."'";
		
		return $this->db->query($cmd);

	}
	
	public function pilihan_jenis_mutasi()
	{
		$opsi = '';
		
		$cmd = "select * from t_jenis_mutasi where aktif=1 order by id_jenis_mutasi";
		
		foreach ( $this->db->query( $cmd )->result() as $row ) {
			$opsi .= '<option value="'. $row->id_jenis_mutasi .'">'. $row->jenis_mutasi .'</option>';
		}
		
		return $opsi;
	}

	public function tambah($arrPost, $postIdPegawai)
	{
		$this->db->trans_begin();

		//$arrnonaktif = array(
			//'aktif' => 0
			//);
		//$this->db->where('id_pegawai', $postIdPegawai);
		//$this->db->update('t_mutasi', $arrnonaktif);
		
		$this->db->insert($this->tabel, $arrPost);
				
		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	public function ubah($arrData, $postId){
		
		$this->db->trans_begin();

		//Cek data mutasi di database
		if ( $this->getDataById($postId)->num_rows() == 1 ) {

			//Count Transaksi D2D
			$jml_transaksi = $this->Trxd2d->countTransaksiD2DPerMutasi($postId);

			if ( $jml_transaksi == 0 ) {
					
				$row = $this->getDataById($postId)->row();
				if ( empty($row->id_lokasi) && empty($row->id_homebase) ) {
					$this->db->where('id_mutasi', $postId);
					$this->db->update($this->tabel, $arrData);
							
					if ($this->db->trans_status() === FALSE):
						$this->db->trans_rollback();
						return $this->db->_error_message();
					else:
						$this->db->trans_commit();
					endif;	
				} else {
					return 'Mutasi tidak dapat di ubah karena telah di validasi oleh lokasi mutasi tujuan !';
				}
				
			} else {
				return 'Maaf data tidak dapat di ubah karena sudah pernah melakukan transaksi rekam Door To Door !';
			}
		
		} else {
			return 'Maaf data tidak di temukan. Silahkan segarkan tabel mutasi !';
		}
	}

	private function countMutasiLama($getIdMutasi)
	{
		$cmd = "select count(*) as jml from t_mutasi where id_mutasi_sebelumnya='". $getIdMutasi ."'";
		return $this->db->query($cmd)->row()->jml;
	}

	public function hapus($ID){
		
		$this->db->trans_begin();

		//Cek data mutasi di database
		if ( $this->getDataById($ID)->num_rows() == 1 ) {

			//Count Transaksi D2D
			$jml_transaksi = $this->Trxd2d->countTransaksiD2DPerMutasi($ID);

			if ( $jml_transaksi == 0 ) {
				
				//Jika id mutasi belum pernah melakukan mutasi maka bisa dilakukan penghapusan !
				if ( $this->countMutasiLama($ID) == 0 ) {
					
					$row = $this->getDataById($ID)->row();
					if ( empty($row->id_lokasi) && empty($row->id_homebase) ) {
						$arrAktif = array(
							'aktif' => 1
							);
						$this->db->where('id_mutasi', $row->id_mutasi_sebelumnya);
						$this->db->update('t_mutasi', $arrAktif);


						$this->db->where('id_mutasi',$ID);
						$this->db->delete($this->tabel);
								
						if ($this->db->trans_status() === FALSE):
							$this->db->trans_rollback();
							return $this->db->_error_message();
						else:
							$this->db->trans_commit();
						endif;
					} else {
						return 'Mutasi tidak dapat dihapus karena telah di validasi oleh lokasi mutasi tujuan !';
					}
					
				} else {
					return 'Maaf Mutasi telah di rekam oleh mutasi lainnya !';
				}

				
			} else {
				return 'Maaf data tidak dapat di hapus karena sudah pernah melakukan transaksi rekam Door To Door !';
			}
		
		} else {
			return 'Maaf data tidak di temukan. Silahkan segarkan tabel mutasi !';
		}
	}

	public function stsaktif($arrPost, $postId)
	{
		$this->db->trans_begin();

		$getIdPegawai = $this->getDataById($postId)->row()->id_pegawai;

		$arrDataNonAktif = array(
			'aktif' => 0
			);
		$this->db->where('id_pegawai', $getIdPegawai);
		$this->db->update($this->tabel, $arrDataNonAktif);

		$this->db->where('id_mutasi', $postId);
		$this->db->update($this->tabel, $arrPost);

		if ($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return $this->db->_error_message();
		else:
			$this->db->trans_commit();
		endif;
	}

	private function countTotalMutasi($getIdPegawai)
	{
		return $this->db->query("select * from t_mutasi where id_pegawai='". $getIdPegawai ."'")->num_rows();
	}

	public function getLoadAllDataWithLimit($dari,$sampai,$filter,$sort){
		$cmd =  "select * from ". $this->view ." where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}

	public function countTotalDataMutasiKeluar($filter)
	{
		$cmd =  "select count(*) as total from v_mutasi_keluar where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

	public function getDataMutasiKeluar($dari,$sampai,$filter,$sort){
		$cmd =  "select v_mutasi_keluar.*,lokasi.lokasi AS lokasi_sebelumnya from v_mutasi_keluar left join lokasi on lokasi.id_lokasi=v_mutasi_keluar.lokasi_asal where ". $filter ." order by ". $sort ." limit ". $dari .",". $sampai;
		return $this->db->query($cmd);
	}
	
	public function countDataAll($filter){
		$cmd =  "select count(*) as total from ". $this->view ." where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

	public function getDataMutasiSebelumnya($getIdMutasi)
	{
		return $this->db->query("select month(tgl_sk) as bulan from t_mutasi where id_mutasi_sebelumnya='". $getIdMutasi ."'");
	}

	public function totalGridMutasiMasuk($filter)
	{
		$cmd =  "select count(*) as total from v_mutasi_masuk where ". $filter;
		return $this->db->query($cmd)->row()->total;
	}

	public function getGridMutasiMasuk($filter,$sort)
	{
		$cmd =  "select * from v_mutasi_masuk where ". $filter ." order by ". $sort ;
		return $this->db->query($cmd)->result_array();
	}

}