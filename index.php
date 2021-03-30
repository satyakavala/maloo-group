<?php
error_reporting(0);
$conn = mysqli_connect("localhost","root","");
$db_selected = mysqli_select_db($conn,'maloo_group_task');
if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = 'CREATE DATABASE maloo_group_task';
  echo $sql;
  mysqli_query($conn,$sql);
  $db_selected = mysqli_select_db($conn,'maloo_group_task');
}
date_default_timezone_set("Asia/Calcutta");
$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
$parameters = [
  'start' => '1',
  'limit' => '20', //No. of Currencies
  'convert' => 'USD'
];
//Initialize Header
$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: 38b33274-8f5c-425d-9731-4809a4330f8b' //key created from coin market website
];
$qs = http_build_query($parameters); // query string encode the parameters
$request = "{$url}?{$qs}"; // create the request URL
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $request,            
  CURLOPT_HTTPHEADER => $headers,     
  CURLOPT_RETURNTRANSFER => 1         
));

$response = curl_exec($curl);
$crypto_res=json_decode($response);
//print_r($crypto_res);
//read data and insert it into database
mysqli_query($conn,"DROP TABLE IF EXISTS `crypto_currency`");
$create_sql="CREATE TABLE `crypto_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `num_market_pairs` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `usd` varchar(255) NOT NULL,
  `platform` varchar(255)  NULL,
  `added_on` date NOT NULL,
  `token_address` text  NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
mysqli_query($conn,$create_sql);
foreach($crypto_res->data as $crypt)
{
	$sql_insert="INSERT INTO `crypto_currency`(`name`, `symbol`, `num_market_pairs`, `value`, `usd`,`added_on`,`token_address`,`platform`) VALUES ('".$crypt->name."','".$crypt->symbol."','".$crypt->num_market_pairs."','1','".round($crypt->quote->USD->price,2)."','".$crypt->date_added."','".$crypt->platform->token_address."','".$crypt->platform->name."')";
  $result=mysqli_query($conn,$sql_insert);  
}
if(intval($result))
{
  header("Location: view_currencies.php");
}
else
{
  echo "something went wrong Please try again later";
}
curl_close($curl); // Close request
?>

