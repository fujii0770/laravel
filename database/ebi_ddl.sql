/*
SQLyog Community
MySQL - 8.0.18 : Database - ebi_iot
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cms_apicustom` */

DROP TABLE IF EXISTS `cms_apicustom`;

CREATE TABLE `cms_apicustom` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permalink` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kolom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orderby` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_query_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sql_where` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `method_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `responses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_apikey` */

DROP TABLE IF EXISTS `cms_apikey`;

CREATE TABLE `cms_apikey` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `screetkey` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hit` int(11) DEFAULT NULL,
  `status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_dashboard` */

DROP TABLE IF EXISTS `cms_dashboard`;

CREATE TABLE `cms_dashboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_email_queues` */

DROP TABLE IF EXISTS `cms_email_queues`;

CREATE TABLE `cms_email_queues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_at` datetime DEFAULT NULL,
  `email_recipient` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_cc_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_attachments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_sent` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_email_templates` */

DROP TABLE IF EXISTS `cms_email_templates`;

CREATE TABLE `cms_email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_logs` */

DROP TABLE IF EXISTS `cms_logs`;

CREATE TABLE `cms_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_cms_users` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_menus` */

DROP TABLE IF EXISTS `cms_menus`;

CREATE TABLE `cms_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'url',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `id_cms_privileges` int(11) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_menus_privileges` */

DROP TABLE IF EXISTS `cms_menus_privileges`;

CREATE TABLE `cms_menus_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cms_menus` int(11) DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_moduls` */

DROP TABLE IF EXISTS `cms_moduls`;

CREATE TABLE `cms_moduls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_protected` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_notifications` */

DROP TABLE IF EXISTS `cms_notifications`;

CREATE TABLE `cms_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cms_users` int(11) DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_privileges` */

DROP TABLE IF EXISTS `cms_privileges`;

CREATE TABLE `cms_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint(1) DEFAULT NULL,
  `theme_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_privileges_roles` */

DROP TABLE IF EXISTS `cms_privileges_roles`;

CREATE TABLE `cms_privileges_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_visible` tinyint(1) DEFAULT NULL,
  `is_create` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_edit` tinyint(1) DEFAULT NULL,
  `is_delete` tinyint(1) DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `id_cms_moduls` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_settings` */

DROP TABLE IF EXISTS `cms_settings`;

CREATE TABLE `cms_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_input_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dataenum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `helper` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_setting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_statistic_components` */

DROP TABLE IF EXISTS `cms_statistic_components`;

CREATE TABLE `cms_statistic_components` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cms_statistics` int(11) DEFAULT NULL,
  `componentID` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_name` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_statistics` */

DROP TABLE IF EXISTS `cms_statistics`;

CREATE TABLE `cms_statistics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `cms_users` */

DROP TABLE IF EXISTS `cms_users`;

CREATE TABLE `cms_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_email_alert` int(11) NOT NULL DEFAULT '0',
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Table structure for table `ebi_countries` */

DROP TABLE IF EXISTS `ebi_countries`;

CREATE TABLE `ebi_countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ponds_kind` */
DROP TABLE IF EXISTS `ponds_kind`;

CREATE TABLE `ponds_kind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pond_kind` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `end` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `aquaculture_method` */
DROP TABLE IF EXISTS `aquaculture_method`;

CREATE TABLE `aquaculture_method` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `ebi_kind` */
DROP TABLE IF EXISTS `ebi_kind`;

CREATE TABLE `ebi_kind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kind` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `training_period` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `aquaculture_method_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
 FOREIGN KEY (`aquaculture_method_id`)
 REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 
/*Table structure for table `ebi_farms` */
DROP TABLE IF EXISTS `ebi_farms`;

CREATE TABLE `ebi_farms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `farm_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `farm_name_en` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `farm_image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `farm_image_en` text COLLATE utf8mb4_unicode_ci,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_import_state_logs` */

DROP TABLE IF EXISTS `ebi_import_state_logs`;

CREATE TABLE `ebi_import_state_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_user` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_measure_point_tag` */

DROP TABLE IF EXISTS `ebi_measure_point_tag`;

CREATE TABLE `ebi_measure_point_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `measure_point` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_id` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_minmaxs` */

DROP TABLE IF EXISTS `ebi_minmaxs`;

CREATE TABLE `ebi_minmaxs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ph_values_min` decimal(8,2) DEFAULT NULL,
  `orp_values_min` decimal(8,2) DEFAULT NULL,
  `tmp_values_min` decimal(8,2) DEFAULT NULL,
  `ec_values_min` decimal(8,2) DEFAULT NULL,
  `ec_abs_values_min` decimal(8,2) DEFAULT NULL,
  `res_values_min` decimal(8,2) DEFAULT NULL,
  `tds_values_min` decimal(8,2) DEFAULT NULL,
  `sal_values_min` decimal(8,5) DEFAULT NULL,
  `press_values_min` decimal(8,3) DEFAULT NULL,
  `do_values_min` decimal(8,2) DEFAULT NULL,
  `do_ppm_values_min` decimal(8,2) DEFAULT NULL,
  `mv_values_min` decimal(8,2) DEFAULT NULL,
  `turb_fnu_values_min` decimal(8,2) DEFAULT NULL,
  `ph_values_max` decimal(8,2) DEFAULT NULL,
  `orp_values_max` decimal(8,2) DEFAULT NULL,
  `tmp_values_max` decimal(8,2) DEFAULT NULL,
  `ec_values_max` decimal(8,2) DEFAULT NULL,
  `ec_abs_values_max` decimal(8,2) DEFAULT NULL,
  `res_values_max` decimal(8,2) DEFAULT NULL,
  `tds_values_max` decimal(8,2) DEFAULT NULL,
  `sal_values_max` decimal(8,5) DEFAULT NULL,
  `press_values_max` decimal(8,3) DEFAULT NULL,
  `do_values_max` decimal(8,2) DEFAULT NULL,
  `do_ppm_values_max` decimal(8,2) DEFAULT NULL,
  `mv_values_max` decimal(8,2) DEFAULT NULL,
  `turb_fnu_values_max` decimal(8,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `disable_flag` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sigma_t_values_min` decimal(8,2) DEFAULT NULL,
  `sigma_t_values_max` decimal(8,2) DEFAULT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_pond_alerts` */

DROP TABLE IF EXISTS `ebi_pond_alerts`;

CREATE TABLE `ebi_pond_alerts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pond_id` int(11) NOT NULL,
  `alert_date` date NOT NULL,
  `alert_time` time NOT NULL,
  `first_criterion` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criterion_total` int(11) NOT NULL,
  `alert_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disable_flag` tinyint(4) NOT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ebi_pond_states_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_pond_states_id`)
  REFERENCES `ebi_pond_states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_pond_states` */

DROP TABLE IF EXISTS `ebi_pond_states`;

CREATE TABLE `ebi_pond_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_target` date NOT NULL,
  `time_target` time NOT NULL,
  `pond_id` int(11) NOT NULL,
  `ph_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orp_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tmp_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ec_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ec_abs_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `res_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tds_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sal_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `press_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `do_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `do_ppm_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mv_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `turb_fnu_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gps_lat_values` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gps_long_values` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sigma_t_values` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  `line_no` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_ponds` */

DROP TABLE IF EXISTS `ebi_ponds`;

CREATE TABLE `ebi_ponds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pond_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `water_amount` decimal(10,2) NOT NULL,
  `farm_id` int(10) unsigned  NULL,
  `pond_width` decimal(10,2) NOT NULL,
  `water_dept` decimal(10,2) NOT NULL,
  `pond_vertical_width` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pond_image_area` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_se` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_sw` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_ne` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_nw` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delta_measure` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pond_method` tinyint(4) NULL DEFAULT '0',
  `tag1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ebi_amount` int(10) NULL,
  `ponds_kind_id` int(10) unsigned  NULL,
  `area` int(10) unsigned  NOT NULL,
  `created_user` int(11)  NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`farm_id`)
  REFERENCES `ebi_farms` (`id`),
  FOREIGN KEY (`ponds_kind_id`)
  REFERENCES `ponds_kind` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `ebi_user_farms`;

CREATE TABLE `ebi_user_farms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `farm_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `versions` */

DROP TABLE IF EXISTS `versions`;

CREATE TABLE `versions` (
  `version_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `versionable_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `versionable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_data` blob NOT NULL,
  `reason` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`version_id`),
  KEY `versionable_id` (`versionable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_aquacultures` */

DROP TABLE IF EXISTS `ebi_aquacultures`;

CREATE TABLE `ebi_aquacultures` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `farm_id` int(10) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `estimate_shipping_date` date NOT NULL,
  `completed_date` date DEFAULT NULL,
  `food_amount` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `power_consumption` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `medicine_amount` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `feeding_num` int(11) DEFAULT NULL COMMENT '(回 / 日)',
  `shrimp_num` int(11)  NOT NULL COMMENT '(匹)',
  `target_size` decimal(8,2) DEFAULT NULL,
  `target_weight` decimal(8,2) NOT NULL COMMENT '(g)',
  `servival_rate` decimal(8,2) NOT NULL COMMENT '(％)',
  `shipment_count` int(11) DEFAULT NULL,
  `shipment_standard_20` int(11) DEFAULT NULL,
  `shipment_standard_30` int(11) DEFAULT NULL,
  `shipment_standard_40` int(11) DEFAULT NULL,
  `shipment_standard_50` int(11) DEFAULT NULL,
  `shipment_standard_60` int(11) DEFAULT NULL,
  `shipment_standard_70` int(11) DEFAULT NULL,
  `shipment_standard_80` int(11) DEFAULT NULL,
  `shipment_standard_90` int(11) DEFAULT NULL,
  `shipment_standard_100` int(11) DEFAULT NULL,
  `shipment_standard_110` int(11) DEFAULT NULL,
  `shipment_standard_120` int(11) DEFAULT NULL,
  `shipment_standard_130` int(11) DEFAULT NULL,
  `shipment_standard_140` int(11) DEFAULT NULL,
  `shipment_standard_150` int(11) DEFAULT NULL,
  `shipment_standard_160` int(11) DEFAULT NULL,
  `shipment_standard_170` int(11) DEFAULT NULL,
  `shipment_standard_180` int(11) DEFAULT NULL,
  `shipment_standard_190` int(11) DEFAULT NULL,
  `shipment_standard_200` int(11) DEFAULT NULL,
  `sell` int(11) DEFAULT NULL COMMENT '売上実績',
  `ebi_remaining` int(11) DEFAULT NULL COMMENT 'エビ残',
  `average_price` decimal(8,2) DEFAULT  NULL COMMENT '平均単価',
  `feed_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計餌費用(実績)',
  `medicine_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計薬費用(実績)',
  `ebi_price` int(10) NOT NULL  COMMENT '稚エビ初期費用',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `status` int(10) NOT NULL  COMMENT '0養殖中 1完了',
  `aquaculture_method_id` int(10)  NOT NULL,
  `income_and_expenditure` int(10) NULL COMMENT '収支(実績)',
  `created_user` int(11) NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`farm_id`)
  REFERENCES `ebi_farms` (`id`),
  FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
  FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ponds_aquacultures` */

DROP TABLE IF EXISTS `ponds_aquacultures`;

CREATE TABLE `ponds_aquacultures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `takeover_ponds_id` int(10) unsigned  NULL,
  `ebi_ponds_id` int(10) unsigned NOT NULL,
  `completed_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL,
  `shrimp_num` int(11)  NOT NULL COMMENT '(匹)',
  `target_size` decimal(8,2) DEFAULT NULL,
  `target_weight` decimal(8,2) NOT NULL COMMENT '(g)',
  `shipment_count` int(11) DEFAULT NULL,
  `sell` int(11) DEFAULT NULL COMMENT '売上実績',
  `ebi_remaining` int(11) DEFAULT NULL COMMENT 'エビ残',
  `average_price` decimal(8,2) DEFAULT  NULL COMMENT '平均単価',
  `feed_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計餌費用(実績)',
  `medicine_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計薬費用(実績)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `status` int(10) NOT NULL  COMMENT '0養殖中 1完了',
  `aquaculture_method_id` int(10)  NOT NULL,
  `income_and_expenditure` int(10) NULL COMMENT '収支(実績)',
  `created_user` int(11) NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`takeover_ponds_id`)
  REFERENCES `ebi_ponds` (`id`),
  FOREIGN KEY (`ebi_ponds_id`)
  REFERENCES `ebi_ponds` (`id`),
  FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_shrimp_states` */

DROP TABLE IF EXISTS `ebi_shrimp_states`;

CREATE TABLE `ebi_shrimp_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_target` date NOT NULL,
  `pond_id` int(11) NOT NULL,
  `size` decimal(8,2) DEFAULT NULL,
  `weight` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_bait_inventories` */

DROP TABLE IF EXISTS `ebi_bait_inventories`;

CREATE TABLE `ebi_bait_inventories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bait_name`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` decimal(8,3) DEFAULT NULL,
  `amount_per_bag` decimal(8,2) NOT NULL,
  `threshold` int(11)  NOT NULL,
  `next_purchase` int(11)  NOT NULL,
  `order_qty` int(11)  NOT NULL,
  `order_date` date  NOT NULL,
  `arrival_date` date   NOT NULL,
  `status` int(10) NOT NULL,
  `order_status` int(10) NULL,
  `kind` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_shrimp_states` */

DROP TABLE IF EXISTS `ebi_shrimp_states`;

CREATE TABLE `ebi_shrimp_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_target` date NOT NULL,
  `pond_id` int(11) NOT NULL,
  `size` decimal(8,2) DEFAULT NULL,
  `weight` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_shrimp_states` */

DROP TABLE IF EXISTS `ebi_shrimp_states`;

CREATE TABLE `ebi_shrimp_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_target` date NOT NULL,
  `pond_id` int(11) NOT NULL,
  `size` decimal(8,2) DEFAULT NULL,
  `weight` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `created_user` int(11) NOT NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `threshold_feed` */

DROP TABLE IF EXISTS `threshold_feed`;

CREATE TABLE `threshold_feed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) NOT NULL,
  `weight` int(11) NOT NULL COMMENT '10万匹あたりの餌(kg)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `aquaculture_method_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
   FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `Threshold_grow` */

DROP TABLE IF EXISTS `threshold_grow`;

CREATE TABLE `threshold_grow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) NOT NULL,
  `weight` int(11) NOT NULL  COMMENT '(g)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `aquaculture_method_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
   FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `feed_price` */

DROP TABLE IF EXISTS `feed_price`;

CREATE TABLE `feed_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_bait_inventories_id` int(10) unsigned NOT NULL,
  `price` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_bait_inventories_id`)
  REFERENCES `ebi_bait_inventories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `feed_inventory_remaining` */

DROP TABLE IF EXISTS `feed_inventory_remaining`;

CREATE TABLE `feed_inventory_remaining` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_bait_inventories_id` int(10) unsigned NOT NULL,
  `remaining_amount` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_bait_inventories_id`)
  REFERENCES `ebi_bait_inventories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ebi_price` */

DROP TABLE IF EXISTS `ebi_price`;

CREATE TABLE `ebi_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `weight` decimal(8,2) NOT NULL,
  `ebi_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `feed_cumulative` */

DROP TABLE IF EXISTS `feed_cumulative`;

CREATE TABLE `feed_cumulative` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_aquacultures_id` int(10) NOT NULL,
  `ponds_aquacultures_id` int(10) unsigned  NOT NULL,
  `ebi_bait_inventories_id` int(10) unsigned NOT NULL,
  `cumulative` decimal(8,2) NOT NULL,
  `cost` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_aquacultures_id`)
  REFERENCES `ebi_aquacultures` (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`),
  FOREIGN KEY (`ebi_bait_inventories_id`)
  REFERENCES `ebi_bait_inventories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `medicine_cumulative` */

DROP TABLE IF EXISTS `medicine_cumulative`;

CREATE TABLE `medicine_cumulative` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_aquacultures_id` int(10)  NOT NULL,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `ebi_bait_inventories_id` int(10) unsigned NOT NULL,
  `cumulative` decimal(8,2) NOT NULL,
  `cost` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_aquacultures_id`)
  REFERENCES `ebi_aquacultures` (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`),
  FOREIGN KEY (`ebi_bait_inventories_id`)
  REFERENCES `ebi_bait_inventories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `weather` */

DROP TABLE IF EXISTS `weather`;

CREATE TABLE `weather` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weather` int(10) NOT NULL,
  `day` timestamp NOT NULL,
  `temperature_over` decimal(8,2)  NULL,
  `temperature_under` decimal(8,2) NULL,
  `humidity` decimal(8,2) NULL,
  `precipitation` decimal(8,2) NULL,
  `barometric_pressure` decimal(8,2) NULL,
  `wind` decimal(8,2) NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `shipment` */

DROP TABLE IF EXISTS `shipment`;

CREATE TABLE `shipment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
   `num` int(10) NOT NULL,
  `sell` int(10)  NOT NULL,
  `ebi_weight` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


/*Table structure for table `default_ponds` */

DROP TABLE IF EXISTS `default_ponds`;

CREATE TABLE `default_ponds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `water_amount` decimal(10,2) NOT NULL,
  `pond_width` decimal(10,2) NOT NULL,
  `water_dept` decimal(10,2) NOT NULL,
  `pond_vertical_width` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lat_long_se` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_sw` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_ne` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat_long_nw` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delta_measure` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pond_method` tinyint(4) NULL DEFAULT '0',
  `tag1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tag4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ebi_amount` int(10) NULL,
  `ponds_kind_id` int(10) unsigned  NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ponds_kind_id`)
  REFERENCES `ponds_kind` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `default_farm` */

DROP TABLE IF EXISTS `default_farm`;

CREATE TABLE `default_farm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `food_amount` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `power_consumption` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `medicine_amount` decimal(8,2) DEFAULT NULL COMMENT '(円)(目標)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `feeding_num` int(11) DEFAULT NULL COMMENT '(回 / 日)',
  `shrimp_num` int(11)  NOT NULL COMMENT '(匹)',
  `target_size` decimal(8,2) DEFAULT NULL,
  `target_weight` decimal(8,2) NOT NULL COMMENT '(g)',
  `servival_rate` decimal(8,2) NOT NULL COMMENT '(％)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `aquaculture_method_id` int(10)  NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
  FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `years_report` */

DROP TABLE IF EXISTS `years_report`;

CREATE TABLE `years_report` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `farm_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shrimp_num` int(11) NULL COMMENT '(匹)',
  `shipment_count` int(11) DEFAULT NULL,
  `shipment_standard_20` int(11) DEFAULT NULL,
  `shipment_standard_30` int(11) DEFAULT NULL,
  `shipment_standard_40` int(11) DEFAULT NULL,
  `shipment_standard_50` int(11) DEFAULT NULL,
  `shipment_standard_60` int(11) DEFAULT NULL,
  `shipment_standard_70` int(11) DEFAULT NULL,
  `shipment_standard_80` int(11) DEFAULT NULL,
  `shipment_standard_90` int(11) DEFAULT NULL,
  `shipment_standard_100` int(11) DEFAULT NULL,
  `shipment_standard_110` int(11) DEFAULT NULL,
  `shipment_standard_120` int(11) DEFAULT NULL,
  `shipment_standard_130` int(11) DEFAULT NULL,
  `shipment_standard_140` int(11) DEFAULT NULL,
  `shipment_standard_150` int(11) DEFAULT NULL,
  `shipment_standard_160` int(11) DEFAULT NULL,
  `shipment_standard_170` int(11) DEFAULT NULL,
  `shipment_standard_180` int(11) DEFAULT NULL,
  `shipment_standard_190` int(11) DEFAULT NULL,
  `shipment_standard_200` int(11) DEFAULT NULL,
  `sell` int(11) DEFAULT NULL COMMENT '売上実績',
  `feed_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計餌費用(実績)',
  `medicine_cumulative` decimal(8,2) DEFAULT  NULL COMMENT '累計薬費用(実績)',
  `ebi_price` int(10) NULL  COMMENT '稚エビ初期費用',
  `income_and_expenditure` int(10) NULL COMMENT '収支(実績)',
  `target_syusi` int(10) NULL COMMENT '収支(目標)',
  `year` int(10) NOT NULL COMMENT '年度',
  `created_user` int(11) NULL DEFAULT '1',
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`farm_id`)
  REFERENCES `ebi_farms` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `ebi_baits`;

CREATE TABLE `ebi_baits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `ebi_bait_inventories_id` int(10) unsigned NOT NULL,
  `bait_at` timestamp NOT NULL,
  `amount` int(10) NOT NULL,
  `baits_amount` int(10) NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`),
  FOREIGN KEY (`ebi_bait_inventories_id`)
  REFERENCES `ebi_bait_inventories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `threshold_fcr`;

CREATE TABLE `threshold_fcr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) NOT NULL,
  `fcr` int(11) NOT NULL  COMMENT '(g)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `aquaculture_method_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
   FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `threshold_adg`;

CREATE TABLE `threshold_adg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(10) NOT NULL,
  `adg` int(11) NOT NULL  COMMENT '(g/日)',
  `ebi_kind_id` int(10) unsigned NOT NULL,
  `aquaculture_method_id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
   FOREIGN KEY (`ebi_kind_id`)
  REFERENCES `ebi_kind` (`id`),
   FOREIGN KEY (`aquaculture_method_id`)
  REFERENCES `aquaculture_method` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `fcr`;

CREATE TABLE `fcr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `fcr` int(10) NOT NULL,
  `fcr_date` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `adg`;

CREATE TABLE `adg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ponds_aquacultures_id` int(10) unsigned NOT NULL,
  `adg` int(10) NOT NULL,
  `adg_date` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ponds_aquacultures_id`)
  REFERENCES `ponds_aquacultures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `cost`;

CREATE TABLE `cost` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cost` int(10) unsigned NOT NULL,
  `kind` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `solution`;
CREATE TABLE `solution` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ebi_pond_states_id` int(10) unsigned NOT NULL,
  `work` varchar(1000) NOT NULL,
  `weather` int(10) NOT NULL,
  `feedtime` int(10) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ebi_pond_states_id`)
  REFERENCES `ebi_pond_states` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



/*Table structure for table `ebi_user_farms` */
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
