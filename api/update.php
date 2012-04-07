<?php

// connect
$m = new Mongo();
$collection = $m->openfood->foods;
$food = $_POST["food"];

if(!empty($food)){
/*     $collection->update(array('_id' => $food->_id->$id), array('$set' => $food), true); */
    $cursor = $collection->find(array('nid' => (int) $food["nid"]));

/* echo $food["_id"]['$id']; */


header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
  
$json = '{"food": [' ;

// iterate through the results
foreach ($cursor as $record) {
  if(!empty($record->name)) {
    $json .= json_encode($food);
  }
}

$json .= ']}';

echo $json;
}
?>