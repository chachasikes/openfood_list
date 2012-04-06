<?php

// connect
$m = new Mongo();

  $food = $_POST;

  if(!empty($food)){
    $m->openfood->foods->update(array('nid' => $food["food"]["nid"]), array('$set' => $food["food"]), true);
    $record = $m->openfood->foods->find(array('nid' => 745))->limit(1);
  }

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");

$cursor = $record;
$json = '{"food": [' ;

// iterate through the results
foreach ($record as $record) {
  if(!empty($record->name)) {
    $json .= json_encode($record);
  }
}

$json .= ']}';

echo $json;

?>