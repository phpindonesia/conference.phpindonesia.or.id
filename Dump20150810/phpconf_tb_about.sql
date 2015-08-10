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
-- Table structure for table `tb_about`
--

DROP TABLE IF EXISTS `tb_about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_about` (
  `idab` int(11) NOT NULL AUTO_INCREMENT,
  `ab_url` varchar(100) DEFAULT NULL,
  `ab_judul` varchar(100) DEFAULT NULL,
  `ab_meta_desc` varchar(225) DEFAULT NULL,
  `ab_isi` text,
  `ab_display` enum('Y','N') DEFAULT NULL,
  `ab_tanggal` datetime DEFAULT NULL,
  `idad` char(6) DEFAULT NULL,
  PRIMARY KEY (`idab`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_about`
--

LOCK TABLES `tb_about` WRITE;
/*!40000 ALTER TABLE `tb_about` DISABLE KEYS */;
INSERT INTO `tb_about` VALUES (1,'overview.html','Overview','<h2><em>PHP Indonesia introduction</em></h2>\r\n<p>&nbsp; &nbsp; &nbsp;PHP Indonesia adalah komunitas pemrogram berbasis Bahasa Scripting PHP yang pertama kali disusun oleh Rasmus Lerdorf kemudian dikembangkan oleh Zeev Surasky','<h2><em>PHP Indonesia introduction</em></h2>\r\n<p>&nbsp; &nbsp; &nbsp;PHP Indonesia adalah komunitas pemrogram berbasis Bahasa Scripting PHP yang pertama kali disusun oleh Rasmus Lerdorf kemudian dikembangkan oleh Zeev Surasky dan Andy Gutzman dengan interpreteur &nbsp;Zend Engines, &nbsp;serta dikembangkan oleh anggota komunitas dari seluruh dunia. Untuk saat ini, bahasa scripting PHP merupakan salah satu bahasa pemrograman berbasis web yang sangat popular, &nbsp;sehubungan dengan trend bisnis saat ini yang cenderung menggunakan aplikasi berbasis web.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Pada awalnya PHP Indonesia merupakan sebuah Group diskusi online di facebook yang dibuat pada tanggal 8 Februari 2008 oleh Sonny Arlianto Kurniawan, atas usulan Rama Yurindra pada tanggal 6 Februari 2008 disebuah Caf&eacute; di kemang.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Pada tanggal 31 Maret 212 bertempat di Auditorium PT Microsoft Gedung BEJ II lt 19, Jakarta, diadakan Gathering anggota yang menjadi salah satu tonggak sejarah penting komunitas PHP Indonesia. &nbsp;Pada pertemuan ini, bertemu para anggota yang memiliki passion untuk lebih mengembangkan komunitas PHP Indonesia tidak hanya sebatas group diskusi online, akan tetapi menyusun struktur organisasi dengan membentuk perwakilan PHP Indonesia diseluruh kota Indonesia yang semuanya dilaksanakan oleh anggota komunitas ini yang memiliki spirit dan passion yang sama. Sejak tahun 2012 hingga tahun 2015 telah terbentuk perwakilan komunitas PHP Indonesia hingga di 14 Provinsi.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Dalam aktivitasnya, perwakilan PHP Indonesia, secara periodik melakukan Meet Up, Gathering, Workshop, atau seminar, baik bekerja sama dengan Institusi kampus pendidikan tinggi, bekerja sama dengan komunitas IT lainnya, dan bantuan dari perusahaan-perusahaan berbasis telekomunikasi. Sedang di Jakarta, aktivitas komunitas PHP Indonesia cukup banyak mendapat dukungan dari perusahaan IT Multi Nasional seperti PT Microsoft Indonesia, PT IBM Indonesia, Detik.com, perusahaan e-commerce seperti Ezytravel.co,id serta lembaga nirlaba lainnya seperti GEPI (Global Entrepreneur Program Incubator), dan lain-lain.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Perkembangan anggota diskusi online berkembang sangat pesat, pada awal Juni 2015 telah bergabung lebih dari 81.000 anggota. &nbsp;Banyak anggota serta kenyataan begitu banyak anggota yang memiliki latar belakang keahlian pemrograman dari berbagai bahasa pemrograman serta keahlian tekhnologi informasi lainnya, seperti teknologi jaringan dan multimedia, pada akhirnya komunitas PHP Indonesia tidak lagi khusus bagi pemrogram PHP, akan tetapi sudah menjadi rumah besar bagi komunitas IT nasional. &nbsp;Sehingga group PHP Indonesia di Facebook merupakan group IT di Indonesia yang terbesar dan teraktif di media social Facebook.&nbsp;</p>\r\n<h2><em>What is PHP Conference 2015?</em></h2>\r\n<p>&nbsp; &nbsp; &nbsp;Pada tahun 2015 ini, dimotori oleh Peter Jack Kambey (dan beberapa pegiat lain), komunitas PHP Indonesia kembali merencanakan pelaksanaan conference dengan nama &ldquo;PHP Indonesia Inter- national Conference 2015 goes to Bandung&rdquo; &nbsp;dengan tema &ldquo;PHP Ecosystem&rdquo;.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Pelaksanaan acara ini direncanakan digedung Sabuga di kota Bandung - Jawa Barat, pertim- bangan pemilihan kota bandung sebagai lokasi acara, selain merupakan salah satu kota yang memi- liki nilai sejarah yang tinggi, juga disebabkan Bandung adalah salah satu sentra pengembangan industry IT tanah air khususnya OPEN &nbsp;SOURCE, dan dukungan pemerintah dalam pengembangan dan penerapan IT, baik dalam industri maupun tata pemerintahan.</p>\r\n<p>&nbsp; &nbsp; &nbsp;PHP Indonesia International Conference 2015 bertujuan mempertemukan seluruh komponen yang berada dalam PHP Ecosystem agar didapat sinergi yang menguntungkan baik dari dunia Indus- tri, Pemerintahan, Dunia pendidikan, Penyedia Solusi berbasis IT, maupun penyedia perangkat teknologi pendukung. Selain itu, tujuan penting lainnya adalah untuk memudahkan para pengguna layanan IT mendapatkan dan memilih teknologi yang tepat, handal dan terpercaya.</p>\r\n<p>&nbsp; &nbsp; &nbsp;Dalam conference ini, direncanakan menghadirkan ahli dari Zend Technology, merupakan perusahaan &nbsp;yang mengembangkan teknologi PHP, dan akan menjabarkan tentang perkembangan teknologi PHP serta kemampuan PHP dalam dunia Enterprise dan dukungan dan layanan bagi peng- gunaan PHP dalam dunia Enterprise, Selain perwakilan dari Zend Technology, conference ini juga akan menghadirkan perwakilan dari industri perangkat keras, baik server, dan perangkat pendukung jaringan lainnya, serta akan dihadiri oleh vendor piranti lunak multinasional.</p>','Y','2015-08-09 09:10:13','111100');
/*!40000 ALTER TABLE `tb_about` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-10 10:00:53
