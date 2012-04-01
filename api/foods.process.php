<?php

global $foodcards_svg;
  
function foodcards_svg_setup_sheets() {
  // This will read through the files one by one and add new cards to the current sheet until it is all done.
  
  if(!isset($GLOBALS['foodcards_svg'])) {
    $GLOBALS['foodcards_svg'] = array(
      'card' => 1,
      'sheet' => 1,
      'column' => 1,
      'row' => 1,
      'sheets' => array(),
      'grid' => array(),
    );

    $GLOBALS['foodcards_svg']['grid'] = array(
      'current_x' => 40,
      'current_y' => 50,
      'items_per_sheet' => 30,
      'sheet_card_count' => 1,
      'sheet_x' => 40,
      'sheet_y' => 50,
      'margin_x' => 3,
      'margin_y' => 3,      
      'width' => 175,
      'height' => 67,
      'viewboxA_start' => '25',
      'viewboxB_start' => '33.33594',
      'viewboxA' => '75',
      'viewboxB' => '83.33594',
      'font_size' => '17.24952',
      'font_name' => 'U.S.101',
      'font' => foodcards_svg_font_us101(),
      'columns' => 3,
      'rows' => 10,
    );
    
    $GLOBALS['foodcards_svg']['sheets']['sheet_1'] = '';
    
    $deck_name = arg(1);
    $file_dir = 'foodcardssvg/';

  }
}

function foodcards_svg($food,  $card_text) {    
  // foodcards_svg_setup_sheets();
  global $foodcards_svg;
  
  $card = array();
  
  // Alter layout for sheet.
  foodcards_svg_setup_card($card);

  // Defaults
  $font = foodcards_svg_font_us101();

  if(!empty($food['food_color_text'])) {
    $text_color = $food['food_color_text'];
  }
  else {
    $text_color = "#131313";
  }
  if($text_color == 'FFFFFF' || $text_color == 'EEEFE6') {
    $text_color = "#EEEFE6";
  }
  else {
    $text_color = "#131313";  
  }
  
  $card = array(
    'text_string' => strtoupper($card_text),
    'shape_fill' => $food['food_color_background'],
    'text_fill' =>  $text_color,
  );

  if($card['shape_fill'] == '' ) {
    $card['shape_fill'] = '444444';
  }

  // Process text.
  $text_array = explode(" ", $card['text_string']);

  $chunks = array_chunk($text_array, 2);

  foreach ($chunks as $chunk) {
    if(isset($chunk[1])) {
      $merged = $chunk[0] . " "  . $chunk[1];
      if(strlen($merged) > 18) {

      }
    }
  }

  $card['text'] = '';
  $card['text_multiple'] = '';

  $line_position_single = $foodcards_svg['grid']['height'];
  $line_position_multiple = $foodcards_svg['grid']['height'];

  if(count($chunks) > 1 && count($chunks) < 3) {  
    $line_position_single = $foodcards_svg['grid']['viewboxB_start'];  
    $line_position_multiple = $foodcards_svg['grid']['viewboxB'];  
    foreach ($chunks as $chunk) {
      $line = implode(" ", $chunk);      


      $card['text'] .= '
      <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA_start'] . ' ' . $line_position_single . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $line 
      . '</text>';
      $card['text_multiple'] .= '
      <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA'] . ' ' . $line_position_multiple . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $line 
      . '</text>';
      
      $line_position_single  = $line_position_single + $foodcards_svg['grid']['font_size'];
      $line_position_multiple  = $line_position_multiple + $foodcards_svg['grid']['font_size'];
    }
  }
  else if(count($chunks) >= 3) {  
    $line_position_single = $foodcards_svg['grid']['viewboxB_start'] - $foodcards_svg['grid']['font_size'];  
    $line_position_multiple = $foodcards_svg['grid']['viewboxB'] - $foodcards_svg['grid']['font_size'];
    foreach ($chunks as $chunk) {
      $line = implode(" ", $chunk);
      $card['text'] .= '
      <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA_start'] . ' ' . $line_position_single . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $line 
      . '</text>';
      $card['text_multiple'] .= '
      <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA'] . ' ' . $line_position_multiple . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $line 
      . '</text>';
      $line_position  = $line_position + $foodcards_svg['grid']['font_size'];
    }
  }
  else {
    $line_position_single =  $foodcards_svg['grid']['viewboxB_start'] + ($foodcards_svg['grid']['font_size'] * 0.4);  
    $line_position_multiple =  $foodcards_svg['grid']['viewboxB'] + ($foodcards_svg['grid']['font_size'] * 0.4);  
    $card['text'] .= '
    <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA_start'] . ' ' . $line_position_single . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $card['text_string'] 
    . '</text>';  
    $card['text_multiple'] .= '
    <text transform="matrix(1 0 0 1 ' . $foodcards_svg['grid']['viewboxA'] . ' ' . $line_position_multiple . ')" fill="' . $card['text_fill'] . '" font-family="\'' . $foodcards_svg['grid']['font_name'] . '\'" font-size="' . $foodcards_svg['grid']['font_size'] . '">' . $card['text_string'] 
    . '</text>';  
  }
 
  // Build SVG
  $card['svg'] = '<svg version="1.1"
  	 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
  	 x="0px" y="0px" width="' . $foodcards_svg['grid']['width'] . 'px" height="' . $foodcards_svg['grid']['height'] . 'px" viewBox="0 0 ' . $foodcards_svg['grid']['width'] . ' ' . $foodcards_svg['grid']['height'] . '" enable-background="new 0 0 ' . $foodcards_svg['grid']['width'] . ' ' . $foodcards_svg['grid']['height'] . '"
  	 xml:space="preserve">
  <style type="text/css">
  <![CDATA[' .
  $font .
  '</style>
  <defs>
  </defs>
  <rect x="0" y="0" fill="#'.  $card['shape_fill'] .'" width="' . $foodcards_svg['grid']['width'] . '" height="' . $foodcards_svg['grid']['height'] . '"/>' 
  . $card['text'] . '</svg>';

  // Build Card for multiple SVG.
  $card['multiple_svg'] = '
  <g>
  <rect x="' . $foodcards_svg['grid']['current_x'] . '" y="' . $foodcards_svg['grid']['current_y'] . '" fill="#'.  $card['shape_fill'] .'" width="' . $foodcards_svg['grid']['width'] . '" height="' . $foodcards_svg['grid']['height'] . '"/>' 
  . $card['text_multiple'] .'</g>
  ';


  // Save this card in a set of new sheets.
 foodcards_svg_save_file($card);

  $foodcards_svg['card']++;
  $foodcards_svg['grid']['sheet_card_count']++;
  $foodcards_svg['column']++;

  // Arrange cards on sheets.
  if($foodcards_svg['column'] > $foodcards_svg['grid']['columns']) {
    $foodcards_svg['column'] = 1;
    $foodcards_svg['row']++;
  }

  if($foodcards_svg['grid']['sheet_card_count'] > $foodcards_svg['grid']['items_per_sheet']) {
    $foodcards_svg['sheet']++;
    $foodcards_svg['sheets']['sheet_' . $foodcards_svg['sheet']] = '';

    // Reset the sheet.
    $foodcards_svg['grid']['sheet_card_count'] = 1;
    $foodcards_svg['column'] = 1;
    $foodcards_svg['row'] = 1;  

    $foodcards_svg['grid']['sheet_x'] = 40;
    $foodcards_svg['grid']['sheet_y'] = 50;
    $foodcards_svg['grid']['current_x'] = 40;
    $foodcards_svg['grid']['current_y'] = 50;

    $foodcards_svg['grid']['viewboxA_start'] = '25';
    $foodcards_svg['grid']['viewboxB_start'] = '33.33594';
    $foodcards_svg['grid']['viewboxA'] = '75';
    $foodcards_svg['grid']['viewboxB'] = '83.33594';

  }  
  // Display individual card.
  return $card['svg'];
}


function foodcards_svg_setup_card(&$card) {
  global $foodcards_svg;
  
  $foodcards_svg['grid']['current_x'] = $foodcards_svg['grid']['sheet_x'] + 
    (($foodcards_svg['grid']['width']  + $foodcards_svg['grid']['margin_x']) * ($foodcards_svg['column'] - 1));

  $foodcards_svg['grid']['current_y'] = $foodcards_svg['grid']['sheet_y'] + 
    (($foodcards_svg['grid']['height'] + $foodcards_svg['grid']['margin_y']) * ($foodcards_svg['row'] - 1));

  $foodcards_svg['grid']['viewboxA'] = $foodcards_svg['grid']['viewboxA_start'] + $foodcards_svg['grid']['current_x'];
  $foodcards_svg['grid']['viewboxB'] = $foodcards_svg['grid']['viewboxB_start'] + $foodcards_svg['grid']['current_y'];
}

function foodcards_svg_save_file(&$card) {  
  global $foodcards_svg;

  $svg_prefix = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="612px" height="792px" viewBox="0 0 612 792" enable-background="new 0 0 612 792" xml:space="preserve">';
  $svg_suffix = '</svg>';

  $file_name = 'foodcard_sheet_' . $foodcards_svg['sheet'] . '.svg';
  
  // TODO get the variable from the view from somewhere.
  
  $deck_name = arg(1);
  $file_dir = file_default_scheme() . '://' . '/foodcards_svg/' . $deck_name . '/';

  $file = $file_dir . $file_name;

  $data = $card['multiple_svg'];
  $foodcards_svg['sheets']['sheet_' . $foodcards_svg['sheet']] .= $data;
  
  $registration = foodcards_svg_registration_marks_30();

  file_unmanaged_save_data($svg_prefix . $foodcards_svg['sheets']['sheet_' . $foodcards_svg['sheet']] . $registration . $svg_suffix, $file, FILE_EXISTS_REPLACE);
}


function foodcards_svg_registration_marks_30() {
  $registration = '<g>
	<g>
		<rect x="0" y="47"  fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="117" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="187" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="257" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="327" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="397" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="467" fill="#CCCAB4" width="612" height="3"/>		
		<rect x="0" y="537" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="607" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="677" fill="#CCCAB4" width="612" height="3"/>
		<rect x="0" y="747" fill="#CCCAB4" width="612" height="3"/>
	</g>
</g>
<g>
	<g>
		<rect x="37" y="0" fill="#CCCAB4" width="3" height="791"/>
		<rect x="215" y="0" fill="#CCCAB4" width="3" height="791"/>
		<rect x="393" y="0" fill="#CCCAB4" width="3" height="791"/>
		<rect x="571" y="0" fill="#CCCAB4" width="3" height="791"/>
	</g>
</g>';
  return $registration;  
}

function foodcards_svg_font_us101() {
  $font = '@font-face{font-family:\'U.S.101\';src:url("data:;base64,\
T1RUTwADACAAAQAQQ0ZGIOG3+M8AAATQAABZr0dQT1PD5Jr2AABegAAAEYBjbWFwjVcvnQAAADwA\
AASUAAAAAQAAAAMAAAAMAAQEiAAAAR4BAAAHAB4AJgAnAF8AYAB+AKAAowCkAKUApgCnAKgAqQCq\
AKsArACtAK4ArwCwALEAsgCzALQAtQC2ALcAuAC5ALoAuwC8AL0AvgC/AMAAwgDDAMQAxQDGAMcA\
yADLAMwAzwDQANEA0gDUANUA1gDXANgA2QDcAN0A3gDfAOAA4gDjAOQA5QDmAOcA6ADrAOwA7wDw\
APEA8gD0APUA9gD3APgA+QD8AP0A/gD/AQcBDQERAR8BMAExAUEBQgFSAVMBXwFgAWEBeAF9AX4B\
kgLGAscCyQLZAtoC2wLcAt0DwCATIBQgGCAZIBogHCAdIB4gISAiICYgMCA6IKMgrCEiISYiAiIG\
Ig8iESISIhUiGSIaIh4iKyJIImAiZSXK8ADwAv//AAAAIAAnACgAYABhAKAAoQCkAKUApgCnAKgA\
qQCqAKsArACtAK4ArwCwALEAsgCzALQAtQC2ALcAuAC5ALoAuwC8AL0AvgC/AMAAwQDDAMQAxQDG\
AMcAyADJAMwAzQDQANEA0gDTANUA1gDXANgA2QDaAN0A3gDfAOAA4QDjAOQA5QDmAOcA6ADpAOwA\
7QDwAPEA8gDzAPUA9gD3APgA+QD6AP0A/gD/AQYBDAERAR4BMAExAUEBQgFSAVMBXgFgAWEBeAF9\
AX4BkgLGAscCyQLYAtoC2wLcAt0DwCATIBQgGCAZIBogHCAdIB4gICAiICYgMCA5IKMgrCEiISYi\
AiIGIg8iESISIhUiGSIaIh4iKyJIImAiZCXK8ADwAf///+EAQf/hABz/4QBf/7//w/+///r/v//b\
AAH/4f+//+sAU//3AFL/8f/r//L/9v/J/+P/vQBL/83/3f/V/73/4v/e/+X/vP/u/+r/7f/p/+r/\
xP/q/+3/6f/t/+n/yv/p/+z/6P/q/+f/0f+1/+v/5//o/7//tv/r/+f/6v/m/+f/qv/n/+r/5v/q\
/+b/t//m/+n/5f/n/+T/qP+b/+j/5P/l/6T/5P/0//D/7f/X/8f/YP9L/1D/PP9B/5r/YP98/07/\
Sv9m/tP9uP3B/bf9qf2q/az9o/2p/SzgXOB14Cnf7+Bb4E3gWuBY4FDgUuBT4ErgMuBR4Fffd9/I\
3ufe697c3tnelN5O3lne1d7I3sLeqN6F3oPbKBDzEGwAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAQCAAEBAQhVLlMuMTAxAAEBATr4\
OgH4GAT4OwwV+1P8lBwF7BwG4gUeoABIgoEl/4uLHqAASIKBJf+LiwwH+C4P+DUQ+DcRkRxZqRIA\
IQIAAQAJABEAGgAmADEAOgBBAEMASwBQAFcAYgBnAG4AcwB4AH4AhACOAJYAngCkAKoAsAC2AL0A\
xADNANQA4wDnAPcBBG5vdGVxdWFsaW5maW5pdHlsZXNzZXF1YWxncmVhdGVyZXF1YWxwYXJ0aWFs\
ZGlmZnN1bW1hdGlvbnByb2R1Y3RwaWludGVncmFsT21lZ2FyYWRpY2FsYXBwcm94ZXF1YWxEZWx0\
YWxvemVuZ2VhcHBsZWZyYW5jR2JyZXZlZ2JyZXZlSWRvdGFjY2VudFNjZWRpbGxhc2NlZGlsbGFD\
YWN1dGVjYWN1dGVDY2Fyb25jY2Fyb25kbWFjcm9udW5pMDBBMHNmdGh5cGhlbm1hY3Jvbi5wZXJp\
b2RjZW50ZXJlZC5FdXJvQXNoIFBpa2FjaHUgRm9udC9GU1R5cGUgMCBkZWYAAAEAAeMBhx4AAAEE\
AgABABsAHgA8AF0A0gF3AkoDEAMrA3oDyAP7BBkEMwRCBFEEZQUFBSYFmwZMBncG/weoB8cIdQkd\
CTgJXgl8CZYJsgouCxMLQwvDDFsMrgzODOkNiA2oDboOBg42DksOfA6hD0YPmhBCEJ8RNRFOEZ4R\
uxHxEiQSRhJnEoESmBKwEs4S2RLyEyITohQ6FI0UrRTIFWcVhxWZFeUWFRYqFlsWgBclF3kYIRh+\
GRQZLRl9GZoZ0BoDGiUaRhreGu0bhxvXG/UcjR1OHWQdlh3uHt0fbR9/H64f5SACIB4gYyCrILkg\
2CEHIRYhUSGJIaMh0iICIjgiWyOMJAUkFyQqJEQkgSSPJLYkxCTcJUYlXCV+JbAlyiXZJhQmqSbX\
J4Mn6yhTKI4ooCjOKXop4ip7KpUqpCreKx4rfywPLDkslyztLRMtKy2eLfwuxy8sMEIwUDCxMOUx\
dDLAMwAzSDOOM840ZzTRNX81sDXoNh42TjZwNpg2vzbhN0E39jiyOWw6ITsAO648Dzx4PN89Pj1x\
Pak94z4jPms+sT7xP4o/9ECiQNNBC0FBQXFBk0G7QeJCBEJkQxlD1USPRURGI0bRRzJHm0gCSGFI\
lEjMSQZJQkoWSkBKaEs9S3lLrUvKTDhMwU0HTbpN204DTsZPGk/fUKRQwlFvUhxSxlNwVCFU0lUz\
VTZVRVVRVWBV+vy1qhYcBVX3kBz6qwdsqhUcBRf7Uhz66QcO/LUO/DP3sfgwFfsTBlr6TQX3dQZ+\
HPuFFftu+1r3bgcO+wr3pvnHFfssBmj4tgX3cwb3yfy2FfstBmj4tgX3cwYOuPjm+mQV1PgZBfcb\
BkL8GQX3BQZx+x8F+wUGTvvZBfcPBnH7IAX7DwZG/AgF+xsG0fgIBftCBkX8CAX7GwbR+AgF+wQG\
p/cgBfcCBsj32QX7Cgao9x8F9wcG1PgZBfcaBkP8GQX3J/sfFftBBk772QX3QgYOePgAHAXlFfcB\
+xIG9H7nWdoy+yD7HhjRVkyuRBteaH5wcB9vb31lWhpUnV2vZR6ic8Fi4FDxRdJMtFMIvEakOCsa\
+ylUIfsDTB5mdl19VIQI+zD7AfcxB/sJmSXMMvcI9yL3FxiuYKlspHgIarS6e78buq+brKQfo6qX\
sbgaxXm9ZrQecahQuy7NMctMw2i6CFjPctrmGuSj17rKHsDR07XomQgO94X4ShwEdBX7kQf7M0w7\
+xH7Ekzb9zMe95EH9zPK2/cS9xHKO/szHvuToRX7vQdlj3GTfR53lp2BpRuknZWflh+TmY+lsRr3\
vQexh6WDmR6fgHmVchtxeYF3gB+DfYdxZRr45PdfFfcFBvzIHPqrBfsGBvnQ+HIV+5EH+zNMO/sR\
+xJM2/czHveRB/czytv3EvcRyjv7Mx77k6EV+70HZo9xk3wed5WdgaUbpZ2Vn5Yfk5mPpbEa970H\
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\
WFAaeop2inAe++X7cRVNbmxoYRpumWynbB73WfttBb6kpbC+Gq14sWa1Hg6F+UX4txXiNfsK+wo3\
3wVxXFp+WBtaWZimWh8zM/sG9wjl5AV0uoC4thq8mL2lvR404fcK9wrkMgWhu7iWthu4uYB0uB/s\
7PcH+wctLQWlXJhZVxpdgF52Xx77nve2FWVpfW9uH2hremVgGmSYaaRtHmaqsnm8G7OumqiqH6yq\
nLC2GrZ9r26oHqxrZJxdGw78XPet+ccV+zMGaPi2Bfd5Bg77WfgS+fUV924H9wv3rgXaBjD7rgXm\
+24G/IYW924H9wv3rgXaBjD7rgXm+24GDvt2+Mn3UxX7ZvdCBfe6B/dm90IF+4kHPT/ZPwX7s/uJ\
Fftm90IF97oH92b3QgX7iQc9P9k/BQ78lPeq91MV+2b3QgX3ugf3ZvdCBfuJBz0/2T8FDvyUz/nV\
Ffdm+0IF+7oH+2b7QgX3iQfZ1z3XBQ6B9yoW+eL7Hvc/9x7sB9qTwZuqHs6u1q33Bxu80oaC5h/7\
RgeXPlKRZhtlcIB2fB9/eoVpWhpT+Df+jftT+eL7eP3iBw6B+Hr6jRX7P/sl/eL7U/ni+x73P/ce\
7Afak8Gbqh7Orc+t8hv3Un0F91Mc+qv7UxwEpgaVVlyQZBtrc4B2fB9/eoVpWhpTBw54+Y/48BX7\
P/1C9z8HDnj4mBwFVRX75feV+z/7lf3t+1P57fuW9z/3lvflBw54+JgcBVUV++X3lfs/+5X78feV\
+z/7lfvl+1P35fuW9z/3lvfx+5b3P/eW9+UHDvyM96n5CBX7bvta924HDoj4F/thFfnvB1OOVZ1Y\
rmCoabNxvghwwn3FyhreodS3zB7nyum59w8b9/VOORz6GykcBeX7Bhz6GwYO+I75RfpmFdbNb1TC\
H8NUp0g8GjxvSFRUHlRUSG88Gz5IpsJUH1LDb87aGtynzsPBHsLEz6baGw78hfes924V+24H+wv7\
rgU8Bub3rgUw924GDvtZ96z3bhX7bgf7C/uuBTwG5veuBTD3bgb4hhb7bgf7DPuuBT0G5veuBTD3\
bgYO+1n3rBwFVRX7bgf7C/uvBTwG5vevBTD3bgb4hhb7bgf7DPuvBT0G5vevBTD3bgYO+3bP+dUV\
92b7QgX7ugf7ZvtCBfeJB9nXPdcF97P3iRX3ZvtCBfu6B/tm+0IF94kH2dc91wUO92L3qfduFftu\
+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\
qrafppirkK8IkLCOt7wa+B8Hx4i6hKwehKx/p3qjbLZYoEKMCCT7+/cx+1f7MQYOePjv+noV9wf7\
B/tM+0z3SftI+wn7CvtJ90n7TPtM+wf3B/dM90z7RfdF9wn3CvdF+0YFDvu494P6JhX3EQe7jKqT\
mZoIm5yTprAa1HKvWFhxblCHi4WMgh77IAaKlYuSkBrKob+2sh6wsr+dzBvIu3ppsB+2Y6BPOxo2\
bVNPcR7Rda5MIxo4d0xiYB5eYFN1RBtLVp6xYR9etHXG2pCLkpIa9yUGX5BslHoecZqifqobw6e2\
4b6ArnWdH3qZbJNfjAgO+XP5vBwFbhX3EvcLaEX3BR/3BUbiLcf7Cgi+JqX7APsFGi55MWc0Hmc0\
WD5JSUlJP1g1ZwhnNDF5LhsuMZ2vNR80rj++SM1JzVjYZ+IIZuJ55ega9xqu9xDQ9wYez/cE6eL3\
DcgIvvD3AqX3Cxt6+wYVQUN8bUQfRGxMYVVUVFRhS21CCG1BfD89Gvs5xPsg9wf7CB77B/cG9x1R\
9zUb9zX3HcX3CPcFH/cG9wjE9x/3NxrbfNht1R5t1WHLVcAI9wf7Cfsfxfs2G/d5/F0V+x8Gvoaw\
gqAerH1ym2gbbHR/cn4ff3aFZVYa+6AHVI9jlHQea5ike7Abxqm+8R+Ri5SWGvceBox5i36CGj57\
TWpcHlRlUG86GzRMqslmH3erf66IsgiIrImxthr3dQfZkMGVqR7qqtC79wEb0sF1YLIftF2gSziD\
i4F/Gg7L+PT3vRX7twZU+70F+2MG95ocBVUF97YG96gc+qsF+20GLfiAFfsE+Pf7Afz3BZn6ahXo\
90oF91sG+yj7SgUOy/j0970V+7cGVPu9BftjBveaHAVVBfe2BveoHPqrBfttBi34gBX7BPj3+wH8\
9wWQHASMFfdqBvcU+0oF+z8GS9BLRgX7QAYOy/j0970V+7cGVPu9BftjBveaHAVVBfe2BveoHPqr\
BfttBi34gBX7BPj3+wH89wW9HASMFftK+0X3Sgf4gBb7SvtF90oHDsv49Pe9Ffu3BlT7vQX7Ywb3\
mhwFVQX3tgb3qBz6qwX7bQYt+IAV+wT49/sB/PcF91n6ahX7JAb7KPdKBfdaBg7L+PT3vRX7twZU\
+70F+2MG95ocBVUF97YG96gc+qsF+20GLfiAFfsE+Pf7Afz3BfcGHATZFbKufW+pH6hwmmpjGmN8\
aW5vHm9uZ31iG2JnmaduH26nfK2zGrSarKilHqeqr5m0G4hEFXh5hH18H3x9hHp3GneSepp9Hn2a\
nYSfG6CckpmaH5qZkpyfGqCEnHyYHpl8eZJ2Gw7L+PT3vRX7twZU+70F+2MG95ocBVUF97YG96gc\
+qsF+20GLfiAFfsE+Pf7Afz3BS76dBX3IgegsLKVtRuoqYaAqx98t62DoxulrJaisx/7Igd0Y2qA\
cRtzaZOaXx+Wa22QbhthZIF2Zh8OvfnS+i0V+2cGmIuUkRr3M1baIFFhdV5yHnxvgW2IawiIbIpo\
Yxr8Wgcsk0eaYh5Qorhu0Bv3AcHk90cfk4uWmhr3ZwaMdIt6gBr7GHAiVj4eMUwpXvsaG/siJb7w\
TR9qwHjFhcsIhsCIy9Ya+BEH9xeT5Zy+HqjmwcvYsQimwMmY0Bv3COVnRMwfzkCtI/saGoCLfYp4\
Hvux/owV+x8HXvsGBSoGr/cGBVH3HwYOLPlnHAVVFftX/Cv7+/fA+1f7wPw5+Cv7V/z/HAVVB/dJ\
9wEV6PdKBfdbBvso+0oFDiz5ZxwFVRX7V/wr+/v3wPtX+8D8Ofgr+1f8/xwFVQf3XPe3FfdqBvcU\
+0oF+z8GS9BLRgX7QAYOLPlnHAVVFftX/Cv7+/fA+1f7wPw5+Cv7V/z/HAVVB/eP97cV+0r7RfdK\
B/iAFvtK+0X3SgcOLPlnHAVVFftX/Cv7+/fA+1f7wPw5+Cv7V/z/HAVVB/gA9wEV+yQG+yj3SgX3\
WgYO/Ez30BwFVRUc+qv7aBwFVQeU9wEV6PdKBfdbBvso+0oFDvxM99AcBVUVHPqr+2gcBVUH97cE\
92oG9xT7SgX7PwZL0EtGBftABg78TPfQHAVVFRz6q/toHAVVB773txX7SvtF90oH+GQW+0r7RfdK\
Bw78TPfQHAVVFRz6q/toHAVVB/dT9wEV+yQG+yj3SgX3WgYO9fnxHAVVFRz6q/tSB/wC+iAF/iD7\
XRwFVfdoB/fs/eUF+eUH/BH3CxX3IgegsLKVtRuoqYaAqx98t62DoxulrJaisx/7Igd0Y2qAcRtz\
aZOaXx+Wa22QbhthZIF2Zh8OzvnX+jcV/IUHPoVMf1kef1l3X29kCDRMKV/7GRv7GSi34kwfarp1\
v4HECIK+h8PHGviFB9iRype9Hpe9n7ensgjiy+239xkb9xntXzTKH61coVeUUgiUWI9TTxr8qYcV\
/H0HSo5ekHMeN56+Yd4byrikvKQfmaaTqI2rCI2mjKuyGvh9B82IuIajHt54WbU4G0teclpyH31w\
g26JbAiJcIpqZBrL+LcV6PdKBfdbBvso+0oFDs751/o3FfyFBz6FTH9ZHn9Zd19vZAg0TClf+xkb\
+xkot+JMH2q6db+BxAiCvofDxxr4hQfYkcqXvR6XvZ+3p7II4svtt/cZG/cZ7V80yh+tXKFXlFII\
lFiPU08a/KmHFfx9B0qOXpBzHjeevmHeG8q4pLykH5mmk6iNqwiNpoyrshr4fQfNiLiGox7eeFm1\
OBtLXnJach99cINuiWwIiXCKamQawvltFfdqBvcU+0oF+z8GS9BLRgX7QAYOzvnX+jcV/IUHPoVM\
f1kef1l3X29kCDRMKV/7GRv7GSi34kwfarp1v4HECIK+h8PHGviFB9iRype9Hpe9n7ensgjiy+23\
9xkb9xntXzTKH61coVeUUgiUWI9TTxr8qYcV/H0HSo5ekHMeN56+Yd4byrikvKQfmaaTqI2rCI2m\
jKuyGvh9B82IuIajHt54WbU4G0teclpyH31wg26JbAiJcIpqZBro+W0V+0r7RfdKB/iAFvtK+0X3\
SgcOzvnX+jcV/IUHPoVMf1kef1l3X29kCDRMKV/7GRv7GSi34kwfarp1v4HECIK+h8PHGviFB9iR\
ype9Hpe9n7ensgjiy+239xkb9xntXzTKH61coVeUUgiUWI9TTxr8qYcV/H0HSo5ekHMeN56+Yd4b\
yrikvKQfmaaTqI2rCI2mjKuyGvh9B82IuIajHt54WbU4G0teclpyH31wg26JbAiJcIpqZBr3ivi3\
FfskBvso90oF91oGDs751/o3FfyFBz6FTH9ZHn9Zd19vZAg0TClf+xkb+xkot+JMH2q6db+BxAiC\
vofDxxr4hQfYkcqXvR6XvZ+3p7II4svtt/cZG/cZ7V80yh+tXKFXlFIIlFiPU08a/KmHFfx9B0qO\
XpBzHjeevmHeG8q4pLykH5mmk6iNqwiNpoyrshr4fQfNiLiGox7eeFm1OBtLXnJach99cINuiWwI\
iXCKamQaYPjBFfciB6CwspW1G6iphoCrH3y3rYOjG6WslqKzH/siB3RjaoBxG3Npk5pfH5ZrbZBu\
G2FkgXZmHw6Z+cn6qBX7Xk8F9xtqT88yG2VrfnJxH3BwfWpiGlmeXbFiHqRwx1nrQu0/z06xXQjG\
RKg3KxomaTlISh5JRjBq+wUb+337H/cU95RgH/dexgX7N6fSOfcGG7uxmqinH6eombC5Grt5t2iz\
Hm6tWbhEwiTbTr12nwgs5lzv9wAa9rHg2Moewczbpusb91n3E/sG+3fGH/uo+EIV+2oG+xX3SgX3\
QAbLRsvQBfc/Bg7S+PocBVUV92j+MQb7AoE5eFYe+yNX+wND+z0b+xIttN1NH2e6dcCCxgiDwIfG\
zRr6Mfdo/lMHPpVTn2keYqOzd8IbwrOftKMfn6yVw9ka+4r6wBXo90oF91sG+yj7SgUO0vj6HAVV\
Ffdo/jEG+wKBOXhWHvsjV/sDQ/s9G/sSLbTdTR9nunXAgsYIg8CHxs0a+jH3aP5TBz6VU59pHmKj\
s3fCG8Kzn7SjH5+slcPZGvuTHATiFfdqBvcU+0oF+z8GS9BLRgX7QAYO0vj6HAVVFfdo/jEG+wKB\
OXhWHvsjV/sDQ/s9G/sSLbTdTR9nunXAgsYIg8CHxs0a+jH3aP5TBz6VU59pHmKjs3fCG8Kzn7Sj\
H5+slcPZGvttHATiFftK+0X3Sgf4gBb7SvtF90oHDtL4+hwFVRX3aP4xBvsCgTl4Vh77I1f7A0P7\
PRv7Ei203U0fZ7p1wILGCIPAh8bNGvox92j+Uwc+lVOfaR5io7N3whvCs5+0ox+frJXD2RpM+sAV\
+yQG+yj3SgX3WgYOtvi8+KkV/Kn7aPipB/vG+dQF930G90r81fdR+NUF93kG/JX3ARXo90oF91sG\
+yj7SgUOtvi8+KkV/Kn7aPipB/vG+dQF930G90r81fdR+NUF93kG/Hj3txX7SvtF90oH+IAW+0r7\
RfdKBw5N+Yj3VxX7V/1f91sH+HL6XwX8RfdX+Sz7Vwb8cP5jBfd9HAT/FftqBvsV90oF90AGy0bL\
0AX3PwYOy/j0970V+7cGVPu9BftjBveaHAVVBfe2BveoHPqrBfttBi34gBX7BPj3+wH89wWZ+moV\
6PdKBfdbBvso+0oFDsv49Pe9Ffu3BlT7vQX7Ywb3mhwFVQX3tgb3qBz6qwX7bQYt+IAV+wT49/sB\
/PcFkBwEjBX3agb3FPtKBfs/BkvQS0YF+0AGDs749Pe9Ffu3BlT7vQX7Ywb3mhwFVQX3tgb3qBz6\
qwX7bQYt+IAV+wT49/sB/PcFtxwEjBX7SvtF90oH+IAW+0r7RfdKBw7L+PT3vRX7twZU+70F+2MG\
95ocBVUF97YG96gc+qsF+20GLfiAFfsE+Pf7Afz3BfdY+moV+yQG+yj3SgX3WgYOzvj0970V+7cG\
VPu9BftjBveaHAVVBfe2BveoHPqrBfttBi34gBX7BPj3+wH89wX3BxwE2RWyrn1vqR+ocJpqYxpi\
fGlucB5vbmd9YhtiZ5mnbh9up3ytsxq0mqyopR6nqq+ZtBuIRBV4eYR9fB98fYR6dxp3knqafR59\
mp2EnxugnJKZmh+amZKcnxqghJx8mB6ZfHmSdhsOy/j0970V+7cGVPu9BftjBveaHAVVBfe2Bveo\
HPqrBfttBi34gBX7BPj3+wH89wUt+nQV9yIHn7CzlbQbqKmGgKsffLitg6IbpayXorMf+yIHdGJq\
f3IbdGmTml4flmttkG4bYmOBd2YfDr350votFftnBpiLlJEa9zNW2iBRYXVech58b4FtiGsIiGyK\
aGMa/FoHLJNHmmIeUKK4btAb9wHB5PdHH5OLlpoa92cGjHSLeoAa+xhwIlY+HjFMKV77Ghv7IiW+\
8E0fasB4xYXLCIbAiMvWGvgRB/cXk+Wcvh6o5sHL2LEIpsDJmNAb9wjlZ0TMH85ArSP7GhqAi32K\
eB77sf6MFfsfB177BgUqBq/3BgVR9x8GDiz5ZxwFVRX7V/wr+/v3wPtX+8D8Ofgr+1f8/xwFVQf3\
KvcBFej3SgX3Wwb7KPtKBQ4s+WccBVUV+1f8K/v798D7V/vA/Dn4K/tX/P8cBVUH91z3txX3agb3\
FPtKBfs/BkvQS0YF+0AGDiz5ZxwFVRX7V/wr+/v3wPtX+8D8Ofgr+1f8/xwFVQf3j/e3FftK+0X3\
Sgf4gBb7SvtF90oHDiz5ZxwFVRX7V/wr+/v3wPtX+8D8Ofgr+1f8/xwFVQf33/cBFfskBvso90oF\
91oGDvxM99AcBVUVHPqr+2gcBVUHlPcBFej3SgX3Wwb7KPtKBQ78TPfQHAVVFRz6q/toHAVVB/e3\
BPdqBvcU+0oF+z8GS9BLRgX7QAYO/Ez30BwFVRUc+qv7aBwFVQe+97cV+0r7RfdKB/hkFvtK+0X3\
SgcO/Ez30BwFVRUc+qv7aBwFVQf3U/cBFfskBvso90oF91oGDvX58RwFVRUc+qv7Ugf8AvogBf4g\
+10cBVX3aAf37P3lBfnlB/wS9wsV9yIHn7CzlbQbqKmGgKsffLitg6IbpayXorMf+yIHdGJqf3Ib\
dGmTml4flmttkG4bYmOBd2YfDs751/o3FfyFBz6FTH9ZHn9Zd19vZAg0TClf+xkb+xkot+JMH2q6\
db+BxAiCvofDxxr4hQfYkcqXvR6XvZ+3p7II4svtt/cZG/cZ7V80yh+tXKFXlFIIlFiPU08a/KmH\
Ffx9B0qOXpBzHjeevmHeG8q4pLykH5mmk6iNqwiNpoyrshr4fQfNiLiGox7eeFm1OBtLXnJach99\
cINuiWwIiXCKamQay/i3Fej3SgX3Wwb7KPtKBQ7O+df6NxX8hQc+hUx/WR5/WXdfb2QINEwpX/sZ\
G/sZKLfiTB9qunW/gcQIgr6Hw8ca+IUH2JHKl70el72ft6eyCOLL7bf3GRv3Ge1fNMofrVyhV5RS\
CJRYj1NPGvyphxX8fQdKjl6Qcx43nr5h3hvKuKS8pB+ZppOojasIjaaMq7Ia+H0HzYi4hqMe3nhZ\
tTgbS15yWnIffXCDbolsCIlwimpkGsL5bRX3agb3FPtKBfs/BkvQS0YF+0AGDs751/o3FfyFBz6F\
TH9ZHn9Zd19vZAg0TClf+xkb+xkot+JMH2q6db+BxAiCvofDxxr4hQfYkcqXvR6XvZ+3p7II4svt\
t/cZG/cZ7V80yh+tXKFXlFIIlFiPU08a/KmHFfx9B0qOXpBzHjeevmHeG8q4pLykH5mmk6iNqwiN\
poyrshr4fQfNiLiGox7eeFm1OBtLXnJach99cINuiWwIiXCKamQa6PltFftK+0X3Sgf4gBb7SvtF\
90oHDs751/o3FfyFBz6FTH9ZHn9Zd19vZAg0TClf+xkb+xkot+JMH2q6db+BxAiCvofDxxr4hQfY\
kcqXvR6XvZ+3p7II4svtt/cZG/cZ7V80yh+tXKFXlFIIlFiPU08a/KmHFfx9B0qOXpBzHjeevmHe\
G8q4pLykH5mmk6iNqwiNpoyrshr4fQfNiLiGox7eeFm1OBtLXnJach99cINuiWwIiXCKamQa94n4\
txX7JAb7KPdKBfdaBg7O+df6NxX8hQc+hUx/WR5/WXdfb2QINEwpX/sZG/sZKLfiTB9qunW/gcQI\
gr6Hw8ca+IUH2JHKl70el72ft6eyCOLL7bf3GRv3Ge1fNMofrVyhV5RSCJRYj1NPGvyphxX8fQdK\
jl6Qcx43nr5h3hvKuKS8pB+ZppOojasIjaaMq7Ia+H0HzYi4hqMe3nhZtTgbS15yWnIffXCDbols\
CIlwimpkGmD4wRX3IgefsLOVtBuoqYaAqx98uK2DohulrJeisx/7Igd0Ymp/cht0aZOaXh+Wa22Q\
bhtiY4F3Zh8OmfnJ+qgV+15PBfcbak/PMhtla35ycR9wcH1qYhpZnl2xYh6kcMdZ60LtP89OsV0I\
xkSoNysaJmk5SEoeSUYwavsFG/t9+x/3FPeUYB/3XsYF+zen0jn3Bhu7sZqopx+nqJmwuRq7ebdo\
sx5urVm4RMIk2069dp8ILOZc7/cAGvax4NjKHsHM26brG/dZ9xP7Bvt3xh/7qPhCFftqBvsV90oF\
90AGy0bL0AX3PwYO0vj6HAVVFfdo/jEG+wKBOXhWHvsjV/sDQ/s9G/sSLbTdTR9nunXAgsYIg8CH\
xs0a+jH3aP5TBz6VU59pHmKjs3fCG8Kzn7SjH5+slcPZGvuL+sAV6PdKBfdbBvso+0oFDtL4+hwF\
VRX3aP4xBvsCgTl4Vh77I1f7A0P7PRv7Ei203U0fZ7p1wILGCIPAh8bNGvox92j+Uwc+lVOfaR5i\
o7N3whvCs5+0ox+frJXD2Rr7kxwE4hX3agb3FPtKBfs/BkvQS0YF+0AGDtL4+hwFVRX3aP4xBvsC\
gTl4Vh77I1f7A0P7PRv7Ei203U0fZ7p1wILGCIPAh8bNGvox92j+Uwc+lVOfaR5io7N3whvCs5+0\
ox+frJXD2Rr7bRwE4hX7SvtF90oH+IAW+0r7RfdKBw7S+PocBVUV92j+MQb7AoE5eFYe+yNX+wND\
+z0b+xIttN1NH2e6dcCCxgiDwIfGzRr6Mfdo/lMHPpVTn2keYqOzd8IbwrOftKMfn6yVw9kaS/rA\
FfskBvso90oF91oGDrb4vPipFfyp+2j4qQf7xvnUBfd9BvdK/NX3UfjVBfd5BvyV9wEV6PdKBfdb\
Bvso+0oFDrb4vPipFfyp+2j4qQf7xvnUBfd9BvdK/NX3UfjVBfd5Bvx497cV+0r7RfdKB/iAFvtK\
+0X3SgcOTfmI91cV+1f9X/dbB/hy+l8F/EX3V/ks+1cG/HD+YwX3fRwE/xX7agb7FfdKBfdABstG\
y9AF9z8GDnj3afhQFfsc9zb3aAbe90gF+7v3NvgHBt/3SPcnRlf7AwX3HPs2+2gGOPtIBfe7+zb8\
BwY3+0j7J9AFDviO+UL47RX7JSb7BUP7ERsiOrDUUh9fw3XP2xr3Cbji5sQerMDEnMob9xH3A0P7\
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\
vfjHHAXCFftqBvsV90oF90AGy0bL0AX3Pwb3H/1zFftnBpiLlJEa9zNW2iBRYXVech58b4FtiGsI\
iGyKaGMa/FoHLJNHmmIeUKK4btAb9wHB5PdHH5OLlpoa92cGjHSLeoAa+xhwIlY+HjFMKV77Ghv7\
IiW+8E0fasB4xYXLCIbAiMvWGvgRB/cXk+Wcvh6o5sHL2LEIpsDJmNAb9wjlZ0TMH85ArSP7GhqA\
i32KeB4O0fcDFvj8W/dXu/i+9+IH9533F/si+7GJH/yTB/uxjfsX+yL7nRv7DvdXFfIG1Iy+oKq2\
n6aYq5CvCJCwjre8GvgfB8eIuoSsHoSsf6d6o2y2WKBCjAgk+/v3MftX+zEGDvy1DvzJ97r48BX7\
P/u69z8HDhwG4gT6lPsT/pQGDvyM96n5CBX7bvta924HDr350RwEwRX7RPsFbr9dp02OGVFhdV5y\
H3xvgW2IawiIbIpoYxqMTAX4PAZT+wMF/AX7AfflBlb7AQX7sE0GLJNHmmIeUKK4btAbwIq+prvB\
9z/7EBgkOvsAWPsaG/siJb7wTR9qwHjFhcsIhsCIy9Yaujrq3PcBOvXcowf3F5PlnL4eqObBy9ix\
CKbAyZjQG/cLifRT5PsCCA76lBT57xUAAAEAAAAKAB4ALAABREZMVAAIAAQAAAAA//8AAQAAAAFr\
ZXJuAAgAAAABAAAAAQAEAAIAAAABAAgAARC6AAQAAABcAMIBAAEOATQBQgFQAYIBkAGeAawBugHI\
AfYCBAISApgC3gMIAz4DtAPmA/QEQgSwBP4FKAV+BfQGHgbUBvoHjAgeCEAI0gjcCQIJGAkmCTAJ\
RgmUCb4JyAnSChgKIgo0Ck4KaApyCnwLOgtEC4ILiAvyDFgMcgzYDP4NJA0+DWQNig2QDZ4OEA4+\
DrAO3g9UD4IPiA+eD6wPxg/UD+IQABAGECgQLhA8EEoQWBBmEHQQghCQEJYQrAAPAA3/vAAP/7oA\
Iv+1AEX/6ABQ/+UAU//zAFT/8ABVABUAVwATAFgAEABaABUAiv91AKv/AgCt/0wAr/8cAAMACP/Y\
ABL/iQB3/9gACQAiABsANf/dADf//AA4AAMAOv/EAIoACwCr/2kArf+yAK//gwADAAj/1gAS/4gA\
d//WAAMAEv/eABUAJAAY//wADAAN/7UAD/+1ABH/sgAS/3wAE/+uABT/rAAV/8EAFv+nABf/rwAY\
/6gAGf+qABr/qgADABL/3gAV/9YAGP//AAMAEv/UABUAIwAY//UAAwAS/6cAFQA4ABj/0wADABL/\
uwAVACIAGP/xAAMAEv/XABUAJQAY//wACwAN/4wAD/+LABL/zAAT/+4AFP/tABX/xQAW/+8AF//r\
ABj//QAZ/+sAG//jAAMAEv/ZABUAJQAY//sAAwAS/9oAFQAhABj/+AAhAAj/xwANABwADgAgAA8A\
HAAk//gAKP/2ADD/+AAy//gANf/JADb/8wA3/7gAOP++ADr/qgBCAAcAQwASAET//wBG//8ASAAJ\
AFX/4gBWAAQAV//WAFj/2wBa/9UAav//AGv//wB3/8cAsf/4AL3/oADB/1gAwv+dAMP/ogDE/48A\
zv//ABEAIv/3ADAAAwA3//MAOP/yADr/4gCK//gAjQADAI4AAgCr/0MArP+IAK3/jQCv/10AsP95\
ALv/YgC8/6cAvf+sAL7/mAAKACL/+gApAAEALAABADAACACK//0Aq/9GAK3/kACv/2AAu/9nAL3/\
sQANACL/8AArAAwANQATADf/+QA4//gAOf/jADr/5wCr/z0ArP+CAK3/hwCu/3MAr/9XALD/cgAd\
AA3/cgAOABUAD/9xACL/xAAr/+IAMAAUAEL/4wBG/+sASgAcAEsADgBQ/+sAU//nAFb/5wCQ/+MA\
k//rAJT/6wCr/xAArP9VAK3/WwCu/0cAr/8qALD/RgC9/70AyP85AMr/gwDM/1MAz/9EANj/RADa\
/48ADAAiABQANQAXADf/+gA4//oAOv/sAIoALACr/2EArP+mAK3/qwCu/5gAr/97ALD/lwADACL/\
9gCt/40Ar/9cABMADgAHACT/8AAo/+4AMP/xADUAMwBCAAYARv/7AFD//QBWAAYAWv/MAI7/6gCQ\
AAYAu/9PAL3/mADK/6gAzP94ANj/WADa/6EA4P+vABsACP9eAA7/pgAiAC8AJAALACgACgAwAAwA\
NAAcADX/wQA2AAcAN/+9ADj/ygA6/6EAVgAaAFr/2AB3/14AigBHAKv/fQCt/8cAr/+XALEACwC7\
/2sAvP+wAL3/tQC+/6IAv/+hAMP/twDg/8IAEwANAAoADwAJACIACQBC//sAVgADAIoAIACQ//wA\
q/9WAK3/oACv/3AAu/9fAL3/qQDI/1IAyv+cAMz/bADP/1oA2P9bANr/pQDg/6wACgAi//IANQAW\
ADf/+wA4//oAOf/oADr/6wCK//YAq/8/AK3/iQCv/1kAFQAN/1AADgAXAA//SAAi/9YAK//aAEIA\
AgBGAA0AUAANAIr/pQCQAAIAkwANAJQADQCr/yIArf9sAK//PADI/1kAyv+kAMz/cwDP/2gA2P9o\
ANr/sgAdAA4AAQAkAAcAKAAHADAABwA1AB4ANgABADcAAwA4AAIAOv/yAEL/+QBG//sAUP/7AFYA\
BABaACQAjgAGAJD/+QCU//sAsQAHALv/ZgC9/7EAw/+xAMj/UADK/5oAzP9qAM//VQDY/1UA2v+f\
AN7/YgDg/6wACgAiAA4ANQAlADcADQA4AAwAOv/5AFUAJACKABIAq/9bAK3/pQCv/3UALQAN/7MA\
Dv/dAA//sgAb/6gAHP+oACL/yQAkABgAKAAYACv/6AAwABkANAAjADcAQAA4AD4AOgBAAEL/pgBE\
/6UARv+mAEj/pQBKACEASwAUAFD/pgBT/68AVP+1AFb/rQBX/8AAWP+9AFr/wABq/60Aa/+tAIr/\
zACNABkAjgATAJD/pgCT/6cAq/8WAKz/WwCt/2AArv9MAK//MACw/0sAu/94ALz/vQC9/8IAvv+u\
AL//rQAJAA3/9QAP//QAIv/tAIr/8gCr/zkArP9+AK3/gwCv/1MAsP9vACQADf++AA4AAwAP/70A\
G//+ABz//gAi/7sAJP/+ACj//gAw//4ANAAGADUAQABC/9gARv/jAEj/5ABKABYAUP/jAFP/9QBW\
//UAWgAXAGr/4wBr/+MAiv/CAI3//gCQ/9kAk//jAKv/BwCs/0wArf9RAK7/PgCv/yEAsP89ALv/\
XAC8/6EAvf+mAL7/kgC//5EAJAAN/8gADgAJAA//xwAb//8AHP//ACL/vwAk//0AKP/9ADD//QA0\
AAQANQA8AEL/3ABG/+UASP/mAEoAEgBQ/+UAU//1AFb/9QBaABgAav/mAGv/5gCK/84Ajf/9AJD/\
3ACT/+UAq/8LAKz/UACt/1UArv9CAK//JQCw/0EAu/9bALz/oAC9/6UAvv+SAL//kQAIAA7/9wAk\
/+UAMP/mADL/5gBG//IAUP/1AFr/4gC9/44AJAAN/48ADv+/AA//jgAb/90AHP/dACL/rAAk/+oA\
KP/qADD/6wA0//UANQBAAEL/qABG/7UASP+zAEoAFgBQ/7UAUf/UAFb/1ABX//UAav+iAGv/ogCK\
/6cAjf/rAJD/qACT/7UAq/74AKz/PQCt/0IArv8vAK//EgCw/y4Au/9JALz/jgC9/5MAvv9/AL//\
fgACAFcADQBaABEACQAi/8QANQAqADcAHwA4ABwAOgAfAIr/kgCr/xEArf9bAK//KwAFAAj/8wBL\
/+0AV//+AFj//ABa//wAAwBXAAMAWAACAFoAAQACAEkABwBMAAcABQAI//gAVQATAFj//wBZ//AA\
Wv/+ABMACAAyAEIAFQBGACAARwBKAEoAKgBLABwATQAqAFAAIABUADAAVQBFAJAAFQCTACAAlAAg\
AMj/bADK/7YAzP+GAM//fADY/3wA2v/GAAoAQgAPAEYAGwBNAB4AUwAeAJAAEADK/7EAzP+BAM//\
dgDY/3YA2v/AAAIACAABAFoABQACADUAIQBL//sAEQANACEADgAKAA8AIQBCAAUARv/7AEgACQBQ\
//wAVAAJAFYAEACQAAUAyP9dAMr/pwDM/3cAz/9VANj/VgDa/6AA4P+4AAIAVwAZAFoAGQAEAFEA\
BQBXAAgAWAAGAFoABQAGAAgAAQA1/60AUQAHAFcACQBYAAYAWgAFAAYACP/7ADX/pQBVABUAVwAC\
AFgAAQBZ//IAAgBVABcAWgABAAIARAACAFYABgAvAAgALAAN/8MADv/qAA//wgAbAC0AHAAtAEIA\
GQBEACMARQAkAEYAJABHAEsASAAkAEkAJABKACQASwAWAEwAJABNACQATgAkAE8AJABQACQAUQAk\
AFIAJABTACQAVAAyAFUARgBWACQAVwBGAFgAQgBZADsAWgBEAFsAKACQABkAkwAkAJQAJADI/3AA\
yf+1AMr/ugDL/6cAzP+KAM4AIwDP/38A0P/EANL/tQDY/38A2f/EANr/yQDb/7UAAgAIAAgAVQAh\
AA8ACAAfABsAKQAcACkANAAfAEIAIwBGABkASQAgAFAAGgCQACQAyP97AMr/xADM/5UAz/90ANj/\
dQDa/78AAQAIAAcAGgAN/9MADgAfAA//0wAbACIAHAAiAEL/+gBEAAMARgAEAEgABABNABkAUAAE\
AFQAEwCQ//oAkwAEAMj/UADJ/5UAyv+aAMv/hwDM/2oAzf+GAM//XwDQ/6QA0v+VANj/XwDa/6kA\
2/+VABkADf/UAA4AHgAP/9QAGwAfABwAHwBC//YARgABAEgAAQBNABUAUAABAFQAEACQ//cAkwAB\
AMj/TQDJ/5IAyv+XAMv/gwDM/2cAzf+CAM//XADQ/6EA0v+TANj/XADa/6YA2/+TAAYAQv/9AET/\
7gBG/+4AUP/xAFL/9QDP/0gAGQAN/84ADgAcAA//zgAbAB8AHAAfAEL/9wBGAAEASAACAE0AGQBQ\
AAEAVAAQAJD/+ACTAAEAyP9OAMn/kwDK/5gAy/+EAMz/aADN/4MAz/9cAND/oQDS/5MA2P9cANr/\
pgDb/5MACQAi/8MANQApADcAHgA4ABsAOgAeAIr/kQCr/xAArf9aAK//KgAJACL/+QA1/60AN//c\
ADj/4gA6/6YAiv/xAKv/RgCt/5AAr/9gAAYAIgAUADX/sQA3/7EAOP+9ADr/kgCKACwACQAi/7QA\
NQApADcAHgA4ABsAOgAeAIr/dACr/wAArf9KAK//GwAJACL/+gA1/60AN//cADj/4gA6/6YAiv/x\
AKv/RwCt/5AAr/9hAAEAIv/yAAMAV///AFj//QBa//0AHAAI/y4ADf+DAA7/hwAP/4MAJP9fACj/\
XQAw/18AMv9fADX/MAA2/1oAN/8eADj/JAA6/xAAQv9uAEP/egBE/2YARf9oAEb/ZgBI/3EAUP9n\
AFL/aABV/0gAVv9rAFf/PQBY/0EAWv88AGr/ZgBr/2YACwAN/4MAD/+DACT/XwAo/10AMP9fADL/\
XwA1/zAANv9aADf/HgA4/yQAOv8QABwACP8qAA3/fwAO/4MAD/9/ACT/WwAo/1kAMP9bADL/WwA1\
/ywANv9WADf/GwA4/yAAOv8NAEL/awBD/3YARP9iAEX/ZABI/20AUP9jAFL/ZABV/0UAVv9oAFf/\
OQBY/z4AWv84AGr/YgBr/2IAd/8qAAsADf9bAA//WwAk/zcAKP81ADD/NwAy/zcANf8IADb/MgA3\
/vYAOP78ADr+6AAdAAj/JgAN/3wADv+AAA//fAAk/1cAKP9VADD/VwAy/1cANf8oADb/UgA3/xcA\
OP8dADr/CQBC/2cAQ/9yAET/XgBF/2AARv9eAEj/aQBQ/18AUv9gAFX/QQBW/2QAV/81AFj/OgBa\
/zUAav9eAGv/XgB3/yYACwAN/4MAD/+DACT/XwAo/10AMP9fADL/XwA1/zAANv9aADf/HgA4/yQA\
Ov8QAAEAIv/6AAUAIv9ZADX/fgA3/2IAOP9hADr/UgADADX/fgA3/2IAOv9SAAYAIv9ZADX/fgA3\
/2IAOP9hADn/TwA6/1IAAwA1/28AN/9TADr/QwADADX/fgA3/2IAOv9SAAcADf9eAA//XAAi/1UA\
Tv9lAE//ZQBR/2QAU/9lAAEAIv9VAAgADf9eAA//XAAi/1UAQ/9jAE7/ZQBP/2UAUf9jAFP/ZQAB\
ACL/SwADAFf/SABY/0cAWv9GAAMAV/9DAFj/QgBa/0EAAwBX/0gAWP9HAFr/RgADAFf/QwBY/0IA\
Wv9BAAMAV/9HAFj/RgBa/0UAAwBX/0cAWP9GAFr/RQADAFf/SABY/0YAWv9GAAEAVf9sAAUAVf93\
AFf/SABY/0YAWf83AFr/RgADAFf/SABY/0cAWv9GAAIAFwAIAAgAAAANAA8AAQARABoABAAiACUA\
DgAnACgAEgArAC0AFAAvADEAFwAzADsAGgBBAEQAIwBGAEoAJwBMAFoALABpAGkAOwBsAGwAPAB2\
AHgAPQCNAI0AQACQAJAAQQCrALEAQgC7AL8ASQDBAMQATgDIAMgAUgDKAMwAUwDPANAAVgDYANsA\
WA==")}
]]>';

  return $font;
}

?>