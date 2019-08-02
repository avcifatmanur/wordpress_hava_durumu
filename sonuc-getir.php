<?php
include '../../../wp-load.php';

 if (!defined('ABSPATH')) {
	exit;
} 

 if (!@$_POST['islem']=="sonucgetir") {
  exit;  
}
 
 $url = "http://api.openweathermap.org/data/2.5/weather?q=$_POST[Sehir]&appid=$_POST[apikey]";

$icerik = @file_get_contents($url);
 if (!$icerik) {
     echo "api hatalı girilmiş.";
 }else{
$data = json_decode($icerik , TRUE);
$data['main']['temp']=$data['main']['temp']-273.15; 
echo floor($data['main']['temp']); }
 
?>
