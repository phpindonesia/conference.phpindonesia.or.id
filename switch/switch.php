<?php

switch((isset($_GET['url']) ? $_GET['url'] : '')){
case 'schedule':
include 'pages/schedule.php';
break;
case 'sponsor':
include 'pages/sponsor-exhibitor.php';
break;
case 'agenda':
include 'pages/agenda.php';
break;
case 'venue':
include 'pages/venue.php';
break;
case 'register':
include 'pages/register.php';
break;
case 'contact':
include 'pages/contact.php';
break;
case 'about':
include 'pages/about.php';
break;
default:
include 'pages/home.php';
}

?>