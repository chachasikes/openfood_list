<?php

include('../../openfoodmongo_authenticate.php');
connectMongo(false);

if(!empty($_GET['page'])){
 $page = $_GET['page'];
}
else{
  $page = 0;
}
$page_items = 500;

$collection = $m->openfood->foods;

// find everything in the collection
if(!empty($_GET['search'])){
  $search_split = explode(",", $_GET['search']);
  $multiple_searches = implode("|", $search_split);
  $search = new MongoRegex('/'. $multiple_searches . '/i');
  $cursor = $collection->find(array("name" => $search))->skip($page * $page_items)->limit($page_items)->sort(array("name" => 1));
  $count = $cursor->count();
}
else if(!empty($_GET['nid'])){
    $search = explode(",", $_GET['nid']);
    $cursor = $collection->find(array("nid" => (int) $search[0]))->skip($page * $page_items)->limit($page_items)->sort(array("nid" => 1));
    $count = $cursor->count();  
}
else if(!empty($_GET['category'])){
  if($_GET['category'] === 'all') {
    $cursor = $collection->find()->limit($page_items)->skip($page * $page_items)->sort(array("name" => 1));
    $count = $collection->find()->count();
  }
  else {
    $search_split = explode(",", $_GET['category']);
    $multiple_searches = implode("|", $search_split);
  
    $search = new MongoRegex('/'. $multiple_searches . '/i');
  
    $cursor = $collection->find(array("category" => $search))->skip($page * $page_items)->limit($page_items)->sort(array("name" => 1));
    $count = $collection->find(array("category" => $search))->count();
  }

}
header('Access-Control-Allow-Origin: *.foodcards.org | *.chachaville.com');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");


$json = '{"foods": [' ;

$i = 0;

foreach ($cursor as $obj) {
  if(!empty($obj['name'])) {
    if($i > 0) {
     $json .= ',';
    }

  if (!empty($_GET['colors'])) {
    $obj = array(
      "food_color_text" => $obj["food_color_text"],
      "food_color_background" => $obj["food_color_background"],
/*       "openfood_update" => $obj["openfood_update"], */
      "nid" => $obj["nid"]
    );
  }



    $json .= json_encode($obj);
  
    $i++;
  }
}

$json .= '], "count" : ' . $count . '}';

echo $json;

?>