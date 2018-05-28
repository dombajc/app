<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Display extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	private $folder = '';
	
	public function __construct() {
        parent::__construct();
		$this->load->model('Trxd2d');
		$this->load->model('Targetpelypd');
		$this->load->model('Trxpd');
		
    }
	
	public function index()
	{
		$data['title'] = 'DISPLAY';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'monitoring realtime';
		$data['arrTriwulan'] = $this->Triwulan->getAllData()->result_array();
		
        $this->load->view($this->Opsisite->getDataSite() ['display_template'], $data);
	}
	
	public function api_json()
	{
		$json = array();
		
		$getCapaianTertinggi = array();
		$getCapaianTerendah = array();
		$rata2 = array();
		
		foreach( $this->Triwulan->getAllData()->result() as $td )
		{
			$upadTertinggi = $this->Trxd2d->getPersentaseDashboardUpadTertinggi($this->Thanggaran->getIdThAktif(), $td->id_triwulan);
			
			$getCapaianTertinggi[$td->id_triwulan]['lokasi'][] = $upadTertinggi->lokasi;
			$getCapaianTertinggi[$td->id_triwulan]['persen'][] = number_format((($upadTertinggi->total_input/$upadTertinggi->total_target)*100),2) .' %';
			
		}
		
		foreach( $this->Triwulan->getAllData()->result() as $down )
		{
			$upadTerendah = $this->Trxd2d->getPersentaseDashboardUpadTerendah($this->Thanggaran->getIdThAktif(), $down->id_triwulan);
			$value = $upadTerendah->total_target == 0 ? 0 : number_format((($upadTerendah->total_input/$upadTerendah->total_target)*100),2);
			
			$getCapaianTerendah[$down->id_triwulan]['lokasi'][] = $upadTerendah->lokasi;
			$getCapaianTerendah[$down->id_triwulan]['persen'][] = $value .' %';
			
		}
		
		foreach( $this->arrRatarata('33') as $rata ) {
			$rata2[$rata['id_triwulan']]['nonasn'] = number_format( $rata['ratarata'], 0);
		}
		
		foreach( $this->arrRatarata('99') as $rata ) {
			$rata2[$rata['id_triwulan']]['asn'] = number_format( $rata['ratarata'], 0);
		}
		
		$gettarget = $this->get_arr_target();
		$getpungutan = $this->get_arr_pungut();
		
		$arrX = array();
		
		$arrtargetpkb = array();
		$arrpungutpkb = array();
		$resultpkb = array();
		$arrX['name'] = 'Triwulan';
		$arrtargetpkb['name'] = 'Target';
		$arrpungutpkb['name'] = 'Realisasi';
		
		
		$arrtargetbbnkb = array();
		$arrpungutbbnkb = array();
		$resultbbnkb = array();
		$arrtargetbbnkb['name'] = 'Target';
		$arrpungutbbnkb['name'] = 'Realisasi';
		
		$arrtargetpbbkb = array();
		$arrpungutpbbkb = array();
		$resultpbbkb = array();
		$arrtargetpbbkb['name'] = 'Target';
		$arrpungutpbbkb['name'] = 'Realisasi';
		
		$arrtargetpap = array();
		$arrpungutpap = array();
		$resultpap = array();
		$arrtargetpap['name'] = 'Target';
		$arrpungutpap['name'] = 'Realisasi';
		
		foreach( $this->Triwulan->getAllData()->result() as $pkb )
		{
			
			$arrX['data'][] = $pkb->triwulan;
			$arrtargetpkb['data'][] = !empty($gettarget[$pkb->id_triwulan]['01']) ? $gettarget[$pkb->id_triwulan]['01']+0 : 0;
			$arrpungutpkb['data'][] = isset( $getpungutan[$pkb->id_triwulan]['01'] ) ? $getpungutan[$pkb->id_triwulan]['01']+0 : 0;
			$arrtargetbbnkb['data'][] = !empty($gettarget[$pkb->id_triwulan]['02']) ? $gettarget[$pkb->id_triwulan]['02']+0 : 0;
			$arrpungutbbnkb['data'][] = !empty( $getpungutan[$pkb->id_triwulan]['02'] ) ? $getpungutan[$pkb->id_triwulan]['02']+0 : 0;
			$arrtargetpbbkb['data'][] = !empty($gettarget[$pkb->id_triwulan]['03']) ? $gettarget[$pkb->id_triwulan]['03']+0 : 0;
			$arrpungutpbbkb['data'][] = !empty( $getpungutan[$pkb->id_triwulan]['03'] ) ? $getpungutan[$pkb->id_triwulan]['03']+0 : 0;
			$arrtargetpap['data'][] = !empty($gettarget[$pkb->id_triwulan]['04']) ? $gettarget[$pkb->id_triwulan]['04']+0 : 0;
			$arrpungutpap['data'][] = !empty( $getpungutan[$pkb->id_triwulan]['04'] ) ? $getpungutan[$pkb->id_triwulan]['04']+0 : 0;
		}
		
		array_push( $resultpkb, $arrX );
		array_push( $resultpkb, $arrtargetpkb );
		array_push( $resultpkb, $arrpungutpkb );
		
		array_push( $resultbbnkb, $arrX );
		array_push( $resultbbnkb, $arrtargetbbnkb );
		array_push( $resultbbnkb, $arrpungutbbnkb );
		
		array_push( $resultpbbkb, $arrX );
		array_push( $resultpbbkb, $arrtargetpbbkb );
		array_push( $resultpbbkb, $arrpungutpbbkb );
		
		array_push( $resultpap, $arrX );
		array_push( $resultpap, $arrtargetpap );
		array_push( $resultpap, $arrpungutpap );
		
		$json['d2dtertinggi'] = $getCapaianTertinggi;
		$json['d2dterendah'] = $getCapaianTerendah;
		$json['getpkb'] = $resultpkb;
		$json['getbbnkb'] = $resultbbnkb;
		$json['getpbbkb'] = $resultpbbkb;
		$json['getpap'] = $resultpap;
		$json['rata2'] = $rata2;
		
		echo json_encode($json);
	}
	
	public function arrRatarata( $sts_pegawai )
	{
		$cmd = "SELECT
			t_triwulan.id_triwulan,
			IFNULL((
			SELECT
			AVG(t_trx_d2d.`jumlah`)
			FROM
			t_trx_d2d
			LEFT JOIN (v_mutasi,v_pegawai_v2) ON
			v_pegawai_v2.`id_pegawai`=v_mutasi.`id_pegawai` AND v_mutasi.`id_mutasi`=t_trx_d2d.`id_mutasi`
			WHERE
			t_trx_d2d.`id_anggaran`='". $this->Thanggaran->getIdThAktif() ."' AND t_trx_d2d.`id_bulan`=t_bulan.`id_bulan` AND
			v_pegawai_v2.`id_sts_pegawai`='". $sts_pegawai ."'
			),0) ratarata
			FROM
			t_triwulan
			LEFT JOIN t_bulan ON
			t_bulan.`id_triwulan`=t_triwulan.`id_triwulan`
			GROUP BY t_triwulan.`id_triwulan`";
			
		return $this->db->query($cmd)->result_array();
	}
	
	public function get_arr_target()
	{
		$arr = array();
		$cmd = "SELECT
			id_triwulan,id_rek_pd,total
			FROM
			t_target_pely_pd
			WHERE
			id_anggaran='". $this->Thanggaran->getIdThAktif() ."'
			GROUP BY id_triwulan,id_rek_pd";
		foreach ( $this->db->query($cmd)->result_array() as $row ){
			$arr[$row['id_triwulan']][$row['id_rek_pd']] = $row['total'];
		};
		return $arr;
	}
	
	public function get_arr_pungut()
	{
		$arr = array();
		$cmd = "SELECT
			id_triwulan,id_rek_pd,SUM(realisasi) AS jml
			FROM
			v_list_pungut_pd
			WHERE
			id_anggaran='". $this->Thanggaran->getIdThAktif() ."'
			GROUP BY id_triwulan,id_rek_pd";
		foreach ( $this->db->query($cmd)->result() as $row ){
			$arr[$row->id_triwulan][$row->id_rek_pd] = $row->jml;
		};
		return $arr;
	}

}
