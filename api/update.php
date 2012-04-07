<?php

// connect

include('../../openfoodmongo_authenticate.php');

$collection = $m_write->openfood->foods;
$food = $_POST["food"];

if(!empty($food)){
    // Hard coding mappings until figure out mongo syntax.
    $food_obj = array('$set' => array(
      'food_color_background' => $food["food_color_background"],
      'food_color_text' => $food["food_color_text"],
      'openfood_update' => new MongoDate(strtotime(date('Y-M-d h:i:s')))
    ));

/*     $food_obj = array('$pushAll' => $food); */
/*     $food_obj = array('$each' => $food); */
    $collection->update(array('nid' => (int) $food["nid"]), $food_obj, array("upsert" => false, "multiple" => true));
/*     $collection->save($food); */
    $cursor = $collection->find(array('nid' => (int) $food["nid"]));
header('Access-Control-Allow-Origin: *.foodcards.org | *.chachaville.com');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
  
$json = '{"food": [' ;

// iterate through the results
foreach ($cursor as $record) {
/*   if(!empty($record->name)) { */
    $json .= json_encode($record);
/*   } */
}

$json .= ']}';

echo $json;
}
?>