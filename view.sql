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

/*Table structure for table `v_arr_transaksi_spbu` */

DROP TABLE IF EXISTS `v_arr_transaksi_spbu`;

/*!50001 DROP VIEW IF EXISTS `v_arr_transaksi_spbu` */;
/*!50001 DROP TABLE IF EXISTS `v_arr_transaksi_spbu` */;

/*!50001 CREATE TABLE  `v_arr_transaksi_spbu`(
 `id_transaksi_pbbkb` char(15) ,
 `id_lokasi_pbbkb` char(15) ,
 `id_anggaran` char(15) ,
 `id_bulan` char(5) ,
 `id_dasar_trx_pbbkb` char(5) ,
 `create_by` char(15) ,
 `last_update` datetime ,
 `id_lokasi` char(5) 
)*/;

/*Table structure for table `v_group_entry_pbbkb` */

DROP TABLE IF EXISTS `v_group_entry_pbbkb`;

/*!50001 DROP VIEW IF EXISTS `v_group_entry_pbbkb` */;
/*!50001 DROP TABLE IF EXISTS `v_group_entry_pbbkb` */;

/*!50001 CREATE TABLE  `v_group_entry_pbbkb`(
 `id_lokasi_pbbkb` char(15) ,
 `id_lokasi` char(5) ,
 `id_anggaran` char(15) ,
 `id_bulan` char(5) 
)*/;

/*View structure for view v_arr_transaksi_spbu */

/*!50001 DROP TABLE IF EXISTS `v_arr_transaksi_spbu` */;
/*!50001 DROP VIEW IF EXISTS `v_arr_transaksi_spbu` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_arr_transaksi_spbu` AS (select `t_transaksi_pbbkb2`.`id_transaksi_pbbkb` AS `id_transaksi_pbbkb`,`t_transaksi_pbbkb2`.`id_lokasi_pbbkb` AS `id_lokasi_pbbkb`,`t_transaksi_pbbkb2`.`id_anggaran` AS `id_anggaran`,`t_transaksi_pbbkb2`.`id_bulan` AS `id_bulan`,`t_transaksi_pbbkb2`.`id_dasar_trx_pbbkb` AS `id_dasar_trx_pbbkb`,`t_transaksi_pbbkb2`.`create_by` AS `create_by`,`t_transaksi_pbbkb2`.`last_update` AS `last_update`,`v_penyetor_pbbkb`.`id_lokasi` AS `id_lokasi` from (`t_transaksi_pbbkb2` left join `v_penyetor_pbbkb` on((`v_penyetor_pbbkb`.`id_lokasi_pbbkb` = `t_transaksi_pbbkb2`.`id_lokasi_pbbkb`)))) */;

/*View structure for view v_group_entry_pbbkb */

/*!50001 DROP TABLE IF EXISTS `v_group_entry_pbbkb` */;
/*!50001 DROP VIEW IF EXISTS `v_group_entry_pbbkb` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_group_entry_pbbkb` AS (select `v_arr_transaksi_spbu`.`id_lokasi_pbbkb` AS `id_lokasi_pbbkb`,`v_arr_transaksi_spbu`.`id_lokasi` AS `id_lokasi`,`v_arr_transaksi_spbu`.`id_anggaran` AS `id_anggaran`,`v_arr_transaksi_spbu`.`id_bulan` AS `id_bulan` from `v_arr_transaksi_spbu` where (`v_arr_transaksi_spbu`.`id_dasar_trx_pbbkb` = 'D0') group by `v_arr_transaksi_spbu`.`id_lokasi_pbbkb`,`v_arr_transaksi_spbu`.`id_lokasi`,`v_arr_transaksi_spbu`.`id_anggaran`,`v_arr_transaksi_spbu`.`id_bulan`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
