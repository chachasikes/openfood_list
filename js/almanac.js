// Functions and namespace for this app.
var almanac = {};
  
$(document).ready(function(){
  almanac.loadContent();
});

almanac.loadContent = function() {
  var contentData = "data/foods.json" + "?cache=" + Math.floor(Math.random()*11);
  var data = "";

  $.ajax({
    url:  contentData,
    dataType: 'json',
    data: data,
    success: almanac.contentLoadSuccess,
    error: almanac.loadDataError
  });

  return false;
};

almanac.loadDataError = function(data) {
  console.log(data);
  return false;
};

almanac.contentLoadSuccess = function(data) {
  almanac.data = data;
  almanac.content = data["content"];
  if(window.location.hash !== "") {
    hash = window.location.hash.replace('#', '');
    almanac.loadFood(hash);
  }
  else{
    almanac.loadFoods();
  }
  return false;
};

almanac.loadFoods = function(){
  var type = "food";
  var foodsContainer = $('div#foods-links');
  var foodsMarkup = foodsContainer.html();
  var foodContent = {};
  foodsContainer.empty();
  $.template( "foodsTemplate", foodsMarkup );        
 

  for (var i in almanac.content) {
    for (key in almanac.content[i]) {
      if(almanac.content[i][key].type == type) {
       foodContent.key = key;
      
        for (item in almanac.content[i][key]) {
          if(item == "title") {
            foodContent.title = almanac.content[i][key][item];
          }
        }
          $.tmpl("foodsTemplate", almanac.content[i][key])
          .appendTo(foodsContainer);
      }
    }
  }

  return false;
};

almanac.loadFood = function(key) {
  for (var i in almanac.content) {  
    if(almanac.content[i][key] !== null && almanac.content[i][key] !== undefined) {

      var itemsContainer = $('div#foods');
      var itemsMarkup = itemsContainer.html();
      itemsContainer.empty();
      $.template( "itemsTemplate", itemsMarkup ); 
      $.tmpl("itemsTemplate", almanac.content[i][key])
      .appendTo(itemsContainer);  

      
      foodContent = {};
      var itemContainer = $('.food');
      var itemMarkup = itemContainer.html();
      itemContainer.empty();
      $.template( "itemTemplate", itemMarkup);

      
      for (item in almanac.content[i][key]) {
        if(item != "title") {
          foodContent.itemTitle = item;
          foodContent.itemContent = almanac.content[i][key][item];
          foodContent.itemKey = key;          
          foodContent.data = almanac.loadData(almanac.content[i][key], item);
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

      $('title').html(almanac.content[i][key].title);

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

almanac.loadData = function(content, source) {
  var data = {};
  for (var i in almanac.content) {  

    if(almanac.content[i].source_data !== null && almanac.content[i].source_data !== undefined && source !== undefined) {
      if(almanac.content[i].source_data[source] !== undefined) {
        data = almanac.content[i].source_data[source];
      return data;
      }

    }
  }
};