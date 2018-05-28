<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fungsi extends CI_Model{
    private $dbPadOL;

    public function __construct()
    {
        parent::__construct();
        $this->dbPadOL = $this->load->database('dbPadOL', TRUE);
    }

	public function format_tgl_indo($date) {
		$tgl = $this->formatdatetosql($date);
        $tanggal = substr($tgl, 8, 2);
        $bulan = $this->getBulan(substr($tgl, 5, 2));
        $tahun = substr($tgl, 0, 4);
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    public function format_sql_to_indo($tgl) {
        $tanggal = substr($tgl, 8, 2);
        $bulan = $this->getBulan(substr($tgl, 5, 2));
        $tahun = substr($tgl, 0, 4);
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    public function getDateFromNip($nip) {
        $tanggal = substr($nip, 6, 2);
        $bulan = substr($nip, 4, 2);
        $tahun = substr($nip, 0, 4);
        return $tahun . '-' . $bulan . '-' . $tanggal;
    }

    function getBulan($bln) {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
	
	public function formatdatetosql($date) {

        $tanggal = substr($date, 0, 2);
        $bulan = substr($date, 3, 2);
        $tahun = substr($date, 6, 4);
        return $tahun . '-' . $bulan . '-' . $tanggal;
    }

    public function formatsqltodate($date) {

        $tanggal = substr($date, 8, 2);
        $bulan = substr($date, 5, 2);
        $tahun = substr($date, 0, 4);
        return $tanggal . '-' . $bulan . '-' . $tahun;
    }
	
	public function getOpsiSelectYear(){
		$opsi = '';
		for ( $y=2005; $y<=date('Y'); $y++ ){
			$opsi .= '<option value="'. $y .'"> '. $y .' </option>';
		}
		return $opsi;
	}

    public function getopsiJenisPelaporan()
    {
        $opsi = '';
        $cmd = "select * from n_jenis_pelaporan";
        foreach( $this->db->query($cmd)->result() as $row)
        {
            $opsi .= '<option value="'. $row->id_jenis_pelaporan .'">'. $row->jenis_pelaporan .'</option>';
        }
        return $opsi;
    }

    public function getDetilByJenisPelaporan($id)
    {
        $cmd = "select * from n_jenis_pelaporan where id_jenis_pelaporan='". $id ."'";
        return $this->db->query($cmd)->row();
    }

    public function arrKodeJenisPajak()
    {
        $cmd = "select * from d_jenis_pkb_bbnkb";
        return $this->db->query($cmd)->result_array();
    }

    public function formatangkalaporan($value)
    {
        if ( $value == 0 ) {
            return '-';
        } else {
            return number_format($value, 0);
        }
    }

    // update 17 09 2016

    //get semester by ID
    public function get_semester_by_id($id)
    {
        $cmd = "select * from n_semester where id_semester='". $id ."'";
        return $this->db->query($cmd)->row();
    }

    // get transaksi rekon PAD Online 
    public function arr_transaksi_pad_ol($get_kode, $get_th, $get_semester)
    {
        $cond = "LEFT(kd_rekening,CHAR_LENGTH('". $get_kode ."')) = '". $get_kode ."'";
        $cond .= $get_semester == 1 ? " and tgl_penerima>='". $get_th ."-01-01' and tgl_penerima<='". $get_th ."-06-31'" : " and tgl_penerima>='". $get_th ."-07-01' and tgl_penerima<='". $get_th ."-12-31'";
        $arr_trx_upad = $this->arr_transaksi_upad($cond);
        $arr_trx_samtu = $this->arr_transaksi_samtu($cond);
        return array_merge($arr_trx_upad, $arr_trx_samtu);
    }

    private function arr_transaksi_upad($get_cond)
    {
        $arr = array();
        $cmd = "SELECT 
a.id_induk,MONTH(tgl_penerima) AS bulan,SUM(pokok) AS tot_pokok
FROM
  trx_penerima a
  LEFT JOIN lokasi b ON b.`ID_LOKASI`=a.`id_lokasi`
WHERE ". $get_cond ." AND samsat NOT IN ('besar','other')
  GROUP BY a.id_induk,MONTH(tgl_penerima)";
        foreach ( $this->dbPadOL->query($cmd)->result() as $row )
        {
            $arr[$row->id_induk][$row->bulan] = $row->tot_pokok;
        }

        return $arr;
    }

    private function arr_transaksi_samtu($get_cond)
    {
        $arr = array();
        $cmd = "SELECT 
a.id_lokasi,MONTH(tgl_penerima) AS bulan,SUM(pokok) AS tot_pokok
FROM
  trx_penerima a
  LEFT JOIN lokasi b ON b.`ID_LOKASI`=a.`id_lokasi`
WHERE ". $get_cond ." AND samsat='besar'
  GROUP BY a.id_lokasi,MONTH(tgl_penerima)";
        foreach ( $this->dbPadOL->query($cmd)->result() as $row )
        {
            $arr[$row->id_lokasi][$row->bulan] = $row->tot_pokok;
        }

        return $arr;
    }

    //sum array untuk PHP yang tidak support
    public function sum_array($array)
    {
        $value = 0;
        foreach ( $array as $row )
        {
            $value += $row;
        }
        return $value;
    }

    public function stylecetakhtml() {
        return '<style type="text/css" media="print,screen">
            body { background:#fff; font-size: 9pt; margin:0.5em auto; font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;}
            body { color: #111; }
            h2 {
                margin:0;
                font-weight:bold;
                font-size:10pt;
            }
            #tabel { border-collapse : collapse; width: 100%; page-break-before: always; font-size: 9pt}
            #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
            #tabel tfoot tr td { font-weight : bold; }
            h1,h2,h3,h4,h5,h6 { margin:0;}
        </style>';
    }

    public function getdatetimesql( $datetime ) {
        
        $exp = explode(' ', $datetime);
        $time = $exp[1];
        $date = $this->formatdatetosql($exp[0]);

        return array(
            'getdate' => $date,
            'gettime' => $time
            );
    }

    public function getdatetimesql_string( $datetime ) {
        $get = $this->getdatetimesql( $datetime );
        return $get['getdate'] .' '. $get['gettime'];
    }

    public function terbilang($x)
    {
      $abil = array("", "Satu", "Dua", "Tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
      if ($x < 12)
        return " " . $abil[$x];
      elseif ($x < 20)
        return $this->terbilang($x - 10) . " Belas";
      elseif ($x < 100)
        return $this->terbilang($x / 10) . " Puluh" . $this->terbilang($x % 10);
      elseif ($x < 200)
        return " Seratus" . $this->terbilang($x - 100);
      elseif ($x < 1000)
        return $this->terbilang($x / 100) . " Ratus" . $this->terbilang($x % 100);
      elseif ($x < 2000)
        return " Seribu" . $this->terbilang($x - 1000);
      elseif ($x < 1000000)
        return $this->terbilang($x / 1000) . " Ribu" . $this->terbilang($x % 1000);
      elseif ($x < 1000000000)
        return $this->terbilang($x / 1000000) . " Juta" . $this->terbilang($x % 1000000);
    }
}