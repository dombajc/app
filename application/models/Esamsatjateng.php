<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Esamsatjateng extends CI_Model{

	private $tabel = 'dt_respon_api_korlantas';
	private $view = 'v_jurnal_transaksi_penerimaan_pkb';

	public function __construct()
 	{
  		parent::__construct();
        $this->dbesamjat = $this->load->database('dbesamsatjateng', TRUE);
 	}

	public function arr_jurnal_by($tgl1, $tgl2, $jns_tr = '', $bank = '') {

		$this->dbesamjat->select('a.*, b.nama_bank');
		$this->dbesamjat->from($this->view .' a');
		$this->dbesamjat->join('dt_bank_pembayaran b', 'b.id_bank = a.id_bank', 'left');

		$this->dbesamjat->where('tgl_pembayaran>=', $tgl1);
		$this->dbesamjat->where('tgl_pembayaran<=', $tgl2);

		if ( isset($jns_tr) && $jns_tr != '' ) {
			$this->dbesamjat->where('jenis_transaksi', $jns_tr);
		}

		if ( isset($bank) && $bank != '' ) {
			$this->dbesamjat->where('a.id_bank', $bank);
		}

		return $this->dbesamjat->get();
	}

	public function arr_rekap_by($tgl1, $tgl2, $jns_tr = '', $bank = '', $kode) {
		
		$arr = array();

		$this->dbesamjat->select('kd_jenis,count(no_polisi) as oby,sum(bayar_'. $kode .'_pokok) as pokok,sum(bayar_'. $kode .'_denda) as denda');

		$this->dbesamjat->where('tgl_pembayaran>=', $tgl1);
		$this->dbesamjat->where('tgl_pembayaran<=', $tgl2);
		
		if ( isset($jns_tr) && $jns_tr != '' ) {
			$this->dbesamjat->where('jenis_transaksi', $jns_tr);
		}

		if ( isset($bank) && $bank != '' ) {
			$this->dbesamjat->where('id_bank', $bank);
		}

		$this->dbesamjat->group_by('kd_jenis');
		$result = $this->dbesamjat->get( $this->view );

		foreach ( $result->result() as $r ) {
			$arr[ $r->kd_jenis ]['oby'] = $r->oby;
			$arr[ $r->kd_jenis ]['pokok'] = $r->pokok;
			$arr[ $r->kd_jenis ]['denda'] = $r->denda;
		}

		return $arr;
	}

	public function arr_rekap_lalu_by($tgl1, $tgl2, $jns_tr = '', $bank = '', $kode) {
		
		$arr = array();

		$this->dbesamjat->select('ISNULL(count(no_polisi), 0) as oby,ISNULL(sum(cast(bayar_'. $kode .'_pokok as bigint)), 0) as pokok,ISNULL(sum(cast(bayar_'. $kode .'_denda as bigint)), 0) as denda');

		$this->dbesamjat->where('tgl_pembayaran>=', $tgl1);
		$this->dbesamjat->where('tgl_pembayaran<', $tgl2);
		
		if ( isset($jns_tr) && $jns_tr != '' ) {
			$this->dbesamjat->where('jenis_transaksi', $jns_tr);
		}

		if ( isset($bank) && $bank != '' ) {
			$this->dbesamjat->where('id_bank', $bank);
		}

		return $this->dbesamjat->get( $this->view )->row();
	}

	public function get_opsi_jenis_transaksi() {
		$opsi = '';

		//$this->dbesamjat->where('aktif', 1);
		foreach( $this->dbesamjat->get('ds_jenis_transaksi')->result() as $r ) {
			$opsi .= '<option value="'. $r->jenis_transaksi .'">'. $r->nama_transaksi .'</option>';
		};

		return $opsi;
	}

	public function get_nama_transaksi_by_id( $id ) {
		$this->dbesamjat->where('jenis_transaksi', $id);
		return $this->dbesamjat->get('ds_jenis_transaksi')->row()->nama_transaksi;
	}

	public function get_opsi_jenis_penerimaan() {
		$this->dbesamjat->where('aktif', 1);
		$this->dbesamjat->order_by('urutan');
		foreach( $this->dbesamjat->get('ds_jenis_penerimaan')->result() as $r ) {
			$opsi .= '<option value="'. $r->jenis_penerimaan .'">'. $r->nama_penerimaan .'</option>';
		};

		return $opsi;
	}

	public function get_nama_penerimaan_by_id( $id ) {
		$this->dbesamjat->where('jenis_penerimaan', $id);
		return $this->dbesamjat->get('ds_jenis_penerimaan')->row()->nama_penerimaan;
	}

	public function get_opsi_bank() {
		$opsi = '';

		$this->dbesamjat->select('id_bank,nama_bank');
		$this->dbesamjat->where('aktif', 1);
		foreach( $this->dbesamjat->get('dt_bank_pembayaran')->result() as $r ) {
			$opsi .= '<option value="'. $r->id_bank .'">'. $r->nama_bank .'</option>';
		};

		return $opsi;
	}

	public function get_bank_by_id( $id ) {
		$this->dbesamjat->select( 'nama_bank' );
		$this->dbesamjat->where( 'id_bank', $id );
		return $this->dbesamjat->get( 'dt_bank_pembayaran' )->row()->nama_bank;
	}

	// update tgl 25 07 2017
	public function view_resume_sts( $start_date, $last_date, $bank, $transaksi, $penerimaan ) {
		$arr_resume_by_kd_jenis = array();
		
		$this->dbesamjat->select( 'kd_jenis,sum('. $penerimaan .') as jumlah' );
		$this->dbesamjat->where( 'tgl_pembayaran>', $start_date );
		$this->dbesamjat->where( 'tgl_pembayaran<=', $last_date );
		$this->dbesamjat->where( 'id_bank', $bank );
		$this->dbesamjat->where( 'jenis_transaksi', $transaksi );
		$this->dbesamjat->group_by( 'kd_jenis' );
		foreach( $this->dbesamjat->get( $this->view )->result() as $r ) {
			$arr_resume_by_kd_jenis[ $r->kd_jenis ] = $r->jumlah;
		}

		return $arr_resume_by_kd_jenis;
	}

	public function option_jenis_penerimaan( ) {
        $this->dbesamjat->select( 'id_jenis_tr_esamsat, jenis_tr_esamsat' );
        $this->dbesamjat->where( 'aktif', 1 );
        
        $opsi = '';
        foreach( $this->dbesamjat->get( 'ds_tabel_bantu_jenis_transaksi' )->result() as $r ) {
            $opsi .= '<option value="'. $r->id_jenis_tr_esamsat .'"> '. $r->jenis_tr_esamsat .' </option>';
        }

        return $opsi;
    }

    public function get_jenis_penerimaan_by_id( $id ) {
        $this->dbesamjat->where( 'id_jenis_tr_esamsat', $id );
        return $this->dbesamjat->get( 'ds_tabel_bantu_jenis_transaksi' )->row();
    }

    public function get_data_jr( $jenis , $tgl_awal, $tgl_akhir, $bank ) {

    	$filter_bank = $bank == '99' ? "" : " and g.id_bank='". $bank ."'";
		$filter_bank .= $jenis == '99' ? "" : " and g.jenis_transaksi='". $jenis ."'";
    	$cmd = "select
REPLACE(CONVERT(CHAR(10),tgl_daftar, 103),'/','') as TGL_TRANSAKSI,
'040055' as 'SAMSAT_BAYAR'
,a.no_polisi as NO_POLISI
,substring(g.kode_billing,0,25) as NO_KOHIR
,REPLACE(CONVERT(CHAR(10),dateadd(year,-1,tgl_jatuh_tempo), 103),'/','') as TGL_MATI_LALU
,REPLACE(CONVERT(CHAR(10),g.tgl_penetapan, 103),'/','') as TGL_PENETAPAN
	,no_pol_lama as NO_POLISI_LAMA
	,REPLACE(CONVERT(CHAR(10),tgl_jatuh_tempo, 103),'/','') as TGL_MATI_YAD
	,right(id_warna_tnkb,1) as KODE_PLAT
	,b.id_jenis_kend as KODE_JENIS_KEND
	,b.id_gol_kend as KODE_GOLONGAN
	,2 as 'KODE_MUTASI'
	,g.bayar_jr_pokok as Jr_pokok
	,g.bayar_jr_denda as jr_denda
	,0 as d1,0 as d2,0 as d3,0 as d4,0 as d5,0 as d6,0 as d7,0 as d8,0 as d9
	,cylinder as CC_KEND
	,a.no_rangka as NO_RANGKA
	,a.no_mesin as NO_MESIN
	,thn_buat as THN_PEMBUATAN
	,substring(a.Nama_pemilik,0,35) as NAMA_PEMILIK
	,substring(a.alamat1+' '+a.Alamat2,0,65) as ALAMAT_PEMILIK
	,substring(f.nm_merk_kend+'/'+a.nm_type_kend,0,25) as 'MERK'
	,e.id_lokasi_khusus as 'SAMSAT_ASAL'
	,c.warna_kend as WARNA
	,d.bahan_bakar as BHN_BAKAR
	,'JR' as 'FLAG'
	,id_kepemilikan as NIK
	,no_telp as NO_HP
	from v_jurnal_transaksi_penerimaan_pkb g
	left join [192.168.99.185].[samsat_online01].dbo.kendaraan_jalan a on g.id_api=a.id_kepemilikan_sblm
	left join (SELECT distinct id_jenis, cc_bawah,cc_atas, id_gol_kend, id_jenis_kend from [192.168.99.185].[samsat_online01].dbo.gol_kend) b on a.id_jenis_map=b.id_jenis and a.id_gol_kend=b.id_gol_kend and a.cylinder>=b.cc_bawah and a.cylinder<cc_atas
	left join [192.168.99.185].[samsat_online01].dbo.p_warna_kend c on a.id_warna_kend=c.id_warna_kend
	left join [192.168.99.185].[samsat_online01].dbo.p_bahan_bakar d on a.id_bahan_bakar=d.id_bahan_bakar
	left join [192.168.99.185].[samsat_online01].dbo.p_lokasi e on a.id_lokasi=e.id_lokasi
	left join [192.168.99.185].[samsat_online01].dbo.p_merk_kend f on a.id_merk_kend=f.id_merk_kend
	where a.kd_kasir='999999' and g.tgl_pembayaran>='". $tgl_awal ."' and g.tgl_pembayaran<='". $tgl_akhir ."'". $filter_bank;
		return $this->dbesamjat->query( $cmd );
	}
	
	public function get_rekap_potensi_all(){
		$arr = array();
		$paramtahun = $this->security->xss_clean($_GET['y']);
		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;
		$field_hitung = '';
		switch( $this->security->xss_clean($_GET['jnstipe'])) {
			case '00':
					$field_hitung = 'count(*)';
				break;
			case '01':
					if ( $this->security->xss_clean($_GET['jnstrx']) == '99' ) {
						$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_pokok+'. $this->security->xss_clean($_GET['jnsbyr']) .'_denda)';
					} elseif ( $this->security->xss_clean($_GET['jnstrx']) == '00' ) {
						$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_pokok)';
					} elseif ( $this->security->xss_clean($_GET['jnstrx']) == '01' ) {
						$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_denda)';
					}
				break;
		}

		$set_tgl_awal = date( 'Y-m-d 00:00:00', 
			strtotime(
				date( $tahun .'-01-01' )
				));
		$set_tgl_akhir = date( 'Y-12-t 23:59:59', 
			strtotime(
				date( $tahun .'-12-01' )
				));
		$whereBank = empty($this->security->xss_clean($_GET['b'])) ? "" : " and id_bank='". $this->security->xss_clean($_GET['b']) ."'";

		$cmd = "select b.persamaan_pad as id_lokasi,month(tgl_bayar) as bulan,". $field_hitung ." as jml from v_pembayaran as a
		left join [192.168.99.52].db_sie.dbo.p_lokasi as b on a.id_lokasi=b.id_lokasi
		where tgl_bayar>='". $set_tgl_awal ."' and tgl_bayar<='". $set_tgl_akhir ."'". $whereBank ." group by b.persamaan_pad,month(tgl_bayar)";
		foreach( $this->dbesamjat->query($cmd)->result() as $row ) {
			$arr[$row->id_lokasi][$row->bulan] = $row->jml;	
		};
		return $arr;
	}

	public function get_rekap_potensi_by_lokasi(){
		$arr = array();
		$paramtahun = $this->security->xss_clean($_GET['y']);
		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;
		$field_hitung = '';
		switch( $this->security->xss_clean($_GET['jnstipe'])) {
			case '00':
					$field_hitung = 'count(*)';
				break;
			case '01':
				if ( $this->security->xss_clean($_GET['jnstrx']) == '99' ) {
					$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_pokok+'. $this->security->xss_clean($_GET['jnsbyr']) .'_denda)';
				} elseif ( $this->security->xss_clean($_GET['jnstrx']) == '00' ) {
					$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_pokok)';
				} elseif ( $this->security->xss_clean($_GET['jnstrx']) == '01' ) {
					$field_hitung = 'sum('. $this->security->xss_clean($_GET['jnsbyr']) .'_denda)';
				}
				break;
		}

		$set_tgl_awal = date( 'Y-m-d 00:00:00', 
			strtotime(
				date( $tahun .'-01-01' )
				));
		$set_tgl_akhir = date( 'Y-12-t 23:59:59', 
			strtotime(
				date( $tahun .'-12-01' )
				));
		$whereBank = empty($this->security->xss_clean($_GET['b'])) ? "" : " and id_bank='". $this->security->xss_clean($_GET['b']) ."'";

		$cmd = "select kd_jenis,month(tgl_bayar) as bulan,". $field_hitung ." as jml from v_pembayaran where tgl_bayar>='". $set_tgl_awal ."' and tgl_bayar<='". $set_tgl_akhir ."' and id_lokasi='". substr($this->security->xss_clean($_GET['lok']),2) ."'". $whereBank ." group by kd_jenis,month(tgl_bayar)";
		foreach( $this->dbesamjat->query($cmd)->result() as $row ) {
			$arr[$row->kd_jenis][$row->bulan] = $row->jml;	
		};
		return $arr;
	}
	
	public function get_jurnal_potensi_all(){
		$paramtahun = $this->security->xss_clean($_GET['y']);
		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;

		$set_tgl_awal = date( 'Y-m-d 00:00:00', 
			strtotime(
				date( $tahun .'-'. $this->security->xss_clean($_GET['m']) .'-01' )
				));
		$set_tgl_akhir = date( 'Y-m-t 23:59:59', 
			strtotime(
				date( $tahun .'-'. $this->security->xss_clean($_GET['m']) .'-01' )
				));
		$whereBank = empty($this->security->xss_clean($_GET['b'])) ? "" : " and id_bank='". $this->security->xss_clean($_GET['b']) ."'";

		$cmd = "select no_polisi,nama_pemilik,alamat,concat(tgl_bayar, ' ', jam_bayar) as tgl_bayar,b.nama_bank,kode_billing,bayar_". $this->security->xss_clean($_GET['jnsbyr']) ."_pokok as pokok,bayar_". $this->security->xss_clean($_GET['jnsbyr']) ."_denda as denda from v_jurnal_transaksi_penerimaan_pkb a
		left join dt_bank_pembayaran b
			on b.id_bank=a.id_bank
			where tgl_pembayaran>='". $set_tgl_awal ."' and tgl_pembayaran<='". $set_tgl_akhir ."'". $whereBank;
		return $this->dbesamjat->query($cmd);
	}

	public function get_jurnal_potensi_by_lokasi(){
		$paramtahun = $this->security->xss_clean($_GET['y']);
		$tahun = $this->Thanggaran->getDataByID($paramtahun)->row()->th_anggaran;

		$set_tgl_awal = date( 'Y-m-d 00:00:00', 
			strtotime(
				date( $tahun .'-'. $this->security->xss_clean($_GET['m']) .'-01' )
				));
		$set_tgl_akhir = date( 'Y-m-t 23:59:59', 
			strtotime(
				date( $tahun .'-'. $this->security->xss_clean($_GET['m']) .'-01' )
				));
		$whereBank = empty($this->security->xss_clean($_GET['b'])) ? "" : " and id_bank='". $this->security->xss_clean($_GET['b']) ."'";

		$cmd = "select no_polisi,nama_pemilik,alamat,concat(tgl_bayar, ' ', jam_bayar) as tgl_bayar,b.nama_bank,kode_billing,bayar_". $this->security->xss_clean($_GET['jnsbyr']) ."_pokok as pokok,bayar_". $this->security->xss_clean($_GET['jnsbyr']) ."_denda as denda from v_jurnal_transaksi_penerimaan_pkb a
		left join dt_bank_pembayaran b
			on b.id_bank=a.id_bank
			where tgl_pembayaran>='". $set_tgl_awal ."' and tgl_pembayaran<='". $set_tgl_akhir ."' and id_lokasi='". substr($this->security->xss_clean($_GET['lok']),2) ."'". $whereBank;
		return $this->dbesamjat->query($cmd);
	}
}