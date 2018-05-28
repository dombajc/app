/*
SQLyog Ultimate v11.33 (32 bit)
MySQL - 5.6.24 : Database - dbd2d
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dbd2d` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `v_lokasi_upad` */

DROP TABLE IF EXISTS `v_lokasi_upad`;

/*!50001 DROP VIEW IF EXISTS `v_lokasi_upad` */;
/*!50001 DROP TABLE IF EXISTS `v_lokasi_upad` */;

/*!50001 CREATE TABLE  `v_lokasi_upad`(
 `id_lokasi` varchar(20) ,
 `id_induk` varchar(20) ,
 `lokasi` varchar(50) ,
 `nama_kota` varchar(50) ,
 `alamat` text ,
 `kota` char(25) ,
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

/*Table structure for table `v_penyetor_pbbkb` */

DROP TABLE IF EXISTS `v_penyetor_pbbkb`;

/*!50001 DROP VIEW IF EXISTS `v_penyetor_pbbkb` */;
/*!50001 DROP TABLE IF EXISTS `v_penyetor_pbbkb` */;

/*!50001 CREATE TABLE  `v_penyetor_pbbkb`(
 `id_lokasi_pbbkb` char(15) ,
 `no_spbu` char(25) ,
 `nama_spbu` char(50) ,
 `alamat_spbu` char(100) ,
 `id_lokasi` char(5) ,
 `id_kecamatan` char(15) ,
 `telp` char(25) ,
 `aktif` tinyint(1) ,
 `create_by` char(15) ,
 `last_update` datetime ,
 `kecamatan` char(50) ,
 `lokasi` varchar(50) ,
 `kota` char(25) 
)*/;

/*View structure for view v_lokasi_upad */

/*!50001 DROP TABLE IF EXISTS `v_lokasi_upad` */;
/*!50001 DROP VIEW IF EXISTS `v_lokasi_upad` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_lokasi_upad` AS (select `lokasi`.`id_lokasi` AS `id_lokasi`,`lokasi`.`id_induk` AS `id_induk`,`lokasi`.`lokasi` AS `lokasi`,`lokasi`.`nama_kota` AS `nama_kota`,`lokasi`.`alamat` AS `alamat`,`t_kota`.`kota` AS `kota`,`lokasi`.`kdpos` AS `kdpos`,`lokasi`.`telp` AS `telp`,`lokasi`.`fax` AS `fax`,`lokasi`.`samsat` AS `samsat`,`lokasi`.`kd_wil` AS `kd_wil`,`lokasi`.`kabkot` AS `kabkot`,`lokasi`.`email` AS `email`,`lokasi`.`d2d_pusat` AS `d2d_pusat`,`lokasi`.`blokir` AS `blokir` from (`lokasi` left join `t_kota` on((convert(`t_kota`.`id_kota` using utf8) = `lokasi`.`kota`))) where ((`lokasi`.`id_lokasi` = `lokasi`.`id_induk`) and (`lokasi`.`id_lokasi` <> '05'))) */;

/*View structure for view v_penyetor_pbbkb */

/*!50001 DROP TABLE IF EXISTS `v_penyetor_pbbkb` */;
/*!50001 DROP VIEW IF EXISTS `v_penyetor_pbbkb` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_penyetor_pbbkb` AS (select `t_penyetor_pbbkb`.`id_lokasi_pbbkb` AS `id_lokasi_pbbkb`,`t_penyetor_pbbkb`.`no_spbu` AS `no_spbu`,`t_penyetor_pbbkb`.`nama_spbu` AS `nama_spbu`,`t_penyetor_pbbkb`.`alamat_spbu` AS `alamat_spbu`,`t_penyetor_pbbkb`.`id_lokasi` AS `id_lokasi`,`t_penyetor_pbbkb`.`id_kecamatan` AS `id_kecamatan`,`t_penyetor_pbbkb`.`telp` AS `telp`,`t_penyetor_pbbkb`.`aktif` AS `aktif`,`t_penyetor_pbbkb`.`create_by` AS `create_by`,`t_penyetor_pbbkb`.`last_update` AS `last_update`,`v_kecamatan`.`kecamatan` AS `kecamatan`,`v_kecamatan`.`lokasi` AS `lokasi`,`v_lokasi_upad`.`kota` AS `kota` from ((`t_penyetor_pbbkb` left join `v_kecamatan` on((`v_kecamatan`.`id_kecamatan` = `t_penyetor_pbbkb`.`id_kecamatan`))) left join `v_lokasi_upad` on((`v_lokasi_upad`.`id_lokasi` = convert(`t_penyetor_pbbkb`.`id_lokasi` using utf8))))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
