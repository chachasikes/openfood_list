// Functions and namespace for this app.
var foods = {};
  
var foodsContainer;
var foodsMarkup;
var foodContainer;
var foodMarkup;
var foodContent;
var categories;
var itemContainer;
foods.setup = false;
  
$(document).ready(function(){
  foods.loadContent();
  foods.searchFood();
  foods.loadCategories();

  foods.customizeInitialLoad();
  
});


foods.customizeInitialLoad = function() {
  $("div#search input.search").val("Search for a food");
  $("div#search input.search").focus(function(){
  $("div#search input.search").val('');
  });
};

foods.loadContent = function() {
  var path = "http://localhost/mongofood/api/foods.php";
  var contentData = path + "?cache=" + Math.floor(Math.random()*11);
  
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
  
/* // Isotope
    $(function(){      
      $('div#foods').isotope({
        itemSelector: '.food',
        layoutMode : 'masonry'
      });
    });
*/
    
  }
  return false;
};

foods.foodClick = function() {

  $("div#foods div.food a").click(function(){
      var food_id = $(this).attr("id"); 


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
  foodsContainer.empty();
  for (var i in foods.content) {
    $.tmpl("foodsTemplate", foods.content[i])
      .appendTo(foodsContainer); 
  }

  return false;
};

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
  $("div#search input.button").click(function(){
  var searchValue = $("div#search input.search").val();

  var path = "http://localhost/mongofood/api/search.php?search=" + searchValue;
   console.log(path);
  var contentData = path + "&cache=" + Math.floor(Math.random()*11);
  
  var data = "";

  $.ajax({
    url:  contentData,
    dataType: 'json',
    data: data,
    success: foods.contentLoadSuccess,
    error: foods.loadDataError
  });


  return false;


  });
}


foods.loadCategories = function() {
  
    var path = "http://localhost/mongofood/api/categories.php";
   
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



  $("div#filters").change(function(){
 
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
  
    var path = "http://localhost/mongofood/api/search.php?category=" + searchValues;
/*     console.log(path); */
   
    var contentData = path + "&cache=" + Math.floor(Math.random()*11);
    
    var data = "";
  
    $.ajax({
      url:  contentData,
      dataType: 'json',
      data: data,
      success: foods.contentLoadSuccess,
      error: foods.loadDataError
    });
  
  
    return false;
  
  });
}

foods.editButtons = function(food) {
  var record = {};
  record.food = foodContent;

  $("div.colors .background input.picker").spectrum({
    color: foodContent.food_color_background
  });

  $("div.colors .background input.picker").spectrum({
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


    var currentColor =  food.food_color_text;

    if(currentColor == '131313') {
      $('div.colors .text input[name=food_color_text]:nth(0)').attr('checked',true);
    }
    if(currentColor == 'EEEFE6') {

      $('div.colors .text input[name=food_color_text]:nth(1)').attr('checked',true);
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

    foods.updateRecord(record);
  });
};

foods.updateRecord = function(record) {

    var path = "http://localhost/mongofood/api/update.php";

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
  foods.loadFood(data.food[0]["_id"]["$id"]);
  foods.userMessage(data.food[0], "Saved");
};

foods.userMessage = function(data, message) {
  $('div#message').html(message);
  $('div#message').css('background-color', '#' + data.food_color_background);
  $('div#message').css('color', '#' + data.food_color_text);
  $('div#message').show().fadeOut(2000);
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
  
  for(var i = 0; i < canvas.length; i++) {
    image.count = i;
    image.canvas["food-canvas"] = document.getElementById(canvas[i]);
    image.context["context" + i] = image.canvas["food-canvas"].getContext("2d");
    image.img["img" + i] = document.getElementById(images[i]);
    image.imageObj["food-image"] = new Image();
    $(image.imageObj["food-image"]).attr('count', i);

    $(image.imageObj["food-image"]).attr('width', image.img["img" + i].width);

    $(image.imageObj["food-image"]).attr('height', image.img["img" + i].height);
    image.imageObj["food-image"].src = image.img["img" + i].src;

    image.imageObj["food-image"].onload = function(){
      image.width = parseInt($(this).attr('width'));
      image.height = parseInt($(this).attr('height'));      
      var count = $(this).attr('count');
      image.context["context" +  count].drawImage(image.img["img" + count], 0, 0,  image.width,  image.height, 0, 0, image.width/2,  image.height/2);     
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
}

