<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtdistribusibbm extends CI_Controller {

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
	private $folder = 'inputan/';
	
	public function __construct() {
        parent::__construct();
        $this->load->Model('Pbbkb');
        $this->load->Model('Entriandistribusi');
		if($this->session->userdata('status_login')==FALSE){
            redirect('login');
        }
    }
	
	public function index()
	{
		$data['title'] = 'Input Data Distribusi BBM di Badan Usaha';
		$data['subtitle'] = '';
		$data['dinamisContent'] = $this->folder.'data transaksi distribusi bahan bakar';
		
        $this->load->view($this->Opsisite->getDataSite() ['default_template'], $data);
	}

	public function act_crud()
	{
		$json=array();
		$error="";
		$msg="";

		$postAksi = $this->security->xss_clean($_POST['haksi']);
		$postID = $postAksi == 'add' ? time() : $this->security->xss_clean($_POST['hID']);
		$postPerusahaan = $this->security->xss_clean($_POST['slctspbu']);
		$postTh = $this->security->xss_clean($_POST['slcttahun']);
		$postBulan = $this->security->xss_clean($_POST['slctbulan']);
		$postTgl = $this->security->xss_clean($_POST['slcttgl']);
		$postPenyalur = $this->security->xss_clean($_POST['slctpenyedia']);
		$getUser = $this->session->userdata('id_user');
		$last_update = date('Y-m-d H:i:s');
		
		$n = 0;
		foreach ($_POST['idpbbkb'] as $r) {
			$arr[$n][1] = $r;
			$n++;
		}

		$n = 0;
		foreach ($_POST['txtliter'] as $r) {
			$arr[$n][2] = $r;
			$n++;
		}

		$i = 0;
		$arrPostItem = array();
		foreach( $arr as $p ) {

			$arrPostItem[] = array(
				'id_item_distribusi_bbm' => $postID.$i,
				'id_distribusi_bbm' => $postID,
				'id_item_pbbkb' => $p[1],
				'jumlah' => $p[2] 
				);
			$i++;
		}

		switch ($postAksi) {
			case 'add':
				$arrPostDistribusi = array(
					'id_distribusi_bbm' => $postID,
					'id_lokasi_pbbkb' => $postPerusahaan,
					'id_anggaran' => $postTh,
					'id_bulan' => $postBulan,
					'tgl_entry' => $postTgl,
					'id_penyalur' => $postPenyalur,
					'create_by' => $getUser,
					'last_update' => $last_update
					);

				$result = $this->Entriandistribusi->tambah($arrPostDistribusi, $arrPostItem);
				break;

			case 'edit':
				$arrPostDistribusi = array(
						'id_lokasi_pbbkb' => $postPerusahaan,
						'id_anggaran' => $postTh,
						'id_bulan' => $postBulan,
						'tgl_entry' => $postTgl,
						'id_penyalur' => $postPenyalur,
						'create_by' => $getUser,
						'last_update' => $last_update
						);
					
				$result = $this->Entriandistribusi->ubah($arrPostDistribusi, $arrPostItem, $postID);
				break;

			default:
				$error = 'Maaf perintah tidak diketemukan ! Eksekusi Gagal !';
				break;
		}

		

		if (!empty($result)):
			$error = $result;
		else:
			$this->db->trans_commit();
			$msg = 'Transaksi berhasil di simpan !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

	public function get_json_grid()
	{
		$cond = '1=1';
		$sort = empty($_POST['sortdatafield']) || $_POST['sortdatafield'] == '' ? 'id_distribusi_bbm asc' : $_POST['sortdatafield']." ". $_POST['sortorder'];
		$cond .= empty($_POST['cariBU']) || $_POST['cariBU'] == '' ? '' : " and nama_spbu like '%". $_POST['cariBU'] ."%'";
		$cond .= empty($_POST['cariPenyalur']) || $_POST['cariPenyalur'] == '' ? '' : " and nama_penyalur like '%". $_POST['cariPenyalur'] ."%'";
		$cond .= empty($_POST['cariBulan']) || $_POST['cariBulan'] == '' ? '' : " and id_bulan='". $_POST['cariBulan'] ."'";
		$cond .= empty($_POST['cariTh']) || $_POST['cariTh'] == '' ? '' : " and id_anggaran='". $_POST['cariTh'] ."'";

		$cmd = "select id_distribusi_bbm,tgl_input,nama_penyalur,provinsi,kota_asal,nama_spbu,lokasi,total from v_entry_distribusi where ". $cond ." order by ". $sort;
		$items = $this->db->query($cmd)->result_array();

		echo json_encode($items);
	}

	public function view_detil()
	{
		$json = array();
		$error = '';
		$getEntrian = '';
		$getItem = '';
		$postId = $this->security->xss_clean($_POST['postid']);

		$getEntrian = $this->Entriandistribusi->getDataById($postId);
		$count = $getEntrian->num_rows();
		if ( $count == 1 ) {
			$getEntrian = $getEntrian->row();
			$getItem = $this->Entriandistribusi->getItemEntrianById($postId);
		} else {
			$error = 'Maaf Data tidak di ketemukan. Kemungkinan data telah di hapus. Silahkan ulangi cari tabel !';
		}

		$json['error']=$error;
		$json['getEntrian'] = $getEntrian;
		$json['itemEntrian'] = $getItem;
		echo json_encode($json);
	}

	public function act_delete()
	{
		$json=array();
		$error="";
		$msg="";

		$postID = $this->security->xss_clean($_POST['id']);
		$result = $this->Entriandistribusi->hapus($postID);

		if (!empty($result)):
			$error = $result;
		else:
			$this->db->trans_commit();
			$msg = 'Transaksi berhasil di hapus !';
		endif;

		$json['error']=$error;
		$json['msg']=$msg;
		echo json_encode($json);
	}

}
