<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Nosts extends CI_Model{

	private $tabel = 'dt_no_sts';
	private $view = 'v_history_no_sts';

	public function __construct()
 	{
  		parent::__construct();
        $this->dbesamjat = $this->load->database('dbesamsatjateng', TRUE);
 	}

 	public function checking( $th, $tr, $bank, $tgl, $penerimaan ) {

 		// check tgl akhir sts tidak boleh kurang dari tanggal
 		$this->dbesamjat->where( 'tgl_batas_penyetoran>=', $this->Fungsi->getdatetimesql_string( $tgl ) );
 		$this->dbesamjat->where( 'id_bank', $bank );
		$this->dbesamjat->where( 'jenis_transaksi', $tr );
		$this->dbesamjat->where( 'id_th_anggaran_app', $th );
		$this->dbesamjat->where( 'id_jenis_tr_esamsat', $penerimaan );

		return $this->dbesamjat->get( 'dt_no_sts' );

 	}

 	public function get_last( $th, $tr, $bank, $tgl, $penerimaan ) {

 		$this->dbesamjat->select("isnull((max(auto_no_sts)+1),1) as no_sts, isnull(max(tgl_batas_penyetoran),'2017-07-16 00:00:00') as start_tgl");
 		//$this->dbesamjat->where( 'tgl_batas_penyetoran<=', $this->Fungsi->getdatetimesql_string( $tgl ) );
 		$this->dbesamjat->where( 'id_bank', $bank );
		$this->dbesamjat->where( 'jenis_transaksi', $tr );
		$this->dbesamjat->where( 'id_th_anggaran_app', $th );
		$this->dbesamjat->where( 'id_jenis_tr_esamsat', $penerimaan );

		return $this->dbesamjat->get( 'dt_no_sts' )->row();
 	}

 	public function simpan( $arr_data ) {
 		$this->dbesamjat->trans_begin();
		$this->dbesamjat->insert($this->tabel, $arr_data);
				
		if ($this->dbesamjat->trans_status() === FALSE):
			$this->dbesamjat->trans_rollback();
			return $this->dbesamjat->_error_message();
		else:
			$this->dbesamjat->trans_commit();
		endif;
 	}

 	public function getAllby() {
 		$this->dbesamjat->select("id_sts,format_no_sts,tgl_batas_sts,jenis_tr_esamsat,nama_bank,
		parsename(convert(varchar, cast(iif(id_jenis_tr_esamsat = '001',
			(
				select sum(bayar_pkb_pokok) from v_jurnal_transaksi_penerimaan_pkb x
				where x.tgl_pembayaran>=a.tgl_mulai_penyetoran and x.tgl_pembayaran<=a.tgl_batas_penyetoran
				and x.id_bank=a.id_bank
			)
		,
			(
				select sum(bayar_pkb_denda) from v_jurnal_transaksi_penerimaan_pkb x
				where x.tgl_pembayaran>=a.tgl_mulai_penyetoran and x.tgl_pembayaran<=a.tgl_batas_penyetoran
				and x.id_bank=a.id_bank
			)
		) as money),1),2) as total");
		$this->dbesamjat->order_by("tgl_batas_penyetoran", "desc");
 		return $this->dbesamjat->get( $this->view .' as a' )->result();
 	}

 	public function getById( $id ) {
 		$this->dbesamjat->where( 'id_sts', $id );
 		return $this->dbesamjat->get( $this->view );
 	}
}