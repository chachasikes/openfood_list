<?php

include('../../openfoodmongo_authenticate.php');
connectMongo(true);

$collection = $m->openfood->foods;

  $food_obj = array('$set' => array(
    'food_color_background' => '020010',
    'food_color_text' => 'ffffff',
    'openfood_update' => new MongoDate(strtotime(date('Y-M-d h:i:s')))
    ));



  $collection->update(array('nid' => 751), $food_obj, array("upsert" => false, "multiple" => false));
  $cursor = $collection->find(array('nid' => 751));

header('Access-Control-Allow-Origin: *.foodcards.org | *.chachaville.com');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
$json= '';  
/* $json = '{"food": [' ; */

// iterate through the results
foreach ($cursor as $record) {
/*   if(!empty($record->name)) { */
    $json .= json_encode(  $record);
/*   } */
}

/* $json .= ']}'; */

echo $json;

?>