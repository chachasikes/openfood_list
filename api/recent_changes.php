<?php
include('../../openfoodmongo_authenticate.php');
connectMongo(false);

if(!empty($_GET['page'])){
 $page = $_GET['page'];
}
else{
  $page = 0;
}
$page_items = 500;

$collection = $m->openfood->foods;

// find everything in the collection
$query = array("openfood_update" => array('$ne' => "null" ));

$cursor = $collection->find($query)->limit($page_items)->sort(array("openfood_update" => -1));
$count = $cursor->count();

header('Access-Control-Allow-Origin: *.foodcards.org | *.chachaville.com');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
 $page;
$json = '{"foods": [' ;

$i = 0;

// iterate through the results
foreach ($cursor as $obj) {
  if(!empty($obj['openfood_update'])) {
    if($i > 0) {
     $json .= ',';
    }
    
    // Convert date



    $obj['openfood_update']->date = date('Y-M-d h:i:s', $obj['openfood_update']->sec);

    $json .= json_encode(array("name" => $obj['name'], "date" => $obj['openfood_update']->date ));
  
    $i++;
  }
}
/* $json .= "," . json_encode(array('count' => $collection->count())); */

$json .= '], "count" : ' . $count . '}';

echo $json;

?>