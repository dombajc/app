/*
SQLyog Ultimate v11.33 (32 bit)
MySQL - 5.6.24-log : Database - dbd2d
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dbd2d` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `v_list_pungut_pd` */

DROP TABLE IF EXISTS `v_list_pungut_pd`;

/*!50001 DROP VIEW IF EXISTS `v_list_pungut_pd` */;
/*!50001 DROP TABLE IF EXISTS `v_list_pungut_pd` */;

/*!50001 CREATE TABLE  `v_list_pungut_pd`(
 `id_anggaran` char(15) ,
 `id_triwulan` char(15) ,
 `id_lokasi` char(15) ,
 `id_rek_pd_pungut` char(15) ,
 `id_rek_pd` varchar(15) ,
 `id_bulan` char(15) ,
 `realisasi` int(11) 
)*/;

/*View structure for view v_list_pungut_pd */

/*!50001 DROP TABLE IF EXISTS `v_list_pungut_pd` */;
/*!50001 DROP VIEW IF EXISTS `v_list_pungut_pd` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_list_pungut_pd` AS (select `t_pungut_pd`.`id_anggaran` AS `id_anggaran`,`t_bulan`.`id_triwulan` AS `id_triwulan`,`t_pungut_pd`.`id_lokasi` AS `id_lokasi`,`t_pungut_pd`.`id_rek_pd` AS `id_rek_pd_pungut`,if((`t_rek_pd`.`sub_rek_pd` = '00'),`t_pungut_pd`.`id_rek_pd`,left(`t_pungut_pd`.`id_rek_pd`,char_length(`t_rek_pd`.`sub_rek_pd`))) AS `id_rek_pd`,`t_bulan`.`id_bulan` AS `id_bulan`,`t_pungut_pd`.`jumlah` AS `realisasi` from ((`t_pungut_pd` left join `t_bulan` on((`t_bulan`.`id_bulan` = `t_pungut_pd`.`id_bulan`))) left join `t_rek_pd` on((`t_pungut_pd`.`id_rek_pd` = `t_rek_pd`.`id_rek_pd`))) order by `t_pungut_pd`.`id_lokasi`,`t_bulan`.`id_bulan`) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
