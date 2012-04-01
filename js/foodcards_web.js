
// Functions and namespace for this app.
var foodcards = {};

foodcards.containers = ['#2F1E3A','#771F1D','#241E30','#5A533D','#8F1A1D','#3A813F','#54AD47','#6FA054','#9E1C2C','#477D3B','#3C1C42','#323A20','#34170E','#3F3A29','#3A2221','#3B194F','#BB2326','#453E70','#6E70B4','#7A297E','#276532','#64503D','#735037','#5F3A20','#F26322','#711813','#AF9E74','#87171A','#DA3227','#789E3E','#9B7052','#40332A','#EF4024','#56A345','#18100C','#8D4E22','#4F2E25','#633313','#222A1A','#C22D27','#911A1D','#A08664','#B22024','#2D703E','#764B40','#334B33','#784062','#591F60','#75163D','#EDC557','#763851','#704625','#652262','#3F5225','#EE7E7F','#437C3B','#66AB44','#8CAF4E','#518641','#B9752A','#D56C84','#855F2E'];
foodcards.buttons = ['#1C2617','#C92728','#DF4A26','#551B56','#3E7136','#6D2D1C','#461D12','#30391D','#9997CA','#639540','#9B2624','#C4905F','#567C38','#B13B31','#78B250','#AD6427','#715C3F','#401C54','#53873D','#D95D2A','#6BA547','#315E2E','#398A41','#5BA545','#327338','#4E0D36','#26562A','#A01D27','#352B16','#8a6b56','#3F223A','#A81D4D','#AF1E38','#4F141E','#BB2026','#67092E','#961B1E','#5E0E22','#4D121D','#A31C42','#563534','#DC2B27','#3DA648','#974E2B','#6A8C57','#69331A','#44883F','#612718','#391C10','#523F2F','#1D150D','#2C5F2F','#2B171D','#CE2027','#F47C2A','#89A53D','#A57F74','#235A2C'];

/* Load FoodCards */
foodcards.styleCards = function() {
  $('div.section div.container').each(function(){
    $(this).css('backgroundColor', foodcards.containers[Math.floor(Math.random()*foodcards.containers.length)]);
  });

  $('#tabs .section .buttons a').each(function(){
    $(this).css('backgroundColor', foodcards.buttons[Math.floor(Math.random()*foodcards.buttons.length)]);
  });

  return false;
};

$(document).ready(function() {

  foodcards.loadContent();

  $('#foods-carousel').jcarousel({

    });

	$(function() {
		$( "#tabs" ).tabs();
		
    $('a[href]').each(function(){
      if(this.hash){
        if($(this.hash + '.ui-tabs-panel').length > 0 && $(this).parents('div.ui-tabs').length == 0 ){
          link = this.href.replace(this.hash, '');
          page = window.location.href.replace(window.location.hash, '');
          if(link == page || link == ''){
            this.onclick = function(){
              $('.tabs').tabs('select', this.hash);
              return false;
            }
          }
        }
      }
    });
	});

  $.address.change(function(event){
    $("#tabs").tabs( "select" , window.location.hash );
  });

  // when the tab is selected update the url with the hash
  $("#tabs").bind("tabsselect", function(event, ui) { 
    window.location.hash = ui.tab.hash;
  });
  
  
    foodcards.imageCrop();
  
});

foodcards.loadContent = function() {
  var contentData = "data/content.json" + "?cache=" + Math.floor(Math.random()*11);
  var data = "";

  $.ajax({
    url:  contentData,
    dataType: 'json',
    data: data,
    success: foodcards.contentLoadSuccess,
    error: foodcards.loadDataError
  });

  return false;
};


foodcards.loadDataError = function(data) {
  console.log(data);
  return false;
};

foodcards.contentLoadSuccess = function(data) {
  foodcards.data = data;
  foodcards.content = data["content"];

  foodcards.loadBlogItems();
  foodcards.loadGameItems();
  foodcards.loadRecommendationItems();
  return false;
};

foodcards.loadBlogItems = function() {
  var container = $('div#blog .items');
  var template = "blogTemplate";
  var type = "Blog";
  var markup = container.html();
  container.empty();
  // Compile the markup as a named template

  $.template( template, markup );

  for (var i in foodcards.content) {
    for (key in foodcards.content[i]) {
      if(foodcards.content[i][key].category == type) {
        $.tmpl( template, foodcards.content[i][key])
        .appendTo(container);
      };
    }
  }

  // Recolor cards.
  foodcards.styleCards();
  return false;
};


foodcards.loadGameItems = function() {
  var container = $('div#games .items');
  var template = "gameTemplate";
  var type = "Game";
  var markup = container.html();
  container.empty();
  // Compile the markup as a named template

  $.template( template, markup );

  for (var i in foodcards.content) {
    for (key in foodcards.content[i]) {
      if(foodcards.content[i][key].category == type) {
        $.tmpl( template, foodcards.content[i][key])
        .appendTo(container);
      };
    }
  }

  // Recolor cards.
  foodcards.styleCards();
  return false;
};

foodcards.loadRecommendationItems = function() {
  var container = $('div#recommendations .items');
  var template = "recommendTemplate";
  var type = "Review";
  var markup = container.html();
  container.empty();
  // Compile the markup as a named template

  $.template( template, markup );

  for (var i in foodcards.content) {
    for (key in foodcards.content[i]) {
      if(foodcards.content[i][key].category == type) {
        $.tmpl( template, foodcards.content[i][key])
        .appendTo(container);
      };
    }
  }

  // Recolor cards.
  foodcards.styleCards();
  return false;
};



foodcards.imageCrop = function() {
  // Almost, not quite.
  // Trying to resize and crop images with canvas & javascript.
  var canvas = ['canvas-1', 'canvas-2', 'canvas-3', 'canvas-4', 'canvas-5', 'canvas-6'];
  var images = ['image-1', 'image-2', 'image-3', 'image-4', 'image-5', 'image-6'];
  
  foodcards.canvas = {};
  foodcards.context = {};
  foodcards.img = {};
  foodcards.imageObj = {};
  
  for(var i = 0; i < canvas.length; i++) {
    foodcards.count = i;
    foodcards.canvas["canvas" + i] = document.getElementById(canvas[i]);
    foodcards.context["context" + i] = foodcards.canvas["canvas" + i].getContext("2d");
    foodcards.img["img" + i] = document.getElementById(images[i]);
    foodcards.imageObj["image" + i] = new Image();
    $(foodcards.imageObj["image" + i]).attr('count', i);
    $(foodcards.imageObj["image" + i]).attr('width', foodcards.img["img" + i].width);
    $(foodcards.imageObj["image" + i]).attr('height', foodcards.img["img" + i].height);
    foodcards.imageObj["image" + i].src = foodcards.img["img" + i].src;

    foodcards.imageObj["image" + i].onload = function(){
      foodcards.width = parseInt($(this).attr('width'));
      foodcards.height = parseInt($(this).attr('height'));      
      var count = $(this).attr('count');
      foodcards.context["context" +  count].drawImage(foodcards.img["img" + count], 0, 0, 240, 240);     
    }
  }

};