<?php
include('../../../openfoodmongo_authenticate.php');
$foods_collection = $m->openfood->foods;

// find everything in the collection
$cursor = $foods_collection->find();
$category_index = $m_write->openfood->categories->drop();
$category_index = $m_write->openfood->categories;



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


$collection = $m->openfood->categories;
$cursor = $collection->find();
// find everything in the collection
/* $cursor = $collection->find(array('name' => "Dill"))->limit(10); */
/* $cursor = $collection->findOne(array('nid' => '739')); */
// $cursor = $collection->find()->limit(300);

// Print data
header('Access-Control-Allow-Origin: *.chachaville.com');
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