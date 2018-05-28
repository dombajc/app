<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transaksiesamsat extends CI_Model{

	public function __construct() {
        parent::__construct();
        $this->db203 = $this->load->database('db203', TRUE);
    }

    public function result_laporan()
    {
    	$arr = array();
    	$now = array();

    	$param = $this->uri->uri_to_assoc(2);
		
		$postth = $this->Thanggaran->getDataByID($param['ta'])->row()->th_anggaran;
		$postbulan = sprintf("%'.02d", $param['bln']);
		$postawal= $param['tgl_awal'] == '-' ? '' : $this->Fungsi->formatdatetosql($this->security->xss_clean($param['tgl_awal']));
		$postakhir = $param['tgl_akhir'] == '-' ? '' : $this->Fungsi->formatdatetosql($this->security->xss_clean($param['tgl_akhir']));
		$postrange = $param['range'];
		$postjenis = $param['jenis'];
		if ( $postrange == 0 ) :
			$first_date = date( $postth .'-'. $postbulan .'-01' );
			$last_date = date( 'Y-m-t', strtotime($first_date));
			$before_first_date = date( $postth .'-01-01' );
			$before_last_date = date( 'Y-m-d', strtotime ( '-1 day' , strtotime ( $first_date ) ));
		else:
			$first_date = empty($postawal) ? date( $postth .'-m-01' ) : $postawal;
			$last_date = empty($postakhir) ? '' : $postakhir;
			$before_first_date = date( $postth .'-01-01' );
			$before_last_date = date( 'Y-m-d', strtotime ( '-1 day' , strtotime ( $first_date ) ));
		endif;


		$rows = $this->db203->query($this->get_cmd($first_date, $last_date));
		$before_rows = $this->db203->query( $this->get_cmd_resume_before( $before_first_date, $before_last_date));

		foreach ( $rows->result() as $row )
		{
			$now[$row->kd_jenis]['oby'] = $row->oby;
			$now[$row->kd_jenis]['pokok'] = $row->pokok;
			$now[$row->kd_jenis]['denda'] = $row->denda;
			$now[$row->kd_jenis]['jumlah'] = $row->jumlah;
		}

		$arr['now'] = $now;
		$arr['before'] = $before_rows->row();
		return $arr;
    }

    public function get_jenis_pungutan()
    {
    	return $this->db->get('d_jenis_pkb_bbnkb')->result();
    }

    private function get_cmd($first_date, $last_date)
    {
    	$where_last_date = '';
    	if ( !empty($last_date) )
    		$where_last_date = " and cast(last_input as date)<='". $last_date ."'";
    	return "select
			kd_jenis,count(id_api) as oby,sum(pokokpkb) as pokok,sum(dendapkb) as denda, sum(pokokpkb+dendapkb) as jumlah from dt_respon_api_korlantas
			where cast(last_input as date)>='". $first_date ."'". $where_last_date ."
			group by kd_jenis";
    }

    private function get_cmd_resume_before($first_date, $last_date)
    {
    	return "select
			count(id_api) as oby,sum(pokokpkb) as pokok,sum(dendapkb) as denda, sum(pokokpkb+dendapkb) as jumlah from dt_respon_api_korlantas
			where cast(last_input as date)>='". $first_date ."' and cast(last_input as date)<='". $last_date ."'";
    }
	
}