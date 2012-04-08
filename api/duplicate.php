<?php

include('../../openfoodmongo_authenticate.php');
connectMongo(true);

$collection = $m->openfood->foods;


if(!empty($_POST["food"])){
  $food = $_POST["food"];
  // Hard coding mappings until figure out mongo syntax.
  $food_obj = array('$set' => array(
    'is_duplicate' => $food["is_duplicate"],
  ));

  $collection->update(array('nid' => (int) $food["nid"]), $food_obj, array("upsert" => false, "multiple" => false));
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