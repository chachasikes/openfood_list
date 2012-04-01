<?php

// connect
$m = new Mongo();
$collection = $m->chacha->foods;

// add another record, with a different "shape"
$obj = array( "title" => "title", "value" => true );
$collection->insert($obj);

// find everything in the collection
$cursor = $collection->find();


header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
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

$json .= ']}';

echo $json;

?>