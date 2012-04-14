// Functions and namespace for this app.
var foods = {};
var foodsContainer;
var foodsMarkup;
var foodContainer;
var foodMarkup;
var foodContent;
var categories;
var itemContainer;
foods.page = 0;
foods.maxPages = 1;
foods.itemsPerPage = 500;
foods.setup = false;
var query = {path: "api/kitchen.php"};

$(document).ready(function(){
  foods.userMessage({'food_color_background': '555555', 'color':  'ffffff'}, "Loading Foods", 3000);

  foods.loadCategories();
  foods.searchFood();
  foods.loadContent(query);

  foods.customizeInitialLoad();
});


foods.customizeInitialLoad = function() {
  $("div#search input.search").val("Search for a technique");
  $('div.search-string').html("Showing all kitchen techniques");

  $("div#search input.search").focus(function(){
    $("div#search input.search").val('');
  });

/*   $('div#legend').hide(); */
/*   $('footer').hide(); */

$('#about').modal({show: true});

/*   $('div#legend a.close').click(function(){$(this).parent().hide();}); */


/*   $('footer a.close').click(function(){$(this).parent().hide();}); */
};

foods.loadContent = function(query) {
  var path = query.path;
  var search;
  if (query.search !== undefined) {
    search = "&search=" + query.search;
  } 
  else {
    search = "";
  }
  if (query.category !== undefined) {
    category = "&category=" + query.category;
  } 
  else {
    category = "";
  }

  var page = foods.page;
  var contentData = path + "?page=" + page + search + category + "&cache=" + Math.floor(Math.random()*11);

  var data = "";

  $.ajax({
    url:  contentData,
    dataType: 'json',
    data: data,
    success: foods.contentLoadSuccess,
    error: foods.loadDataError
  });

  return false;
};

foods.loadDataError = function(data) {
  console.log(data);
  return false;
};

foods.contentLoadSuccess = function(data) {
  foods.data = data;
  foods.content = data["foods"];
  $('div.food-count').html("found " + foods.data.count + " foods");

  if(window.location.hash !== "") {
    hash = window.location.hash.replace('#', '');
    foods.loadFood(hash);
  }
  else{
    if(foods.setup === false) {
      foods.setupLoadFoods();
      foods.loadFoods();
    }
    else {
      foods.loadFoods();
    }


  foods.foodClick();


  // Isotope

  if($('div#foods .food').length > 0) {
/*
    $(function(){
      $('div#foods').isotope({
        itemSelector: '.food',
        layoutMode : 'masonry'
      });
    });
*/
  }
  else {
    foods.userMessage({'food_color_background': '555555', 'color':  'ffffff'}, "No results found", 10000);
  }

  }
  return false;
};

foods.foodClick = function() {

  $("div#foods div.food a").click(function(){
      var food_id = $(this).attr("id");
      $('a.back').show();
      foods.loadFood(food_id);
    }
  );
  return false;
};

foods.setupLoadFoods = function() {
  var type = "food";
  foodsContainer = $('div#foods');
  foodsMarkup = foodsContainer.html();
  foodContent = {};

  foodsContainer.empty();
  $.template( "foodsTemplate", foodsMarkup );

  foodContainer = $('div#food');
  foodMarkup = foodContainer.html();
  $.template( "foodTemplate", foodMarkup );

  foods.setup = true;
};

foods.loadFoods = function() {
  if(foods.page == 0) {
    foodsContainer.empty();
  }

  for (var i in foods.content) {
    $.tmpl("foodsTemplate", foods.content[i])
      .appendTo(foodsContainer);
  }

  $('div#foods .food').css('visibility', 'visible');

  $('div.food-count').html("found " + foods.data.count + " foods");
  
/*   foods.handleDuplicates(); */

  if(foods.data.count !== undefined) {
    foods.maxPages = Math.floor(foods.data.count / foods.itemsPerPage);
  }  
	$(window).scroll(function(){
  	var scrolledDistance =  $(document).height() - $(window).height() - 1;
  	 if($(window).scrollTop() == scrolledDistance ){
  	  if(foods.page < foods.maxPages) {
        foods.page++;
        foods.userMessage({'food_color_background': '555555', 'color':  'ffffff'}, "Loading More Foods", 6000);
        foods.loadContent(query);
        }
  	 }
  }); 
  
  return false;
};

foods.loadMoreFoods = function(page) {
/*   console.log("laoding more"); */
}

foods.loadFood = function(key) {
  for (var i in foods.content) {
    if(foods.content[i]["_id"] !== null && foods.content[i]['_id']['$id'] == key) {

      foodContainer.empty();

      foodContent = foods.content[i];

      $.tmpl("foodTemplate", foodContent )
      .appendTo(foodContainer);

      foods.editButtons(foodContent);
      foods.imageCrop();
      break;
    }
  }
  return false;
};

foods.loadData = function(content, source) {
  var data = {};
  for (var i in foods.content) {

    if(foods.content[i].source_data !== null && foods.content[i].source_data !== undefined && source !== undefined) {
      if(foods.content[i].source_data[source] !== undefined) {
        data = foods.content[i].source_data[source];
      return data;
      }
    }
  }
};

foods.searchFood = function() {
  $("div#search input.search").keypress(function(e) {
    // On hit enter
    if ( e.which == 13 ) {
     // Reset pager
      foods.page = 0;
      foods.maxPages = 1;
      $('div.food-count').html("Searching...");
     
      var searchValue = $("div#search input.search").val();
      $('div.search-string').html(searchValue);
      $("div#filters select option").attr("selected", false);
    
      query = {path:"api/search_kitchen.php", "search": searchValue};
      foods.loadContent(query);
    }
  });
  
 $("div#search input.button").click(function(){
   // Reset pager
    foods.page = 0;
    foods.maxPages = 1;

    $('div.food-count').html("Searching...");   

    var searchValue = $("div#search input.search").val();
    $('div.search-string').html(searchValue);
    $("div#filters select option").attr("selected", false);
  
    query = {path:"api/search_kitchen.php", "search": searchValue}; 
    foods.loadContent(query);
  }
 );
}

foods.loadCategories = function() {
    var path = "api/kitchen_categories.php";
    var contentData = path + "?cache=" + Math.floor(Math.random()*11);
    var data = "";

    $.ajax({
      url:  contentData,
      dataType: 'json',
      data: data,
      success: foods.categoriesLoadSuccess,
      error: foods.loadDataError
    });
};

foods.categoriesLoadSuccess = function(data) {
  var all = {'category':'all'};
  categories = data["categories"];
  categories.unshift(all);
  itemsContainer = $('div#filters select#categories');

  var itemsMarkup = itemsContainer.html();
  itemsContainer.empty();
  $.template( "itemsTemplate", itemsMarkup );

  $.tmpl("itemsTemplate", categories)
  .appendTo(itemsContainer);

  $("div#filters select option:nth(0)").attr("selected", true);

  $("div#filters").change(function(){
    // Reset pager
    foods.page = 0;
    foods.maxPages = 1;
    var page = foods.page;

    // Reset search text box
    $("div#search input.search").val("Search for a food");

    var searchValues = '';
    var numberSelected = $("select#categories option:selected").length;
    var i = 0;

    $("select#categories option:selected").each(function () {
      searchValues += $(this).text().trim();
      if(i < numberSelected - 1) {
        searchValues += ",";
      }
      i++;
    });

    $('div.search-string').html(searchValues);
    $('div.food-count').html("Searching...");

    query = {path: "api/search_kitchen.php", category: searchValues};
    foods.loadContent(query);

    return false;
  });
}

foods.editButtons = function(food) {
  var record = {};
  record.food = foodContent;

/*
  $("div.colors .background input.picker").spectrum({
    color: foodContent.food_color_background,
    showInput: true,
    change: function(color) {
      var record = {};
      record.food = foodContent;
      var newColor = color.toHexString();
      newColor = newColor.replace('#', "");
      record.food.food_color_background = newColor;

      $("div#foods .food a#" + record.food["_id"]["$id"]).css("background-color", "#" + newColor);

      foods.updateRecord(record);
    }
  });
*/

  $("input.reset-depiction").click(function(){
    $('input.depiction').val(foodContent.depiction);  
    $('input.depiction_credit_url').val(foodContent.depiction_credit_url);  
    return false; 
  });
  
  $("input.change-depiction").click(function(){
      var record = {};
      record.food = foodContent;
      record.food.depiction = $('input.depiction').val();
      record.food.depiction_credit_url = $('input.depiction_credit_url').val();
      console.log(record.food.depiction);
      console.log(record.food.depiction_credit_url);
      foods.updateRecord(record);
      foods.userMessage(record.food, "Saved Image Information", 3000);
      return false; 
  });

  var currentColor =  food.food_color_text;

  if(currentColor == '131313') {
    $('div.colors .text input[name=food_color_text]:nth(0)').attr('checked',true);
  }
  if(currentColor == 'EEEFE6') {

    $('div.colors .text input[name=food_color_text]:nth(1)').attr('checked',true);
  }


  $("div.colors .background input[name=food_color_background]").change(function() {
    var record = {};
    record.food = foodContent;

    var currentColor = $(this).val();
    if(currentColor == 'beige') {
      record.food.food_color_background = 'F0E8C4';
    }
    if(currentColor == 'blue') {
      record.food.food_color_background = 'E0EDFE';
    }
    if(currentColor == 'grey') {
      record.food.food_color_background = 'C0BFC4';
    }

    $("div#foods .food a#" + record.food["_id"]["$id"]).css("color", "#" + record.food.food_color_background);

    // foods.updateRecord(record);
  });

  var currentColor =  food.food_color_background;

  if(currentColor == 'F0E8C4') {
    $('div.colors .background input[name=food_color_background]:nth(0)').attr('checked',true);
  }
  if(currentColor == 'E0EDFE') {
    $('div.colors .background input[name=food_color_background]:nth(1)').attr('checked',true);
  }
  if(currentColor == 'C0BFC4') {
    $('div.colors .background input[name=food_color_background]:nth(2)').attr('checked',true);
  }

  $("div.colors .text input[name=food_color_text]").change(function() {
    var record = {};
    record.food = foodContent;

    var currentColor = $(this).val();
    if(currentColor == 'dark') {
      record.food.food_color_text = '131313';
    }
    if(currentColor == 'light') {
      record.food.food_color_text = 'EEEFE6';
    }

    $("div#foods .food a#" + record.food["_id"]["$id"]).css("color", "#" + record.food.food_color_text);

    // foods.updateRecord(record);
  });
};

foods.updateRecord = function(record) {
    var record = record;
    var path = "api/update.php";

    var contentData = path + "?cache=" + Math.floor(Math.random()*11);


    $.ajax({
      url:  contentData,
      type: 'POST',
      dataType: 'json',
      data: record,
      success: foods.updateLoadSuccess,
      error: foods.loadDataError
    });

};

foods.updateLoadSuccess = function(data, message) {
  if(data.food[0] !== null) {
    foods.loadFood(data.food[0]["_id"]["$id"]);
    foods.userMessage(data.food[0], "Saved", 2000);
  }
};

foods.userMessage = function(data, message, duration) {
  $('div#message').html(message);
  $('div#message').css('background-color', '#' + data.food_color_background);
  $('div#message').css('color', '#' + data.food_color_text);
  $('div#message').show().fadeOut(duration, 'easeOutBack');
}

foods.imageCrop = function() {
  // Almost, not quite.
  // Trying to resize and crop images with canvas & javascript.
  var image  = {};
  var canvas = ['food-canvas'];
  var images = ['food-image'];

  image.canvas = {};
  image.context = {};
  image.img = {};
  image.imageObj = {};

  image.img["img"] = document.getElementById(images[0]);
  image.canvas["food-canvas"] = document.getElementById(canvas[0]);

  if(image.img["img"] !== undefined && image.canvas["food-canvas"] !== undefined && image.canvas["food-canvas"] !== null) {

    image.context["context"] = image.canvas["food-canvas"].getContext("2d");

    image.imageObj["food-image"] = new Image();
    $(image.imageObj["food-image"]).attr('width', image.img["img"].width);
    $(image.imageObj["food-image"]).attr('height', image.img["img"].height);

    image.imageObj["food-image"].src = image.img["img"].src;

    image.imageObj["food-image"].onload = function(){
      image.width = parseInt($(this).attr('width'));
      image.height = parseInt($(this).attr('height'));
      image.context["context"].drawImage(image.img["img"], image.width * -0.1,  image.height * -0.5,  image.width,  image.height);
    }
  }
};

foods.formatDate = function (datetime) {
  var date = new Date(datetime.sec*1000);
  // hours part from the timestamp
  var hours = date.getHours();
  // minutes part from the timestamp
  var minutes = date.getMinutes();
  // seconds part from the timestamp
  var seconds = date.getSeconds();
  // will display time in 10:30:23 format
  var formattedTime = hours + ':' + minutes + ':' + seconds;
  return date;
};

foods.handleDuplicates = function() {
  $('div#foods div.food div.dup input').click( function(){
      var food_id = $(this).parent().parent().attr("_id");
      var value = $(this).is(':checked');
      var key = food_id ;
      for (var i in foods.content) {
        if(foods.content[i]["_id"] !== null && foods.content[i]['_id']['$id'] == key) {
          foodContent = foods.content[i];
          foodContent.is_duplicate = value;
          var record = {};
          record.food = foodContent;
          foods.updateRecord(record);   
        }
      }
  });
};
