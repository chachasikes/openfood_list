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

$collection = $m->openfood->kitchen;

// find everything in the collection
$cursor = $collection->find()->skip($page * $page_items)->limit($page_items)->sort(array("name" => 1));
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
  if(!empty($obj['name'])) {
    if($i > 0) {
     $json .= ',';
    }

    $json .= json_encode($obj);
  
    $i++;
  }
}
/* $json .= "," . json_encode(array('count' => $collection->count())); */

$json .= '], "count" : ' . $count . '}';

echo $json;

?>