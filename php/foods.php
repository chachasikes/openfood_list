<?php

// connect
$m = new Mongo();
$collection = $m->chacha->foods;

// add another record, with a different "shape"
$obj = array( "title" => "title", "value" => true );
$collection->insert($obj);

// find everything in the collection
$cursor = $collection->find();

// iterate through the results
foreach ($cursor as $obj) {
  if(!empty($obj["name"])) {
    if(!empty($obj["food_color_background"])) {
      $background_color = '#' . $obj["food_color_background"];
    }  
    else {
      $background_color = '#666666';
    }
    if(!empty($obj["food_color_text"])) {
      $text_color = '#' . $obj["food_color_text"];
    }  
    else {
      $text_color = '#efefef';
    }

    echo '<div class="food" style="background-color:' . $background_color . ';color:' . $text_color . '">' . $obj["name"] . '</div>';
  }
}

?>