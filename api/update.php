<?php

// connect
$m = new Mongo();

// find everything in the collection
/* if(!empty($_POST['nid'])){ */

/*   $update_object = $_POST['update']; */

/*   if(!empty($update_object->nid)) { */

  $record = $m->openfood->foods->find(array('nid' => 745));
  //$record->update(array('nid' => $obj_load->nid), array('$set' => $update_object), true);
  
/*   } */
/* } */

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
header("Content-type: application/json");

$cursor = $record;
$json = '{"foods_updated": [' ;

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