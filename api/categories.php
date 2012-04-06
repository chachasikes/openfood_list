<?php

// connect
$m = new Mongo();
$categories = $m->openfood->categories;
$foods = $m->openfood->foods;


// find everything in the collection
if(!empty($_GET['category'])){


    $search_split = explode(",", $_GET['category']);
    $multiple_searches = implode("|", $search_split);
    $search = new MongoRegex('/'. $multiple_searches . '/i');
    $cursor = $foods->find(array("category" => $search))->limit(1000)->sort(array("name" => 1));
    



}

else {
  
    $cursor = $categories->find()->limit(1000)->sort(array("category" => 1));    
  
}

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