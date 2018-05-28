<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtpegawai extends CI_Controller {

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
	private $folder = 'pengaturan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->Model('Pegawai');
        $this->load->Model('Trxd2d');
		$this->load->Model('Mutasi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'DATA PEGAWAI UP3AD';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'management pegawai';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function tabeldatapegawaiaktif()
	{
		$this->load->view('pegawai/data pegawai aktif untuk mutasi');
	}

	public function form_mutasi($getID)
	{
		$get = $this->Mutasi->getDataById($this->security->xss_clean($getID));

		if ( $get->num_rows() == 0 ) {
			echo 'Maaf Data tidak diketemukan !';
		} elseif ($this->Mutasi->getJumlahMutasiSebelumnya($this->security->xss_clean($getID)) > 0) {
			echo 'Maaf Pegawai telah melakukan mutasi sebelumnya. Silahkan cari di riwayat mutasi keluar untuk merubah data !';
		} else {
			$row = $get->row();

			$data['title'] = 'FORM MUTASI KELUAR';
			$data['dinamisContent'] = 'pegawai/form mutasi keluar pegawai';
			$data['pegawai'] = $row;
			$data['getjabatan'] = $row->id_sts_pegawai;

			$this->load->view($this->Opsisite->getDataSite() ['blank_template'], $data);
		}
		
	}

	private function pengecekanInputByNipJikaASN($sts, $nip)
	{
		$result = 0;
		if ( $sts == '33' ) {
			if ( $this->Pegawai->cekIDByNip($nip) == 0 ) {
				$result = 0;
			} else {
				$result = 1;
			}
		} else {
			$result = 0;
		}
		return $result;
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";
		$id = time();
		$track = date('Y-m-d H:i:s');
		

		switch ($_POST['haksi']) {
			
			case 'add':
				$nip = $_POST['slctstatus'] == '99' ? '' : $_POST['slctstatus'] == '66' ? $this->security->xss_clean($_POST['txtnrp']) : $this->security->xss_clean($_POST['txtnip']);
				$tgl_lahir = $_POST['slctstatus'] == '99' ?  $this->Fungsi->formatdatetosql($this->security->xss_clean($_POST['txttgllahir'])) : $this->Fungsi->getDateFromNip($nip);
				$arrPostASN = array();
				//Jika status ASN dan Cek NIP
				if ( $this->pengecekanInputByNipJikaASN( $this->security->xss_clean($_POST['slctstatus']), $this->security->xss_clean($_POST['txtnip'])) == 0 ) {
					//Jika status ASN
					$arrPost = array(
						'id_pegawai' => $id,
						'nip' => $nip,
						'id_sts_pegawai' => $this->security->xss_clean($_POST['slctstatus']),
						'nama_pegawai' => strtoupper($this->security->xss_clean($_POST['txtnama'])),
						'tgl_lahir' => $_POST['slctstatus'] == 66 ? '' : $tgl_lahir,
						'no_hp' => $_POST['slctstatus'] == 66 ? '' : $this->security->xss_clean($_POST['txtnohp']),
						'id_pangkat' => $this->security->xss_clean($_POST['slctpangkat']),
						'id_user' => $this->session->userdata('id_user'),
						'history_buat' => $track
					);

					$arrPostMutasi = array(
						'id_mutasi' => $id.'99',
						'id_pegawai' => $id,
						'id_tujuan_mutasi' => $this->Lokasi->getDataById($this->security->xss_clean($_POST['slcthomebase']))->row()->id_induk,
						'id_lokasi' => $this->security->xss_clean($_POST['slctlokasi']),
						'id_homebase' => $_POST['slctstatus'] == 66 ? '' : $this->security->xss_clean($_POST['slcthomebase']),
						'id_jabatan' => $this->security->xss_clean($_POST['slctjabatan']),
						'tgl_sk' => '2016-01-01',
						'tgl_mulai' => '2016-01-01',
						'id_user' => $this->session->userdata('id_user'),
						'update_akhir' => $track,
						'aktif' => 1
					);

					$result = $this->Pegawai->tambah($arrPost, $arrPostMutasi);
					if ( $result == ''){
						$msg = 'Pegawai Baru berhasil di simpan !';
					}else{
						$error = $result;
					}
				} else {
					$error = "Maaf NIP sudah pernah terdaftar !";
				}
				break;
			case 'edit':
				$nip = $_POST['slctstatus'] == '99' ? '' : $_POST['slctstatus'] == '66' ? $this->security->xss_clean($_POST['txtnrp']) : $this->security->xss_clean($_POST['txtnip']);
				$tgl_lahir = $_POST['slctstatus'] == '99' ?  $this->Fungsi->formatdatetosql($this->security->xss_clean($_POST['txttgllahir'])) : $this->Fungsi->getDateFromNip($nip);
				// Cek penggunaan NIP tidak boleh kembar kecuali ID Pengguna
				if ( $this->Pegawai->cekEditNIP( $nip, $this->security->xss_clean($_POST['hID']) ) == 0 ) {
					$arrPost = array(
						'id_sts_pegawai' => $this->security->xss_clean($_POST['slctstatus']),
						'nip' => $nip,
						'nama_pegawai' => strtoupper($this->security->xss_clean($_POST['txtnama'])),
						'id_pangkat' => $this->security->xss_clean($_POST['slctpangkat']),
						'tgl_lahir' => $tgl_lahir,
						'no_hp' => $this->security->xss_clean($_POST['txtnohp']),
						'id_user' => $this->session->userdata('id_user'),
						'history_update' => $track
					);
					
					$result = $this->Pegawai->update($arrPost, $this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Data Pegawai berhasil di diperbaharui !'. $tgl_lahir;
					}else{
						$error = $result;
					}
				} else {
					$error = 'Maaf NIP sudah pernah di gunakan. Mohon di cek kembali !';
				}

				
				break;
			case 'validasi':
				$arrPost = array(
					'aktif' => $this->security->xss_clean($_POST['value'])
				);

				$result = $this->Pegawai->validasi($arrPost, $this->security->xss_clean($_POST['hID']));
				if ( $result == ''){
					$msg = 'Data Pegawai berhasil di diperbaharui !';
				}else{
					$error = $result;
				}
				break;
			case 'delete':

				if ( $this->Trxd2d->countTransaksiByIdPegawai($this->security->xss_clean($_POST['hID'])) == 0 ) {
					$result = $this->Pegawai->delete($this->security->xss_clean($_POST['hID']));
					if ( $result == ''){
						$msg = 'Data Pegawai berhasil di dihapus !';
					}else{
						$error = $result;
					}
				} else {
					$error = 'Maaf pegawai tidak dapat dihapus karena sudah pernah melakukan input transaksi !';
				}

				break;
			default:
				$error = 'Aksi tidak ditemukan !';
				break;
		}

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function json_grid()
	{
		
		$where = "id_jenis_mutasi='333333' AND aktif=1";		
		$where .= empty($_POST['postLokasi']) || $_POST['postLokasi'] == "" || $_POST['postLokasi'] == "99" ? "" : " and id_lokasi LIKE '%". $_POST['postLokasi'] ."%'";
		$where .= empty($_POST['postNIP']) || $_POST['postNIP'] == "" ? "" : " and nip LIKE '%". $_POST['postNIP'] ."%'";
		$where .= empty($_POST['postNama']) || $_POST['postNama'] == "" ? "" : " and nama_pegawai LIKE '%". $_POST['postNama'] ."%'";
		$where .= empty($_POST['postStatus']) || $_POST['postStatus'] == "" ? "" : " and id_sts_pegawai='". $_POST['postStatus'] ."'";
		
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'kepalaupad DESC,id_jabatan,id_homebase DESC,id_sts_pegawai' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		
		$items = $this->Pegawai->getSortPegawai($where, $sort)->result_array();

		echo json_encode($items);
	}
	
	public function json_grid_old()
	{
		$page = $_POST['current'];
		$countrow = $_POST['rowCount'];
		$offset = ($page-1)*$countrow;
		$urutan="kepalaupad DESC,id_jabatan,id_homebase DESC,id_sts_pegawai";
		$where = "id_jenis_mutasi='333333' AND aktif=1";
		
		$where .= empty($_POST['postLokasi']) || $_POST['postLokasi'] == "" || $_POST['postLokasi'] == "99" ? "" : " and id_lokasi LIKE '%". $_POST['postLokasi'] ."%'";
		$where .= empty($_POST['postNIP']) || $_POST['postNIP'] == "" ? "" : " and nip LIKE '%". $_POST['postNIP'] ."%'";
		$where .= empty($_POST['postNama']) || $_POST['postNama'] == "" ? "" : " and nama_pegawai LIKE '%". $_POST['postNama'] ."%'";
		
		//Handles Sort querystring sent from Bootgrid
		if (isset($_REQUEST['sort']) && is_array($_REQUEST['sort']) )
		{
			$urutan = "";
			$jmlSort = count($_REQUEST['sort']);
			$i = 1;
			foreach($_REQUEST['sort'] as $key=> $value)
			{
				$urutan.="$key $value";
				if ( $i < $jmlSort ){
					$urutan.=",";
				}else{
					$urutan.="";
				}
				$i++;
			}
		}
		
		//Handles search querystring sent from Bootgrid
		//if (isset($_REQUEST['searchPhrase']) )
		//{
		//$search=trim($_REQUEST['searchPhrase']);
		//$where.= " AND ( movie LIKE '".$search."%' OR year LIKE '".$search."%' OR genre LIKE '".$search."%' ) ";
		//}
		
		$total = $this->Pegawai->countDataAll($where);
		$query = $this->Pegawai->getSortPegawaiOld($offset,$countrow,$where,$urutan);
		$cek = $query->num_rows();
		
		$data = '';
		if ( $cek >= 1 ){
			
			$i = 1;
			foreach ( $query->result() as $row ){
				$data .= '{"id_pegawai" : "'. $row->id_pegawai .'",
				"id_mutasi" : "'. $row->id_mutasi .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"nip" : "'. $row->nip .'",
				"jabatan" : "'. $row->jabatan .'",
				"nama_lokasi" : "'. $row->nama_lokasi .'",
				"nama_homebase" : "'. $row->nama_homebase .'",
				"status_pegawai" : "'. $row->status_pegawai .'",
				"aktif" : "'. $row->aktif .'"}';
				$i++;
				if ( $i > $cek ){
					$data .= '';
				}else{
					$data .= ',';
				}
				
			}
		}
		
		$json = '{"current": '. $page .',
		  "rowCount": '. $countrow .',
		  "rows": [';
			$json .= $data;
		$json .='],
		"total": '. $total .'}';
		
		echo $json;
	}

	public function json_grid_easyui()
	{
		$offset=0;
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        
        //$filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
        $cond = "id_jenis_mutasi='333333' AND aktif=1";

        $urutan="";
        if(isset($_POST['sort'])){
            $pisah_sort=explode(',',$_POST['sort']);
            $pisah_order=explode(',',$_POST['order']);
            $jml_urutan=count($pisah_sort);
            if($jml_urutan>0){
                for($j=0;$j<$jml_urutan;$j++){
                    
                    $sort = isset($pisah_sort[$j]) ? strval($pisah_sort[$j]) : '';
                    $order = isset($pisah_order[$j]) ? strval($pisah_order[$j]) : '';
                    $urutan.=$sort.' '.$order;
                    $urutan.=',';
                }
            }
        }
        $urutan.='kepalaupad DESC,id_jabatan,id_homebase DESC,id_sts_pegawai';

        $total = $this->Pegawai->countDataAll($cond);
		$query = $this->Pegawai->getSortPegawai($offset,$rows,$cond,$urutan);
		$count = $query->num_rows();

        $json = '{"total":' . $total . ',"rows":[';

        $i = 1;

        foreach ($query->result() as $row):
            $json.='{"id_pegawai" : "'. $row->id_pegawai .'",
				"id_mutasi" : "'. $row->id_mutasi .'",
				"nama_pegawai" : "'. $row->nama_pegawai .'",
				"nip" : "'. $row->nip .'",
				"jabatan" : "'. $row->jabatan .'",
				"nama_lokasi" : "'. $row->nama_lokasi .'",
				"nama_homebase" : "'. $row->nama_homebase .'",
				"status_pegawai" : "'. $row->status_pegawai .'",
				"aktif" : "'. $row->aktif .'"}';
            if ($i == $count):
                $json.="";
            else:
                $json.=",";
            endif;
            $i++;
        endforeach;
        $json.=']}';

        echo $json;
	}

	public function json_detil()
	{
		//$this->load->Model('Historilokasi');

		$json=array();
		$error="";
		$id_sts_pegawai = '';
		$nama_pegawai = '';
		$nip = '';
		$id_pangkat = '';
		$id_sts_pegawai = '';
		$arrLokasiAktif = array();
		
		$getData = $this->Pegawai->getDataByID($this->security->xss_clean($_POST['hID']));
		if ( $getData->num_rows() == 1 ){
			$row = $getData->row();
			
			$tgl = empty($row->tgl_lahir) ? '' : $this->Fungsi->formatsqltodate($row->tgl_lahir);
			$id_sts_pegawai = $row->id_sts_pegawai;
			$nama_pegawai = $row->nama_pegawai;
			$nip = $row->nip;
			$id_pangkat = $row->id_pangkat;
			$id_sts_pegawai = $row->id_sts_pegawai;
			$tgl_lahir = $tgl;
			$nohp = $row->no_hp;

		} else {
			$status = 0;
			$error = 'Maaf data tidak dapat ditemukan. Silahkan ulangi pencarian atau reload tabel !';
		}
		
		$json['id_sts_pegawai'] = $id_sts_pegawai;
		$json['nama_pegawai'] = $nama_pegawai;
		$json['nip'] = $nip;
		$json['id_pangkat'] = $id_pangkat;
		$json['no_hp'] = $nohp;
		$json['tgl_lahir'] = $tgl_lahir;
		$json['id_sts_pegawai'] = $id_sts_pegawai;
		$json['arrLokasiAktif'] = $arrLokasiAktif;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

	public function json_select_by_name()
	{
		$where = empty($_GET['lokasi']) ? "" : " and id_lokasi='". $_GET['lokasi'] ."'";
		$cmd = "select id_pegawai,nama_pegawai,nip from v_pegawai_v2 where nama_pegawai LIKE '%". $_GET['q'] ."%'". $where;
		$query = $this->db->query($cmd);
		$count = $query->num_rows();
		echo '{"total_count": '. $count .',
			"incomplete_results": false,
			"items": [';
		$i = 1;
		foreach ( $query->result() as $row){
			echo '{
				"id": "'. $row->id_pegawai .'",
				"nama_pegawai": "'. $row->nama_pegawai .'",
				"nip": "'. $row->nip .'"
			}';
			
			if ( $i < $count ){
				echo ',';
			}
			$i++;
		}
			
		echo ']}';
	}

	public function json_homebase()
	{
		
		$json=array();
		$error = '';
		$arrHomebase = array();

		$cmd = "select * from v_homebase where id_lokasi='". $this->security->xss_clean($_POST['postLokasi']) ."'";
		$q = $this->db->query($cmd);
		if ( $q->num_rows() == 1 ) {
			$row = $q->row();
			if ( $row->d2d_pusat == 1 ) {
				$rowPusat = $this->Lokasi->getAllPusat()->row();
				$arrHomebase[] = array(
					'id_lokasi' => $rowPusat->id_lokasi,
					'lokasi' => $rowPusat->lokasi
				);
			}

			foreach ( $this->Lokasi->getInduknSamtu($row->id_lokasi) as $r ) {
				$arrHomebase[] = array(
					'id_lokasi' => $r['id_lokasi'],
					'lokasi' => $r['lokasi']
				);
			}
		} else {
			$error = 'Maaf Lokasi tidak ditemukan !';
		}
		
		$json['arrHomebase'] = $arrHomebase;
		$json['error'] = $error;
		
		echo json_encode($json);
	}

	public function json_lokasi_d2d()
	{
		$json=array();
		$error="";
		$opsi = array();

		$postHomebase = $this->security->xss_clean($_POST['postLokasiMutasi']);
		$dataLokasi = $this->Lokasi->getLokasiD2D( $postHomebase );
		$opsi = $dataLokasi->result_array();

		$json['error'] = $error;
		$json['opsi'] = $opsi;
		
		echo json_encode($json);
	}

}
