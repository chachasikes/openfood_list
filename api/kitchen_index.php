<?php
include('../../openfoodmongo_authenticate.php');
connectMongo(true);
$kitchen_collection = $m->openfood->kitchen;

// find everything in the collection
$cursor = $kitchen_collection->find();
$category_index = $m->openfood->kitchen_categories->drop();
$category_index = $m->openfood->kitchen_categories;

$json = '';
foreach ($cursor as $obj) {
  if(!empty($obj['name'])) {
    if($obj['category_array'][0] !== '') {
      foreach($obj['category_array'] as $category) {
        $category = strtolower(trim($category)); 
        $category_obj = array('$addToSet' => array('name' => $obj["name"]), '$set' => array('category' => $category));
        $category_index->update(array('category' => $category), $category_obj, true);      
      }
    }
  }
}


$collection = $m->openfood->kitchen_categories;
$cursor = $collection->find();

// Print data
header('Access-Control-Allow-Origin: *.foodcards.org | *.chachaville.com');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");
$json = '{"categories": [' ;

$i = 0;

// iterate through the results

foreach ($cursor as $obj) {
  if(!empty($obj['category'])) {
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