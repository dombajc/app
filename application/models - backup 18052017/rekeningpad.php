<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rekeningpad extends CI_Model{

	private $dbPadOL;

    public function __construct()
    {
        parent::__construct();
        $this->dbPadOL = $this->load->database('dbPadOL', TRUE);
    }
	
	public function arr_kode_rekening($id_tahun)
	{
		$cmd = "SELECT
kd_rekening,no_rekening,nm_rekening,
(
SELECT COUNT(*) FROM rekening b WHERE b.kd_subrekening=a.`kd_rekening`
) AS anakan
FROM rekening a
WHERE th='". $this->Thanggaran->getDataByID($id_tahun)->row()->th_anggaran ."' AND LEFT(kd_rekening,4)='.1.2' order by no_rekening";
		return $this->dbPadOL->query($cmd)->result();
	}
	
	public function arr_transaksi($cond)
	{
		$arr = array();
		$cmd = "SELECT
kd_rekening,SUM(jumlah_setoran) AS total
FROM trx_penerima WHERE ". $cond ."
GROUP BY kd_rekening";
		foreach ( $this->dbPadOL->query($cmd)->result() as $row )
		{
			$arr[$row->kd_rekening] = $row->total;
		}
		return $arr;
	}
	
	private function get_kode_by_tahun($tahun)
	{
		$data = array();
		$cmd = "SELECT
kd_rekening,no_rekening,nm_rekening,IF(a.`kd_rekening`=a.`kd_subrekening`,0,a.`kd_subrekening`) AS parent,
(
SELECT COUNT(*) FROM rekening b WHERE b.kd_subrekening=a.`kd_rekening`
) AS anakan
FROM rekening a WHERE a.`th`='". $this->Thanggaran->getDataByID($tahun)->row()->th_anggaran ."' AND LEFT(kd_rekening,4)='.1.2' ORDER BY no_rekening";
		foreach ( $this->dbPadOL->query($cmd)->result() as $row):
			$data[$row->parent][] = $row;
        endforeach;
		return $data;
	}
	
	private function check_kode_pilihan($data, $parent = 0, $arrMenu, $get_kode_pilihan)
	{
		static $i = 1;
		if (isset($data[$parent])) {
			
			$html = "<ul>";
			$i++;
			foreach ($data[$parent] as $v) {
				$child = $this->check_kode_pilihan($data, $v->kd_rekening, $arrMenu, $get_kode_pilihan);
				$checked = in_array($v->kd_rekening, $get_kode_pilihan) == 0 ? '' : 'checked';
				$html .= "<li><input type=\"checkbox\" name=\"postkoderetribusi[]\" value=\"". $v->kd_rekening ."\" ".$checked."><span>" . $v->no_rekening . " <label class=\"badge badge-info\">". $v->nm_rekening ."</label></span>";
				if ($child) {
					$i--;
					$html .= $child;
				}
				$html .= '</li>'; 
			}
			$html .= "</ul>";
			return $html;
		} else {
			return false;
		}
	}
	
	public function show_pilihan_kode_by_tahun($by_tahun){
		$get_rekening_by_tahun = $this->get_kode_by_tahun($by_tahun);
		$get_kode_pilihan_user_by_tahun = $this->Setkoderetribusi->get_arr_kode_rekening_by_tahun($by_tahun);
		
		return $this->check_kode_pilihan($get_rekening_by_tahun, '.1', $get_rekening_by_tahun, $get_kode_pilihan_user_by_tahun);
	}
	
	public function laporan_buku_bantu_monitoring_tunggakan($by_tahun, $by_bulan, $by_lokasi)
	{
		$get_rekening_by_tahun = $this->get_kode_by_tahun($by_tahun);
		$get_kode_pilihan_user_by_tahun = $this->Setkoderetribusi->get_arr_kode_rekening_by_tahun($by_tahun);
		$get_entrian = $this->Rekamretribusi->get_entrian($by_tahun, $by_bulan, $by_lokasi)->result();
		$arr_entrian = $this->arr_total_entrian($get_kode_pilihan_user_by_tahun, $get_entrian);
		$arr_oby = $this->arr_total_obyek($get_kode_pilihan_user_by_tahun, $get_entrian);
		
		return $this->format_tabel_buku_bantu_monitoring_tunggakan($get_rekening_by_tahun, '.1', $get_kode_pilihan_user_by_tahun, $arr_entrian, $arr_oby);
	}
	
	public function footer_laporan_buku_bantu_monitoring_tunggakan($by_tahun, $by_bulan, $by_lokasi)
	{
		$get_bln_ini = $this->Rekamretribusi->get_entrian($by_tahun, $by_bulan, $by_lokasi);
		$arr_bln_ini = $this->result_footer_buku_bantu_monitoring_tunggakan($get_bln_ini);
		$get_bln_lalu = $this->Rekamretribusi->get_entrian_sd_bln_lalu($by_tahun, $by_bulan, $by_lokasi);
		$arr_bln_lalu = $this->result_footer_buku_bantu_monitoring_tunggakan($get_bln_lalu);
		$html = '<tr>
			<td colspan="2" align="center"><b>JUMLAH BLN INI</b></td>
			<td align="right"><b>'. number_format($arr_bln_ini['tot_oby'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_ini['tot_jml'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_ini['tot_oby'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_ini['tot_jml'],0) .'</b></td>
			<td align="right"><b>0</b></td>
			<td align="right"><b>0</b></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>JUMLAH BLN YLL</b></td>
			<td align="right"><b>'. number_format($arr_bln_lalu['tot_oby'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_lalu['tot_jml'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_lalu['tot_oby'],0) .'</b></td>
			<td align="right"><b>'. number_format($arr_bln_lalu['tot_jml'],0) .'</b></td>
			<td align="right"><b>0</b></td>
			<td align="right"><b>0</b></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><b>JUMLAH S/D BLN INI</b></td>
			<td align="right"><b>'. number_format(($arr_bln_ini['tot_oby'] + $arr_bln_lalu['tot_oby']),0) .'</b></td>
			<td align="right"><b>'. number_format(($arr_bln_ini['tot_jml'] + $arr_bln_lalu['tot_jml']),0) .'</b></td>
			<td align="right"><b>'. number_format(($arr_bln_ini['tot_oby'] + $arr_bln_lalu['tot_oby']),0) .'</b></td>
			<td align="right"><b>'. number_format(($arr_bln_ini['tot_jml'] + $arr_bln_lalu['tot_jml']),0) .'</b></td>
			<td align="right"><b>0</b></td>
			<td align="right"><b>0</b></td>
			<td></td>
		</tr>';
		return $html;
	}
	
	private function result_footer_buku_bantu_monitoring_tunggakan($result)
	{
		$tot_oby = 0;
		$tot_jml = 0;
		foreach ( $result->result() as $row )
		{
			$tot_oby += $row->tot_oby;
			$tot_jml += $row->tot_jumlah;
		}
		$arr['tot_oby'] = $tot_oby;
		$arr['tot_jml'] = $tot_jml;
		return $arr;
	}
	
	private function format_tabel_buku_bantu_monitoring_tunggakan($data, $parent = 0, $rekeningUser, $get_entrian, $arr_oby)
	{
		static $i = 1;
		static $no = 1;
		if (isset($data[$parent])) {
			$i = $i+2;
			
			foreach ($data[$parent] as $v) {
				if ( in_array($v->kd_rekening, $rekeningUser) == true )
				{
					
					if ( $v->anakan == 0 ) {
						$html .= '<tr>
							<td align="right" width="5%">'. $no .'. </td>
							<td width="30%">'. $v->nm_rekening .'</td>
							<td align="right" width="8%">'. number_format($arr_oby[$v->kd_rekening]['jml'],0) .'</td>
							<td align="right" width="10%">'. number_format($get_entrian[$v->kd_rekening]['jml'],0) .'</td>
							<td align="right" width="8%">'. number_format($arr_oby[$v->kd_rekening]['jml'],0) .'</td>
							<td align="right" width="10%">'. number_format($get_entrian[$v->kd_rekening]['jml'],0) .'</td>
							<td align="right" width="8%">'. number_format(0,0) .'</td>
							<td align="right" width="10%">'. number_format(0,0) .'</td>
							<td></td>
						</tr>';
						$no++;
					}
					$child = $this->format_tabel_buku_bantu_monitoring_tunggakan($data, $v->kd_rekening, $rekeningUser, $get_entrian, $arr_oby);
					if ($child) {
						$i = $i-2;
						$html .= $child;
					}
				}
				
			}
			$html .= "";
			return $html;
		} else {
			return false;
		}
	}
	
	public function laporan_rd01($by_tahun, $by_bulan, $by_lokasi, $by_target){
		$get_rekening_by_tahun = $this->get_kode_by_tahun($by_tahun);
		$get_target_by_tahun = $this->arr_target_by_tahun($by_tahun, $by_lokasi, $by_target)->result();
		$get_kode_pilihan_user_by_tahun = $this->Setkoderetribusi->get_arr_kode_rekening_by_tahun($by_tahun);
		$get_entrian = $this->Rekamretribusi->get_entrian($by_tahun, $by_bulan, $by_lokasi)->result();
		$get_entrian_sd_bln_lalu = $this->Rekamretribusi->get_entrian_sd_bln_lalu($by_tahun, $by_bulan, $by_lokasi)->result();
		$arr_entrian = $this->arr_total_entrian($get_kode_pilihan_user_by_tahun, $get_entrian);
		$arr_entrian_sd_bln_lalu = $this->arr_total_entrian($get_kode_pilihan_user_by_tahun, $get_entrian_sd_bln_lalu);
		$arr_target = $this->arr_target($get_kode_pilihan_user_by_tahun, $get_target_by_tahun);

		return $this->format_tabel_laporan($get_rekening_by_tahun, '.1', $get_kode_pilihan_user_by_tahun, $arr_entrian, $arr_entrian_sd_bln_lalu, $arr_target);
	}
	
	public function arr_total_entrian($a, $b)
	{
		$arr = array();
		foreach ( $a as $row )
		{
			$val = 0;
			foreach ( $b as $row_b )
			{
				if ( stripos($row_b->kd_rekening, $row) !== false )
				{
					$val += $row_b->tot_jumlah;
				}
			}
			$arr[$row] = array('jml'=>$val);
		}
		return $arr;
	}
	
	public function arr_total_obyek($a, $b)
	{
		$arr = array();
		foreach ( $a as $row )
		{
			$val = 0;
			foreach ( $b as $row_b )
			{
				if ( stripos($row_b->kd_rekening, $row) !== false )
				{
					$val += $row_b->tot_oby;
				}
			}
			$arr[$row] = array('jml'=>$val);
		}
		return $arr;
	}
	
	public function arr_target($a, $b)
	{
		foreach ( $a as $row )
		{
			$val = 0;
			foreach ( $b as $row_b )
			{
				if ( stripos($row_b->kd_rekening, $row) !== false )
				{
					$val += $row_b->total;
				}
			}
			$arr[$row] = $val;
		}
		return $arr;
	}
	
	private function format_tabel_laporan($data, $parent = 0, $rekeningUser, $get_entrian, $get_entrian_sd_bln_lalu, $get_target)
	{
		static $i = 1;
		if (isset($data[$parent])) {
			$i = $i+2;
			
			foreach ($data[$parent] as $v) {
				if ( in_array($v->kd_rekening, $rekeningUser) == true )
				{
					$tot_sd_bln_ini = $get_entrian[$v->kd_rekening]['jml'] + $get_entrian_sd_bln_lalu[$v->kd_rekening]['jml'];
					$persen_sd_bln_ini = $tot_sd_bln_ini == 0 || $get_target[$v->kd_rekening] == 0 ? 0 : ( $tot_sd_bln_ini / $get_target[$v->kd_rekening] ) * 100;
					$html .= '<tr>
						<td width="20%">'. $this->sub_spasi($i) .' '. $v->no_rekening .'</td>
						<td width="20%">'. $v->nm_rekening .'</td>
						<td align="right" width="8%">'. number_format($get_target[$v->kd_rekening],0) .'</td>
						<td width="5%"></td>
						<td align="right" width="8%">'. number_format($get_entrian[$v->kd_rekening]['jml'],0) .'</td>
						<td align="right" width="8%">'. number_format($get_entrian_sd_bln_lalu[$v->kd_rekening]['jml'],0) .'</td>
						<td align="right" width="8%">'. number_format($get_entrian[$v->kd_rekening]['jml'],0) .'</td>
						<td width="5%"></td>
						<td align="right" width="8%">'. number_format($tot_sd_bln_ini,0) .'</td>
						<td align="center" width="5%">'. number_format($persen_sd_bln_ini,2) .'</td>
					</tr>';
					$child = $this->format_tabel_laporan($data, $v->kd_rekening, $rekeningUser, $get_entrian, $get_entrian_sd_bln_lalu, $get_target);
					if ($child) {
						$i = $i-2;
						$html .= $child;
					}
				}
				
			}
			return $html;
		} else {
			return false;
		}
	}
	
	public function arr_target_by_tahun($param_th, $param_lokasi, $param_target)
	{
		$cmd = "SELECT kd_rekening,SUM(I+II+III+IV) AS total
FROM akp_biro a WHERE a.`th`='". $this->Thanggaran->getDataByID($param_th)->row()->th_anggaran ."' AND LEFT(kd_rekening,4)='.1.2' AND kndsi=". $param_target ." AND LEFT(id_lokasi,LENGTH('". $param_lokasi ."'))='". $param_lokasi ."'
GROUP BY kd_rekening ORDER BY kd_rekening";
		return $this->dbPadOL->query($cmd);
	}
	
	public function sub_spasi($i)
	{
		$spasi = '';
		for ( $j=5; $j<=$i; $j++)
		{
			$spasi .='&nbsp;';
		}
		return $spasi;
	}
}