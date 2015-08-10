<?php

include "../config/config.php";

$db = dbConn::getConnection();

//session_start();

//$idad=$_SESSION['uid'];
$idad = 111100;

function string_limit_words($string, $word_limit) 

{

$words = explode(' ', $string);

return implode(' ', array_slice($words, 0, $word_limit));

}

$judul = str_replace("#","-",str_replace("$","-",str_replace("~","-",str_replace(".","-",str_replace(",","-",str_replace("+","-",str_replace("*","-",str_replace("'","-",str_replace('"','-',str_replace('@','-',str_replace('&','-',$_POST['txtjdlabout'])))))))))));

$judul = htmlentities($judul);



//Title to friendly URL conversion

$newjdl=string_limit_words($judul, 8); // First 6 words

$aurl=preg_replace('/[^a-z0-9]/i',' ', $newjdl);

$newurl=str_replace("/","", (str_replace("#","", (str_replace("?","", (str_replace(",","",(str_replace("^","",(str_replace("&amp;","+",(str_replace("~","",(str_replace("'","",(str_replace(";","",(str_replace(":","",str_replace("(","",(str_replace(")","",str_replace(" ","-",$newjdl)))))))))))))))))))))));

$url=strtolower($newurl).'.html'; // Final URL

$medes = substr($_POST['txtisiabout'],0,225);

$isi = $_POST['txtisiabout'];

$display = 'Y';

$tgl = date("Y-m-d H:i:s");

	  

if(empty($isi) || empty($judul)){

	echo '1';

}else{

	

// Insert data into mysql

$spq="INSERT INTO tb_about

(ab_url,ab_judul,ab_meta_desc,ab_isi,ab_display,ab_tanggal,idad)

VALUES(:url,:judul,:medes,:isi,:display,:tgl,:idad)";




$qpq = $db->prepare($spq);

$qpq->bindParam(':url', $url, PDO::PARAM_STR);		
$qpq->bindParam(':judul', $judul, PDO::PARAM_STR);		
$qpq->bindParam(':medes', $medes, PDO::PARAM_STR);		
$qpq->bindParam(':isi', $isi, PDO::PARAM_STR);		
$qpq->bindParam(':display', $display, PDO::PARAM_STR);		
$qpq->bindParam(':tgl', $tgl, PDO::PARAM_STR);
$qpq->bindParam(':idad', $idad, PDO::PARAM_STR);		
		



$qpq->execute();


echo '3';

}

?>