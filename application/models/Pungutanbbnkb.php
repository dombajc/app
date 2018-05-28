<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pungutanbbnkb extends CI_Model{

	private $tabel='t_pungut_pd';
	private $n_jenis = 'd_jenis_pkb_bbnkb';

	protected function arrJenis()
	{
		$cmd = "select * from ". $this->n_jenis;
		return $this->db->query($cmd);
	}

	protected function arrRekambbnkb($getTh, $getBulan, $getLokasi, $getRek)
	{
		$arr = array();
		$cmd = "SELECT
a.id_jenis,a.obyek,a.pokok,a.sanksi
FROM
n_item_rekam_bbnkb a
LEFT JOIN n_rekam_bbnkb b ON b.id_rekam_bbnkb=a.id_rekam_bbnkb
WHERE b.id_anggaran='". $getTh ."' AND b.id_bulan=". $getBulan ." AND b.id_lokasi='". $getLokasi ."' and b.id_rek_pd='". $getRek ."'";
		foreach( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_jenis]['obyek'] = $row->obyek;
			$arr[$row->id_jenis]['pokok'] = $row->pokok;
			$arr[$row->id_jenis]['sanksi'] = $row->sanksi;
		}
		return $arr;
	}

	public function cekTransaksi($getTh, $getBulan, $getLokasi, $getRek)
	{
		$cmd = "SELECT 
  a.*,
  b.nama_user,
  c.`nama_rekening`
FROM
  n_rekam_bbnkb a 
  LEFT JOIN t_users b 
    ON b.id_user = a.id_user 
    LEFT JOIN t_rek_pd c
    ON c.`id_rek_pd`=a.`id_rek_pd` WHERE a.id_anggaran='". $getTh ."' AND a.id_bulan=". $getBulan ." AND a.id_lokasi='". $getLokasi ."' and a.id_rek_pd='". $getRek ."'";
		return $this->db->query($cmd);
	}

	public function arrListInput($getTh, $getBulan, $getLokasi, $getRek)
	{
		$arr = array();
		$hasil_rekam = $this->arrRekambbnkb($getTh, $getBulan, $getLokasi, $getRek);
		$arr['detil'] = $this->Pd->getdetilrekeningbyid($getRek);

		foreach( $this->arrJenis()->result() as $jenis )
		{
			$oby = empty($hasil_rekam[$jenis->id_jenis]['obyek']) ? 0 : $hasil_rekam[$jenis->id_jenis]['obyek'];
			$pokok = empty($hasil_rekam[$jenis->id_jenis]['pokok']) ? 0 : $hasil_rekam[$jenis->id_jenis]['pokok'];
			$sanksi = empty($hasil_rekam[$jenis->id_jenis]['sanksi']) ? 0 : $hasil_rekam[$jenis->id_jenis]['sanksi'];
			$arr['list'][] = array(
				'id_jenis' => $jenis->id_jenis,
				'kode_jenis' => $jenis->kode_jenis,
				'obyek' => $oby,
				'pokok' => $pokok,
				'sanksi' => $sanksi
				);
		}

		return $arr;
	}

	public function arrTransaksi($getidth, $getbulan, $getidrek, $getjenispelaporan)
	{
		$field = $getjenispelaporan == '00' ? 'SUM(pokok+sanksi)' : 'SUM(obyek)';
		$arr = array();
		$cmd = "SELECT
id_lokasi,id_jenis,". $field ." AS total
FROM
n_rekam_bbnkb a
LEFT JOIN n_item_rekam_bbnkb b ON b.`id_rekam_bbnkb`=a.`id_rekam_bbnkb`
WHERE a.`id_anggaran`='". $getidth ."' and a.id_bulan=". $getbulan ." AND left(id_rek_pd,char_length('". $getidrek ."'))='". $getidrek ."'
GROUP BY id_lokasi,id_jenis";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi][$row->id_jenis] = $row->total;
		}
		return $arr;
	}

	public function arrTransaksiLalu($getidth, $getbulan, $getidrek, $getjenispelaporan)
	{
		$field = $getjenispelaporan == '00' ? 'SUM(pokok+sanksi)' : 'SUM(obyek)';
		$arr = array();
		$cmd = "SELECT
id_jenis,". $field ." AS total
FROM
n_rekam_bbnkb a
LEFT JOIN n_item_rekam_bbnkb b ON b.`id_rekam_bbnkb`=a.`id_rekam_bbnkb`
WHERE a.`id_anggaran`='". $getidth ."' and a.id_bulan<". $getbulan ." AND left(id_rek_pd,char_length('". $getidrek ."'))='". $getidrek ."'
GROUP BY id_jenis";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_jenis] = $row->total;
		}
		return $arr;
	}

	// Laporan Tahunan
	public function arrTahunanPerJenis($getidth, $getidrek, $getjenispelaporan)
	{
		$field = $getjenispelaporan == '00' ? 'SUM(pokok+sanksi)' : 'SUM(obyek)';
		$arr = array();
		$cmd = "SELECT
id_bulan,id_jenis,". $field ." AS total
FROM
n_rekam_bbnkb a
LEFT JOIN n_item_rekam_bbnkb b ON b.`id_rekam_bbnkb`=a.`id_rekam_bbnkb`
WHERE a.`id_anggaran`='". $getidth ."' AND left(id_rek_pd,char_length('". $getidrek ."'))='". $getidrek ."'
GROUP BY id_bulan,id_jenis";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_bulan][$row->id_jenis] = $row->total;
		}
		return $arr;
	}
	
	// Laporan multi tahunan
	public function arrMultiTahun($getidthawal, $getidthakhir, $getidrek, $getjenispelaporan)
	{
		$field = $getjenispelaporan == '00' ? 'SUM(pokok+sanksi)' : 'SUM(obyek)';

		$arr = array();
		$cmd = "SELECT
b.`th_anggaran`,a.`id_bulan`,". $field ." AS total
FROM
n_rekam_bbnkb a
LEFT JOIN n_item_rekam_bbnkb c ON c.`id_rekam_bbnkb`=a.`id_rekam_bbnkb`
LEFT JOIN t_th_anggaran b ON b.`id_anggaran`=a.`id_anggaran`
WHERE left(a.id_rek_pd,char_length('". $getidrek ."'))='". $getidrek ."' AND b.`th_anggaran`>='". $getidthawal ."' AND b.`th_anggaran`<='". $getidthakhir ."' GROUP BY b.`th_anggaran`,a.`id_bulan`";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->th_anggaran][$row->id_bulan] = $row->total;
		}
		return $arr;
	}

	//Laporan PD 05 dan 06
	public function getarrpd($getth, $getbulan, $getlokasi, $getrek)
	{
		$arr = array();
		$cmd = "SELECT id_jenis,SUM(obyek) AS tot_obyek,SUM(pokok) AS tot_pokok,SUM(sanksi) as tot_sanksi FROM arr_pd_05_06
WHERE id_anggaran='". $getth ."' AND id_bulan=". $getbulan ." AND id_lokasi='". $getlokasi ."' and id_rek_pd='". $getrek ."'
GROUP BY id_jenis";
		foreach ( $this->db->query($cmd)->result() as $row ) {
			$arr[$row->id_jenis] = $row;
		}
		return $arr;
	}

	public function getarrpdLalu($getth, $getbulan, $getlokasi, $getrek)
	{
		$cmd = "SELECT SUM(obyek) AS oby,SUM(pokok) AS pokok,SUM(sanksi) as sanksi FROM arr_pd_05_06
WHERE id_anggaran='". $getth ."' AND id_bulan<". $getbulan ." AND id_lokasi='". $getlokasi ."' and id_rek_pd='". $getrek ."'";
		return $this->db->query($cmd)->row();
	}

	// update 17 09 2016
	// untuk pelaporan rekonsiliasi aplikasi APP dengan PAD ONLINE
	public function arr_transaksi_rekon($get_anggaran, $get_semester)
	{
		$arr = array();
		$cmd = "SELECT id_bulan,id_lokasi,SUM(pokok) AS tot_pokok FROM v_trx_bbnkb WHERE id_anggaran='". $get_anggaran ."' and id_bulan in (select id_bulan from t_bulan where id_semester='". $get_semester ."') GROUP BY id_bulan,id_lokasi";
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$arr[$row->id_lokasi][$row->id_bulan] = $row->tot_pokok;
		}
		return $arr;
	}

}