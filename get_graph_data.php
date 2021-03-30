<?php
include "config.php";
$date=date('Y-m-d\Th:i');
$values=array();
//$url = 'https://production.api.coindesk.com/v2/price/values/ETH?start_date=2021-03-23T12:52&end_date=2021-03-24T00:52&ohlc=false';
$url = 'https://production.api.coindesk.com/v2/price/values/'.$_REQUEST['currency'];
$parameters = [
  'start_date' => date("Y-m-d\Th:i", strtotime("-1 year", strtotime($date))),
  'end_date' =>  $date,
  'ohlc'=>'false'
];
//Initialize Header
$headers = [
  'Accepts: application/json'
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
print_r($response);

/*$currency_history=json_decode($response);
for ($row = 0; $row < sizeof($currency_history->data->entries) ; $row++) {
   $values['date']=$currency_history->data->entries[$row][0];
   $valuees['value']=$currency_history->data->entries[$row][1];
   $data['results'][] = array(
    'date' => date('Y-m-d',substr($currency_history->data->entries[$row][0],0,-3)),
    'value' => $currency_history->data->entries[$row][1]
  );
}
echo json_encode($data);*/
?>