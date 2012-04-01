// Functions and namespace for this app.
var foods = {};
  
$(document).ready(function(){
  foods.loadContent();
});

foods.loadContent = function() {
  var contentData = "http://localhost/mongofood/api/foods.php" + "?cache=" + Math.floor(Math.random()*11);
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
    foods.loadFoods();
  }
  return false;
};

foods.loadFoods = function(){
  var type = "food";
  var foodsContainer = $('div#foods');
  var foodsMarkup = foodsContainer.html();
  var foodContent = {};
  foodsContainer.empty();
  $.template( "foodsTemplate", foodsMarkup );        

  for (var i in foods.content) {
    $.tmpl("foodsTemplate",  foods.content[i])
      .appendTo(foodsContainer); 
  }

  return false;
};

foods.loadFood = function(key) {
  for (var i in foods.content) {  
    if(foods.content[i][key] !== null && foods.content[i][key] !== undefined) {

      var itemsContainer = $('div#foods');
      var itemsMarkup = itemsContainer.html();
      itemsContainer.empty();
      $.template( "itemsTemplate", itemsMarkup ); 
      $.tmpl("itemsTemplate", foods.content[i][key])
      .appendTo(itemsContainer);  

      
      foodContent = {};
      var itemContainer = $('.food');
      var itemMarkup = itemContainer.html();
      itemContainer.empty();
      $.template( "itemTemplate", itemMarkup);

      
      for (item in foods.content[i][key]) {
        if(item != "title") {
          foodContent.itemTitle = item;
          foodContent.itemContent = foods.content[i][key][item];
          foodContent.itemKey = key;          
          foodContent.data = foods.loadData(foods.content[i][key], item);
          if(foodContent.data !== undefined) {
            foodContent.dataName = foodContent.data["name"];
            foodContent.dataSourceName = foodContent.data["source_name"];
            foodContent.dataFrequency = foodContent.data["frequency"];
            foodContent.dataPublishedDate = foodContent.data["published_date"];
            foodContent.dataPermalink = foodContent.data["permalink"];
          }
          
          $.tmpl("itemTemplate", foodContent)
            .appendTo(itemContainer);
        }
      }

      $('title').html(foods.content[i][key].title);

      $(function(){      
        $('.items').isotope({
          itemSelector: '.item',
          layoutMode : 'masonry'
        });
      });
   
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