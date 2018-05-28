<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'welcome';
$route['default_controller'] = 'home';

$route['login'] = 'login';
$route['checkinglogin'] = 'login/validasi_login';

$route['dtusers'] = 'pengguna';
$route['aksi_users'] = 'pengguna/act_crud';
$route['load_data_users'] = 'pengguna/json_users';
$route['detil_data_user'] = 'pengguna/json_detil';

$route['setanggaran'] = 'setthanggaran';
$route['aksi_th_anggaran'] = 'setthanggaran/act_crud';
$route['load_data_th_anggaran'] = 'setthanggaran/json_data_grid';
$route['detil_data_th_anggaran'] = 'setthanggaran/json_detil';

$route['settarget'] = 'settingtarget';
$route['aksi_target'] = 'settingtarget/act_crud';
$route['load_data_target'] = 'settingtarget/json_grid';
$route['detil_data_target'] = 'settingtarget/json_detil';

$route['targetpelypd'] = 'targetpelayananpd';
$route['aksi_targetpelypd'] = 'targetpelayananpd/act_crud';
$route['load_data_targetpelypd'] = 'targetpelayananpd/json_grid';
$route['detil_data_targetpelypd'] = 'targetpelayananpd/json_detil';

$route['datapegawai'] = 'dtpegawai';
$route['aksi_pegawai'] = 'dtpegawai/act_crud';
$route['load_data_pegawai'] = 'dtpegawai/json_grid';
$route['load_data_pegawai_lama'] = 'dtpegawai/json_grid_old';
$route['detil_data_pegawai'] = 'dtpegawai/json_detil';
$route['get_lokasi_homebase'] = 'dtpegawai/json_homebase';
$route['get_select_by_nama_pegawai'] = 'dtpegawai/json_select_by_name';
$route['inputmutasikeluar/(:num)'] = 'dtpegawai/form_mutasi/$1';
$route['getlokasid2dfrommutasi'] = 'dtpegawai/json_lokasi_d2d';
$route['grid_pegawai'] = 'dtpegawai/json_grid_easyui';
$route['upload_foto_pegawai'] = 'dtpegawai/actuploadpicture';
$route['clear_foto'] = 'dtpegawai/actclearpicture';

$route['history_pegawai'] = 'riwayatpegawai';
$route['load_grid_per_pegawai'] = 'riwayatpegawai/json_grid';
$route['aksi_riwayat_lokasi'] = 'riwayatpegawai/act_crud';
$route['detil_data_riwayat'] = 'riwayatpegawai/json_detil';
$route['get_selet_lokasi_aktif_by_pegawai'] = 'riwayatpegawai/json_lokasi_aktif';

$route['inputresume'] = 'rekaminputan';
$route['show_form_inputan'] = 'rekaminputan/json_detil_rekam_by_pegawai';
$route['aksi_simpan_rekam'] = 'rekaminputan/act_crud';
$route['grid_rekam_pegawai'] = 'rekaminputan/json_grid_pegawai';

$route['pungutpd'] = 'pungutanpd';
$route['load_pungutpd'] = 'pungutanpd/json_detil_rekam_by_lokasi';
$route['aksi_pungutpd'] = 'pungutanpd/act_crud';

$route['ubahkatasandi'] = 'updatepassword';
$route['aksi_ubah_password'] = 'updatepassword/act_change_password';

$route['ubahdatapengguna'] = 'updateprofile';
$route['aksi_ubah_datapengguna'] = 'updateprofile/act_update';

$route['rekapupad'] = 'laprekapupad';
$route['viewlaprekapperupad/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'laprekapupad/act_report/$1/$2/$3/$4/$5/$6/$7/$8';
$route['viewpdfrekapd2d/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'laprekapupad/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8';
$route['viewexcelrekapperupad'] = 'laprekapupad/get_excel';

$route['resumed2d'] = 'resumed2dperupad';
$route['viewresumed2dperupad/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'resumed2dperupad/act_report/$1/$2/$3/$4/$5/$6';
$route['viewpdfresumed2d/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'resumed2dperupad/get_pdf/$1/$2/$3/$4/$5/$6';

$route['reportpd'] = 'reportpd';
$route['viewobyekpd/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reportpd/act_report/$1/$2/$3/$4/$5/$6/$7/$8';
$route['viewpdfobyekpd/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'reportpd/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8';

$route['setparaf'] = 'aturparaf';
$route['aksi_setparaf'] = 'aturparaf/act_crud';
$route['get_setparaf'] = 'aturparaf/json_detil';
$route['hapus_setting_pejabat'] = 'aturparaf/act_del';

$route['aksi_mutasi'] = 'dtmutasi/aksi';

$route['pegawaimutasikeluar'] = 'dtmutasikeluar';
$route['load_data_mutasi_keluar'] = 'dtmutasikeluar/json_grid';
$route['detil_mutasi_pegawai'] = 'dtmutasikeluar/json_detil';
$route['detil_tambah_mutasi_pegawai'] = 'dtmutasikeluar/json_detil_untuk_penambahan_mutasi';

$route['statusmutasi'] = 'stsmutasi';
$route['load_data_mutasi_masuk'] = 'stsmutasi/json_grid';
$route['set_aktif_mutasi'] = 'stsmutasi/activate';

$route['list_daftar_pegawai_aktif'] = 'dtpegawai/tabeldatapegawaiaktif';

$route['mutasimasuk'] = 'dtmutasimasuk';
$route['load_data_mutasi_masuk'] = 'dtmutasimasuk/json_grid';
$route['get_detil_lokasi_pegawai'] = 'dtmutasimasuk/json_lokasi_pegawai';
$route['aksi_mutasi_masuk'] = 'dtmutasimasuk/aksi_crud';

$route['itempbbkb'] = 'itempbbkb';
$route['dataitempbbkb'] = 'itempbbkb/load_json_grid';
$route['aksi_item_pbbkb'] = 'itempbbkb/act_crud';
$route['select_item_pbbkb'] = 'itempbbkb/item_detil';

$route['page_kecamatan'] = 'dtkecamatan';
$route['load_data_kecamatan'] = 'dtkecamatan/load_json_grid';
$route['view_kecamatan'] = 'dtkecamatan/view_detil';
$route['aksi_kecamatan'] = 'dtkecamatan/act_crud';
$route['get_json_kecamatan_by_lokasi'] = 'dtkecamatan/json_kecamatan_by_lokasi';

$route['page_spbu'] = 'dtspbu';
$route['load_data_spbu'] = 'dtspbu/load_json_grid';
$route['view_spbu'] = 'dtspbu/view_detil';
$route['aksi_spbu'] = 'dtspbu/act_crud';
$route['get_json_penyetor_by_id_lokasi'] = 'dtspbu/get_json_spbu';


$route['entrypbbkb'] = 'entrypbbkb';
$route['view_rekam_pbbkb'] = 'entrypbbkb/json_entry';
$route['aksi_rekam_pbbkb'] = 'entrypbbkb/act_crud';
$route['get_json_opsi_tanggal'] = 'entrypbbkb/get_opsi_tgl';
$route['aksi_hapus_rekam_pbbkb'] = 'entrypbbkb/delete_rekam';

$route['transaksi_penyaluran_bbm'] = 'dtdistribusibbm';
$route['aksi_penyaluran_bbm'] = 'dtdistribusibbm/act_crud';
$route['load_data_entrian_distribusi_bbm'] = 'dtdistribusibbm/get_json_grid';
$route['view_entrian_distribusi'] = 'dtdistribusibbm/view_detil';
$route['hapus_entrian_distribusi_bbm'] = 'dtdistribusibbm/act_delete';


$route['pbbkbbulanan'] = 'pbbkbperbulan';
$route['viewrekappbbkbperbulan/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pbbkbperbulan/act_report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
$route['viewpdfpbbkbperbulan/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pbbkbperbulan/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';

$route['pbbkbperupad'] = 'pbbkbperupad';
$route['viewrekappbbkbperupad/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pbbkbperupad/act_report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';
$route['viewpdfpbbkbperupad/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'pbbkbperupad/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';

$route['kota_penyalur_bbm'] = 'dtasalkotabbm';
$route['aksi_kota_penyalur_bbm'] = 'dtasalkotabbm/act_crud';
$route['load_data_kota_penyalur'] = 'dtasalkotabbm/load_json_grid';
$route['view_kota_asal'] = 'dtasalkotabbm/view_detil';
$route['get_json_kota_asal_by_provinsi'] = 'dtasalkotabbm/json_kota_by_provinsi';


$route['data_penyalur_bbm'] = 'dtpenyalurbbm';
$route['aksi_penyalur_bbm'] = 'dtpenyalurbbm/act_crud';
$route['load_data_penyalur_bbm'] = 'dtpenyalurbbm/load_json_grid';
$route['view_penyalur_bbm'] = 'dtpenyalurbbm/view_detil';
$route['get_opsi_penyalur_by_kota'] = 'dtpenyalurbbm/get_data_by_id_kota';

$route['laporan_bulanan_distribusi_bbm'] = 'montlyreportdistributionbbm';
$route['viewreportmontlydistributionbbm/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'montlyreportdistributionbbm/act_report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';
$route['viewpdfmontlydistributionbbm/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'montlyreportdistributionbbm/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10';

$route['rekap_obyk_spbu'] = 'laporanrekapobyekspbu';
$route['view_rekap_obyk_spbu/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'laporanrekapobyekspbu/act_report/$1/$2/$3/$4/$5/$6/$7/$8';
$route['pdf_rekap_obyk_spbu/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'laporanrekapobyekspbu/pdf/$1/$2/$3/$4/$5/$6/$7/$8';

$route['rekam_pungutan_pkb'] = 'rekampungutanpkb';
$route['get_array_lokasi_by_induk'] = 'rekampungutanpkb/arrLokasi';
$route['get_inputan_pkb'] = 'rekampungutanpkb/arrinputan';
$route['aksi_rekam_pkb'] = 'rekampungutanpkb/act';
$route['hapus_rekam_pkb'] = 'rekampungutanpkb/hapus';

$route['rekam_pungutan_bbnkb'] = 'rekampungutanbbnkb';
$route['get_inputan_bbnkb'] = 'rekampungutanbbnkb/arrinputan';
$route['aksi_rekam_bbnkb'] = 'rekampungutanbbnkb/act';
$route['hapus_rekam_bbnkb'] = 'rekampungutanbbnkb/hapus';

// Script update 23 08 2016
$route['rekap_pkb_bbnkb'] = 'laporanrekappkbbbnkb';
$route['get_array_tipe_rekening'] = 'laporanrekappkbbbnkb/tiperekening';
$route['viewlaporanrekappkbbbnkbsejateng/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrekappkbbbnkb/act_report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12/$13/$14/$15/$16';
$route['pdflaporanrekappkbbbnkbsejateng/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrekappkbbbnkb/show_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12/$13/$14/$15/$16';

$route['laporan_pd_06_07_02'] = 'laporanpd060702';
$route['viewlaporanpd060702/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanpd060702/act_report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';
$route['pdflaporanpd060702/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanpd060702/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';

$route['rekonapptopad'] = 'laporanrekondata';
$route['viewrekonapptopad/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrekondata/act_report/$1/$2/$3/$4/$5/$6/$7/$8';
$route['pdfrekonapptopad/(:any)/(:any)/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrekondata/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8';

$route['reportrd01'] = 'laporanretribusi01';

// update untuk aplikasi retribusi 24 10 2016
$route['pemilihankoderekening'] = 'pilihkoderekening';
$route['cari_pengguna_pemilihankoderekening'] = 'pilihkoderekening/result_search_user';
$route['tampilkan_data_setting_kode_rekening'] = 'pilihkoderekening/arr_result_setting';
$route['aksi_setting_kode_rekening'] = 'pilihkoderekening/act_crud';

$route['entryretribusi'] = 'entryrekamretribusi';
$route['form_entry_retribusi'] = 'entryrekamretribusi/open_form';
$route['simpan_entrian_retribusi'] = 'entryrekamretribusi/save_form';
$route['hapus_entrian_retribusi'] = 'entryrekamretribusi/delete_entry';

$route['data_skpd_lain'] = 'dataskpdlain';
$route['act_data_skpd_lain'] = 'dataskpdlain/act_crud';
$route['grid_data_skpd_lain'] = 'dataskpdlain/json_tabel';
$route['row_data_skpd_lain'] = 'dataskpdlain/json_row';

$route['rekening_skpd_lain'] = 'datarekeningskpdlain';
$route['get_option_sub_rekening_skpd_lain'] = 'datarekeningskpdlain/sub_rekening_option';
$route['aksi_rekening_skpd_lain'] = 'datarekeningskpdlain/act';
$route['grid_rekening_skpd_lain'] = 'datarekeningskpdlain/json_tabel';
$route['get_no_rekening_by_id'] = 'datarekeningskpdlain/get_no_rekening';
$route['get_option_skpd_lain'] = 'datarekeningskpdlain/get_opsi_skpd_lain';

$route['entry_retribusi_skpd_lain'] = 'entriretribusiskpdlain';
$route['view_form_rekening_skpd_lain'] = 'entriretribusiskpdlain/json_view_rekening_skpd_lain';
$route['simpan_entrian_retribusi_skpd_lain'] = 'entriretribusiskpdlain/act';
$route['hapus_entri_retribusi_skpd_lain'] = 'entriretribusiskpdlain/hapus';

// 08 11 2016
$route['laporan_rd'] = 'laporanrd';
$route['report_rd/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrd/report/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';
$route['pdf_rd/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanrd/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11/$12';

//14 11 2016
$route['entri_target_retribusi_skpd_lain'] = 'entritargetretribusiskpdlain';
$route['view_entri_target_rekening_skpd_lain'] = 'entritargetretribusiskpdlain/json_view_rekening_skpd_lain';
$route['simpan_target_retribusi_skpd_lain'] = 'entritargetretribusiskpdlain/act';
$route['hapus_target_retribusi_skpd_lain'] = 'entritargetretribusiskpdlain/hapus';

$route['laporan_buku_bantu_monitor_tunggakan'] = 'laporanbukubantumonitoringtunggakan';
$route['report_buku_bantu_monitoring_retribusi/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanbukubantumonitoringtunggakan/report/$1/$2/$3/$4/$5/$6/$7/$8';
$route['pdf_buku_bantu_monitoring_retribusi/(:any)/(:any)/(:any)/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'laporanbukubantumonitoringtunggakan/get_pdf/$1/$2/$3/$4/$5/$6/$7/$8';

// Update 01032017 fasilitas PAP
$route['data_jenis_peruntukan_pap'] = 'datajenisperuntukanpap';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
