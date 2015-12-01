<?php 
require('setup.inc.php');

//$url = 'http://api.iml.ru/list/service?type=json';
//$login = '07308';
//$pass = 'TAaB4myF';
//
//$curl = curl_init($url);
//curl_setopt($curl, CURLOPT_HEADER, false);
//curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($curl, CURLOPT_USERPWD, $login.":".$pass);
//curl_setopt($curl, CURLOPT_SSLVERSION, 3);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//$response = curl_exec($curl);
//$result = json_decode($response, true); // результат запроса  
//
//$arr = array();
//foreach ($result as $data) {
//    $arr[$data['Code']] = $data['Description'];
//}
//
//print_r($arr);

$iml = new Imldelivery\Model\DeliveryType\Iml;
$arr = $iml->getSdRegions();
print_r($arr, true);
echo count($arr);