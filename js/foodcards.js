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

  categories = data["categories"];

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

foods.editButtons = function(food) {

$("div.colors .background input.picker").spectrum({
    color: food.food_color_background
});

/* $("div.colors .background input.picker").spectrum("show"); */
};

foods.updateRecord = function() {

  var record = {};
  record.nid = 745;


    var path = "http://localhost/mongofood/api/search.php?category=" + searchValues;
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

};