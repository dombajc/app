<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Splitzjr extends CI_Controller {

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
	private $folder = 'laporan/';
	
	public function __construct() {
        parent::__construct();
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
        $this->load->Model( 'Esamsatjateng' );
    }
	
	public function index() {

		$data['title'] = 'SPLIT JR';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'download split jr';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function downloaddat( $jenis, $tgl1, $tgl2, $bank = '99' ) {
		$exp_tgl_1 = explode(' ', $this->security->xss_clean($tgl1));
		$exp_tgl_2 = explode(' ', $this->security->xss_clean($tgl2));
		
		$get_tgl_awal = $this->Fungsi->formatdatetosql( $exp_tgl_1[0] );
		$get_tgl_akhir = $this->Fungsi->formatdatetosql( $exp_tgl_2[0] );
		
		$isian = '';
		$get = $this->Esamsatjateng->get_data_jr( $jenis, $get_tgl_awal .' '. $exp_tgl_1[1], $get_tgl_akhir .' '. $exp_tgl_2[1], $bank )->result();
		
		foreach ( $get as $r ) {
			$plat = sscanf($r->NO_POLISI, "%[A-Z]%d%[A-Z]");
			$plat_lama = sscanf($r->NO_POLISI_LAMA, "%[A-Z]%d%[A-Z]");
			if (isset($plat_lama)){
            $plat_lama[0] = (strlen($plat_lama[0])<=2)?$plat_lama[0]:substr($plat_lama[0],0,2);
            $plat_lama[1] = (strlen($plat_lama[1])<=4)?$plat_lama[1]:substr($plat_lama[1],0,4);
            $plat_lama[2] = (strlen($plat_lama[2])<=3)?$plat_lama[2]:substr($plat_lama[2],0,3);
            }

			$isian .= $r->TGL_TRANSAKSI.str_pad($r->SAMSAT_BAYAR,6).str_pad($plat[0],2).str_pad($plat[1],4," ",STR_PAD_LEFT).str_pad($plat[2],3," ",STR_PAD_LEFT).str_pad($r->NO_KOHIR,25).$r->TGL_MATI_LALU.$r->TGL_PENETAPAN.str_pad($plat_lama[0],2).str_pad($plat_lama[1],4," ",STR_PAD_LEFT).str_pad($plat_lama[2],3," ",STR_PAD_LEFT).$r->TGL_MATI_YAD.$r->KODE_PLAT.str_pad($r->KODE_JENIS_KEND,3).str_pad($r->KODE_GOLONGAN,2).str_pad($r->KODE_MUTASI,1).str_pad($r->Jr_pokok,7," ",STR_PAD_LEFT).str_pad($r->jr_denda,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad(0,7," ",STR_PAD_LEFT).str_pad($r->CC_KEND,5).str_pad($r->NO_RANGKA,25).str_pad($r->NO_MESIN,25).str_pad($r->THN_PEMBUATAN,4).str_pad($r->NAMA_PEMILIK,35).str_pad($r->ALAMAT_PEMILIK,65).str_pad($r->MERK,25).str_pad($r->SAMSAT_ASAL,6).str_pad($r->WARNA,20).str_pad($r->BHN_BAKAR,8)."JR".str_pad($r->NIK,20," ",STR_PAD_LEFT).str_pad($r->NO_HP,20," ",STR_PAD_LEFT)."
";
		}

		$nama = "99_JR". date('dmY') .".DAT";    
        @header("Content-disposition: attachment; filename=$nama");
		@header('Content-type: text/plain');
        echo $isian;
	
	}

	
}
