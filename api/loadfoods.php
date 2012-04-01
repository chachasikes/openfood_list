<?php

// connect
$m = new Mongo();
$collection = $m->openfood->foods;

// find everything in the collection
$cursor = $collection->find();


// add another record, with a different "shape"
$obj = array( "title" => "title", "value" => true );

$file_path = '../data/open_food_bkg_colors.json';
$file_data = file_get_contents($file_path);
/* var_dump($file_data); */

$json = json_decode($file_data);
$objects = $json->foods;

foreach ($objects as $obj_load) {
  $collection->insert($obj_load);
}



// find everything in the collection
$cursor = $collection->find();


// Print data
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