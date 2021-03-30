<?php
include 'config.php';
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name 
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
		$searchQuery = " and (id like '%".$searchValue."%' or 
        name like '%".$searchValue."%' or 
		symbol like '%".$searchValue."%' or 
		value like '%".$searchValue."%' or 
		usd like '%".$searchValue."%' or 
		token_address like '%".$searchValue."%' 
		) ";
}

## Total number of records without filtering
$sel = mysqli_query($conn,"select count(*) as allcount from crypto_currency WHERE 1");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of record with filtering 
$sel = mysqli_query($conn,"select count(*) as allcount from crypto_currency WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from crypto_currency WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

//echo $empQuery;
$empRecords = mysqli_query($conn, $empQuery);
$data = array();
while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array( 
    "id"=>$row['id'],
    "name"=>$row['name'],
    "symbol"=>$row['symbol'],
    "value"=>$row['value'],
    "usd"=>$row['usd'],
    "token_address"=>$row['token_address'],
    "view"=>"<a href='view_graph.php?currency=".$row['symbol']."'>Click Here</a>",
    );
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data
);
echo json_encode($response);