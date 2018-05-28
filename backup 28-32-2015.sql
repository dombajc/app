/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.27 : Database - dbd2d
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `dbd2d`;

/*Table structure for table `lokasi` */

DROP TABLE IF EXISTS `lokasi`;

CREATE TABLE `lokasi` (
  `id_lokasi` varchar(20) NOT NULL,
  `id_induk` varchar(20) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `nama_kota` varchar(50) NOT NULL,
  `alamat` text,
  `kota` varchar(30) DEFAULT NULL,
  `kdpos` varchar(10) DEFAULT NULL,
  `telp` text,
  `fax` varchar(15) DEFAULT NULL,
  `samsat` enum('pusat','besar','kecil','other') DEFAULT NULL,
  `kd_wil` varchar(15) DEFAULT NULL,
  `kabkot` enum('Kota','Kabupaten') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `d2d_pusat` tinyint(1) NOT NULL DEFAULT '0',
  `blokir` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_lokasi`),
  KEY `ID_INDUK` (`id_induk`),
  KEY `3` (`samsat`),
  KEY `4` (`kd_wil`),
  KEY `5` (`kabkot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lokasi` */

insert  into `lokasi`(`id_lokasi`,`id_induk`,`lokasi`,`nama_kota`,`alamat`,`kota`,`kdpos`,`telp`,`fax`,`samsat`,`kd_wil`,`kabkot`,`email`,`d2d_pusat`,`blokir`) values ('05','05','Kantor PUSAT','SEMARANG','Jln.Pemuda No. 1','Semarang','50113','024-899828','023-838888','pusat','SMG','Kota','pusat@gmail.com',0,0),('050','05','ATM BANK JATENG','SEMARANG','BP PUSAT Sub Bag Keuangan\r\nJl. Pemuda No. 1 Semarang','Semarang','','024 3515514','024 3517227','kecil',NULL,NULL,NULL,0,NULL),('0500','05','ATM BANK BRI','SEMARANG','Jl. Pemuda No. 1','Semarang','50142','024-3515514','024-3587227','kecil',NULL,NULL,NULL,0,NULL),('0501','0501','UP3AD Kota Semarang I','UP3AD Kota Semarang I','Jl. Brigjen Sudiarto 428','Semarang','0','024-6712916','024-6715449','pusat','SMG','Kota','up3ad.smg1@gmail.com',1,0),('050101','0501','Samsat Khusus DPMall','UP3AD Kota Semarang I','','Semarang','0','','','kecil','',NULL,'',0,0),('050102','0501','SAMKEL Kota Semarang I','UP3AD Kota Semarang I','','Semarang','0','','','kecil','',NULL,'',0,0),('050103','0501','DRIVE THRU Lotte Mart','UP3AD Kota Semarang I','','Semarang','0','','','kecil','',NULL,'',0,0),('0502','0502','UP3AD Kota Semarang II','UP3AD Kota Semarang II','Jl.Setiabudi No. 110, Srondol ','Semarang','50269','024-7475810','024-7478858','pusat','SMG','Kota','up3ad.smg2@gmail.com',1,0),('050201','0502','SAMKEL Kota Semarang II','UP3AD Kota Semarang II','','Semarang','0','','','kecil','',NULL,'',0,0),('0503','0503','UP3AD Kota Semarang III','UP3AD Kota Semarang III','Jl. Hanoman No. 2','Semarang','50146','024-7601476','024-7601476','pusat','SMG','Kota','up3ad.smg.III@gmail.com',1,0),('050301','0503','SAMSAT Khusus Bank Jateng','UP3AD Kota Semarang III','','Semarang','0','','','kecil','',NULL,'',0,0),('050302','0503','SAMSAT Jateng Fair','UP3AD Kota Semarang III','Arena PRPP','Semarang','0','','','kecil',NULL,NULL,'',0,0),('0504','0504','UP3AD Kota Salatiga','UP3AD Kota Salatiga','Jl. Brigjen Sudiarto No.2','Salatiga','0','0298-327015','0298-327015','pusat','SMG','Kota','up3ad.kota.sltg@gmail.com',0,0),('0505','0505','UP3AD Kabupaten Semarang','UP3AD Kabupaten Semarang','Jl. MT Haryono Sidomulyo Ungaran Timur','Ungaran','50511','+6224-76910391,','+6224-76910392','pusat','SMG','Kota','kabsemarangup3ad@gmail.com',0,0),('050501','0505','SAMKEL UP3AD Kab. Semarang','UP3AD Kabupaten Semarang','','Ungaran','0','','','kecil','',NULL,'',0,0),('050502','0505','Samsat Cepat Klepu','UP3AD Kabupaten Semarang','Klepu','Kab Semarang','','','','kecil',NULL,NULL,NULL,0,NULL),('0506','0506','UP3AD Kabupaten Kendal','UP3AD Kabupaten Kendal','Jl. Soekarno Hatta No. 101','Kendal','51314','0294-381209','0294-381209','pusat','SMG','Kabupaten','up3ad.kab.kendal@gmail.com',0,0),('050601','0506','SAMKEL Kab. Kendal','UP3AD Kabupaten Kendal','','','0','','','kecil',NULL,NULL,'',0,0),('0507','0507','UP3AD Kabupaten Demak','UP3AD Kabupaten Demak','Jl. Sultan Trenggono Katonsari ','Demak','0','0291-686075','0291-686075','pusat','SMG','Kabupaten','up3ad.demak@gmail.com',0,0),('050701','0507','SAMKEL Kab. Demak','UP3AD Kabupaten Demak','','','0','','','kecil',NULL,NULL,'',0,0),('0508','0508','UP3AD Kabupaten Grobogan','UP3AD Kabupaten Grobogan','Jl.P. Diponegoro No. 1','Purwodadi','58111','0292-421303','0292-421303','pusat','PT','Kota','up3adkabgrobogan@gmail.com',0,0),('050801','0508','SAMKEL Kab. Grobogan','UP3AD Kabupaten Grobogan','','','0','','','kecil',NULL,NULL,'',0,0),('050802','0508','SAMSAT PATEN WIROSARI','UP3AD Kabupaten Grobogan','PURWODADI','GROBOGAN','','','','kecil',NULL,NULL,NULL,0,NULL),('0509','0509','UP3AD Kota Surakarta','UP3AD Kota Surakarta','Jalan Prof. DR. Soeharso Nomor 17 Jajar Laweyan','Surakarta','57144','0271 714919,0271 718007,','0271 714919','pusat','SKA','Kota','up3adsurakarta@gmail.com',0,0),('050901','0509','SAMKEL UP3AD Kota Surakarta','UP3AD Kota Surakarta','','Surakarta','0','','','kecil','',NULL,'',0,0),('050902','0509','DRIVE THRU Surakarta','UP3AD Kota Surakarta','','Surakarta','0','','','kecil','',NULL,'',0,0),('050903','0509','Asrama Haji Donohudan','UP3AD Kota Surakarta','','Surakarta','0','','','other',NULL,NULL,'',0,0),('0510','0510','UP3AD Kabupaten Sukoharjo','UP3AD Kabupaten Sukoharjo','Jl. Jaksa Agung R. Suprapto No.9 ','Sukoharjo','0','0271-593145','0271-593145','pusat','SKA','Kabupaten','up3ad.kab.sukoharjo@gmail.com',0,0),('051001','0510','Samkel Kab. Sukoharjo','UP3AD Kabupaten Sukoharjo','','','','','','kecil',NULL,NULL,NULL,0,NULL),('0511','0511','UP3AD Kabupaten Klaten','UP3AD Kabupaten Klaten','','Delanggu','0','','','pusat','SKA','Kabupaten','up3ad.kab.klt.dlg@gmail.com',0,0),('051101','0511','Samsat Pembantu Prambanan','UP3AD Kabupaten Klaten','Jl. Manisrenggo No. 1\r\nDsn. Tlogo Ds. Tlogo, Kec. Prambanan','Prambanan','0','(0274) 496943','(0274) 496943','besar','',NULL,'',0,0),('051102','0511','Samsat Pembantu Delanggu','UP3AD Kabupaten Klaten','','Delanggu','0','','','besar','',NULL,'up3ad.kab.klt.dlg@gmail.com',0,0),('051103','0511','Samsat Keliling Klaten','UP3AD Kabupaten Klaten','','','','','','kecil',NULL,NULL,NULL,0,NULL),('0512','0512','UP3AD Kabupaten Boyolali','UP3AD Kabupaten Boyolali','JL Raya Boyolali-Solo Km. 2  ','Boyolali','0','0276-321412','0276-321412','pusat','SKA','Kabupaten','up3ad_kab_byl@yahoo.co.id',0,0),('051201','0512','SAMKEL UP3AD Kab. Boyolali','UP3AD Kabupaten Boyolali','','Boyolali','0','','','kecil','',NULL,'',0,0),('0513','0513','UP3AD Kabupaten Sragen','UP3AD Kabupaten Sragen','JL.Raya Barat SukowatiI Km. 02 No.17 ','Sragen','0','0271-891260','0271-891260','pusat','SKA','Kabupaten','up3ad.kab.sragen@gmail.com',0,0),('051301','0513','SAMKEL Kab. Sragen','UP3AD Kabupaten Sragen','','','0','','','kecil',NULL,NULL,'',0,0),('051302','0513','SAMSAT PATEN TANON','UP3AD Kabupaten Sragen','','SRAGEN','','','','kecil',NULL,NULL,NULL,0,NULL),('0514','0514','UP3AD Kabupaten Karanganyar','UP3AD Kabupaten Karanganyar','Jl. Lawu No. 389','Karanganyar','0','0271-495121','0271-495121','pusat','SKA','Kabupaten','upppadkabkra@yahoo.co.id',0,0),('051401','0514','SAMKEL Kab. Karanganyar','UP3AD Kabupaten Karanganyar','','','0','','','kecil',NULL,NULL,'',0,0),('0515','0515','UP3AD Kabupaten Wonogiri','UP3AD Kabupaten Wonogiri','Jl. RM. Said','Wonogiri','0','0273-321815','0273-321815','pusat','SKA','Kabupaten','samsat.wng@gmail.com',0,0),('051501','0515','Samsat Pembantu Purwantoro','UP3AD Kabupaten Wonogiri','','Purwantoro','0','','','besar','',NULL,'',0,0),('051502','0515','Samsat Pembantu Baturetno','UP3AD Kabupaten Wonogiri','','Baturetno','0','','','besar','',NULL,'',0,0),('051503','0515','SAMSAT PATEN EROMOKO','UP3AD Kabupaten Wonogiri','','WONOGIRI','','','','kecil',NULL,NULL,NULL,0,NULL),('0516','0516','UP3AD Kabupaten Pati','UP3AD Kabupaten Pati','Jl.Panglima Sudirman No.52 ','Pati','59113','0295-381863','0295-381863','pusat','PT','Kabupaten','up3ad.kab.pati@gmail.com',0,0),('051601','0516','SAMKEL UP3AD Kab. Pati','UP3AD Kabupaten Pati','','Pati','0','','','kecil','',NULL,'',0,0),('051602','0516','SAMSAT Cepat UP3AD Kab. Pati','UP3AD Kabupaten Pati','','Pati','0','','','kecil',NULL,NULL,'',0,0),('0517','0517','UP3AD Kabupaten Kudus','UP3AD Kabupaten Kudus','Jl. Mejobo No. 63','Kudus','59319','0291-438412','0291-438412','pusat','PT','Kabupaten','up3ad.Kab.kudus@gmail.com',0,0),('0518','0518','UP3AD Kabupaten Jepara','UP3AD Kabupaten Jepara','JL. MT. Haryono No. 2 ','Jepara','59418','0291-591402','0291-591402','pusat','PT','Kabupaten','up3ad.kab.jepara@gmail.com',0,0),('051801','0518','SAMSAT Khusus Karimunjawa','UP3AD Kabupaten Jepara','','Karimunjawa','0','','','kecil','',NULL,'',0,0),('051802','0518','SAMKEL Kab. Jepara','UP3AD Kabupaten Jepara','','','0','','','kecil',NULL,NULL,'',0,0),('051803','0518','SAMSAT PATEN MAYONG','UP3AD Kabupaten Jepara','JEPARA','JEPARA','','','','kecil',NULL,NULL,NULL,0,NULL),('0519','0519','UP3AD Kabupaten Rembang','UP3AD Kabupaten Rembang','Jl.Pemuda 90 ','Rembang','59218','0295-691319','0295-691319','pusat','PT','Kabupaten','up3adrembang@gmail.com',0,0),('051901','0519','SAMKEL Kab. Rembang','UP3AD Kabupaten Rembang','','','0','','','kecil',NULL,NULL,'',0,0),('051902','0519','SAMSAT PATEN REMBANG','UP3AD Kabupaten Rembang','','REMBANG','','','','kecil',NULL,NULL,NULL,0,NULL),('0520','0520','UP3AD Kabupaten Blora','UP3AD Kabupaten Blora','Jl Jendral Sudirman No. 108','Blora','58216','0296-531261','0296-531261','pusat','PT','Kabupaten','up3ad.kabblora@gmail.com',0,0),('052001','0520','SAMSAT Pembantu Cepu','UP3AD Kabupaten Blora','JL Pemuda 86','Cepu','0','0296-425305','0296-425305','besar','',NULL,'up3ad.cepu@gmail.com',0,0),('052002','0520','Samsat Keliling Blora','UP3AD Kabupaten Blora','','','','','','kecil',NULL,NULL,NULL,0,0),('052003','0520','SAMSAT PATEN KUNDURAN','UP3AD Kabupaten Blora','','BLORA','','','','kecil',NULL,NULL,NULL,0,NULL),('0521','0521','UP3AD Kota Pekalongan','UP3AD Kota Pekalongan','Jl. Gajahmada Barat No. 25 Tirto','Pekalongan','51119','0285-424337','0285-424337','pusat','PKL','Kota','up3adktpekalongan@gmail.com',0,0),('052101','0521','DRIVE THRU Pekalongan','UP3AD Kota Pekalongan','','Pekalongan','0','','','kecil',NULL,NULL,'',0,0),('0522','0522','UP3AD Kabupaten Pekalongan','UP3AD Kabupaten Pekalongan','Jl. Raya Kajen-Karanganyar Km 2','Kajen','51182','0285-381677','0285-381677','pusat','PKL','Kota','up3ad_kab_pekalongan@yahoo.co.id',0,0),('052201','0522','SAMKEL Kabupaten Pekalongan','UP3AD Kabupaten Pekalongan','UP3AD Kabupaten Pekalongan','Kajen','','','','kecil',NULL,NULL,NULL,0,0),('0523','0523','UP3AD Kabupaten Batang','UP3AD Kabupaten Batang','JL.Urip Sumohardjo No. 15','Batang','51212','0285-391275','0285-391275','pusat','PKL','Kabupaten','up3ad.kab.batang@gmail.com',0,0),('052301','0523','SAMKEL Kab. Batang','UP3AD Kabupaten Batang','','','0','','','kecil',NULL,NULL,'',0,0),('0524','0524','UP3AD Kabupaten Pemalang','UP3AD Kabupaten Pemalang','Jl. Pemuda No. 49','Pemalang','0','0284-321137','0284-321137','pusat','PKL','Kabupaten','samsat.kab.pml@gmail.com',0,0),('052401','0524','SAMKEL UP3AD Kab. Pemalang','UP3AD Kabupaten Pemalang','','Pemalang','0','','','kecil','',NULL,'',0,0),('0525','0525','UP3AD Kota Tegal','UP3AD Kota Tegal','JL. Kapten Sudibyo No. 152','Tegal','52123','0283-353015','0283-353015','pusat','PKL','Kota','up3ad_kotategal@yahoo.co.id',0,0),('0526','0526','UP3AD Kabupaten Tegal','UP3AD Kabupaten Tegal','JL.Cut Nyak Dien Kalisapu','Slawi','5216','0283-6197719','0283-6197719','pusat','PKL','Kota','up3adslawi@gmail.com',0,0),('0527','0527','UP3AD Kabupaten Brebes','UP3AD Kabupaten Brebes','Jl.Gajahmada No.60 ','Brebes','0','0283-671622','0283-671621','pusat','PKL','Kabupaten','up3ad.kab.brebes@gmail.com',0,0),('052701','0527','Samsat Pembantu Bumiayu','UP3AD Kabupaten Brebes','jl.raya pagojengan km2.bumiayu kab.brebes jawa tengah','Bumiayu','52276','0289430104','0289430104','besar','',NULL,'up3ad.brebes.bma@mail.com',0,0),('052702','0527','Samsat Pembantu Tanjung','UP3AD Kabupaten Brebes','Jl. CENDRAWASIH NO.225 TANJUNG BREBES','Tanjung','52154','0283877403','0283877403','besar','',NULL,'',0,0),('052703','0527','SAMSAT PATEN LARANGAN','UP3AD Kabupaten Brebes','JL. RAYA LARANGAN NO 15 KEC. LARANGAN','BREBES','','','','kecil',NULL,NULL,NULL,0,NULL),('0528','0528','UP3AD Kabupaten Banyumas','UP3AD Kabupaten Banyumas','Jl. Prof. Moch. Yamin No. 7','Purwokerto','53142','0281637801','0281631531','pusat','BMS','Kota','bms@gmail.com',0,0),('052801','0528','Samsat Pembantu Wangon','UP3AD Kabupaten Banyumas','','Wangon','0','','','besar','',NULL,'',0,0),('052802','0528','SAMKEL UP3AD Kab. Banyumas (MOBIL)','UP3AD Kabupaten Banyumas','','Purwokerto','0','','','kecil','',NULL,'',0,0),('052803','0528','DRIVE THRU Purwokerto','UP3AD Kabupaten Banyumas','Halaman Gd. BAKORWIL','Purwokerto','0','','','kecil',NULL,NULL,'',0,0),('052804','0528','SAMKEL UP3AD Kab. Banyumas (BUS)','UP3AD Kabupaten Banyumas','','','','','','kecil',NULL,NULL,NULL,0,NULL),('052805','0528','SAMSAT PATEN SOKARAJA','UP3AD Kabupaten Banyumas','PURWOKERTO','BANYUMAS','','','','kecil',NULL,NULL,NULL,0,NULL),('0529','0529','UP3AD Kabupaten Cilacap','UP3AD Kabupaten Cilacap','Jl Kauman No. 11 ','Cilacap','53214','0282-535908','0282-535908','pusat','BMS','Kota','up3ad.kab.cilacap@gmail.com',0,0),('052901','0529','Samsat Pembantu Majenang','UP3AD Kabupaten Cilacap','','Majenang','0','','','besar','',NULL,'',0,0),('052902','0529','SAMKEL Kab. Cilacap','UP3AD Kabupaten Cilacap','','','0','','','kecil',NULL,NULL,'',0,0),('0530','0530','UP3AD Kabupaten Purbalingga','UP3AD Kabupaten Purbalingga','Jl Mayjend Sungkono km.2 Purbalingga','Purbalingga','53371','0281-891277','0281-892765','pusat','BMS','Kota','up3adkabpbg@gmail.com',0,0),('053001','0530','SAMKEL Kab. Purbalingga','UP3AD Kabupaten Purbalingga','','PURBALINGGA','0','','','kecil',NULL,NULL,'',0,0),('053002','0530','SAMSAT PATEN BUKATEJA','UP3AD Kabupaten Purbalingga','','PURBALINGGA','','','','kecil',NULL,NULL,NULL,0,NULL),('0531','0531','UP3AD Kabupaten Banjarnegara','UP3AD Kabupaten Banjarnegara','JL. Letjen S. Parman No. 143','Banjarnegara','53412','0286-591284','0286-591284','pusat','BMS','Kabupaten','up3ad.kab.bna@gmail.com',0,0),('053101','0531','SAMKEL UP3AD Kab. Banjarnegara','UP3AD Kabupaten Banjarnegara','','Banjarnegara','0','','','kecil',NULL,NULL,'',0,0),('0532','0532','UP3AD Kota Magelang','UP3AD Kota Magelang','JL JENDRAL SUDIRMAN NO. 52 MAGELANG  ','Magelang','56126','0293 362493','0293 313279','pusat','MGL','Kota','samsat.kota.mgl@gmail.com',0,0),('053201','0532','DRIVE THRU Magelang','UP3AD Kota Magelang','Halaman Kantor Satlantas Kota Magelang','Magelang','0','','','kecil',NULL,NULL,'',0,0),('0533','0533','UP3AD Kabupaten Magelang','UP3AD Kabupaten Magelang','Jl.Soekarno - Hatta Mungkid','Magelang','56511','0293-788405','0293-788405','pusat','MGL','Kota','up3ad_mungkid@yahoo.com',0,0),('053301','0533','SAMKEL UP3AD Kab. Magelang','UP3AD Kabupaten Magelang','','','0','','','kecil','',NULL,'',0,0),('0534','0534','UP3AD Kabupaten Purworejo','UP3AD Kabupaten Purworejo','jl. jend. sudirman no 17 purworejo','Purworejo','0','0274321466','','pusat','MGL','Kabupaten','up3adpwr@gmail.com',0,0),('053401','0534','Samsat Pembantu Bagelen','UP3AD Kabupaten Purworejo','','Purworejo','54174','0275756306','','besar','',NULL,'up3ad.kab.pwrj.bgl@gmail.com',0,0),('053402','0534','SAMKEL Kab. Purworejo','UP3AD Kabupaten Purworejo','','','0','','','kecil',NULL,NULL,'',0,0),('053403','0534','SAMSAT PATEN Kutoarjo','UP3AD Kabupaten Purworejo','kecamatan Kutoarjo','Purworejo','','','','kecil',NULL,NULL,NULL,0,NULL),('0535','0535','UP3AD Kabupaten Kebumen','UP3AD Kabupaten Kebumen','JL Tentara Pelajar No. 54','Kebumen','54312','0287381490','0287381490','pusat','MGL','Kota','up3adkebumen@yahoo.co.id',0,0),('053501','0535','SAMSAT Cepat Gombong','UP3AD Kabupaten Kebumen','Komplek Bapelkes Gombong\r\nJl. Wilis No. 1','Gombong','0','','','kecil',NULL,NULL,'',0,0),('053502','0535','Samsat Keliling Kebumen','UP3AD Kabupaten Kebumen','','','','','','kecil',NULL,NULL,NULL,0,NULL),('0536','0536','UP3AD Kabupaten Temanggung','UP3AD Kabupaten Temanggung','Jl. Suwandi Suwardi Temanggung','Temanggung','56229','0293491653','0293491653','pusat','MGL','Kota','up3adkabtemanggung@gmail.com',0,0),('053601','0536','SAMKEL Kab. Temanggung','UP3AD Kabupaten Temanggung','','','0','','','kecil',NULL,NULL,'',0,0),('0537','0537','UP3AD Kabupaten Wonosobo','UP3AD Kabupaten Wonosobo','JL. ANGKATAN 45 NO. 1 WONOSOBO','Wonosobo','56311','0286 321264','0286 321264','pusat','MGL','Kota','up3ad.kab.wsb@gmail.com',0,0),('053701','0537','SAMKEL Kab. Wonosobo','UP3AD Kabupaten Wonosobo','','','0','','','kecil',NULL,NULL,'',0,0);

/*Table structure for table `t_bulan` */

DROP TABLE IF EXISTS `t_bulan`;

CREATE TABLE `t_bulan` (
  `id_bulan` char(15) NOT NULL,
  `id_triwulan` char(15) NOT NULL,
  `bulan` char(25) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_bulan`),
  KEY `id_triwulan` (`id_triwulan`),
  KEY `id_bulan` (`id_bulan`,`id_triwulan`),
  CONSTRAINT `t_bulan_ibfk_1` FOREIGN KEY (`id_triwulan`) REFERENCES `t_triwulan` (`id_triwulan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_bulan` */

insert  into `t_bulan`(`id_bulan`,`id_triwulan`,`bulan`,`aktif`) values ('1','01','Januari',1),('10','04','Oktober',1),('11','04','November',1),('12','04','Desember',1),('2','01','Februari',1),('3','01','Maret',1),('4','02','April',1),('5','02','Mei',1),('6','02','Juni',1),('7','03','Juli',1),('8','03','Agustus',1),('9','03','September',1);

/*Table structure for table `t_jabatan` */

DROP TABLE IF EXISTS `t_jabatan`;

CREATE TABLE `t_jabatan` (
  `id_jabatan` char(5) NOT NULL,
  `jabatan` char(25) NOT NULL,
  `non_asn` tinyint(1) NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_jabatan` */

insert  into `t_jabatan`(`id_jabatan`,`jabatan`,`non_asn`,`aktif`) values ('e1','Eselon I',0,1),('e2','Eselon II',0,1),('e3','Eselon III',0,1),('e4','Eselon IV',0,1),('s','Staff',1,1);

/*Table structure for table `t_master_aplikasi` */

DROP TABLE IF EXISTS `t_master_aplikasi`;

CREATE TABLE `t_master_aplikasi` (
  `id_master` char(5) NOT NULL,
  `site` char(100) NOT NULL,
  `site_singkat` char(25) NOT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `alamat_perusahaan` text NOT NULL,
  `kota` char(50) NOT NULL,
  `no_telp` char(50) NOT NULL,
  `nama_pemilik` varchar(100) NOT NULL,
  `slogan` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_master`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_master_aplikasi` */

insert  into `t_master_aplikasi`(`id_master`,`site`,`site_singkat`,`nama_perusahaan`,`alamat_perusahaan`,`kota`,`no_telp`,`nama_pemilik`,`slogan`,`logo`,`aktif`) values ('1','Aplikasi Pembukuan dan Pelaporan Dinas PPAD Provinsi Jawa Tengah','APP DPPAD JATENG','KLINIK KECANTIKAN','Jl.','BREBES','024','Dr.','None',NULL,1);

/*Table structure for table `t_menu_akses` */

DROP TABLE IF EXISTS `t_menu_akses`;

CREATE TABLE `t_menu_akses` (
  `id_menu_akses` char(5) NOT NULL,
  `id_parent` char(5) NOT NULL,
  `sub` tinyint(1) NOT NULL DEFAULT '0',
  `menu` char(50) NOT NULL,
  `module` char(100) DEFAULT NULL,
  `dipilih` tinyint(1) NOT NULL DEFAULT '1',
  `ikon` char(50) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `pusat` tinyint(1) NOT NULL DEFAULT '0',
  `daerah` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_menu_akses`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_menu_akses` */

insert  into `t_menu_akses`(`id_menu_akses`,`id_parent`,`sub`,`menu`,`module`,`dipilih`,`ikon`,`aktif`,`pusat`,`daerah`) values ('','',0,'',NULL,1,NULL,1,1,0),('1','0',0,'Home','',0,'fa fa-home',1,0,0),('2','0',1,'Pengaturan',NULL,1,'fa fa-wrench',1,1,0),('21','2',0,'Pengguna','dtusers',1,'fa fa-circle-o',1,1,0),('22','2',0,'Tahun Anggaran','setanggaran',1,'fa fa-circle-o',1,1,0),('23','2',0,'Target Pelayanan PD','targetpelypd',1,'fa fa-circle-o',1,1,1),('24','2',0,'Target Pengiriman Dok. D2D','settarget',1,'fa fa-circle-o',1,1,0),('3','0',1,'Management Pegawai',NULL,1,'fa fa-users',1,1,1),('31','3',0,'Data Pegawai','datapegawai',1,'fa fa-circle-o',1,1,1),('32','3',0,'Mutasi','dtmutasi',1,'fa fa-circle-o',1,1,1),('32x','3',0,'Riwayat Lokasi Pegawai','history_pegawai',1,'fa fa-circle-o',0,1,1),('4','0',1,'Rekam Data',NULL,1,'fa fa-edit',1,1,1),('41','4',0,'Obyek Pemungutan PD (x)','pungutpd',1,'fa fa-circle-o',1,1,1),('42','4',0,'Pengiriman Dok. D2D','inputresume',1,'fa fa-circle-o',1,1,1),('5','0',1,'Pelaporan',NULL,1,'fa fa-file',1,1,1),('51','5',0,'Rekap Pengiriman Dok. D2D (x)','rekapupad',1,'fa fa-circle-o',1,1,1),('52','5',0,'Rekap Obyek Pemungutan PD (x)','rekappd',1,'fa fa-circle-o',1,1,1);

/*Table structure for table `t_mutasi` */

DROP TABLE IF EXISTS `t_mutasi`;

CREATE TABLE `t_mutasi` (
  `id_mutasi` char(15) NOT NULL,
  `id_pegawai` char(15) NOT NULL,
  `id_lokasi` char(15) NOT NULL,
  `id_homebase` char(15) NOT NULL,
  `lokasi_lain` char(100) DEFAULT NULL,
  `id_jabatan` char(15) NOT NULL,
  `sk` char(100) DEFAULT NULL,
  `no_sk` char(50) DEFAULT NULL,
  `tgl_sk` date NOT NULL,
  `tgl_mulai` date NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `id_user` char(15) NOT NULL,
  `update_akhir` datetime NOT NULL,
  PRIMARY KEY (`id_mutasi`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `id_jabatan` (`id_jabatan`),
  CONSTRAINT `t_mutasi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `t_pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_mutasi_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `t_jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_mutasi` */

insert  into `t_mutasi`(`id_mutasi`,`id_pegawai`,`id_lokasi`,`id_homebase`,`lokasi_lain`,`id_jabatan`,`sk`,`no_sk`,`tgl_sk`,`tgl_mulai`,`aktif`,`id_user`,`update_akhir`) values ('145913513199','1459135131','0513','0513',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:18:51'),('145913525699','1459135256','0513','0513',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:20:56'),('145913534999','1459135349','0513','0513',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:22:29'),('145913544399','1459135443','0513','0513',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:24:03'),('145913553399','1459135533','0513','0513',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:25:33'),('145913567199','1459135671','0513','0513',NULL,'e3',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 10:27:51'),('145913825399','1459138253','0505','0505',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:10:53'),('145913973399','1459139733','0505','0505',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:35:33'),('145913983599','1459139835','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:37:15'),('145913989399','1459139893','0505','0505',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:38:13'),('145913995999','1459139959','0505','0505',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:39:19'),('145914067699','1459140676','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:51:16'),('145914098899','1459140988','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:56:28'),('145914111099','1459141110','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 11:58:30'),('145914254599','1459142545','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:22:25'),('145914264899','1459142648','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:24:08'),('145914272099','1459142720','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:25:20'),('145914289199','1459142891','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:28:11'),('145914298399','1459142983','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:29:43'),('145914310799','1459143107','0505','0505',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459136436','2016-03-28 12:31:47'),('145914392099','1459143920','0536','0536',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:45:20'),('145914401099','1459144010','0536','0536',NULL,'e3',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:46:50'),('145914421599','1459144215','0536','0536',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:50:15'),('145914434699','1459144346','0536','0536',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:52:26'),('145914445699','1459144456','0536','0536',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:54:16'),('145914456099','1459144560','0536','0536',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:56:00'),('145914464499','1459144644','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:57:24'),('145914472399','1459144723','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 12:58:43'),('145914483599','1459144835','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:00:35'),('145914490999','1459144909','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:01:49'),('145914497499','1459144974','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:02:54'),('145914503999','1459145039','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:03:59'),('145914510899','1459145108','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:05:08'),('145914519799','1459145197','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:06:37'),('145914526599','1459145265','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:07:45'),('145914532999','1459145329','0536','0536',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459143349','2016-03-28 13:08:49'),('145914963999','1459149639','0513','0513',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 14:20:39'),('145914974399','1459149743','0537','0537',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459133423','2016-03-28 14:22:23'),('145914974499','1459149744','0513','0513',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 14:22:24'),('145914980699','1459149806','0537','0537',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459133423','2016-03-28 14:23:26'),('145914980899','1459149808','0513','0513',NULL,'s',NULL,NULL,'2016-01-01','2016-01-01',1,'1459134128','2016-03-28 14:23:28'),('145914986799','1459149867','0537','0537',NULL,'e4',NULL,NULL,'2016-01-01','2016-01-01',1,'1459133423','2016-03-28 14:24:27');

/*Table structure for table `t_pangkat` */

DROP TABLE IF EXISTS `t_pangkat`;

CREATE TABLE `t_pangkat` (
  `id_pangkat` char(15) NOT NULL,
  `pangkat` char(25) NOT NULL,
  `golongan` char(5) NOT NULL,
  `asn` tinyint(1) NOT NULL DEFAULT '1',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_pangkat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_pangkat` */

insert  into `t_pangkat`(`id_pangkat`,`pangkat`,`golongan`,`asn`,`aktif`) values ('1a','JURU MUDA','Ia',1,1),('1b','JURU MUDA TK I','Ib',1,1),('1c','JURU','Ic',1,1),('1d','JURU TK I','Id',1,1),('2a','PENGATUR MUDA','IIa',1,1),('2b','PENGATUR MD TK I','IIb',1,1),('2c','PENGATUR','IIc',1,1),('2d','PENGATUR TK I','IId',1,1),('3a','PENATA MUDA','IIIa',1,1),('3b','PENATA MUDA TK I','IIIb',1,1),('3c','PENATA','IIIc',1,1),('3d','PENATA TK I','IIId',1,1),('4a','PEMBINA','IVa',1,1),('4b','PEMBINA TINGKAT I','IVb',1,1),('4c','PEMBINA UTAMA MUDA','IVc',1,1),('4d','PEMBINA UTAMA MADYA','IVd',1,1),('4e','PEMBINA UTAMA','IVe',1,1),('phl','PHL','-',0,1),('pk','PEGAWAI KONTRAK','-',0,1);

/*Table structure for table `t_pegawai` */

DROP TABLE IF EXISTS `t_pegawai`;

CREATE TABLE `t_pegawai` (
  `id_pegawai` char(15) NOT NULL,
  `id_sts_pegawai` char(3) NOT NULL,
  `nama_pegawai` char(100) NOT NULL,
  `nip` char(25) DEFAULT NULL,
  `id_pangkat` char(15) NOT NULL,
  `id_user` char(15) NOT NULL,
  `history_buat` datetime DEFAULT NULL,
  `history_update` datetime DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_pegawai`),
  KEY `id_sts_pegawai` (`id_sts_pegawai`),
  KEY `id_pangkat` (`id_pangkat`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `t_pegawai_ibfk_1` FOREIGN KEY (`id_sts_pegawai`) REFERENCES `t_sts_pegawai` (`id_sts_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_pegawai_ibfk_2` FOREIGN KEY (`id_pangkat`) REFERENCES `t_pangkat` (`id_pangkat`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_pegawai_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `t_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_pegawai` */

insert  into `t_pegawai`(`id_pegawai`,`id_sts_pegawai`,`nama_pegawai`,`nip`,`id_pangkat`,`id_user`,`history_buat`,`history_update`,`aktif`) values ('1459135131','33','DELVINA','19600403 198903 2 003','3d','1459134128','2016-03-28 10:18:51',NULL,1),('1459135256','33','WIWIEK SUSYANI,SE','19591018 198208 2 001','3d','1459134128','2016-03-28 10:20:56',NULL,1),('1459135349','33','ANI PRATIWI,SE','19630311 198510 2 002','3d','1459134128','2016-03-28 10:22:29',NULL,1),('1459135443','33','KISWANTO,SH','19670725 198903 1 006','3d','1459134128','2016-03-28 10:24:03',NULL,1),('1459135533','33','ANTHONY ADI PUTRANTO,SH','19680709 199303 1 010','3d','1459134128','2016-03-28 10:25:33',NULL,1),('1459135671','33','KOESWARDONO B CHRIS,SH,MM','19601208 198503 1 013','4a','1459134128','2016-03-28 10:27:51',NULL,1),('1459138253','33','ENDANG IRIYANTI,S.SOS, M.SI','19640730 198803 2 005','3d','1459136436','2016-03-28 11:10:53',NULL,1),('1459139733','33','SRI SURYANINGSIH','19620831 199105 2 001','3d','1459136436','2016-03-28 11:35:33',NULL,1),('1459139835','33','AGUSMAN,SE','19590831 198510 1 001','3d','1459136436','2016-03-28 11:37:15',NULL,1),('1459139893','33','SRI NURYANI,SE','19590307 197902 2 001','3d','1459136436','2016-03-28 11:38:13',NULL,1),('1459139959','33','BAMBANG HARDJANTO,S.SOS','19580704 198404 1 010','3d','1459136436','2016-03-28 11:39:19',NULL,1),('1459140676','33','EDY HERIYANTO,SE','19600528 198503 1 008','3c','1459136436','2016-03-28 11:51:16',NULL,1),('1459140988','33','SUHARYADI WAHYU','19680111 199001 1 002','3d','1459136436','2016-03-28 11:56:28',NULL,1),('1459141110','33','KUSDARYANIKUSUMAWARDANI','19730820 200604 2 003','3c','1459136436','2016-03-28 11:58:30',NULL,1),('1459142545','33','KRISNO UTOMO','19660203 199203 1 009','3c','1459136436','2016-03-28 12:22:25',NULL,1),('1459142648','33','ANDY RADITYO KRIDA SUSILO,ST','19800914 201101 1 006','3b','1459136436','2016-03-28 12:24:08',NULL,1),('1459142720','33','SUGIARTO','19661009 198903 1 009','3b','1459136436','2016-03-28 12:25:20',NULL,1),('1459142891','33','SUGIYARTO','19720625 199203 1 006','3b','1459136436','2016-03-28 12:28:11',NULL,1),('1459142983','33','TEGUH PURNOMO','19580705 198503 1 015','3b','1459136436','2016-03-28 12:29:43',NULL,1),('1459143107','33','ARI MARWANTO','19710711 201001 1 002','2b','1459136436','2016-03-28 12:31:47',NULL,1),('1459143920','33','ENNY PUDJIASTUTI, S.SOS','19591028 197901 2 001','3d','1459143349','2016-03-28 12:45:20',NULL,1),('1459144010','33','DRS. AS\'ARI, M.SI','19621212 198510 1 001','4b','1459143349','2016-03-28 12:46:50',NULL,1),('1459144215','33','BAMBANG IRWAN SETIYABUDI, SH','19590310 198503 1 012','3d','1459143349','2016-03-28 12:50:15',NULL,1),('1459144346','33','AGUNG MARDIYANTO, SE','19630318 198512 1 001','3d','1459143349','2016-03-28 12:52:26',NULL,1),('1459144456','33','BAMBANG TRI KUNCORO, SH','19660102 199603 1 003','3d','1459143349','2016-03-28 12:54:16',NULL,1),('1459144560','33','LUKY YUDHIA PERWIRA, SH','19670524 198702 1 003','3d','1459143349','2016-03-28 12:56:00',NULL,1),('1459144644','33','ROCH SURYANI, S.SOS','19660307 198510 2 001','3d','1459143349','2016-03-28 12:57:24',NULL,1),('1459144723','33','NOORSANTI WIDOERI, S.SOS','19690210 198803 2 001','3d','1459143349','2016-03-28 12:58:43',NULL,1),('1459144835','33','SUONO','19590103 198503 1 012','3b','1459143349','2016-03-28 13:00:35',NULL,1),('1459144909','33','TRIMARGAGREDA BUDI SETIYAWAN','19700615 199203 1 009','3b','1459143349','2016-03-28 13:01:49',NULL,1),('1459144974','33','DWI KUSUMAWATI, S.SOS','19651022 198903 2 009','3a','1459143349','2016-03-28 13:02:54',NULL,1),('1459145039','33','SUNAR','19680309 198903 1 007','3a','1459143349','2016-03-28 13:03:59',NULL,1),('1459145108','33','PRATIKNO','19610106 200604 1 004','2c','1459143349','2016-03-28 13:05:08',NULL,1),('1459145197','33','ARIF RAHMADIYANTO, S.SOS','19671214 201001 1 001','2b','1459143349','2016-03-28 13:06:37',NULL,1),('1459145265','33','ARI DIKDO','19750615 201001 1 004','2b','1459143349','2016-03-28 13:07:45',NULL,1),('1459145329','33','KRISTIAWAN NUGRAHANTO, S.SOS','19800408 201001 1 004','2b','1459143349','2016-03-28 13:08:49',NULL,1),('1459149639','33','ARI WIDODO EDIJANTO','19671230 200701 1 007','2c','1459134128','2016-03-28 14:20:39',NULL,1),('1459149743','33','SLAMET PRIYO H.S.SOS','19600505 198503 1 016','3d','1459133423','2016-03-28 14:22:23',NULL,1),('1459149744','33','WAHYONO','19680419 201001 1 002','2b','1459134128','2016-03-28 14:22:24',NULL,1),('1459149806','33','SARONO EDI S, S.SOS','19601231 198503 1 125','3d','1459133423','2016-03-28 14:23:26',NULL,1),('1459149808','33','YOYOK SOEMARJO','19601222 198503 1 009','2a','1459134128','2016-03-28 14:23:28',NULL,1),('1459149867','33','S.HADI WIBOWO','19611028 198603 1 009','3d','1459133423','2016-03-28 14:24:27',NULL,1);

/*Table structure for table `t_riwayat_lokasi_pegawai` */

DROP TABLE IF EXISTS `t_riwayat_lokasi_pegawai`;

CREATE TABLE `t_riwayat_lokasi_pegawai` (
  `id_riwayat_lokasi` char(15) NOT NULL,
  `id_pegawai` char(15) NOT NULL,
  `id_lokasi` char(15) NOT NULL,
  `id_user` char(15) NOT NULL,
  `riwayat_buat` datetime DEFAULT NULL,
  `riwayat_ubah` datetime DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_riwayat_lokasi`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `t_riwayat_lokasi_pegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `t_pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_riwayat_lokasi_pegawai_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `t_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_riwayat_lokasi_pegawai` */

/*Table structure for table `t_set_ttd_pejabat_upad` */

DROP TABLE IF EXISTS `t_set_ttd_pejabat_upad`;

CREATE TABLE `t_set_ttd_pejabat_upad` (
  `id_set_ttd` char(15) NOT NULL,
  `id_ttd` char(15) NOT NULL,
  `id_lokasi` char(15) NOT NULL,
  `nama_pejabat` char(50) NOT NULL,
  `nip` char(25) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_set_ttd`),
  KEY `id_lokasi` (`id_lokasi`),
  KEY `id_ttd` (`id_ttd`,`id_lokasi`),
  KEY `id_ttd_2` (`id_ttd`),
  CONSTRAINT `t_set_ttd_pejabat_upad_ibfk_1` FOREIGN KEY (`id_ttd`) REFERENCES `t_tanda_tangan` (`id_ttd`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_set_ttd_pejabat_upad` */

/*Table structure for table `t_sts_pegawai` */

DROP TABLE IF EXISTS `t_sts_pegawai`;

CREATE TABLE `t_sts_pegawai` (
  `id_sts_pegawai` char(3) NOT NULL,
  `status_pegawai` char(15) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_sts_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sts_pegawai` */

insert  into `t_sts_pegawai`(`id_sts_pegawai`,`status_pegawai`,`aktif`) values ('33','ASN',1),('99','Non ASN',1);

/*Table structure for table `t_tanda_tangan` */

DROP TABLE IF EXISTS `t_tanda_tangan`;

CREATE TABLE `t_tanda_tangan` (
  `id_ttd` char(15) NOT NULL,
  `jabatan_ttd` char(50) NOT NULL,
  `posisi` char(10) NOT NULL,
  `aktif` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_ttd`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_tanda_tangan` */

/*Table structure for table `t_target_d2d` */

DROP TABLE IF EXISTS `t_target_d2d`;

CREATE TABLE `t_target_d2d` (
  `id_target` char(15) NOT NULL,
  `id_anggaran` char(15) NOT NULL,
  `id_triwulan` char(15) NOT NULL,
  `id_jabatan` char(15) NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_target`),
  KEY `id_jabatan` (`id_jabatan`),
  KEY `id_target` (`id_target`,`id_anggaran`),
  KEY `id_target_2` (`id_target`,`id_triwulan`),
  KEY `id_target_3` (`id_target`,`id_jabatan`),
  KEY `id_anggaran` (`id_anggaran`,`id_triwulan`),
  KEY `id_anggaran_2` (`id_anggaran`,`id_jabatan`),
  KEY `id_triwulan` (`id_triwulan`,`id_jabatan`),
  KEY `id_target_4` (`id_target`,`id_anggaran`,`id_triwulan`,`id_jabatan`),
  KEY `id_anggaran_3` (`id_anggaran`),
  KEY `id_triwulan_2` (`id_triwulan`),
  CONSTRAINT `t_target_d2d_ibfk_1` FOREIGN KEY (`id_anggaran`) REFERENCES `t_th_anggaran` (`id_anggaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_target_d2d_ibfk_2` FOREIGN KEY (`id_triwulan`) REFERENCES `t_triwulan` (`id_triwulan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_target_d2d_ibfk_3` FOREIGN KEY (`id_jabatan`) REFERENCES `t_jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_target_d2d` */

insert  into `t_target_d2d`(`id_target`,`id_anggaran`,`id_triwulan`,`id_jabatan`,`total`) values ('14591031481','1458627189','01','e1',0),('145910314810','1458627189','02','e3',30),('145910314811','1458627189','03','e3',30),('145910314812','1458627189','04','e3',30),('145910314813','1458627189','01','e4',45),('145910314814','1458627189','02','e4',45),('145910314815','1458627189','03','e4',45),('145910314816','1458627189','04','e4',45),('145910314817','1458627189','01','s',60),('145910314818','1458627189','02','s',60),('145910314819','1458627189','03','s',60),('14591031482','1458627189','02','e1',0),('145910314820','1458627189','04','s',60),('14591031483','1458627189','03','e1',0),('14591031484','1458627189','04','e1',0),('14591031485','1458627189','01','e2',20),('14591031486','1458627189','02','e2',20),('14591031487','1458627189','03','e2',20),('14591031488','1458627189','04','e2',20),('14591031489','1458627189','01','e3',30);

/*Table structure for table `t_th_anggaran` */

DROP TABLE IF EXISTS `t_th_anggaran`;

CREATE TABLE `t_th_anggaran` (
  `id_anggaran` char(15) NOT NULL,
  `th_anggaran` year(4) NOT NULL,
  `keterangan` text,
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_anggaran`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_th_anggaran` */

insert  into `t_th_anggaran`(`id_anggaran`,`th_anggaran`,`keterangan`,`aktif`) values ('1458627189',2016,'awal penghitungan kinerja',1),('1458634522',2015,'Coba Tahun Lalu',0);

/*Table structure for table `t_triwulan` */

DROP TABLE IF EXISTS `t_triwulan`;

CREATE TABLE `t_triwulan` (
  `id_triwulan` char(15) NOT NULL,
  `triwulan` char(15) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_triwulan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_triwulan` */

insert  into `t_triwulan`(`id_triwulan`,`triwulan`,`aktif`) values ('01','I',1),('02','II',1),('03','III',1),('04','IV',1);

/*Table structure for table `t_trx_d2d` */

DROP TABLE IF EXISTS `t_trx_d2d`;

CREATE TABLE `t_trx_d2d` (
  `id_trx` char(15) NOT NULL,
  `id_mutasi` char(15) NOT NULL,
  `id_bulan` char(15) NOT NULL,
  `id_anggaran` char(15) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT '0',
  `id_user` char(15) NOT NULL,
  PRIMARY KEY (`id_trx`),
  KEY `id_bulan` (`id_bulan`),
  KEY `id_anggaran` (`id_anggaran`),
  KEY `id_mutasi` (`id_mutasi`),
  CONSTRAINT `t_trx_d2d_ibfk_1` FOREIGN KEY (`id_bulan`) REFERENCES `t_bulan` (`id_bulan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_trx_d2d_ibfk_2` FOREIGN KEY (`id_anggaran`) REFERENCES `t_th_anggaran` (`id_anggaran`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_trx_d2d_ibfk_3` FOREIGN KEY (`id_mutasi`) REFERENCES `t_mutasi` (`id_mutasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_trx_d2d` */

/*Table structure for table `t_users` */

DROP TABLE IF EXISTS `t_users`;

CREATE TABLE `t_users` (
  `id_user` char(15) NOT NULL,
  `username` char(25) NOT NULL,
  `katakunci` char(25) NOT NULL,
  `sandi` char(50) NOT NULL,
  `nama_user` char(100) NOT NULL,
  `id_lokasi` char(15) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '1',
  `pusat` tinyint(1) NOT NULL DEFAULT '1',
  `menuakses` text,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_users` */

insert  into `t_users`(`id_user`,`username`,`katakunci`,`sandi`,`nama_user`,`id_lokasi`,`admin`,`pusat`,`menuakses`,`aktif`) values ('1458608926','adminpusat','dombajc','f6338e9679fa67f44f818e3bfd546584','Administrator Pusat','05',1,1,'2,21,22,23,3,31,32,4,41,5,51',1),('1459065329','wawan4276','1003','aa68c75c4a77c87f97fb686b2f068676','WAWAN GUNAWAN UTAMA','05',1,1,'2,21,22,23,24,3,31,4,41,42,5,51',1),('1459065417','ppsmg1','ppsmg1','7d534e40c460e465796fe469ad006cda','Admin pembukuan pelaporan UP3AD Semarang I','0501',1,0,'3,31,4,41,42,5,51',1),('1459130999','ppkbm','ppkbm','377890c9d82b22d440f0de1ca47e9f23','Admin pembukuan pelaporan UP3AD Kebumen','0535',1,0,'3,31,4,41,42,5,51,52',1),('1459133423','ppwnsb','ppwsb','dd48a5b21928bb4ba213f1893dd5004b','admin pembukuan pelaporan UP3AD Kab. Wonosobo','0537',1,0,'3,31,4,41,42,5,51,52',1),('1459133843','ppsltg','ppsltg','24681a75aa183e7af4c88fb87eabb53c','admin pembukuan pelaporan UP3AD Kota Salatiga','0504',1,0,'3,31,4,41,42,5,51,52',1),('1459134128','ppsrg','ppsrg','9f7836618106cb9613e0252524334e57','Admin Pembukuan dan Pelaporan UP3AD Kab. Sragen','0513',1,0,'3,31,32,4,41,42,5,51,52',1),('1459136279','ppsmg2','ppsmg2','bc39606f7bf2b78d99a75eb7e3e371a4','Admin Pembukuan dan Pelaporan UP3AD Kota Smg. II','0502',1,0,'3,31,4,41,42,5,51,52',1),('1459136367','ppsmg3','ppsmg3','d7424f4944e3e701c9358e1f72de0b0e','Admin Pembukuan dan Pelaporan UP3AD Kota Smg. III','0503',1,0,'3,31,4,41,42,5,51,52',1),('1459136436','ppsmg','ppsmg','3c69fd2f46f7ecd2f87301f83518ba46','Admin Pembukuan dan Pelaporan UP3AD Kab. Semarang','0505',1,0,'3,31,4,41,42,5,51,52',1),('1459136622','ppkdl','ppkdl','06d8108454d0de5d9d806a06ac5cf00b','Admin Pembukuan dan Pelaporan UP3AD Kab. Kendal','0506',1,0,'3,31,4,41,5,51,52',1),('1459137270','ppdmk','ppdmk','4915c2ad6a92f4edb730a8ababbb44c2','Admin Pembukuan dan Pelaporan UP3AD Kab. Demak','0507',1,0,'3,31,4,41,42,5,51,52',1),('1459137354','pppati','pppati','938a3aca5666c19c2c53cbf20f95645c','Admin Pembukuan dan Pelaporan UP3AD Kab. Pati','0516',1,0,'3,31,4,41,42,5,51,52',1),('1459137438','ppkds','ppkds','7fb9fb497424f395f7275754d053e419','Admin Pembukuan dan Pelaporan UP3AD Kab. Kudus','0517',1,0,'3,31,4,41,42,5,51,52',1),('1459137509','ppjpr','ppjpr','52e291a1eedfc5584e6f14153502171a','Admin Pembukuan dan Pelaporan UP3AD Kab. Jepara','0518',1,0,'3,31,4,41,42,5,51,52',1),('1459138022','pprbg','pprbg','20644fd3887103a317042b21f22be24c','Admin Pembukuan dan Pelaporan UP3AD Kab. Rembang','0519',1,0,'3,31,4,41,42,5,51,52',1),('1459138103','ppbra','ppbra','2423ca4d6d791c57e69f18cf267aa849','Admin Pembukuan dan Pelaporan UP3AD Kab. Blora','0520',1,0,'3,31,4,41,42,5,51,52',1),('1459143349','pptmg','pptmg','629f09451ae439d6a2785e575631c21f','Admin Pembukuan dan Pelaporan UP3AD Kab. Temanggung','0536',1,0,'3,31,4,41,42,5,51,52',1);

/*Table structure for table `v_bulan` */

DROP TABLE IF EXISTS `v_bulan`;

/*!50001 DROP VIEW IF EXISTS `v_bulan` */;
/*!50001 DROP TABLE IF EXISTS `v_bulan` */;

/*!50001 CREATE TABLE  `v_bulan`(
 `id_bulan` char(15) ,
 `id_triwulan` char(15) ,
 `bulan` char(25) ,
 `aktif` tinyint(1) ,
 `triwulan` char(15) 
)*/;

/*Table structure for table `v_homebase` */

DROP TABLE IF EXISTS `v_homebase`;

/*!50001 DROP VIEW IF EXISTS `v_homebase` */;
/*!50001 DROP TABLE IF EXISTS `v_homebase` */;

/*!50001 CREATE TABLE  `v_homebase`(
 `id_lokasi` varchar(20) ,
 `id_induk` varchar(20) ,
 `lokasi` varchar(50) ,
 `nama_kota` varchar(50) ,
 `alamat` text ,
 `kota` varchar(30) ,
 `kdpos` varchar(10) ,
 `telp` text ,
 `fax` varchar(15) ,
 `samsat` enum('pusat','besar','kecil','other') ,
 `kd_wil` varchar(15) ,
 `kabkot` enum('Kota','Kabupaten') ,
 `email` varchar(100) ,
 `d2d_pusat` tinyint(1) ,
 `blokir` tinyint(1) 
)*/;

/*Table structure for table `v_mutasi` */

DROP TABLE IF EXISTS `v_mutasi`;

/*!50001 DROP VIEW IF EXISTS `v_mutasi` */;
/*!50001 DROP TABLE IF EXISTS `v_mutasi` */;

/*!50001 CREATE TABLE  `v_mutasi`(
 `id_mutasi` char(15) ,
 `id_pegawai` char(15) ,
 `id_lokasi` char(15) ,
 `id_homebase` char(15) ,
 `lokasi_lain` char(100) ,
 `id_jabatan` char(15) ,
 `sk` char(100) ,
 `no_sk` char(50) ,
 `tgl_sk` date ,
 `tgl_mulai` date ,
 `aktif` tinyint(1) ,
 `id_user` char(15) ,
 `update_akhir` datetime ,
 `jabatan` char(25) ,
 `nama_lokasi` varchar(50) ,
 `nama_homebase` varchar(50) 
)*/;

/*Table structure for table `v_pegawai_v2` */

DROP TABLE IF EXISTS `v_pegawai_v2`;

/*!50001 DROP VIEW IF EXISTS `v_pegawai_v2` */;
/*!50001 DROP TABLE IF EXISTS `v_pegawai_v2` */;

/*!50001 CREATE TABLE  `v_pegawai_v2`(
 `id_pegawai` char(15) ,
 `id_sts_pegawai` char(3) ,
 `nama_pegawai` char(100) ,
 `nip` char(25) ,
 `id_pangkat` char(15) ,
 `id_user` char(15) ,
 `history_buat` datetime ,
 `history_update` datetime ,
 `aktif` tinyint(1) ,
 `pangkat` char(25) ,
 `golongan` char(5) ,
 `jabatan` char(25) ,
 `id_jabatan` char(15) ,
 `id_lokasi` char(15) ,
 `nama_lokasi` varchar(50) ,
 `nama_homebase` varchar(50) ,
 `status_pegawai` char(15) 
)*/;

/*Table structure for table `v_users` */

DROP TABLE IF EXISTS `v_users`;

/*!50001 DROP VIEW IF EXISTS `v_users` */;
/*!50001 DROP TABLE IF EXISTS `v_users` */;

/*!50001 CREATE TABLE  `v_users`(
 `id_user` char(15) ,
 `username` char(25) ,
 `katakunci` char(25) ,
 `sandi` char(50) ,
 `nama_user` char(100) ,
 `id_lokasi` char(15) ,
 `admin` tinyint(1) ,
 `pusat` tinyint(1) ,
 `menuakses` text ,
 `aktif` tinyint(1) ,
 `lokasi` varchar(50) 
)*/;

/*View structure for view v_bulan */

/*!50001 DROP TABLE IF EXISTS `v_bulan` */;
/*!50001 DROP VIEW IF EXISTS `v_bulan` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_bulan` AS (select `t_bulan`.`id_bulan` AS `id_bulan`,`t_bulan`.`id_triwulan` AS `id_triwulan`,`t_bulan`.`bulan` AS `bulan`,`t_bulan`.`aktif` AS `aktif`,`t_triwulan`.`triwulan` AS `triwulan` from (`t_bulan` left join `t_triwulan` on((`t_triwulan`.`id_triwulan` = `t_bulan`.`id_triwulan`)))) */;

/*View structure for view v_homebase */

/*!50001 DROP TABLE IF EXISTS `v_homebase` */;
/*!50001 DROP VIEW IF EXISTS `v_homebase` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_homebase` AS (select `lokasi`.`id_lokasi` AS `id_lokasi`,`lokasi`.`id_induk` AS `id_induk`,`lokasi`.`lokasi` AS `lokasi`,`lokasi`.`nama_kota` AS `nama_kota`,`lokasi`.`alamat` AS `alamat`,`lokasi`.`kota` AS `kota`,`lokasi`.`kdpos` AS `kdpos`,`lokasi`.`telp` AS `telp`,`lokasi`.`fax` AS `fax`,`lokasi`.`samsat` AS `samsat`,`lokasi`.`kd_wil` AS `kd_wil`,`lokasi`.`kabkot` AS `kabkot`,`lokasi`.`email` AS `email`,`lokasi`.`d2d_pusat` AS `d2d_pusat`,`lokasi`.`blokir` AS `blokir` from `lokasi` where (`lokasi`.`samsat` in ('pusat','besar'))) */;

/*View structure for view v_mutasi */

/*!50001 DROP TABLE IF EXISTS `v_mutasi` */;
/*!50001 DROP VIEW IF EXISTS `v_mutasi` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mutasi` AS (select `t_mutasi`.`id_mutasi` AS `id_mutasi`,`t_mutasi`.`id_pegawai` AS `id_pegawai`,`t_mutasi`.`id_lokasi` AS `id_lokasi`,`t_mutasi`.`id_homebase` AS `id_homebase`,`t_mutasi`.`lokasi_lain` AS `lokasi_lain`,`t_mutasi`.`id_jabatan` AS `id_jabatan`,`t_mutasi`.`sk` AS `sk`,`t_mutasi`.`no_sk` AS `no_sk`,`t_mutasi`.`tgl_sk` AS `tgl_sk`,`t_mutasi`.`tgl_mulai` AS `tgl_mulai`,`t_mutasi`.`aktif` AS `aktif`,`t_mutasi`.`id_user` AS `id_user`,`t_mutasi`.`update_akhir` AS `update_akhir`,`t_jabatan`.`jabatan` AS `jabatan`,(select `lokasi`.`lokasi` from `lokasi` where (`lokasi`.`id_lokasi` = convert(`t_mutasi`.`id_lokasi` using utf8))) AS `nama_lokasi`,(select `lokasi`.`lokasi` from `lokasi` where (`lokasi`.`id_lokasi` = convert(`t_mutasi`.`id_homebase` using utf8))) AS `nama_homebase` from (`t_mutasi` left join `t_jabatan` on((`t_jabatan`.`id_jabatan` = `t_mutasi`.`id_jabatan`)))) */;

/*View structure for view v_pegawai_v2 */

/*!50001 DROP TABLE IF EXISTS `v_pegawai_v2` */;
/*!50001 DROP VIEW IF EXISTS `v_pegawai_v2` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pegawai_v2` AS (select `t_pegawai`.`id_pegawai` AS `id_pegawai`,`t_pegawai`.`id_sts_pegawai` AS `id_sts_pegawai`,`t_pegawai`.`nama_pegawai` AS `nama_pegawai`,`t_pegawai`.`nip` AS `nip`,`t_pegawai`.`id_pangkat` AS `id_pangkat`,`t_pegawai`.`id_user` AS `id_user`,`t_pegawai`.`history_buat` AS `history_buat`,`t_pegawai`.`history_update` AS `history_update`,`t_pegawai`.`aktif` AS `aktif`,`t_pangkat`.`pangkat` AS `pangkat`,`t_pangkat`.`golongan` AS `golongan`,`v_mutasi`.`jabatan` AS `jabatan`,`v_mutasi`.`id_jabatan` AS `id_jabatan`,`v_mutasi`.`id_lokasi` AS `id_lokasi`,`v_mutasi`.`nama_lokasi` AS `nama_lokasi`,`v_mutasi`.`nama_homebase` AS `nama_homebase`,`t_sts_pegawai`.`status_pegawai` AS `status_pegawai` from (((`t_pegawai` left join `v_mutasi` on((`v_mutasi`.`id_pegawai` = `t_pegawai`.`id_pegawai`))) left join `t_pangkat` on((`t_pangkat`.`id_pangkat` = `t_pegawai`.`id_pangkat`))) left join `t_sts_pegawai` on((`t_sts_pegawai`.`id_sts_pegawai` = `t_pegawai`.`id_sts_pegawai`))) where (`v_mutasi`.`aktif` = 1)) */;

/*View structure for view v_users */

/*!50001 DROP TABLE IF EXISTS `v_users` */;
/*!50001 DROP VIEW IF EXISTS `v_users` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_users` AS (select `t_users`.`id_user` AS `id_user`,`t_users`.`username` AS `username`,`t_users`.`katakunci` AS `katakunci`,`t_users`.`sandi` AS `sandi`,`t_users`.`nama_user` AS `nama_user`,`t_users`.`id_lokasi` AS `id_lokasi`,`t_users`.`admin` AS `admin`,`t_users`.`pusat` AS `pusat`,`t_users`.`menuakses` AS `menuakses`,`t_users`.`aktif` AS `aktif`,`lokasi`.`lokasi` AS `lokasi` from (`t_users` left join `lokasi` on((`lokasi`.`id_lokasi` = convert(`t_users`.`id_lokasi` using utf8))))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
