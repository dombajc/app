<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pd extends CI_Model{

	private $tabel='t_rek_pd';

	public function getRekeningInputTarget()
	{
		$this->db->where('sub_rek_pd','00');
		$this->db->where('aktif', 1);
		return $this->db->get( $this->tabel );
	}

	public function getRekeningRekam( $postLokasi )
	{
		if ( $postLokasi == '05' ) {
			$this->db->where('pusat', 1);
		}
		
		//$this->db->where('show_rekam','1');
		$this->db->where('aktif', 1);
		$this->db->order_by('urutan_rekam');
		return $this->db->get( $this->tabel );
	}

	public function getRadioInput()
	{
		$radio = '';
		foreach ( $this->getRekeningInputTarget()->result() as $row ) {
			$radio .= '<label class="radio-inline">
		  		<input type="radio" name="slctpd" id="radPd" value="'. $row->id_rek_pd .'"> '. $row->nama_rekening .'
		  	</label>';
		}
		return $radio;
	}

	public function getDetilById($postId, $postTriwulan, $postAnggaran)
	{
		$cmd = "SELECT
			id_rek_pd,nama_rekening,
			IFNULL((
			SELECT SUM(total) FROM t_target_pely_pd WHERE
			t_target_pely_pd.`id_anggaran`='". $postAnggaran ."' AND t_target_pely_pd.`id_triwulan`='". $postTriwulan ."'
			and t_target_pely_pd.id_rek_pd=t_rek_pd.id_rek_pd
			),0) AS total_target
			FROM
			t_rek_pd
			WHERE t_rek_pd.`sub_rek_pd`='00' and id_rek_pd='". $postId ."'";
		return $this->db->query($cmd)->row();
	}

	public function getArrayBBNKB()
	{
		$cmd = "select * from ". $this->tabel ." where sub_rek_pd='02'";
		return $this->db->query($cmd)->result_array();
	}

	public function arrbysub($sub)
	{
		$cmd = "select * from ". $this->tabel ." where sub_rek_pd='". $sub ."'";
		return $this->db->query($cmd);
	}

	public function opsiJenis($cond)
	{
		$opsi = '';
		$cmd = "select * from ". $this->tabel . $cond;
		foreach ( $this->db->query($cmd)->result() as $row )
		{
			$opsi .= '<option value="'. $row->id_rek_pd .'">'. $row->nama_rekening .'</option>';
		}
		return $opsi;
	}

	public function getdetilrekeningbyid($getid)
	{
		$cmd = "select * from ". $this->tabel ." where id_rek_pd='". $getid ."'";
		return $this->db->query($cmd)->row();
	}
}