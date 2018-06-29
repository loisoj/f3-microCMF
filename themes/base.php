<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{@ CONSTRUCTR_PAGE_TITLE @}}</title>
        <meta name="keywords" content="{{@ CONSTRUCTR_PAGE_KEYWORDS @}}">
        <meta name="description" content="{{@ CONSTRUCTR_PAGE_DESCRIPTION @}}">
            <link rel="stylesheet" href="{{@ CONSTRUCTR_BASE_URL @}}/helpers/css/reset.css">
        <link rel="stylesheet" href="{{@ CONSTRUCTR_BASE_URL @}}/helpers/css/materialize.min.css">

        <script src="{{@ CONSTRUCTR_BASE_URL @}}/helpers/js/materialize.min.js" charset="utf-8"></script>
        <style>
        	{{@ PAGE_CSS @}}
        </style>
    </head>
    <body>

      <nav>
   <div class="nav-wrapper grey darken-4">
     <ul id="nav-mobile" class="left hide-on-med-and-down">
    {{@ PAGE_NAVIGATION_UL_LI @}}
     </ul>
   </div>
 </nav>

		<div id="content" class="container">

      <div id="head">
  			<h1>{{@ PAGE_NAME @}}</h1>
  		</div>

			{{@ PAGE_CONTENT_HTML @}}
		</div>

		<div id="footer">

		</div>

        <script src="{{@ CONSTRUCTR_BASE_URL @}}/helpers/we.we"></script>
        <script>
        	$(function(){
        		{{@ PAGE_JS @}};
        	});
        </script>

    </body>
</html>
