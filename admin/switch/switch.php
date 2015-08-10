<?php

switch((isset($_GET['url']) ? $_GET['url'] : '')){
case 'informasi':
include 'pages/informasi.php';
break;
case 'register':
include 'pages/register.php';
break;
default:
include 'pages/home.php';
}

?>