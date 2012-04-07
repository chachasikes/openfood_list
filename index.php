<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Foods</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="./"/>
    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/spectrum.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body data-spy="scroll" data-target=".subnav" data-offset="50">


  <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="./index.html">Foods</a>
          <div class="nav-collapse">
            <ul class="nav">

            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">

<!-- Masthead
================================================== -->

<div class="content">

  <div id="search">
    <label>Keyword</label>
    <input class="search" type="text" />
    <input class="button" type="submit" value="submit" />
    <div class="search-string"></div><div class="food-count"></div>
  </div>  

  <div id="filters">
    <label>Categories</label>
    <select id="categories" name="categories" multiple="multiple">
      <option>${category}</option>
    </select>
  </div>  

  <div id="message" class="hide">
  </div>



      <!-- about modal content -->
    <div id="food" class="modal span11 hide">
      <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h2>${name}</h2>
      </div>
      <div class="modal-body">
        <div class="row-fluid">
            <div class="names span7">
            <h4>Names</h4>
            <dd>
              {{if name}}<dt>Long Common Name</dt><dd>${name}</dd>{{/if}}
              {{if simple_name}}<dt>Simple Common Name</dt><dd>${simple_name}</dd>{{/if}}
              {{if scientific_name}}<dt>Scientific Name</dt><dd>${scientific_name}</dd>{{/if}}
              {{if variety}}<dt>Variety</dt><dd>${variety}</dd>{{/if}}
              {{if alternate_names}}<dt>Alternate Names</dt><dd>${alternate_names}</dd>{{/if}}
            </dd>
            {{if category}}
            <h4>Categories</h4>
            <div class="categories">
              ${category}
            </div>
            {{/if}}
    
            {{if description}}
            <h4>Description</h4>
            <div class="description">
              ${description}
            </div>
            {{/if}}
          </div>
           <div class="colors span4 rowfluid">
            <div class="background color" style ="background-color:{{if food_color_background}}#${food_color_background}{{else}}#dedede{{/if}};">
              <div class="modal-label">Background Color</div>
              <p>{{if food_color_background}}#${food_color_background}{{else}}Not set{{/if}}
              <input class="picker" value="{{if food_color_background}}#${food_color_background}{{/if}}" />
            </p></div>
            <div class="text color" style="background-color:{{if food_color_text}}#${food_color_text}{{else}}#222{{/if}}">
              <div class="modal-label">Text Color</div>
              <p>{{if food_color_text}}#${food_color_text}{{else}}Not set{{/if}}</p>
              <input type="radio" name="food_color_text" value="dark"><span>Dark</span><br />
              <input type="radio" name="food_color_text" value="light"><span>Light</span><br />
              </p>
            </div>
            {{if depiction}}

              <div class="image">
              <a href="${depiction}">
                <canvas class="image-canvas" id="food-canvas">
                  <img id="food-image" src="${depiction}" alt="${name}" />
                </canvas>
              </a>
              </div>
            {{/if}}
          </div>
        </div>
        <hr class="muted">
        <div class="row-fluid">
          <div class="data span7">
            <h4>Data Source & Crosswalks</h4>
            <p>Data is cross-referenced and aggregated from a number of different sources. This dataset may be modified from the original reference.</p>
            <dd>
              <dt>Data Source</dt><dd>{{if datasource}}${datasource}{{else}}Mixed{{/if}}</dd>
              {{if freebase_id}}<dt>Freebase ID</dt><dd>${freebase_id}</dd>{{/if}}
              {{if in_foodgenome}}<dt>In FoodGenome</dt><dd>Yes. Last checked on ${in_foodgenome}</dd>{{/if}}
              {{if in_foodista}}<dt>In Foodista</dt><dd>Yes. Last checked on ${in_foodista}</dd>{{/if}}
            </dd>
            <div class="date">
            {{if updated_date}}<p class="created">Created  ${updated_date}</p>{{/if}}
            {{if openfood_update}}<p class="updated">Updated  ${foods.formatDate(openfood_update)}</p>{{/if}}
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" >Close</a>
      </div>
    </div>

  <div id="foods" class="inner-transition">
    <div class="food">
      <p><a id="${_id.$id}" href="#food" data-toggle="modal" style="background-color:{{if food_color_background}}#${food_color_background}{{else}}#dedede{{/if}};color:{{if food_color_text}}#${food_color_text}{{else}}#222{{/if}}">${name}</a></p>
    </div>
  </div>
</div>


     <!-- Footer
      ================================================== -->
      <footer class="footer">
     </footer>

    </div><!-- /container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/google-code-prettify/prettify.js"></script>
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
    <script src="assets/js/application.js"></script>

    <script src="js/spectrum.js"></script>
    <script src="js/jquery-tmpl/jquery.tmpl.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/foods.js"></script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30678726-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

  </body>
</html>
