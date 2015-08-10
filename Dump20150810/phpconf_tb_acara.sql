-- MySQL dump 10.13  Distrib 5.6.24, for Win32 (x86)
--
-- Host: localhost    Database: phpconf
-- ------------------------------------------------------
-- Server version	5.6.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_acara`
--

DROP TABLE IF EXISTS `tb_acara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_acara` (
  `idac` int(11) NOT NULL AUTO_INCREMENT,
  `ac_url` varchar(100) DEFAULT NULL,
  `ac_judul` varchar(100) DEFAULT NULL,
  `ac_meta_desc` varchar(300) DEFAULT NULL,
  `ac_isi` text,
  `ac_display` enum('Y','N') DEFAULT NULL,
  `ac_tanggal` datetime DEFAULT NULL,
  `idad` char(6) DEFAULT NULL,
  PRIMARY KEY (`idac`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_acara`
--

LOCK TABLES `tb_acara` WRITE;
/*!40000 ALTER TABLE `tb_acara` DISABLE KEYS */;
INSERT INTO `tb_acara` VALUES (1,'what-is-php-ecosystem-.html','What is PHP Ecosystem? ','Di era industri modern saat ini, peranan teknologi informasi berbasis jaringan dan web memegang peranan yang sangat penting hampir disemua sektor, baik didunia industri, pemerintahan, maupun pendidikan, sehingga dibutuhkan tekhnologi yang tepat, akurat, handal, efektif, dan efisien,  serta dukungan.','<p>&nbsp; &nbsp; &nbsp; Diera industri modern saat ini, peranan teknologi informasi berbasis jaringan dan web memegang peranan yang sangat penting hampir disemua sektor, baik didunia industri, pemerintahan, maupun pendidikan, sehingga dibutuhkan tekhnologi yang tepat, akurat, handal, efektif, dan efisien, &nbsp;serta dukungan tenaga-tenaga IT yang memiliki keahlian tinggi dan berpengalaman.</p>\r\n<p>&nbsp; &nbsp; &nbsp; Dengan perkembangan lalu lintas data yang begitu besar dan cepat, selain membutuhkan dukungan teknologi server dan infra struktur jaringan yang tepat dan handal, juga dukungan piranti lunak yang sesuai dengan kebutuhan dan dapat diandalkan, baik fungsi maupun dukungan dan perawatan.</p>\r\n<p>&nbsp; &nbsp; &nbsp; PHP salah satu bahasa scripting berbasis web yang paling banyak digunakan dalam aplikasi berbasis web, dengan dukungan teknologi basis data dan teknologi pendukung lainnya, secara tidak langsung telah membuat sebuah ekosistem antara penyedia teknologi piranti keras, penyedia piranti lunak, serta industri dan institusi pengguna tekhnologi ini. Sebagai sebuah ekosistem yang saling bergantungan satu sama lain, tentunya dibutuhkan sinergi yang baik diantara komponen dalam ekosistem ini.&nbsp;</p>','Y','2015-08-01 09:10:50','111100'),(2,'what-is-php-conference-2015-.html','What is PHP Conference 2015? ','Pada tahun 2015 ini, dimotori oleh Peter Jack Kambey (dan beberapa pegiat lain), komunitas PHP Indonesia kembali merencanakan pelaksanaan conference dengan nama “PHP Indonesia Inter- national Conference 2015 goes to Bandung”  dengan tema “PHP Ecosystem”.','<p>&nbsp; &nbsp; &nbsp; Pada tahun 2015 ini, dimotori oleh Peter Jack Kambey (dan beberapa pegiat lain), komunitas PHP Indonesia kembali merencanakan pelaksanaan conference dengan nama &ldquo;PHP Indonesia Inter- national Conference 2015 goes to Bandung&rdquo; &nbsp;dengan tema &ldquo;PHP Ecosystem&rdquo;.</p>\r\n<p>&nbsp; &nbsp; &nbsp; Pelaksanaan acara ini direncanakan digedung Sabuga di kota Bandung - Jawa Barat, pertim- bangan pemilihan kota bandung sebagai lokasi acara, selain merupakan salah satu kota yang memi- liki nilai sejarah yang tinggi, juga disebabkan Bandung adalah salah satu sentra pengembangan industry IT tanah air khususnya OPEN &nbsp;SOURCE, dan dukungan pemerintah dalam pengembangan dan penerapan IT, baik dalam industri maupun tata pemerintahan.</p>\r\n<p>&nbsp; &nbsp; &nbsp; PHP Indonesia International Conference 2015 bertujuan mempertemukan seluruh komponen yang berada dalam PHP Ecosystem agar didapat sinergi yang menguntungkan baik dari dunia Indus- tri, Pemerintahan, Dunia pendidikan, Penyedia Solusi berbasis IT, maupun penyedia perangkat teknologi pendukung. Selain itu, tujuan penting lainnya adalah untuk memudahkan para pengguna layanan IT mendapatkan dan memilih teknologi yang tepat, handal dan terpercaya.</p>\r\n<p>&nbsp; &nbsp; &nbsp; Dalam conference ini, direncanakan menghadirkan ahli dari Zend Technology, merupakan perusahaan &nbsp;yang mengembangkan teknologi PHP, dan akan menjabarkan tentang perkembangan teknologi PHP serta kemampuan PHP dalam dunia Enterprise dan dukungan dan layanan bagi peng- gunaan PHP dalam dunia Enterprise, Selain perwakilan dari Zend Technology, conference ini juga akan menghadirkan perwakilan dari industri perangkat keras, baik server, dan perangkat pendukung jaringan lainnya, serta akan dihadiri oleh vendor piranti lunak multinasional.</p>','Y','2015-08-01 09:13:24','111100'),(3,'highlight&nbsp.html','Highlight&nbsp;','Zend International Informasi perkembangan terkini mengenai perkembangan teknologi PHP dan PHP7 (PHP-NG Next Generation) langsung dari sumber terpercaya, Zend Internasional. Ikuti perkembangan teknologi infrastruktur IT terbaru untuk menjamin informasi data yang Cepat, Aman, dan Ketersediaan Tinggi.','<h1><em>Audience Target</em></h1>\r\n<p><strong>A. &nbsp;Zend International Informasi</strong></p>\r\n<p>perkembangan terkini mengenai perkembangan teknologi PHP dan PHP7 (PHP-NG Next Generation) langsung dari sumber terpercaya, Zend Internasional.</p>\r\n<p><strong>B. &nbsp;IT Infrastructure</strong></p>\r\n<p>Ikuti perkembangan teknologi infrastruktur IT terbaru untuk menjamin informasi data yang Cepat, Aman, dan Ketersediaan Tinggi (Fast, Secure, High Availability)</p>\r\n<p><strong>C. Cloud Computing</strong></p>\r\n<p>Cloud Computing adalah solusi untuk semua kerumitan pengelolaan server dan data center anda di masa sekarang ini dan masa yang akan datang.&nbsp;</p>\r\n<p><strong>D. PHP Enterprise</strong></p>\r\n<p>PHP now ready for enterprise. Simak perkembangan teknologi PHP terbaru, dan temukan solusi yang dapat ditawarkan PHP untuk memenuhi kebutuhan aplikasi enterprise sekarang ini.&nbsp;</p>','Y','2015-08-01 09:17:02','111100');
/*!40000 ALTER TABLE `tb_acara` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-10 10:00:54
